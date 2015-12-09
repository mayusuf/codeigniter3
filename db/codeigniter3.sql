/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50625
Source Host           : localhost:3306
Source Database       : codeigniter3

Target Server Type    : MYSQL
Target Server Version : 50625
File Encoding         : 65001

Date: 2015-09-29 12:08:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `adminId` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `pass` varchar(50) NOT NULL,
  PRIMARY KEY (`adminId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', '0f88029af0facbb3411290aeeb514df4');

-- ----------------------------
-- Table structure for `setup`
-- ----------------------------
DROP TABLE IF EXISTS `setup`;
CREATE TABLE `setup` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `f_name` varchar(100) NOT NULL,
  `l_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(70) NOT NULL,
  `country` varchar(100) NOT NULL,
  `phone` int(11) NOT NULL,
  `age` int(3) NOT NULL,
  `salary` int(4) unsigned NOT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of setup
-- ----------------------------
INSERT INTO `setup` VALUES ('1', 'dvds', 'vdsv', 'svsdvsd', 'svds', ' bsdsd', '4534', '33', '253523', '2015-09-07', '2015-09-08', '1');
INSERT INTO `setup` VALUES ('2', 'sbdsa', 'sdbsd', 'ssd', 'khulna', 'USA', '2543', '54', '1243436', '2015-09-08', '2015-09-08', '1');

-- ----------------------------
-- Table structure for `user_auth`
-- ----------------------------
DROP TABLE IF EXISTS `user_auth`;
CREATE TABLE `user_auth` (
  `u_id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `u_email` varchar(100) NOT NULL,
  `u_pass` char(32) NOT NULL,
  `create_date` date NOT NULL,
  `update_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of user_auth
-- ----------------------------
INSERT INTO `user_auth` VALUES ('2', 'yusuf@netginni.com', 'dd2eb170076a5dec97cdbbbbff9a4405', '2015-08-30', '2015-08-30', '1');
