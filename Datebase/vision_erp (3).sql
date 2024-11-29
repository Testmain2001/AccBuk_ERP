-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2022 at 12:58 PM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vision_erp`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_ledger`
--

CREATE TABLE `account_ledger` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `group_name` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `interst` varchar(20) NOT NULL,
  `credit_limit` double NOT NULL,
  `credit_period` varchar(20) NOT NULL,
  `price_level` double NOT NULL,
  `inventory_allocation` varchar(50) NOT NULL,
  `cost_tracking` varchar(50) NOT NULL,
  `opening_balance` int(11) NOT NULL,
  `mailing` int(11) NOT NULL,
  `bank_reconcilation` int(11) NOT NULL,
  `cheque_book_registor` int(11) NOT NULL,
  `cheque_book_printing` int(11) NOT NULL,
  `tds_tax_details` int(11) NOT NULL,
  `gst_tax_allocation` int(11) NOT NULL,
  `mail_nameforprint` varchar(50) NOT NULL,
  `mail_state` varchar(10) NOT NULL,
  `mail_pin` varchar(20) NOT NULL,
  `mail_contactno1` int(11) NOT NULL,
  `mail_contactno2` int(11) NOT NULL,
  `mail_emailno` varchar(50) NOT NULL,
  `mail_panno` varchar(50) NOT NULL,
  `mail_gstno` varchar(50) NOT NULL,
  `mail_fassaino` varchar(50) NOT NULL,
  `tdstax_deductor` varchar(50) NOT NULL,
  `tdstax_deducteetype` varchar(50) NOT NULL,
  `tdstax_tds_deductionentry` varchar(50) NOT NULL,
  `gsttax_gst_applicable` int(2) NOT NULL,
  `gsttax_calculatefrom` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_ledger`
--

INSERT INTO `account_ledger` (`id`, `user`, `updateduser`, `ClientID`, `Created`, `LastEdited`, `group_name`, `name`, `interst`, `credit_limit`, `credit_period`, `price_level`, `inventory_allocation`, `cost_tracking`, `opening_balance`, `mailing`, `bank_reconcilation`, `cheque_book_registor`, `cheque_book_printing`, `tds_tax_details`, `gst_tax_allocation`, `mail_nameforprint`, `mail_state`, `mail_pin`, `mail_contactno1`, `mail_contactno2`, `mail_emailno`, `mail_panno`, `mail_gstno`, `mail_fassaino`, `tdstax_deductor`, `tdstax_deducteetype`, `tdstax_tds_deductionentry`, `gsttax_gst_applicable`, `gsttax_calculatefrom`) VALUES
('62989ea550ec4', '', '', '623c06fa96782', '2022-06-02 16:57:33', '2022-06-02 16:57:33', '14', 'Aanjali', '1', 1, '1', 0, '1', '1', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0, 0, '', '', '', '', '', '', '', 0, ''),
('62989ebb96cf2', '', '', '623c06fa96782', '2022-06-02 16:57:55', '2022-06-02 16:57:55', '14', 'Manjali', '2', 2, '2', 0, '2', '2', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0, 0, '', '', '', '', '', '', '', 0, ''),
('62989eef88939', '', '', '623c06fa96782', '2022-06-02 16:58:47', '2022-06-02 16:58:47', '18', 'Sonali', '3', 3, '3', 0, '3', '3', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0, 0, '', '', '', '', '', '', '', 0, ''),
('62989f099224d', '', '', '623c06fa96782', '2022-06-02 16:59:13', '2022-06-02 16:59:13', '18', 'monali', '4', 4, '4', 0, '4', '4', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0, 0, '', '', '', '', '', '', '', 0, ''),
('629a0041312ec', '', '', '623c06fa96782', '2022-06-03 18:06:17', '2022-06-03 18:06:17', '8', 'Cash', '', 0, '', 0, '123', '123', 0, 0, 0, 0, 0, 0, 0, '', '', '', 0, 0, '', '', '', '', '', '', '', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `account_ledger_address`
--

CREATE TABLE `account_ledger_address` (
  `id` varchar(25) NOT NULL,
  `al_id` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `address` varchar(50) NOT NULL,
  `Lastedited` datetime NOT NULL,
  `Created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account_ledger_address`
--

INSERT INTO `account_ledger_address` (`id`, `al_id`, `ClientID`, `address`, `Lastedited`, `Created`) VALUES
('62989ea55918e', '62989ea550ec4', '623c06fa96782', '', '2022-06-02 16:57:33', '2022-06-02 16:57:33'),
('62989ebb9fa03', '62989ebb96cf2', '623c06fa96782', '', '2022-06-02 16:57:55', '2022-06-02 16:57:55'),
('62989eef8effd', '62989eef88939', '623c06fa96782', '', '2022-06-02 16:58:47', '2022-06-02 16:58:47'),
('62989f09974e0', '62989f099224d', '623c06fa96782', '', '2022-06-02 16:59:13', '2022-06-02 16:59:13'),
('629a00413cd8e', '629a0041312ec', '623c06fa96782', '', '2022-06-03 18:06:17', '2022-06-03 18:06:17');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transfer`
--

CREATE TABLE `bank_transfer` (
  `id` varchar(20) NOT NULL,
  `recordnumber` int(11) NOT NULL,
  `ClientID` varchar(20) NOT NULL,
  `account_from` varchar(50) NOT NULL,
  `account_to` varchar(50) NOT NULL,
  `balance` double NOT NULL,
  `date` date NOT NULL,
  `amt_pay` double NOT NULL,
  `payment_method` varchar(100) NOT NULL,
  `cheque_no` varchar(20) NOT NULL,
  `Type_Payment` varchar(20) NOT NULL DEFAULT 'Account Transfer',
  `reconciliation` int(10) NOT NULL DEFAULT '0' COMMENT '0-unclear,1-clear,2-bounce',
  `userID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_material`
--

CREATE TABLE `bill_of_material` (
  `id` varchar(15) NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `BOM_type` varchar(40) NOT NULL,
  `product` varchar(25) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `qty` varchar(25) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill_of_material`
--

INSERT INTO `bill_of_material` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `BOM_type`, `product`, `unit`, `qty`, `date`) VALUES
('629dc8a48097b', '623c06fab6b2a', '623c06fa96782', '2022-06-06 14:58:04', '2022-06-06 14:58:04', 'production', '62989fc0e19c8', 'kg', '1', '2022-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `bill_of_material_details`
--

CREATE TABLE `bill_of_material_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(50) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `qty` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bill_of_material_details`
--

INSERT INTO `bill_of_material_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `qty`) VALUES
('629dc8a48117c', '629dc8a48097b', '623c06fa96782', '2022-06-06 14:58:04', '2022-06-06 14:58:04', '6298a015529be', 'kg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `name` mediumtext NOT NULL,
  `email` varchar(30) NOT NULL,
  `mobile` bigint(11) NOT NULL,
  `address` mediumtext NOT NULL,
  `gst_no` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `Created`, `LastEdited`, `name`, `email`, `mobile`, `address`, `gst_no`) VALUES
('623c06fa96782', '2022-03-24 11:21:54', '2022-03-24 11:21:54', 'webotix', 'webotix@gmail.com', 9856235699, 'satara', 'cfasas');

-- --------------------------------------------------------

--
-- Table structure for table `cost_centre`
--

CREATE TABLE `cost_centre` (
  `id` varchar(15) NOT NULL,
  `name` mediumtext NOT NULL,
  `under_group` varchar(15) NOT NULL,
  `ClientID` varchar(30) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_note`
--

CREATE TABLE `credit_note` (
  `id` varchar(15) NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `record_no` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `credit_type` varchar(25) NOT NULL,
  `sale_invoiceno` varchar(25) NOT NULL,
  `customer` varchar(25) NOT NULL,
  `amt_pay` varchar(25) NOT NULL,
  `payment_method` varchar(25) NOT NULL,
  `narration` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `credit_note_details`
--

CREATE TABLE `credit_note_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(30) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `cgst` double NOT NULL,
  `sgst` double NOT NULL,
  `igst` double NOT NULL,
  `qty` double NOT NULL,
  `returnqty` int(10) NOT NULL,
  `rate` double NOT NULL,
  `total` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `updateduser` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `currency_symbol` mediumtext NOT NULL,
  `formal_name` mediumtext NOT NULL,
  `decimal_places` mediumtext NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `debit_note`
--

CREATE TABLE `debit_note` (
  `id` varchar(15) NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `record_no` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `supplier` varchar(25) NOT NULL,
  `amt_pay` varchar(25) NOT NULL,
  `bankid` varchar(25) NOT NULL,
  `balance` varchar(25) NOT NULL,
  `cheque_no` varchar(25) NOT NULL,
  `payment_method` varchar(25) NOT NULL,
  `narration` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `debit_note`
--

INSERT INTO `debit_note` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `record_no`, `date`, `voucher_type`, `supplier`, `amt_pay`, `bankid`, `balance`, `cheque_no`, `payment_method`, `narration`) VALUES
('629a0062d6c3a', '623c06fab6b2a', '623c06fa96782', '2022-06-03 18:06:50', '2022-06-03 18:20:52', '1', '2022-06-03', '6299ffb1a61d2', '62989eef88939', '100', '629a0041312ec', '', '', 'cash', 'dddfdf');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_challan`
--

CREATE TABLE `delivery_challan` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `challan_no` int(10) NOT NULL,
  `date` date NOT NULL,
  `customer` varchar(50) NOT NULL,
  `saleorder_no` varchar(25) NOT NULL,
  `total_quantity` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_challan`
--

INSERT INTO `delivery_challan` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `challan_no`, `date`, `customer`, `saleorder_no`, `total_quantity`) VALUES
('629af5da74aa2', '623c06fab6b2a', '623c06fa96782', '2022-06-04 11:34:10', '2022-06-04 11:34:10', 1, '2022-06-04', '62989ea550ec4', '629af5c55ac4b', 7),
('629de518415e8', '623c06fab6b2a', '623c06fa96782', '2022-06-06 16:59:28', '2022-06-06 16:59:28', 2, '2022-06-06', '62989ea550ec4', '629af5c55ac4b', 10);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_challan_details`
--

CREATE TABLE `delivery_challan_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(30) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `qty` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `delivery_challan_details`
--

INSERT INTO `delivery_challan_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `qty`) VALUES
('629af5da74e4d', '629af5da74aa2', '623c06fa96782', '2022-06-04 11:34:10', '2022-06-04 11:34:10', '62989fc0e19c8', 'kg', 5),
('629af5da7523b', '629af5da74aa2', '623c06fa96782', '2022-06-04 11:34:10', '2022-06-04 11:34:10', '6298a015529be', 'kg', 2),
('629de51841947', '629de518415e8', '623c06fa96782', '2022-06-06 16:59:28', '2022-06-06 16:59:28', '62989fc0e19c8', 'kg', 5),
('629de51841c5c', '629de518415e8', '623c06fa96782', '2022-06-06 16:59:28', '2022-06-06 16:59:28', '6298a015529be', 'kg', 5);

-- --------------------------------------------------------

--
-- Table structure for table `dispatch`
--

CREATE TABLE `dispatch` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `record_no` bigint(10) NOT NULL,
  `date` date NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `sale_invoice_no` varchar(200) NOT NULL,
  `total_quantity` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dispatch_details`
--

CREATE TABLE `dispatch_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `qty` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `name` mediumtext NOT NULL,
  `mobile` bigint(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `user`, `updateduser`, `ClientID`, `Created`, `LastEdited`, `name`, `mobile`, `email`, `password`, `role`) VALUES
('623c06fab6b2a', '', '', '623c06fa96782', '2022-03-24 11:21:54', '2022-03-24 11:21:54', 'Shivani', 8080596630, 'shiv@gmail.com', 'XnCOFXzvzFGHXS/GZ5kVEZ9PAE2N+oCeqydK87yGuwo=', '1');

-- --------------------------------------------------------

--
-- Table structure for table `fixed_vouchertype`
--

CREATE TABLE `fixed_vouchertype` (
  `id` int(11) NOT NULL,
  `voucher_name` varchar(50) NOT NULL,
  `Created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fixed_vouchertype`
--

INSERT INTO `fixed_vouchertype` (`id`, `voucher_name`, `Created`) VALUES
(1, 'Sales', '2022-04-14 11:46:18'),
(2, 'Purchase', '2022-04-14 11:46:18'),
(3, 'Journal', '2022-04-14 11:46:18'),
(4, 'Credit Note / Sales Return', '2022-04-14 11:46:18'),
(5, 'Debit Note / Purchase Return', '2022-04-14 11:46:18'),
(6, 'Payment', '2022-04-14 11:46:18'),
(7, 'Receipt', '2022-04-14 11:46:18'),
(8, 'Contra ', '2022-04-14 11:46:18'),
(9, 'Inward', '2022-04-14 11:46:18'),
(10, 'Transfer to Location', '2022-04-14 11:46:18'),
(11, 'Process', '2022-04-14 11:46:18'),
(12, 'Packing', '2022-04-14 11:46:18'),
(13, 'Dispatch', '2022-04-14 11:46:18'),
(14, 'Physical Stock ', '2022-04-14 11:46:18'),
(15, 'Attendance ', '2022-04-14 11:46:18'),
(16, 'Payroll ', '2022-04-14 11:46:18'),
(17, 'Advance ', '2022-04-14 11:46:18'),
(18, 'Contribution', '2022-04-14 11:46:18'),
(19, 'Overtime', '2022-04-14 11:46:18'),
(20, 'GST', '2022-04-14 11:46:18'),
(21, 'TDS', '2022-04-14 11:46:18'),
(22, 'Income Tax / Advance Tax', '2022-04-14 11:46:18'),
(23, 'Purchase Order', '2022-04-14 11:46:18'),
(24, 'Sales Order', '2022-04-14 11:46:18'),
(25, 'Job work Order', '2022-04-14 11:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `grn`
--

CREATE TABLE `grn` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `grn_no` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` varchar(25) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `purchaseorder_no` varchar(25) NOT NULL,
  `supplier` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grn`
--

INSERT INTO `grn` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `grn_no`, `date`, `type`, `voucher_type`, `purchaseorder_no`, `supplier`, `location`) VALUES
('6298afd7e06b3', '623c06fab6b2a', '623c06fa96782', '2022-06-02 18:10:55', '2022-06-02 18:10:55', 1, '2022-06-02', 'Direct_Purchase', '6298abd39a0c1', '', '62989eef88939', '62989f36e114d');

-- --------------------------------------------------------

--
-- Table structure for table `grn_details`
--

CREATE TABLE `grn_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `qty` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grn_details`
--

INSERT INTO `grn_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `qty`) VALUES
('6298afd7e0b53', '6298afd7e06b3', '623c06fa96782', '2022-06-02 18:10:55', '2022-06-02 18:10:55', '62989fc0e19c8', 'kg', 10);

-- --------------------------------------------------------

--
-- Table structure for table `group_master`
--

CREATE TABLE `group_master` (
  `id` varchar(15) NOT NULL,
  `group_name` mediumtext NOT NULL,
  `parent_group` mediumtext NOT NULL,
  `report` mediumtext NOT NULL,
  `sub_report` mediumtext NOT NULL,
  `report_type` mediumtext NOT NULL,
  `flag` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_master`
--

INSERT INTO `group_master` (`id`, `group_name`, `parent_group`, `report`, `sub_report`, `report_type`, `flag`) VALUES
('1', 'Branches', '', 'Balance Sheet', 'Assets', '', 0),
('3', 'Drawings', 'Capital Account', 'Balance Sheet', 'Liability', 'Capital', 0),
('2', 'Capital Account', '', 'Balance Sheet', 'Liability', '', 0),
('4', 'Deductions', 'Capital Account', 'Balance Sheet', 'Liability', 'Capital', 0),
('5', 'Reserves & Surplus', 'Capital Account', 'Balance Sheet', 'Liability', 'Capital', 0),
('6', 'Current Assets', '', 'Balance Sheet', 'Assets', '', 0),
('7', 'Bank Accounts', 'Current Assets', 'Balance Sheet', 'Assets', 'Current Assets', 0),
('8', 'Cash-in-hand', 'Current Assets', 'Balance Sheet', 'Assets', 'Current Assets', 0),
('9', 'Deposits (Asset)', 'Current Assets', 'Balance Sheet', 'Assets', 'Current Assets', 0),
('10', 'Loans & Advances (Asset)', 'Current Assets', 'Balance Sheet', 'Assets', 'Current Assets', 0),
('11', 'Employee Advances', 'Current Assets', 'Balance Sheet', 'Assets', 'Loans & Advances', 0),
('12', 'Other Advances', 'Current Assets', 'Balance Sheet', 'Assets', 'Loans & Advances', 0),
('13', 'Stock-in-hand', 'Current Assets', 'Balance Sheet', 'Assets', 'Current Assets', 0),
('14', 'Sundry Debtors', 'Current Assets', 'Balance Sheet', 'Assets', 'Current Assets', 0),
('15', 'Current Liabilities', '', 'Balance Sheet', 'Assets', '', 0),
('16', 'Duties & Taxes', 'Current Liabilities', 'Balance Sheet', 'Liability', 'Current Liability', 0),
('17', 'Provisions', 'Current Liabilities', 'Balance Sheet', 'Liability', 'Current Liability', 0),
('18', 'Sundry Creditors', 'Current Liabilities', 'Balance Sheet', 'Liability', 'Current Liability', 0),
('19', 'Fixed Assets', '', 'Balance Sheet', 'Assets', '', 0),
('20', 'Investments', '', 'Balance Sheet', 'Assets', '', 0),
('21', 'Loans (Liability)', '', 'Balance Sheet', 'Liability', '', 0),
('22', 'Bank OD A/c', 'Loans (Liability)', 'Balance Sheet', 'Liability', 'Loans', 0),
('23', 'Secured Loans', 'Loans (Liability)', 'Balance Sheet', 'Liability', 'Loans', 0),
('24', 'Unsecured Loans', 'Loans (Liability)', 'Balance Sheet', 'Liability', 'Loans', 0),
('25', 'Misc. Expenses (ASSET)', '', 'Balance Sheet', 'Assets', '', 0),
('26', 'Suspense A/c', '', 'Balance Sheet', 'Assets', '', 0),
('27', 'Purchase Accounts', '', 'Profit & Loss', 'Trading', 'Debit', 0),
('28', 'Sales Accounts', '', 'Profit & Loss', 'Trading', 'Crebit', 0),
('30', 'Direct Incomes', '', 'Profit & Loss', 'Trading', 'Crebit', 0),
('32', 'Indirect Incomes', '', 'Profit & Loss', 'Trading', 'Crebit', 0),
('29', 'Direct Expenses', '', 'Profit & Loss', 'Trading', 'Debit', 0),
('31', 'Indirect Expenses', '', 'Profit & Loss', 'Trading', 'Debit', 0),
('62414c42b88ee', 'Demo', '3', 'Balance Sheet', 'Liability', 'Capital', 1);

-- --------------------------------------------------------

--
-- Table structure for table `journal_entry`
--

CREATE TABLE `journal_entry` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(25) NOT NULL,
  `recordnumber` int(50) NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `date` date NOT NULL,
  `total_of_debitamt` double NOT NULL,
  `total_of_creditamt` double NOT NULL,
  `record` varchar(10) NOT NULL,
  `account` varchar(25) NOT NULL,
  `debit_amount` double NOT NULL,
  `credit_amount` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `id` varchar(15) NOT NULL,
  `name` mediumtext NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `under_group` varchar(15) NOT NULL,
  `negative_stk_block` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`id`, `name`, `ClientID`, `under_group`, `negative_stk_block`, `Created`, `LastEdited`) VALUES
('62989f36e114d', 'satara', '623c06fa96782', 'Primary', 0, '2022-06-02 16:59:58', '2022-06-02 16:59:58'),
('62989f5198d4e', 'pune', '623c06fa96782', 'Primary', 0, '2022-06-02 17:00:25', '2022-06-02 17:00:25'),
('62989f76a6f1c', 'sangali', '623c06fa96782', 'Primary', 0, '2022-06-02 17:01:02', '2022-06-02 17:01:02');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `page` varchar(50) NOT NULL,
  `subpage` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `page`, `subpage`) VALUES
('m_1', 'Master', '', ''),
('m_10', 'Outstanding Reports', '', ''),
('m_2', 'Purchase', '', ''),
('m_3', 'Sales', '', ''),
('m_4', 'Production', '', ''),
('m_5', 'Bank', '', ''),
('m_6', 'Stock Mgt', '', ''),
('m_7', 'Reports', '', ''),
('m_8', 'Acoount Registor', '', ''),
('m_9', 'Inventory Reports', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `packaging`
--

CREATE TABLE `packaging` (
  `id` varchar(15) NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `batch_no` varchar(25) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  `product` varchar(25) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `qty` varchar(25) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `packaging_details`
--

CREATE TABLE `packaging_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `requiredqty` double NOT NULL,
  `qty` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `physical_stock`
--

CREATE TABLE `physical_stock` (
  `id` varchar(20) NOT NULL,
  `user` varchar(20) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `record_no` bigint(20) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `physical_stock`
--

INSERT INTO `physical_stock` (`id`, `user`, `updateduser`, `ClientID`, `Created`, `LastEdited`, `record_no`, `voucher_type`, `date`, `location`) VALUES
('6299e5b29495d', '623c06fab6b2a', '623c06fab6b2a', '623c06fa96782', '2022-06-03 16:12:58', '2022-06-03 16:16:02', 1, '', '2022-06-03', '62989f36e114d');

-- --------------------------------------------------------

--
-- Table structure for table `physical_stock_details`
--

CREATE TABLE `physical_stock_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `physicalstock` double NOT NULL,
  `stock` double NOT NULL,
  `addstock` double NOT NULL,
  `lessstock` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `physical_stock_details`
--

INSERT INTO `physical_stock_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `physicalstock`, `stock`, `addstock`, `lessstock`) VALUES
('6299e66aae9c4', '6299e5b29495d', '623c06fa96782', '2022-06-03 16:16:02', '2022-06-03 16:16:02', '62989fc0e19c8', 'kg', 101, 99, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pricelist`
--

CREATE TABLE `pricelist` (
  `id` varchar(15) NOT NULL,
  `common_id` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `stock_gruop` varchar(15) NOT NULL,
  `price_level` mediumtext NOT NULL,
  `applicable_date` date NOT NULL,
  `particulars` varchar(15) NOT NULL,
  `from_qty` double NOT NULL,
  `less_qty` double NOT NULL,
  `rate` double NOT NULL,
  `discount` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

CREATE TABLE `production` (
  `id` varchar(15) NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `batch_no` varchar(25) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  `product` varchar(25) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `qty` varchar(25) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `production`
--

INSERT INTO `production` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `batch_no`, `voucher_type`, `location`, `product`, `unit`, `qty`, `date`) VALUES
('629dc9a8530bf', '623c06fab6b2a', '623c06fa96782', '2022-06-06 15:02:24', '2022-06-06 15:02:24', '1', '629dc8de954ca', '62989f36e114d', '62989fc0e19c8', 'kg', '2', '2022-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `production_details`
--

CREATE TABLE `production_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `requiredqty` double NOT NULL,
  `qty` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `production_details`
--

INSERT INTO `production_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `requiredqty`, `qty`) VALUES
('629dc9a853469', '629dc9a8530bf', '623c06fa96782', '2022-06-06 15:02:24', '2022-06-06 15:02:24', '6298a015529be', 'kg', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice`
--

CREATE TABLE `purchase_invoice` (
  `id` varchar(20) NOT NULL,
  `user` varchar(50) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `invoicenumber` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `supplier` varchar(50) NOT NULL,
  `location` varchar(25) NOT NULL,
  `type` varchar(25) NOT NULL,
  `purchaseorder_no` varchar(25) NOT NULL,
  `other` varchar(25) NOT NULL,
  `grandtotal` double NOT NULL,
  `transcost` varchar(25) NOT NULL,
  `transgst` varchar(25) NOT NULL,
  `transamount` double NOT NULL,
  `subt` double NOT NULL,
  `trans` varchar(100) NOT NULL,
  `totcst_amt` double NOT NULL,
  `totsgst_amt` varchar(25) NOT NULL,
  `totigst_amt` double NOT NULL,
  `tcs_tds` varchar(50) NOT NULL,
  `tcs_tds_percen` double NOT NULL,
  `tcs_tds_amt` double NOT NULL,
  `roff` varchar(200) NOT NULL,
  `otrnar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_invoice`
--

INSERT INTO `purchase_invoice` (`id`, `user`, `updateduser`, `ClientID`, `Created`, `LastEdited`, `invoicenumber`, `date`, `voucher_type`, `supplier`, `location`, `type`, `purchaseorder_no`, `other`, `grandtotal`, `transcost`, `transgst`, `transamount`, `subt`, `trans`, `totcst_amt`, `totsgst_amt`, `totigst_amt`, `tcs_tds`, `tcs_tds_percen`, `tcs_tds_amt`, `roff`, `otrnar`) VALUES
('629ddeacbeefc', '623c06fab6b2a', '', '623c06fa96782', '2022-06-06 16:32:04', '2022-06-06 16:32:04', '1', '2022-06-06', '6298a10900a05', '62989eef88939', '62989f36e114d', 'Direct_Purchase', '', '0', 505, '0', '0', 0, 500, '0', 0, '0.00', 5, '', 0, 0, '0', ''),
('629de47aac1bd', '623c06fab6b2a', '', '623c06fa96782', '2022-06-06 16:56:50', '2022-06-06 16:56:50', '2', '2022-06-06', '6298a10900a05', '62989eef88939', '62989f36e114d', 'Direct_Purchase', '', '0', 7.13, '0', '0', 0, 7, '0', 0, '0.00', 0.13, '', 0, 0, '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_invoice_details`
--

CREATE TABLE `purchase_invoice_details` (
  `id` varchar(20) NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(25) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `cgst` double NOT NULL,
  `sgst` double NOT NULL,
  `igst` double NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL,
  `disc` double NOT NULL,
  `taxable` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_invoice_details`
--

INSERT INTO `purchase_invoice_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `cgst`, `sgst`, `igst`, `qty`, `rate`, `disc`, `taxable`, `total`) VALUES
('629ddeacc6f1a', '629ddeacbeefc', '623c06fa96782', '2022-06-06 16:32:04', '2022-06-06 16:32:04', '62989fc0e19c8', 'kg', 0, 0, 1, 100, 2, 0, 200, 202),
('629ddeaccd329', '629ddeacbeefc', '623c06fa96782', '2022-06-06 16:32:04', '2022-06-06 16:32:04', '6298a015529be', 'kg', 0, 0, 1, 100, 3, 0, 300, 303),
('629de47ab371e', '629de47aac1bd', '623c06fa96782', '2022-06-06 16:56:50', '2022-06-06 16:56:50', '62989fc0e19c8', 'kg', 0, 0, 1, 2, 2, 0, 4, 4.04),
('629de47ab665d', '629de47aac1bd', '623c06fa96782', '2022-06-06 16:56:50', '2022-06-06 16:56:50', '6298a015529be', 'kg', 0, 0, 3, 3, 1, 0, 3, 3.09);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `order_no` int(11) NOT NULL,
  `date` date NOT NULL,
  `type` varchar(25) NOT NULL,
  `requisition_no` varchar(25) NOT NULL,
  `supplier` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_order`
--

INSERT INTO `purchase_order` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `order_no`, `date`, `type`, `requisition_no`, `supplier`, `location`) VALUES
('6298a3b4c214e', '623c06fab6b2a', '623c06fa96782', '2022-06-02 17:19:08', '2022-06-02 17:19:08', 1, '2022-06-02', 'Direct_Purchase', '', '62989eef88939', '62989f36e114d');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_details`
--

CREATE TABLE `purchase_order_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `cgst` double NOT NULL,
  `sgst` double NOT NULL,
  `igst` double NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_order_details`
--

INSERT INTO `purchase_order_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `cgst`, `sgst`, `igst`, `qty`, `rate`) VALUES
('6298a3b4c256d', '6298a3b4c214e', '623c06fa96782', '2022-06-02 17:19:08', '2022-06-02 17:19:08', '62989fc0e19c8', 'kg', 0, 0, 1, 10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payment`
--

CREATE TABLE `purchase_payment` (
  `id` varchar(20) NOT NULL,
  `user` varchar(20) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `recordnumber` bigint(20) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `supplier` varchar(50) NOT NULL COMMENT 'vendorid',
  `ptype` varchar(20) NOT NULL,
  `paymentdate` date NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `bankid` varchar(25) NOT NULL,
  `balance` varchar(15) NOT NULL,
  `cheque_no` varchar(25) NOT NULL,
  `amt_pay` float NOT NULL,
  `narration` mediumtext NOT NULL,
  `Type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_payment`
--

INSERT INTO `purchase_payment` (`id`, `user`, `updateduser`, `ClientID`, `Created`, `LastEdited`, `recordnumber`, `voucher_type`, `supplier`, `ptype`, `paymentdate`, `payment_method`, `bankid`, `balance`, `cheque_no`, `amt_pay`, `narration`, `Type`) VALUES
('629de4b49b52e', '623c06fab6b2a', '', '623c06fa96782', '2022-06-06 16:57:48', '2022-06-06 16:57:48', 1, '629b51f599a4d', '62989eef88939', 'PO', '2022-06-06', 'cash', '629a0041312ec', '', '', 14, 'hello', 'Payment');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_payment_details`
--

CREATE TABLE `purchase_payment_details` (
  `id` varchar(20) NOT NULL,
  `ClientID` varchar(20) NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `purchaseid` varchar(1000) NOT NULL,
  `amount` double NOT NULL,
  `discount` double NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_payment_details`
--

INSERT INTO `purchase_payment_details` (`id`, `ClientID`, `parent_id`, `purchaseid`, `amount`, `discount`, `date`) VALUES
('629de4b4a37f8', '623c06fa96782', '629de4b49b52e', '629de47aac1bd', 4, 0, '2022-06-06'),
('629de4b4a99e2', '623c06fa96782', '629de4b49b52e', '629ddeacbeefc', 10, 0, '2022-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition`
--

CREATE TABLE `purchase_requisition` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `record_no` int(11) NOT NULL,
  `date` date NOT NULL,
  `requisition_by` mediumtext NOT NULL,
  `location` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_requisition`
--

INSERT INTO `purchase_requisition` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `record_no`, `date`, `requisition_by`, `location`) VALUES
('6298a39e2bd7e', '623c06fab6b2a', '623c06fa96782', '2022-06-02 17:18:46', '2022-06-02 17:18:46', 1, '2022-06-02', 'hello', '62989f36e114d');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_details`
--

CREATE TABLE `purchase_requisition_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `qty` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purchase_requisition_details`
--

INSERT INTO `purchase_requisition_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `qty`) VALUES
('6298a39e2c375', '6298a39e2bd7e', '623c06fa96782', '2022-06-02 17:18:46', '2022-06-02 17:18:46', '62989fc0e19c8', 'kg', 2);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return`
--

CREATE TABLE `purchase_return` (
  `id` varchar(20) NOT NULL,
  `user` varchar(50) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `recordnumber` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `supplier` varchar(50) NOT NULL,
  `location` varchar(25) NOT NULL,
  `purchase_invoice_no` varchar(25) NOT NULL,
  `other` varchar(25) NOT NULL,
  `grandtotal` double NOT NULL,
  `transcost` varchar(25) NOT NULL,
  `transgst` varchar(25) NOT NULL,
  `transamount` double NOT NULL,
  `subt` double NOT NULL,
  `trans` varchar(100) NOT NULL,
  `totcst_amt` double NOT NULL,
  `totsgst_amt` double NOT NULL,
  `totigst_amt` double NOT NULL,
  `tcs_tds` varchar(50) NOT NULL,
  `tcs_tds_percen` double NOT NULL,
  `tcs_tds_amt` double NOT NULL,
  `roff` varchar(200) NOT NULL,
  `otrnar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_details`
--

CREATE TABLE `purchase_return_details` (
  `id` varchar(20) NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(25) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `cgst` double NOT NULL,
  `sgst` double NOT NULL,
  `igst` double NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL,
  `disc` double NOT NULL,
  `taxable` double NOT NULL,
  `rejectedqty` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role_master`
--

CREATE TABLE `role_master` (
  `id` varchar(20) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `created` datetime NOT NULL,
  `lastedited` datetime NOT NULL,
  `role` varchar(50) NOT NULL,
  `menu` longtext NOT NULL,
  `createMenu` longtext NOT NULL,
  `editMenu` longtext NOT NULL,
  `deleteMenu` longtext NOT NULL,
  `viewMenu` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role_master`
--

INSERT INTO `role_master` (`id`, `ClientID`, `created`, `lastedited`, `role`, `menu`, `createMenu`, `editMenu`, `deleteMenu`, `viewMenu`) VALUES
('1', '623c06fa96782', '2022-03-24 11:46:24', '2022-06-06 14:46:43', 'Admin', 'm_1,s_109,s_111,s_103,s_107,s_101,s_102,s_106,s_108,s_100,s_104,s_105,s_110,m_10,s_1001,s_1000,m_2,s_205,s_202,s_203,s_201,s_204,s_200,s_206,m_3,s_305,s_301,s_303,s_304,s_306,s_300,m_4,s_403,s_401,s_400,s_402,m_5,s_500,s_501,m_6,s_600,s_601,s_700,s_807,s_806,s_802,s_803,s_808,s_804,s_801,s_805,s_800,m_9,s_910,s_902,s_903,s_909,s_904,s_908,s_906,s_905,s_907,s_901,s_900', 'm_1,s_109,s_111,s_103,s_107,s_101,s_102,s_106,s_108,s_100,s_104,s_105,s_110,m_10,s_1001,s_1000,m_2,s_205,s_202,s_203,s_201,s_204,s_200,s_206,m_3,s_305,s_301,s_303,s_304,s_306,s_300,m_4,s_403,s_401,s_400,s_402,m_5,s_500,s_501,m_6,s_600,s_601,s_700,s_807,s_806,s_802,s_803,s_808,s_804,s_801,s_805,s_800,m_9,s_910,s_902,s_903,s_909,s_904,s_908,s_906,s_905,s_907,s_901,s_900', 'm_1,s_109,s_111,s_103,s_107,s_101,s_102,s_106,s_108,s_100,s_104,s_105,s_110,m_10,s_1001,s_1000,m_2,s_205,s_202,s_203,s_201,s_204,s_200,s_206,m_3,s_305,s_301,s_303,s_304,s_306,s_300,m_4,s_403,s_401,s_400,s_402,m_5,s_500,s_501,m_6,s_600,s_601,s_700,s_807,s_806,s_802,s_803,s_808,s_804,s_801,s_805,s_800,m_9,s_910,s_902,s_903,s_909,s_904,s_908,s_906,s_905,s_907,s_901,s_900', 'm_1,s_109,s_111,s_103,s_107,s_101,s_102,s_106,s_108,s_100,s_104,s_105,s_110,m_10,s_1001,s_1000,m_2,s_205,s_202,s_203,s_201,s_204,s_200,s_206,m_3,s_305,s_301,s_303,s_304,s_306,s_300,m_4,s_403,s_401,s_400,s_402,m_5,s_500,s_501,m_6,s_600,s_601,s_700,s_807,s_806,s_802,s_803,s_808,s_804,s_801,s_805,s_800,m_9,s_910,s_902,s_903,s_909,s_904,s_908,s_906,s_905,s_907,s_901,s_900', 'm_1,s_109,s_111,s_103,s_107,s_101,s_102,s_106,s_108,s_100,s_104,s_105,s_110,m_10,s_1001,s_1000,m_2,s_205,s_202,s_203,s_201,s_204,s_200,s_206,m_3,s_305,s_301,s_303,s_304,s_306,s_300,m_4,s_403,s_401,s_400,s_402,m_5,s_500,s_501,m_6,s_600,s_601,s_700,s_807,s_806,s_802,s_803,s_808,s_804,s_801,s_805,s_800,m_9,s_910,s_902,s_903,s_909,s_904,s_908,s_906,s_905,s_907,s_901,s_900');

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoice`
--

CREATE TABLE `sale_invoice` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `sale_invoiceno` int(10) NOT NULL,
  `date` date NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `delivery_challan_no` varchar(200) NOT NULL,
  `total_quantity` double NOT NULL,
  `grandtotal` double NOT NULL,
  `other` varchar(25) NOT NULL,
  `transcost` varchar(25) NOT NULL,
  `transgst` varchar(25) NOT NULL,
  `transamount` double NOT NULL,
  `subt` double NOT NULL,
  `trans` varchar(100) NOT NULL,
  `totcst_amt` double NOT NULL,
  `totsgst_amt` varchar(25) NOT NULL,
  `totigst_amt` double NOT NULL,
  `tcs_tds` varchar(50) NOT NULL,
  `tcs_tds_percen` double NOT NULL,
  `tcs_tds_amt` double NOT NULL,
  `roff` varchar(200) NOT NULL,
  `otrnar` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_invoice`
--

INSERT INTO `sale_invoice` (`id`, `user`, `updateduser`, `ClientID`, `Created`, `LastEdited`, `sale_invoiceno`, `date`, `voucher_type`, `location`, `customer`, `delivery_challan_no`, `total_quantity`, `grandtotal`, `other`, `transcost`, `transgst`, `transamount`, `subt`, `trans`, `totcst_amt`, `totsgst_amt`, `totigst_amt`, `tcs_tds`, `tcs_tds_percen`, `tcs_tds_amt`, `roff`, `otrnar`) VALUES
('629b048ed13c9', '623c06fab6b2a', '', '623c06fa96782', '2022-06-04 12:36:54', '2022-06-04 12:39:05', 1, '2022-06-04', '6298a177cc3e3', '62989f36e114d', '62989ea550ec4', '629af5da74aa2', 2, 4.2, '0', '0', '0', 0, 4, '0', 0, '0.00', 0.2, '', 0, 0, '0', ''),
('629de85721796', '623c06fab6b2a', '', '623c06fa96782', '2022-06-06 17:13:19', '2022-06-06 17:13:19', 2, '2022-06-06', '6298a177cc3e3', '62989f36e114d', '62989ea550ec4', '629de518415e8', 2, 3.12, '0', '0', '0', 0, 3, '0', 0, '0.00', 0.12, '', 0, 0, '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `sale_invoice_details`
--

CREATE TABLE `sale_invoice_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `cgst` double NOT NULL,
  `sgst` double NOT NULL,
  `igst` double NOT NULL,
  `orderqty` double NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL,
  `taxable` double NOT NULL,
  `total` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_invoice_details`
--

INSERT INTO `sale_invoice_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `cgst`, `sgst`, `igst`, `orderqty`, `qty`, `rate`, `taxable`, `total`) VALUES
('629de85721ccd', '629de85721796', '623c06fa96782', '2022-06-06 17:13:19', '2022-06-06 17:13:19', '62989fc0e19c8', 'kg', 0, 0, 5, 5, 1, 2, 2, 2.1),
('629b05114f250', '629b048ed13c9', '623c06fa96782', '2022-06-04 12:39:05', '2022-06-04 12:39:05', '62989fc0e19c8', 'kg', 0, 0, 5, 5, 2, 2, 4, 4.2),
('629de85721fe7', '629de85721796', '623c06fa96782', '2022-06-06 17:13:19', '2022-06-06 17:13:19', '6298a015529be', 'kg', 0, 0, 2, 5, 1, 1, 1, 1.02);

-- --------------------------------------------------------

--
-- Table structure for table `sale_order`
--

CREATE TABLE `sale_order` (
  `id` varchar(15) NOT NULL,
  `user` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `order_no` int(10) NOT NULL,
  `date` date NOT NULL,
  `customer` varchar(50) NOT NULL,
  `location` varchar(25) NOT NULL,
  `address` varchar(200) NOT NULL,
  `grandtotal` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_order`
--

INSERT INTO `sale_order` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `order_no`, `date`, `customer`, `location`, `address`, `grandtotal`) VALUES
('629af5c55ac4b', '623c06fab6b2a', '623c06fa96782', '2022-06-04 11:33:49', '2022-06-04 11:33:49', 1, '2022-06-04', '62989ea550ec4', '62989f36e114d', 'satara', 65),
('629de3463eadb', '623c06fab6b2a', '623c06fa96782', '2022-06-06 16:51:42', '2022-06-06 16:51:42', 2, '2022-06-06', '62989ebb96cf2', '62989f5198d4e', 'dhjdyhj', 6);

-- --------------------------------------------------------

--
-- Table structure for table `sale_order_details`
--

CREATE TABLE `sale_order_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `cgst` double NOT NULL,
  `sgst` double NOT NULL,
  `igst` double NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL,
  `total` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_order_details`
--

INSERT INTO `sale_order_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `cgst`, `sgst`, `igst`, `qty`, `rate`, `total`) VALUES
('629af5c55afb2', '629af5c55ac4b', '623c06fa96782', '2022-06-04 11:33:49', '2022-06-04 11:33:49', '62989fc0e19c8', 'kg', 0, 0, 5, 20, 2, 40),
('629af5c55b2ae', '629af5c55ac4b', '623c06fa96782', '2022-06-04 11:33:49', '2022-06-04 11:33:49', '6298a015529be', 'kg', 0, 0, 2, 25, 1, 25),
('629de3463efa4', '629de3463eadb', '623c06fa96782', '2022-06-06 16:51:42', '2022-06-06 16:51:42', '6298a015529be', 'kg', 0, 0, 1, 2, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `sale_receipt`
--

CREATE TABLE `sale_receipt` (
  `id` varchar(20) NOT NULL,
  `user` varchar(20) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `recordnumber` bigint(20) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `location` varchar(50) NOT NULL,
  `customer` varchar(50) NOT NULL COMMENT 'customerid',
  `ptype` varchar(20) NOT NULL,
  `receiptdate` date NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `bankid` varchar(20) NOT NULL,
  `balance` varchar(15) NOT NULL,
  `cheque_no` varchar(20) NOT NULL,
  `amt_pay` float NOT NULL,
  `narration` mediumtext NOT NULL,
  `Type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_receipt`
--

INSERT INTO `sale_receipt` (`id`, `user`, `updateduser`, `ClientID`, `Created`, `LastEdited`, `recordnumber`, `voucher_type`, `location`, `customer`, `ptype`, `receiptdate`, `payment_method`, `bankid`, `balance`, `cheque_no`, `amt_pay`, `narration`, `Type`) VALUES
('629de8e39c492', '623c06fab6b2a', '', '623c06fa96782', '2022-06-06 17:15:39', '2022-06-06 17:15:39', 1, '629de8c218436', '62989f36e114d', '62989ea550ec4', 'PO', '2022-06-06', 'cash', '629a0041312ec', '', '', 4, 'hello', 'Payment');

-- --------------------------------------------------------

--
-- Table structure for table `sale_receipt_details`
--

CREATE TABLE `sale_receipt_details` (
  `id` varchar(20) NOT NULL,
  `ClientID` varchar(20) NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `saleid` varchar(1000) NOT NULL,
  `amount` double NOT NULL,
  `discount` double NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sale_receipt_details`
--

INSERT INTO `sale_receipt_details` (`id`, `ClientID`, `parent_id`, `saleid`, `amount`, `discount`, `date`) VALUES
('629de8e3a5022', '623c06fa96782', '629de8e39c492', '629de85721796', 2, 0, '2022-06-06'),
('629de8e3b268e', '623c06fa96782', '629de8e39c492', '629b048ed13c9', 2, 0, '2022-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `sale_return`
--

CREATE TABLE `sale_return` (
  `id` varchar(20) NOT NULL,
  `user` varchar(50) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `recordnumber` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `location` varchar(20) NOT NULL,
  `customer` varchar(50) NOT NULL,
  `sale_invoice_no` varchar(25) NOT NULL,
  `other` varchar(25) NOT NULL,
  `grandtotal` double NOT NULL,
  `transcost` varchar(25) NOT NULL,
  `transgst` varchar(25) NOT NULL,
  `transamount` double NOT NULL,
  `subt` double NOT NULL,
  `trans` varchar(100) NOT NULL,
  `totcst_amt` double NOT NULL,
  `totsgst_amt` double NOT NULL,
  `totigst_amt` double NOT NULL,
  `tcs_tds` varchar(50) NOT NULL,
  `tcs_tds_percen` double NOT NULL,
  `tcs_tds_amt` double NOT NULL,
  `roff` varchar(200) NOT NULL,
  `otrnar` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sale_return_details`
--

CREATE TABLE `sale_return_details` (
  `id` varchar(20) NOT NULL,
  `parent_id` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(25) NOT NULL,
  `unit` varchar(25) NOT NULL,
  `cgst` double NOT NULL,
  `sgst` double NOT NULL,
  `igst` double NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL,
  `disc` double NOT NULL,
  `taxable` double NOT NULL,
  `rejectedqty` double NOT NULL,
  `total` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `userID` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `update_id` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `userID`, `name`, `update_id`) VALUES
(2, '', 'Andhra Pradesh', ''),
(3, '', 'Arunachal Pradesh', ''),
(4, '', 'Assam', ''),
(5, '', 'Bihar', ''),
(7, '', 'Chhattisgarh', ''),
(6, '', 'Chandigarh', ''),
(8, '', 'Dadra and Nagar Haveli and Daman and Diu', ''),
(9, '', 'Delhi', ''),
(10, '', 'Goa', ''),
(11, '', 'Gujarat', ''),
(12, '', 'Haryana', ''),
(13, '', 'Himachal Pradesh', ''),
(14, '', 'Jammu and Kashmir', ''),
(15, '', 'Jharkhand', ''),
(16, '', 'Karnataka', ''),
(17, '', 'Kerala', ''),
(18, '', 'Ladakh', ''),
(19, '', 'Lakshadweep', ''),
(20, '', 'Madhya Pradesh', ''),
(21, '', 'Maharashtra', ''),
(22, '', 'Manipur', ''),
(23, '', 'Meghalaya', ''),
(24, '', 'Mizoram', ''),
(25, '', 'Nagaland', ''),
(26, '', 'Odisha', ''),
(27, '', 'Puducherry', ''),
(28, '', 'Punjab', ''),
(29, '', 'Rajasthan', ''),
(30, '', 'Sikkim', ''),
(31, '', 'Tamil Nadu', ''),
(32, '', 'Telangana', ''),
(34, '', 'Uttar Pradesh', ''),
(35, '', 'Uttarakhand', ''),
(36, '', 'West Bengal', ''),
(1, '', 'Andaman and Nicobar Islands', ''),
(33, '', 'Tripura', '');

-- --------------------------------------------------------

--
-- Table structure for table `stock_group`
--

CREATE TABLE `stock_group` (
  `id` varchar(15) NOT NULL,
  `name` mediumtext NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `under_group` varchar(15) NOT NULL,
  `negative_stk_block` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_group`
--

INSERT INTO `stock_group` (`id`, `name`, `ClientID`, `under_group`, `negative_stk_block`, `Created`, `LastEdited`) VALUES
('6242aa584eb60', 'sdasdasf', '', '', 1, '2022-03-29 12:12:32', '2022-03-29 12:12:32'),
('62418c8b9ad76', 'stock grp2', '', '', 1, '2022-03-28 15:53:07', '2022-05-10 13:12:10'),
('627a1763c82df', 'stock grp[ 3', '', '', 0, '2022-05-10 13:12:27', '2022-05-10 13:12:27'),
('629df59037e3b', 'Shivani Jadhav', '623c06fa96782', 'Primary', 0, '2022-06-06 18:09:44', '2022-06-06 18:09:44'),
('629df6c926d27', 'Arati Ingawale', '623c06fa96782', '', 0, '2022-06-06 18:14:57', '2022-06-06 18:14:57'),
('629df6e866868', 'Shivani Sanjay Jadhav', '623c06fa96782', 'Primary', 0, '2022-06-06 18:15:28', '2022-06-06 18:15:28');

-- --------------------------------------------------------

--
-- Table structure for table `stock_journal`
--

CREATE TABLE `stock_journal` (
  `id` varchar(20) NOT NULL,
  `user` varchar(20) NOT NULL,
  `updateduser` varchar(20) NOT NULL,
  `ClientID` varchar(100) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `record_no` bigint(20) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_journal_details`
--

CREATE TABLE `stock_journal_details` (
  `id` varchar(15) NOT NULL,
  `parent_id` varchar(15) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `type` varchar(50) NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `location` varchar(25) NOT NULL,
  `stock` double NOT NULL,
  `qty` double NOT NULL,
  `rate` double NOT NULL,
  `amount` double NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `stock_ledger`
--

CREATE TABLE `stock_ledger` (
  `id` varchar(15) NOT NULL,
  `ClientID` varchar(15) NOT NULL,
  `name` mediumtext NOT NULL,
  `under_group` varchar(15) NOT NULL,
  `sale_invoicing` int(11) NOT NULL,
  `unit` mediumtext NOT NULL,
  `alt_unit` mediumtext NOT NULL,
  `unit_qty` mediumtext NOT NULL,
  `altunit_qty` mediumtext NOT NULL,
  `batch_maintainance` int(11) NOT NULL,
  `bill_of_material` int(11) NOT NULL,
  `cost_tracking` int(11) NOT NULL,
  `costing_method` mediumtext NOT NULL,
  `new_mfg` int(11) NOT NULL,
  `consumed` int(11) NOT NULL,
  `negative_stk_block` int(11) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_ledger`
--

INSERT INTO `stock_ledger` (`id`, `ClientID`, `name`, `under_group`, `sale_invoicing`, `unit`, `alt_unit`, `unit_qty`, `altunit_qty`, `batch_maintainance`, `bill_of_material`, `cost_tracking`, `costing_method`, `new_mfg`, `consumed`, `negative_stk_block`, `Created`, `LastEdited`) VALUES
('62989fc0e19c8', '623c06fa96782', 'pen', 'Primary', 0, 'kg', '', '', '', 0, 1, 0, 'std_cost', 0, 0, 0, '2022-06-02 17:02:16', '2022-06-06 13:35:45'),
('6298a015529be', '623c06fa96782', 'refill', '62989fc0e19c8', 0, 'kg', '', '', '', 0, 1, 0, 'std_cost', 0, 0, 0, '2022-06-02 17:03:41', '2022-06-03 15:27:18');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer`
--

CREATE TABLE `stock_transfer` (
  `id` varchar(15) NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `record_no` varchar(25) NOT NULL,
  `voucher_type` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_transfer`
--

INSERT INTO `stock_transfer` (`id`, `user`, `ClientID`, `Created`, `LastEdited`, `record_no`, `voucher_type`, `location`, `date`) VALUES
('629dacf147fee', '623c06fab6b2a', '623c06fa96782', '2022-06-06 12:59:53', '2022-06-06 12:59:53', '1', '629da8c565b7a', '62989f36e114d', '2022-06-06');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_details`
--

CREATE TABLE `stock_transfer_details` (
  `id` varchar(25) NOT NULL,
  `parent_id` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `product` varchar(15) NOT NULL,
  `unit` mediumtext NOT NULL,
  `fromstock` double NOT NULL,
  `tostock` double NOT NULL,
  `location` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_transfer_details`
--

INSERT INTO `stock_transfer_details` (`id`, `parent_id`, `ClientID`, `Created`, `LastEdited`, `product`, `unit`, `fromstock`, `tostock`, `location`) VALUES
('629dacf1486c0', '629dacf147fee', '623c06fa96782', '2022-06-06 12:59:53', '2022-06-06 12:59:53', '62989fc0e19c8', 'kg', 104, 4, '62989f5198d4e'),
('629dacf148d02', '629dacf147fee', '623c06fa96782', '2022-06-06 12:59:53', '2022-06-06 12:59:53', '6298a015529be', 'kg', 500, 5, '62989f76a6f1c');

-- --------------------------------------------------------

--
-- Table structure for table `submenu`
--

CREATE TABLE `submenu` (
  `id` varchar(20) NOT NULL,
  `sequenceid` int(10) NOT NULL,
  `mid` varchar(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `page` varchar(50) NOT NULL,
  `subpage` varchar(200) NOT NULL,
  `folder` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `submenu`
--

INSERT INTO `submenu` (`id`, `sequenceid`, `mid`, `name`, `page`, `subpage`, `folder`) VALUES
('s_100', 1, 'm_1', 'Role Master', 'role_master_list.php', 'role_master_form.php', 'masters'),
('s_1000', 0, 'm_10', 'Receivables', 'receivables_report_list.php', '', ''),
('s_1001', 1, 'm_10', 'Payables', 'payables_report_list.php', '', ''),
('s_101', 2, 'm_1', 'Employee Master', 'employee_master_list.php', 'employee_master_form.php', ''),
('s_102', 3, 'm_1', 'Group Master', 'group_master_list.php', 'group_master_form.php', ''),
('s_103', 4, 'm_1', 'Cost Centres', 'cost_centre_list.php', 'cost_centre_form.php', ''),
('s_104', 5, 'm_1', 'Stock Group', 'stock_group_list.php', 'stock_group_form.php', ''),
('s_105', 6, 'm_1', 'Stock Ledger/Item', 'stock_ledger_list.php', 'stock_ledger_form.php', ''),
('s_106', 7, 'm_1', 'Location Master', 'location_list.php', 'location_form.php', ''),
('s_107', 8, 'm_1', 'Currency Master', 'currency_list.php', 'currency_form.php', ''),
('s_108', 9, 'm_1', 'Price List Master', 'pricelist_form.php', '', ''),
('s_109', 10, 'm_1', 'Account Ledger', 'account_ledger_masterlist.php', 'account_ledger_masterform.php', ''),
('s_110', 11, 'm_1', 'Voucher Type', 'voucher_type_list.php', 'voucher_type_form.php', ''),
('s_111', 12, 'm_1', 'Bill Of Material', 'bill_of_material_list.php', 'bill_of_material_form.php', ''),
('s_200', 1, 'm_2', 'Purchase Requsition', 'purchase_requisition_list.php', 'purchase_requisition_form.php', ''),
('s_201', 2, 'm_2', 'Purchase Order', 'purchase_order_list.php', 'purchase_order_form.php', ''),
('s_202', 3, 'm_2', 'GRN(Goods Receipts Notes)', 'GRN_list.php', 'GRN_form.php', ''),
('s_203', 4, 'm_2', 'Purchase Invoice', 'purchase_invoice_list.php', 'purchase_invoice_form.php', ''),
('s_204', 5, 'm_2', 'Purchase Payment', 'purchase_payment_list.php', 'purchase_payment_form.php,purchase_history.php', ''),
('s_205', 6, 'm_2', 'Debit  Note', 'debit_note_list.php', 'debit_note_form.php', ''),
('s_206', 7, 'm_2', 'Purchase Return', 'purchase_return_list.php', 'purchase_return_form.php', ''),
('s_300', 1, 'm_3', 'Sales Order', 'sale_order_list.php', '', ''),
('s_301', 2, 'm_3', 'Delivery Chalan', 'delivery_challan_list.php', 'delivery_challan_form.php', ''),
('s_303', 3, 'm_3', 'Sale Invoice', 'sale_invoice_list.php', 'sale_invoice_form.php', ''),
('s_304', 4, 'm_3', 'Sale Receipt', 'sale_receipt_list.php', 'sale_receipt_form.php,sale_history.php', ''),
('s_305', 5, 'm_3', 'Credit Note', 'credit_note_list.php', 'credit_note_form.php', ''),
('s_306', 6, 'm_3', 'Sale Returns', 'sale_return_list.php', 'sale_return_form.php', ''),
('s_400', 1, 'm_4', 'Production', 'production_list.php', 'production_form.php', ''),
('s_401', 2, 'm_4', 'Packaging', 'packaging_list.php', 'packaging_form.php', ''),
('s_402', 3, 'm_4', 'Stock Tranfer', 'stock_transfer_list.php', 'stock_transfer_form.php', ''),
('s_403', 4, 'm_4', 'Dispatch', 'dispatch_list.php', 'dispatch_form.php', ''),
('s_500', 1, 'm_5', 'Bank Transfer', 'bank_transfer_list.php', 'bank_transfer_form.php', 'masters'),
('s_501', 2, 'm_5', 'Journal Entry', 'journal_entry_list.php', 'journal_entry_form.php', ''),
('s_600', 1, 'm_6', 'Physical Stock', 'physical_stock_list.php', 'physical_stock_form.php', ''),
('s_601', 2, 'm_6', 'Stock Journal', 'stock_journal_list.php', 'stock_journal_form.php', ''),
('s_700', 1, 'm_7', 'Accounts Register', '#', '', ''),
('s_800', 1, 'm_8', 'Sales Register', 'sale_registor_list.php', '', ''),
('s_801', 2, 'm_8', 'Purchase Register', 'purchase_registor_list.php', '', ''),
('s_802', 3, 'm_8', 'Credit Note Register', 'credit_note_registor_list.php', '', ''),
('s_803', 4, 'm_8', 'Debit Note Register', 'debit_note_registor_list.php', '', ''),
('s_804', 5, 'm_8', 'Payment Register', 'payment_registor_list.php', '', ''),
('s_805', 6, 'm_8', 'Receipt Register', 'receipt_registor_list.php', '', ''),
('s_806', 7, 'm_8', 'Cash  Receipt Register', 'cash_receipt_registor_list.php', '', ''),
('s_807', 8, 'm_8', 'Cash  Payment Register', 'cash_payment_registor_list.php', '', ''),
('s_808', 9, 'm_8', 'Journal Register', 'journal_registor_list.php', '', ''),
('s_900', 0, 'm_9', 'Sales Order Register', 'sale_order_registor_list.php', '', ''),
('s_901', 1, 'm_9', 'Purchase Order Register', 'purchase_order_registor_list.php', '', ''),
('s_902', 2, 'm_9', 'Dellivery Register', 'delivery_registor_list.php', '', ''),
('s_903', 3, 'm_9', 'GRN Register', 'GRN_registor_list.php', '', ''),
('s_904', 4, 'm_9', 'Pending Delivery Register', 'pending_delivery_registor_list.php', '', ''),
('s_905', 5, 'm_9', 'Pending Sales Order Register', 'pending_sale_order_registor_list.php', '', ''),
('s_906', 6, 'm_9', 'Pending Purchase Order Register', 'pending_purchase_order_registor_list.php', '', ''),
('s_907', 7, 'm_9', 'Physical Stock Register', 'physical_stock_register_list.php', '', ''),
('s_908', 8, 'm_9', 'Pending GRN Register', 'pending_GRN_registor_list.php', '', ''),
('s_909', 9, 'm_9', 'Negative Stock Report', 'negative_stock_report_list.php', '', ''),
('s_910', 10, 'm_9', 'Day Book Report', 'daybook_report_list.php', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `voucher_type`
--

CREATE TABLE `voucher_type` (
  `id` varchar(15) NOT NULL,
  `name` mediumtext NOT NULL,
  `user` varchar(25) NOT NULL,
  `ClientID` varchar(25) NOT NULL,
  `Created` datetime NOT NULL,
  `LastEdited` datetime NOT NULL,
  `parent_voucher` mediumtext NOT NULL,
  `numbering` varchar(25) NOT NULL,
  `numbering_digit` varchar(25) NOT NULL,
  `narration` int(11) NOT NULL,
  `printing_settings` int(11) NOT NULL,
  `scan` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voucher_type`
--

INSERT INTO `voucher_type` (`id`, `name`, `user`, `ClientID`, `Created`, `LastEdited`, `parent_voucher`, `numbering`, `numbering_digit`, `narration`, `printing_settings`, `scan`) VALUES
('6298a05e7233a', 'sale order voucher', '623c06fab6b2a', '623c06fa96782', '2022-06-02 17:04:54', '2022-06-02 17:04:54', '24', 'Manual', 'Prefix', 0, 0, 0),
('6298a10900a05', 'purchase invoice', '623c06fab6b2a', '623c06fa96782', '2022-06-02 17:07:45', '2022-06-02 17:07:45', '2', 'Manual', 'Prefix', 0, 0, 0),
('6298a177cc3e3', 'sale invoice voucher', '623c06fab6b2a', '623c06fa96782', '2022-06-02 17:09:35', '2022-06-02 17:09:35', '1', 'Manual', 'Prefix', 0, 0, 0),
('6298abd39a0c1', 'GRN', '623c06fab6b2a', '623c06fa96782', '2022-06-02 17:53:47', '2022-06-02 17:53:47', '9', 'Manual', 'Prefix', 0, 0, 0),
('6299ffb1a61d2', 'debit note', '623c06fab6b2a', '623c06fa96782', '2022-06-03 18:03:53', '2022-06-03 18:03:53', '5', 'Manual', 'Prefix', 0, 0, 0),
('629b4fb26a1a3', 'Shivani Jadhav', '623c06fab6b2a', '623c06fa96782', '2022-06-04 17:57:30', '2022-06-04 17:57:30', '17', 'Manual', 'Suffix_with_date_starting', 0, 0, 0),
('629b51f599a4d', 'purchase  payment', '623c06fab6b2a', '623c06fa96782', '2022-06-04 18:07:09', '2022-06-04 18:07:09', '6', 'Manual', 'Prefix', 0, 0, 0),
('629da8c565b7a', 'transfer loaction', '623c06fab6b2a', '623c06fa96782', '2022-06-06 12:42:05', '2022-06-06 12:42:05', '10', 'Automatic', 'Suffix_with_date_starting', 0, 0, 0),
('629dc8de954ca', 'production', '623c06fab6b2a', '623c06fa96782', '2022-06-06 14:59:02', '2022-06-06 14:59:02', '11', 'Automatic', 'Suffix_with_date_starting', 0, 0, 0),
('629de8c218436', 'sale receipt voucher', '623c06fab6b2a', '623c06fa96782', '2022-06-06 17:15:06', '2022-06-06 17:15:06', '7', 'Manual', 'Prefix', 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_ledger`
--
ALTER TABLE `account_ledger`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ID` (`id`);

--
-- Indexes for table `account_ledger_address`
--
ALTER TABLE `account_ledger_address`
  ADD KEY `accounts_ledger_id` (`al_id`);

--
-- Indexes for table `bank_transfer`
--
ALTER TABLE `bank_transfer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recordnumber` (`recordnumber`);

--
-- Indexes for table `bill_of_material`
--
ALTER TABLE `bill_of_material`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `bill_of_material_details`
--
ALTER TABLE `bill_of_material_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ID` (`id`);

--
-- Indexes for table `cost_centre`
--
ALTER TABLE `cost_centre`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `credit_note`
--
ALTER TABLE `credit_note`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `credit_note_details`
--
ALTER TABLE `credit_note_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `debit_note`
--
ALTER TABLE `debit_note`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `delivery_challan`
--
ALTER TABLE `delivery_challan`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `delivery_challan_details`
--
ALTER TABLE `delivery_challan_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `dispatch`
--
ALTER TABLE `dispatch`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `dispatch_details`
--
ALTER TABLE `dispatch_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ID` (`id`);

--
-- Indexes for table `fixed_vouchertype`
--
ALTER TABLE `fixed_vouchertype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grn`
--
ALTER TABLE `grn`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `grn_details`
--
ALTER TABLE `grn_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `group_master`
--
ALTER TABLE `group_master`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `journal_entry`
--
ALTER TABLE `journal_entry`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `packaging`
--
ALTER TABLE `packaging`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `packaging_details`
--
ALTER TABLE `packaging_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `physical_stock`
--
ALTER TABLE `physical_stock`
  ADD UNIQUE KEY `ID` (`id`);

--
-- Indexes for table `physical_stock_details`
--
ALTER TABLE `physical_stock_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `pricelist`
--
ALTER TABLE `pricelist`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `production`
--
ALTER TABLE `production`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `production_details`
--
ALTER TABLE `production_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `purchase_invoice`
--
ALTER TABLE `purchase_invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_invoice_details`
--
ALTER TABLE `purchase_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `purchase_payment`
--
ALTER TABLE `purchase_payment`
  ADD UNIQUE KEY `ID` (`id`);

--
-- Indexes for table `purchase_payment_details`
--
ALTER TABLE `purchase_payment_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `purchase_requisition`
--
ALTER TABLE `purchase_requisition`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `purchase_requisition_details`
--
ALTER TABLE `purchase_requisition_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `purchase_return`
--
ALTER TABLE `purchase_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_return_details`
--
ALTER TABLE `purchase_return_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_master`
--
ALTER TABLE `role_master`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sale_invoice`
--
ALTER TABLE `sale_invoice`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sale_invoice_details`
--
ALTER TABLE `sale_invoice_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sale_order`
--
ALTER TABLE `sale_order`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sale_order_details`
--
ALTER TABLE `sale_order_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sale_receipt`
--
ALTER TABLE `sale_receipt`
  ADD UNIQUE KEY `ID` (`id`);

--
-- Indexes for table `sale_receipt_details`
--
ALTER TABLE `sale_receipt_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `sale_return`
--
ALTER TABLE `sale_return`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sale_return_details`
--
ALTER TABLE `sale_return_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stock_group`
--
ALTER TABLE `stock_group`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stock_journal`
--
ALTER TABLE `stock_journal`
  ADD UNIQUE KEY `ID` (`id`);

--
-- Indexes for table `stock_journal_details`
--
ALTER TABLE `stock_journal_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stock_ledger`
--
ALTER TABLE `stock_ledger`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stock_transfer`
--
ALTER TABLE `stock_transfer`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `stock_transfer_details`
--
ALTER TABLE `stock_transfer_details`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `submenu`
--
ALTER TABLE `submenu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `voucher_type`
--
ALTER TABLE `voucher_type`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_transfer`
--
ALTER TABLE `bank_transfer`
  MODIFY `recordnumber` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `fixed_vouchertype`
--
ALTER TABLE `fixed_vouchertype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
