/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50626
Source Host           : localhost:3306
Source Database       : mercadocentral

Target Server Type    : MYSQL
Target Server Version : 50626
File Encoding         : 65001

Date: 2019-12-09 19:31:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `_actualizacion`
-- ----------------------------
DROP TABLE IF EXISTS `_actualizacion`;
CREATE TABLE `_actualizacion` (
  `act_id` int(11) NOT NULL AUTO_INCREMENT,
  `act_version` varchar(256) NOT NULL,
  `act_fh` datetime NOT NULL,
  `act_script` varchar(256) NOT NULL,
  PRIMARY KEY (`act_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of _actualizacion
-- ----------------------------
INSERT INTO `_actualizacion` VALUES ('1', '5.0.1', '2019-12-09 18:22:43', '../scripts/_actualizacion.sql');
