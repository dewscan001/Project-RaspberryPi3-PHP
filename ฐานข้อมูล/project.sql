-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 20, 2019 at 04:29 AM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `astatus` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`, `name`, `astatus`) VALUES
('admin', '12345', 'admin', 0),
('admin12', '12345', 'นาวิน บุญราชสุวรรณf', 1),
('admin12345', '12345', 'ADMINDEW', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `no` int(3) NOT NULL,
  `rfid` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสที่ได้จาก RFID',
  `bname` varchar(500) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อหนังสือ',
  `wname` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อผู้แต่ง',
  `bstatus` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`no`, `rfid`, `bname`, `wname`, `bstatus`) VALUES
(1, '451168296', 'สื่อการสอนเทคนิคการถ่ายภาพด้วยกล้อง DSLR (2556)', 'สุริยพงศ์  พรภู่พุทธคุณ', 1),
(2, '2511247126', 'ชุดการสอนเครื่องมือและการวัดทางไฟฟ้า  โดยรูปแบบแบ่งกลุ่มทำงาน (2558)', 'กฤติยาภรณ์ ศรีสวัสดิ์\r\nปวีณา  แก้วแก่น', 1),
(3, '8076125162', 'โตรงการสอน รายวิชา 11-064-406  เครือข่ายคอมพิวเตอร์ (2557)', 'ปุญญวัฒน์  จันทร์กอง', 0),
(4, '1', 'ใบประลอง รายวิชา  ปฏิบัติการวิเคราะห์และออกแบบดิจิตอล\r\n', 'จิตรดา  จันโยธา', 1);

-- --------------------------------------------------------

--
-- Table structure for table `memberhistory`
--

CREATE TABLE `memberhistory` (
  `hid` int(11) NOT NULL,
  `userid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `hidate` date NOT NULL,
  `hitime` time NOT NULL,
  `histatus` tinyint(1) NOT NULL,
  `bookno` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `userid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `pdate` date NOT NULL COMMENT 'วันที่ชำระเงิน',
  `pmoney` int(5) NOT NULL COMMENT 'จำนวนเงิน',
  `des` text COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ผู้รับเงิน'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `userid`, `pdate`, `pmoney`, `des`, `username`) VALUES
(5, '573226032033-1', '2019-04-19', 4, ' \r\n   <div style=\"width:100%; \" class=\"table-responsive\">\r\n   <table class=\"table table-hover\">\r\n    <thead>\r\n      <tr>\r\n        <th style=\"text-align:center;\" width=\"350px\">ชื่อปริญญานิพนธ์</th>\r\n        <th style=\"text-align:center;\" width=\"200px\">วันที่ยืม</th>\r\n		<th style=\"text-align:center;\" width=\"200px\">วันที่คืน</th>\r\n		<th style=\"text-align:center;\" width=\"100px\">จำนวนเงิน</th>\r\n      </tr>\r\n    </thead>\r\n \r\n		<tbody>\r\n      <tr>\r\n        <td style=\"text-align:left;\">ชุดการสอนเครื่องมือและการวัดทางไฟฟ้า  โดยรูปแบบแบ่งกลุ่มทำงาน (2558)</td>\r\n        <td>1 เมษายน 2562</td>\r\n		<td>12 เมษายน 2562</td>\r\n		<td>4  บาท</td>\r\n      </tr>\r\n    </tbody></table> </div> ', 'admin'),
(6, '573226032052-1', '2019-04-19', 4, ' \r\n   <div style=\"width:100%; \" class=\"table-responsive\">\r\n   <table class=\"table table-hover\">\r\n    <thead>\r\n      <tr>\r\n        <th style=\"text-align:center;\" width=\"350px\">ชื่อปริญญานิพนธ์</th>\r\n        <th style=\"text-align:center;\" width=\"200px\">วันที่ยืม</th>\r\n		<th style=\"text-align:center;\" width=\"200px\">วันที่คืน</th>\r\n		<th style=\"text-align:center;\" width=\"100px\">จำนวนเงิน</th>\r\n      </tr>\r\n    </thead>\r\n \r\n		<tbody>\r\n      <tr>\r\n        <td style=\"text-align:left;\">ใบประลอง รายวิชา  ปฏิบัติการวิเคราะห์และออกแบบดิจิตอล<br />\r\n</td>\r\n        <td>1 เมษายน 2562</td>\r\n		<td>12 เมษายน 2562</td>\r\n		<td>4  บาท</td>\r\n      </tr>\r\n    </tbody></table> </div> ', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusid` int(11) NOT NULL,
  `userid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rfid` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sdate` date NOT NULL COMMENT 'วันที่ยืม',
  `enddate` date NOT NULL COMMENT 'วันที่คืน',
  `wdate` date DEFAULT NULL,
  `wstatus` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'สถานะยืม/คืน',
  `muclt` int(5) NOT NULL COMMENT 'ค่าปรับ',
  `mstatus` tinyint(1) NOT NULL COMMENT 'สถานะการจ่ายหนี้'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusid`, `userid`, `rfid`, `sdate`, `enddate`, `wdate`, `wstatus`, `muclt`, `mstatus`) VALUES
(1, '573226032052-1', '1', '2019-04-01', '2019-04-08', '2019-04-12', 1, 4, 1),
(2, '573226032052-1', '8076125162', '2019-04-10', '2019-04-17', '0000-00-00', 0, 0, 1),
(3, '573226032033-1', '2511247126', '2019-04-15', '2019-04-19', '0000-00-00', 0, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `fingerid` int(3) NOT NULL COMMENT 'รหัสที่ได้จาก finger print',
  `userid` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'รหัสสมาชิก',
  `uname` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ชื่อของสมาชิก',
  `upass` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`fingerid`, `userid`, `uname`, `upass`, `tel`, `address`) VALUES
(0, '573226032033-1', 'นาวิน บุญราชสุวรรณ', '12345', '0000000000', ' ต.ชนบท อ.ชนบท จ.ขอนแก่น 40180   '),
(1, '573226032060-4', 'จักรพงศ์ สุพรรณคำ', '12345', '0', ''),
(2, '573226032052-1', 'วริศ  วรุณศิริ', '12345', '0', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`no`);

--
-- Indexes for table `memberhistory`
--
ALTER TABLE `memberhistory`
  ADD PRIMARY KEY (`hid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusid`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`fingerid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `memberhistory`
--
ALTER TABLE `memberhistory`
  MODIFY `hid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;
--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
