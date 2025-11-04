/*
 Navicat Premium Dump SQL

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : susu

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 01/11/2025 17:15:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for business
-- ----------------------------
DROP TABLE IF EXISTS `business`;
CREATE TABLE `business`  (
  `agentId` int NOT NULL AUTO_INCREMENT,
  `cdate` datetime NULL DEFAULT NULL,
  `lmodify` datetime NULL DEFAULT NULL,
  `businessId` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `businessName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `midName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `postalAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `digitalAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `district` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`agentId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of business
-- ----------------------------
INSERT INTO `business` VALUES (1, NULL, NULL, '123456678', 'Iquipe', 'Andrew', 'NT', ' Quaye', '0548263738', 'iquipe@outlook.com', 'test', 'test', 'accra', 'accra', 'admin', '$2y$10$nY1iR5W8dYhVCGmtg6wKXOZhKcxmLH.1lo/zVfttqQWN3qvWnUJ8e', NULL);

-- ----------------------------
-- Table structure for clients
-- ----------------------------
DROP TABLE IF EXISTS `clients`;
CREATE TABLE `clients`  (
  `clientId` int NOT NULL AUTO_INCREMENT,
  `agentId` int NULL DEFAULT NULL,
  `cdate` datetime NULL DEFAULT NULL,
  `lmodify` datetime NULL DEFAULT NULL,
  `accountNumber` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `midName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `dob` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `maritalStatus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nationality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `occupation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `postalAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `digitalAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `contactAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nkName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nkRelationship` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nkMobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `passport` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `govtIDcard` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'Active',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`clientId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clients
-- ----------------------------
INSERT INTO `clients` VALUES (1, 1, '2025-11-01 16:42:06', '2025-11-01 16:42:06', '1762015326', 'Andrew', 'Christian University', 'Quaye', 'male', '2025-11-01', 'Single', 'ghana', 'ssss', '0548263738', 'andyquayegh@outlook.com', 'Adenta - Dodowa Road', 'Ghana Christian University College', 'Adenta - Dodowa Road\r\nGhana Christian University College', 'Andrew Quaye', 'father', '0548263738', '', '', 'active');
INSERT INTO `clients` VALUES (2, 1, '2025-11-01 16:46:06', '2025-11-01 16:46:06', '1762015566', 'Andrew', 'Christian University', 'Quaye', 'male', '2025-11-01', 'Single', 'ghana', 'ssss', '0548263738', 'andyquayegh@outlook.com', 'Adenta - Dodowa Road', 'Ghana Christian University College', 'Adenta - Dodowa Road\r\nGhana Christian University College', 'Andrew Quaye', 'father', '0548263738', '', '', 'active');
INSERT INTO `clients` VALUES (3, 1, '2025-11-01 17:03:43', '2025-11-01 17:03:43', '1762016623', 'Andrew', 'Christian University', 'Quaye', 'female', '2025-11-06', 'Married', 'ghana', 'ssss', '0548263738', 'andyquayegh@outlook.com', 'Adenta - Dodowa Road', 'Ghana Christian University College', 'Adenta - Dodowa Road\r\nGhana Christian University College', 'Andrew Christian University Quaye', 'father', '0548263738', '', '', 'active');

-- ----------------------------
-- Table structure for ledger
-- ----------------------------
DROP TABLE IF EXISTS `ledger`;
CREATE TABLE `ledger`  (
  `ledgerId` int NOT NULL AUTO_INCREMENT,
  `agentId` int NULL DEFAULT NULL,
  `clientId` int NULL DEFAULT NULL,
  `dr` decimal(10, 2) NULL DEFAULT 0.00,
  `cr` decimal(10, 2) NULL DEFAULT 0.00,
  `updateDate` datetime NULL DEFAULT current_timestamp() ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ledgerId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ledger
-- ----------------------------

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`  (
  `tranId` int NOT NULL AUTO_INCREMENT,
  `agentId` int NULL DEFAULT NULL,
  `clientId` int NULL DEFAULT NULL,
  `cDate` datetime NULL DEFAULT NULL,
  `tranDate` date NULL DEFAULT NULL,
  `details` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ref` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tranType` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `tranMethod` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `contribution` decimal(10, 2) NULL DEFAULT 0.00,
  `payout` decimal(10, 2) NULL DEFAULT 0.00,
  `amount` decimal(10, 2) NULL DEFAULT 0.00,
  `balance` decimal(10, 2) NULL DEFAULT 0.00,
  `isStatus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT 'pending',
  PRIMARY KEY (`tranId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transactions
-- ----------------------------

-- ----------------------------
-- View structure for get_transaction
-- ----------------------------
DROP VIEW IF EXISTS `get_transaction`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `get_transaction` AS SELECT
	transactions.*, 
	clients.accountNumber, 
	clients.firstName, 
	clients.midName, 
	clients.lastName
FROM
	transactions
	INNER JOIN
	clients
	ON 
		transactions.clientId = clients.clientId ;

SET FOREIGN_KEY_CHECKS = 1;
