/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : robot

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-09-17 17:16:52
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for sicker
-- ----------------------------
DROP TABLE IF EXISTS `sicker`;
CREATE TABLE `sicker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provice` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `county` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `doctor` varchar(255) NOT NULL,
  `doctor_no` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `idcard_no` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
