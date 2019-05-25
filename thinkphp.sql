/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50638
 Source Host           : localhost:3306
 Source Schema         : thinkphp

 Target Server Type    : MySQL
 Target Server Version : 50638
 File Encoding         : 65001

 Date: 25/05/2019 22:45:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for group
-- ----------------------------
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `group_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of group
-- ----------------------------
BEGIN;
INSERT INTO `group` VALUES (1, 1, 1);
COMMIT;

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `pid` int(10) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `condition` varchar(255) DEFAULT NULL,
  `type` int(10) DEFAULT NULL,
  `icon` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of menu
-- ----------------------------
BEGIN;
INSERT INTO `menu` VALUES (1, '角色管理', 0, NULL, 1, NULL, 1, 'el-icon-office-building');
INSERT INTO `menu` VALUES (2, '员工管理', 0, NULL, 1, NULL, 1, 'el-icon-user');
INSERT INTO `menu` VALUES (3, '系统设置', 0, NULL, 1, NULL, 1, 'el-icon-setting');
INSERT INTO `menu` VALUES (4, '添加角色', 1, '/addRole', 1, NULL, 1, NULL);
INSERT INTO `menu` VALUES (5, '角色列表', 1, '/roleList', 1, NULL, 1, NULL);
INSERT INTO `menu` VALUES (6, '权限管理', 1, '/power', 1, NULL, 1, NULL);
INSERT INTO `menu` VALUES (7, '添加员工', 2, '/addStaff', 1, NULL, 1, NULL);
INSERT INTO `menu` VALUES (8, '员工列表', 2, '/staffList', 1, NULL, 1, NULL);
INSERT INTO `menu` VALUES (9, '修改密码', 3, '/changePass', 1, NULL, 1, NULL);
INSERT INTO `menu` VALUES (10, '更改资料', 3, '/edit', 1, NULL, 1, NULL);
COMMIT;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role_desc` varchar(255) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `rules` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
BEGIN;
INSERT INTO `role` VALUES (1, '超级管理员', '2019-05-25 22:42:54', 'Boss~', 1, '1,4,5,6,2,7,8,3,9,10');
INSERT INTO `role` VALUES (2, '客服', '2019-05-25 22:26:26', '企业客服', 1, '3,9,10');
INSERT INTO `role` VALUES (3, '业务员', '2019-05-25 22:27:07', '负责公司业务。', 1, '8,3,9,10');
INSERT INTO `role` VALUES (4, '新员工', '2019-05-25 22:43:45', '公司新晋员工', NULL, '8,3,9,10');
COMMIT;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` char(32) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES (1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '2a03922399ec8ed06f000acc94d1d7b45f61bb95');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
