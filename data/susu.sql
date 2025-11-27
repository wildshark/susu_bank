/*
 Navicat Premium Dump SQL

 Source Server         : iqCloud_dB
 Source Server Type    : MySQL
 Source Server Version : 80042 (8.0.42-0ubuntu0.20.04.1)
 Source Host           : 143.244.198.144:3306
 Source Schema         : susu_bank

 Target Server Type    : MySQL
 Target Server Version : 80042 (8.0.42-0ubuntu0.20.04.1)
 File Encoding         : 65001

 Date: 27/11/2025 15:44:52
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
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clients
-- ----------------------------
INSERT INTO `clients` VALUES (10, 1, '2025-11-02 12:10:57', '2025-11-02 12:10:57', '1762085457', 'Andrew', 'A.sssdddddd', 'Doe', 'male', '2025-11-01', 'Married', 'ghana', 'ssss', '0548263738', 'andyquayegh@outlook.com', 'Adenta - Dodowa Road', 'Ghana Christian University College', 'Adenta - Dodowa Road\r\nGhana Christian University College', 'Andrew Quaye', 'father', '0548263738', '', '', 'active');
INSERT INTO `clients` VALUES (11, 1, '2025-11-04 15:40:54', '2025-11-04 15:40:54', '1762270854', 'super', '', 'x', 'male', '2025-11-04', 'Married', 'Ghanaian', 'computer', '066666633', 'anp@p.com', '34234', '23423', '23423', 'sdf', 'sdfsd', 'sdf', '', '', 'active');

-- ----------------------------
-- Table structure for ledger
-- ----------------------------
DROP TABLE IF EXISTS `ledger`;
CREATE TABLE `ledger`  (
  `ledgerId` int NOT NULL AUTO_INCREMENT,
  `agentId` int NULL DEFAULT NULL,
  `clientId` int NULL DEFAULT NULL,
  `dr` double(10, 2) NULL DEFAULT 0.00,
  `cr` double(10, 2) NULL DEFAULT 0.00,
  `updateDate` datetime NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ledgerId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ledger
-- ----------------------------
INSERT INTO `ledger` VALUES (7, 1, 10, 2400.00, 1100.00, '2025-11-04 06:28:17');
INSERT INTO `ledger` VALUES (8, 1, 11, 10000.00, 3000.00, '2025-11-04 16:27:18');

-- ----------------------------
-- Table structure for staffs
-- ----------------------------
DROP TABLE IF EXISTS `staffs`;
CREATE TABLE `staffs`  (
  `staffId` int NOT NULL,
  `agentId` int NULL DEFAULT NULL,
  `cdate` datetime NULL DEFAULT NULL,
  `lmodify` datetime NULL DEFAULT NULL,
  `staffNum` int NULL DEFAULT NULL,
  `fName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `lName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `oName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `dob` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nationality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `mobile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `postalAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `digitalAdress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `homeAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'active',
  PRIMARY KEY (`staffId`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of staffs
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
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES (12, 1, 10, '2025-11-02 12:11:30', '2025-11-02', '', '34553', 'contribution', 'cash', 1000.00, 0.00, 1000.00, 1000.00, 'pending');
INSERT INTO `transactions` VALUES (13, 1, 10, '2025-11-02 12:11:50', '2025-11-02', '', '34553', 'payout', 'cash', 0.00, 200.00, -200.00, 800.00, 'pending');
INSERT INTO `transactions` VALUES (14, 1, 10, '2025-11-02 13:30:11', '2025-11-01', 'test-iquipe', '34553', 'contribution', 'momo', 500.00, 0.00, 500.00, 1300.00, 'pending');
INSERT INTO `transactions` VALUES (15, 1, 10, '2025-11-02 13:31:12', '2025-11-02', 'test-iquipe', '34553', 'contribution', 'cash', 500.00, 0.00, 500.00, 1800.00, 'pending');
INSERT INTO `transactions` VALUES (16, 1, 10, '2025-11-02 15:00:17', '2025-11-02', 'test-iquipe', '34553', 'payout', 'cash', 0.00, 500.00, -500.00, 1300.00, 'pending');
INSERT INTO `transactions` VALUES (17, 1, 10, '2025-11-04 06:28:05', '2025-11-04', 'test-iquipe', '34553', 'contribution', 'cash', 400.00, 0.00, 400.00, 1700.00, 'pending');
INSERT INTO `transactions` VALUES (18, 1, 10, '2025-11-04 06:28:17', '2025-11-04', 'test-iquipe', '34553', 'payout', 'cash', 0.00, 400.00, -400.00, 1300.00, 'pending');
INSERT INTO `transactions` VALUES (19, 1, 11, '2025-11-04 15:43:05', '2025-11-04', 'cash money', '34232', 'contribution', 'cash', 5000.00, 0.00, 5000.00, 5000.00, 'pending');
INSERT INTO `transactions` VALUES (20, 1, 11, '2025-11-04 15:43:48', '2025-11-04', 'cashout', '43534', 'payout', 'momo', 0.00, 1000.00, -1000.00, 4000.00, 'pending');
INSERT INTO `transactions` VALUES (21, 1, 11, '2025-11-04 16:22:39', '2025-11-04', 'Cashout', '', 'payout', 'momo', 0.00, 2000.00, -2000.00, 2000.00, 'pending');
INSERT INTO `transactions` VALUES (22, 1, 11, '2025-11-04 16:27:18', '2025-11-04', 'CashIn', '', 'contribution', 'cash', 5000.00, 0.00, 5000.00, 7000.00, 'pending');

-- ----------------------------
-- View structure for get_all_transaction
-- ----------------------------
DROP VIEW IF EXISTS `get_all_transaction`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `get_all_transaction` AS select `transactions`.`tranId` AS `tranId`,`transactions`.`agentId` AS `agentId`,`transactions`.`clientId` AS `clientId`,`transactions`.`ref` AS `ref`,`transactions`.`tranType` AS `tranType`,`transactions`.`contribution` AS `contribution`,`transactions`.`payout` AS `payout`,`transactions`.`amount` AS `amount`,`transactions`.`balance` AS `balance`,`transactions`.`isStatus` AS `isStatus`,`transactions`.`tranDate` AS `tranDate`,year(`transactions`.`tranDate`) AS `tranYear`,month(`transactions`.`tranDate`) AS `tranMonth`,dayofmonth(`transactions`.`tranDate`) AS `tranDay`,`transactions`.`cDate` AS `cDate`,`clients`.`accountNumber` AS `accountNumber`,`clients`.`firstName` AS `firstName`,`clients`.`midName` AS `midName`,`clients`.`lastName` AS `lastName`,`transactions`.`tranMethod` AS `tranMethod` from (`transactions` join `clients` on((`transactions`.`clientId` = `clients`.`clientId`))) order by `transactions`.`tranId` desc;

-- ----------------------------
-- View structure for get_ledger
-- ----------------------------
DROP VIEW IF EXISTS `get_ledger`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `get_ledger` AS select `ledger`.`ledgerId` AS `ledgerId`,`ledger`.`agentId` AS `agentId`,`ledger`.`clientId` AS `clientId`,`ledger`.`dr` AS `dr`,`ledger`.`cr` AS `cr`,`ledger`.`updateDate` AS `updateDate`,(`ledger`.`dr` - `ledger`.`cr`) AS `bal`,`clients`.`accountNumber` AS `accountNumber`,`clients`.`firstName` AS `firstName`,`clients`.`midName` AS `midName`,`clients`.`lastName` AS `lastName` from (`ledger` join `clients` on((`ledger`.`clientId` = `clients`.`clientId`)));

-- ----------------------------
-- View structure for get_transaction
-- ----------------------------
DROP VIEW IF EXISTS `get_transaction`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `get_transaction` AS select `clients`.`accountNumber` AS `accountNumber`,`clients`.`firstName` AS `firstName`,`clients`.`midName` AS `midName`,`clients`.`lastName` AS `lastName`,`transactions`.`tranId` AS `tranId`,`transactions`.`agentId` AS `agentId`,`transactions`.`clientId` AS `clientId`,`transactions`.`cDate` AS `cDate`,`transactions`.`tranDate` AS `tranDate`,`transactions`.`details` AS `details`,`transactions`.`ref` AS `ref`,`transactions`.`tranType` AS `tranType`,`transactions`.`tranMethod` AS `tranMethod`,`transactions`.`contribution` AS `contribution`,`transactions`.`payout` AS `payout`,`transactions`.`amount` AS `amount`,`transactions`.`balance` AS `balance`,`transactions`.`isStatus` AS `isStatus` from (`transactions` join `clients` on((`transactions`.`clientId` = `clients`.`clientId`)));

-- ----------------------------
-- View structure for get_transactionbymonthyear
-- ----------------------------
DROP VIEW IF EXISTS `get_transactionbymonthyear`;
CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `get_transactionbymonthyear` AS select `get_all_transaction`.`agentId` AS `agentId`,`get_all_transaction`.`tranYear` AS `tranYear`,`get_all_transaction`.`tranMonth` AS `tranMonth`,sum(`get_all_transaction`.`payout`) AS `total_payout`,count(`get_all_transaction`.`agentId`) AS `numTran` from `get_all_transaction` where (`get_all_transaction`.`tranType` = 'payout') group by `get_all_transaction`.`agentId`,`get_all_transaction`.`tranYear`,`get_all_transaction`.`tranMonth`;

SET FOREIGN_KEY_CHECKS = 1;
