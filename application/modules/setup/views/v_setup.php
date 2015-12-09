<div class="container">
    <div class="row">
        <table id="example" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="12%">First Name</th>
                    <th width="12%">Last Name</th>
                    <th width="12%">Address</th>
                    <th width="12%">City</th>
                    <th width="12%">Country</th>
                    <th width="12%">Phone</th>
                    <th width="12%">Age</th>
                    <th width="12%">Action</th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Country</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Action</th>
                </tr>
            </tfoot>


        </table>
    </div>
</div> <!-- /container -->


<script type="text/javascript">

    $(document).ready(function () {
       
        // Setup - add a text input to each footer cell
//        $('#example tfoot th').each(function () {
//            var title = $('#example thead th').eq($(this).index()).text();
//            $(this).html('<input type="text"  />');
//        });
        
        $('#example').DataTable({
            "bJQueryUI": true,
            "bProcessing": true,
            "bServerSide": true,
            "sServerMethod": "GET",
            "sAjaxSource": "<?php echo site_url('setup/load_setup_data'); ?>",
            "iDisplayLength": 10,
            "aLengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            "aaSorting": [
                [0, 'desc']
            ],
            "sPaginationType": "full_numbers",
            "aoColumns": [
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true
                },
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true
                },
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true
                },
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true
                },
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true
                },
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true
                },
                {
                    "bVisible": true,
                    "bSearchable": true,
                    "bSortable": true
                },
                {
                    "bVisible": true,
                    "bSearchable": false,
                    "bSortable": false
                }
            ]
        }).columns().every(function () {
            // Apply the search
            var that = this;
            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) {
                    that
                            .search(this.value)
                            .draw();
                }
            });
        });
               
    });

</script>

