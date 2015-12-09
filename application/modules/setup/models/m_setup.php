<?php

class m_setup extends CI_Model {

    public $table = 'setup';
    public $id = 'id';
    public $f_name = 'f_name';
    public $l_name = 'l_name';
    public $address = 'address';
    public $city = 'city';
    public $country = 'country';
    public $phone = 'phone';
    public $age = 'age';
    public $salary = 'salary';
    public $create_date = 'create_date';

    function __construct() {
        parent::__construct();
        $this->load->library("Db_lib");
    }
    
    function get_all_setup($where = ""){
        
        $this->db->from($this->table);
        if($where !="")
            $this->db->where('(' . $where . ')');
        else {
            $this->db->where('is_active',1);
        }
        return $this->db->get();
    }
    
//    function get_data($user_email, $pass) {
//
//        $this->db->select('*')
//                ->from($this->table)
//                ->where($this->email, $user_email)
//                ->where($this->pass, $pass);
//        
//        $result = $this->db->get()->result();
//        return $result;
//    }
//    
    /**
     * Perform the SQL queries needed for an server-side processing requested,
     * utilising the helper functions of this class, limit(), order() and
     * filter() among others. The returned array is ready to be encoded as JSON
     * in response to an SSP request, or can be modified if needed before
     * sending back to the client.
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array|PDO $conn PDO connection resource or connection parameters array
     *  @param  string $table SQL table to query
     *  @param  string $primaryKey Primary key of the table
     *  @param  array $columns Column information array
     *  @return array          Server-side processing response array
     */
    function get_all_data($request, $table, $primaryKey, $columns) {

        $bindings = array();
        $resFilterLength = array();

        
//        if (isset($request['start']) && $request['length'] != -1) {
//            $this->db->limit(intval($request['start']), intval($request['length']));
//        }

        //$limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        
        $limit = $this->limit($request, $columns);
        $order = $this->order($request, $columns);
        $where = $this->filter($request, $columns);
        
        // Data set length after filtering
        $recordsFiltered = $this->db->query("SELECT COUNT(`{$primaryKey}`) FROM `$table` $where")->num_rows();


        //$recordsFiltered = $resFilterLength[0][0];
        // Total data set length
        $recordsTotal = $this->db->query("SELECT COUNT(`{$primaryKey}`)
			 FROM   `$table`"
        )->num_rows();
        
        // Main query to actually get the data
        $data = $this->db->query("SELECT SQL_CALC_FOUND_ROWS `" . implode("`, `", $this->pluck($columns, 'db')) . "`
			 FROM `$table`
			 $where
			 $order 
                         $limit"
        )->result_array();

        

        //$recordsTotal = $resTotalLength[0][0];


        /*
         * Output
         */
        return array(
            "draw" => intval($request['draw']),
            "recordsTotal" => intval($recordsTotal),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $this->data_output($columns, $data)
        );
    }

    /**
     * Paging
     *
     * Construct the LIMIT clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL limit clause
     */
    public function limit($request, $columns) {

        $limit = '';

        if (isset($request['start']) && $request['length'] != -1) {
            $limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
        }

        return $limit;
    }

    /**
     * Ordering
     *
     * Construct the ORDER BY clause for server-side processing SQL query
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @return string SQL order by clause
     */
    public function order($request, $columns) {

        $order = '';

        if (isset($request['order']) && count($request['order'])) {
            $orderBy = array();
            $dtColumns = $this->pluck($columns, 'dt');

            for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
                // Convert the column index into the column data property
                $columnIdx = intval($request['order'][$i]['column']);
                $requestColumn = $request['columns'][$columnIdx];

                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['orderable'] == 'true') {
                    $dir = $request['order'][$i]['dir'] === 'asc' ?
                            'ASC' :
                            'DESC';

                    $orderBy[] = '`' . $column['db'] . '` ' . $dir;
                }
            }

            $order = 'ORDER BY ' . implode(', ', $orderBy);
        }

        return $order;
    }

    /**
     * Searching / Filtering
     *
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here performance on large
     * databases would be very poor
     *
     *  @param  array $request Data sent to server by DataTables
     *  @param  array $columns Column information array
     *  @param  array $bindings Array of values for PDO bindings, used in the
     *    sql_exec() function
     *  @return string SQL where clause
     */
    public function filter($request, $columns) {

        $globalSearch = array();
        $columnSearch = array();
        $dtColumns = $this->pluck($columns, 'dt');

        if (isset($request['search']) && $request['search']['value'] != '') {
            $str = $request['search']['value'];

            for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
                $requestColumn = $request['columns'][$i];
                $columnIdx = array_search($requestColumn['data'], $dtColumns);
                $column = $columns[$columnIdx];

                if ($requestColumn['searchable'] == 'true') {
                    //$binding = $this->bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                    $globalSearch[] = "`" . $column['db'] . "` LIKE '%". $str ."%'";
                }
            }
        }

        // Individual column filtering
        for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
            $requestColumn = $request['columns'][$i];
            $columnIdx = array_search($requestColumn['data'], $dtColumns);
            $column = $columns[$columnIdx];

            $str = $requestColumn['search']['value'];

            if ($requestColumn['searchable'] == 'true' &&
                    $str != '') {
                //$binding = $this->bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
                $columnSearch[] = "`" . $column['db'] . "` LIKE '%". $str ."%'";
            }
        }

        // Combine the filters into a single string
        $where = '';

        if (count($globalSearch)) {
            $where = '(' . implode(' OR ', $globalSearch) . ')';
        }

        if (count($columnSearch)) {
            $where = $where === '' ?
                    implode(' AND ', $columnSearch) :
                    $where . ' AND ' . implode(' AND ', $columnSearch);
        }

        if ($where !== '') {
            $where = 'WHERE ' . $where;
        }

        return $where;
    }

    /**
     * Create a PDO binding key which can be used for escaping variables safely
     * when executing a query with sql_exec()
     *
     * @param  array &$a    Array of bindings
     * @param  *      $val  Value to bind
     * @param  int    $type PDO field type
     * @return string       Bound key to be used in the SQL where this parameter
     *   would be used.
     */
    public function bind(&$a, $val, $type) {
        $key = ':binding_' . count($a);

        $a[] = array(
            'key' => $key,
            'val' => $val,
            'type' => $type
        );

        return $key;
    }

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    public function pluck($a, $prop) {
        $out = array();

        for ($i = 0, $len = count($a); $i < $len; $i++) {
            $out[] = $a[$i][$prop];
        }

        return $out;
    }

    /**
     * Execute an SQL query on the database
     *
     * @param  resource $db  Database handler
     * @param  array    $bindings Array of PDO binding values from bind() to be
     *   used for safely escaping strings. Note that this can be given as the
     *   SQL query string if no bindings are required.
     * @param  string   $sql SQL query to execute.
     * @return array         Result from the query (all rows)
     */
    public function sql_exec($bindings, $sql = null) {
        // Argument shifting
        if ($sql === null) {
            $sql = $bindings;
        }

        // $mysqli = new mysqli('localhost', 'root', '', 'codeigniter3');
        //$stmt = $mysqli->prepare($sql);
        // Bind parameters
//        if (is_array($bindings)) {
//            for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
//                $binding = $bindings[$i];
//              
//                $stmt->bind_param($binding['key'], $binding['val'], $binding['type']);
//            }
//        }
        // Execute
        try {
            //$stmt->execute();
            $result = $this->db->query($sql)->result_array();
        } catch (PDOException $e) {
            $this->fatal("An SQL error occurred: " . $e->getMessage());
        }

        // Return all
        return $result;
        //return $stmt->fetch();
    }

    /**
     * Create the data output array for the DataTables rows
     *
     *  @param  array $columns Column information array
     *  @param  array $data    Data from the SQL get
     *  @return array          Formatted data in a row based format
     */
    public function data_output($columns, $data) {
        $out = array();

        for ($i = 0, $ien = count($data); $i < $ien; $i++) {
            $row = array();

            for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
                $column = $columns[$j];

                // Is there a formatter?
                if (isset($column['formatter'])) {
                    $row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
                } else {
                    $row[$column['dt']] = $data[$i][$columns[$j]['db']];
                }
            }

            $out[] = $row;
        }

        return $out;
    }

}
