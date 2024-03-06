-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2024 at 08:48 PM
-- Server version: 8.0.34
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final-project`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int NOT NULL,
  `emp_fname` varchar(100) NOT NULL,
  `emp_lname` varchar(100) NOT NULL,
  `job_title` varchar(50) NOT NULL,
  `emp_level` varchar(50) NOT NULL,
  `emp_status` varchar(30) NOT NULL,
  `emp_availability` varchar(10) NOT NULL,
  `emp_note` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fname`, `emp_lname`, `job_title`, `emp_level`, `emp_status`, `emp_availability`, `emp_note`) VALUES
(1, 'Kusalyaaaaa', 'Kuuuuuuuu', 'Worker', 'Level 01', 'Active', 'Yes', 'hello'),
(2, '50f3c240', 'a58039a2', 'Site Engineer', 'Level 03', 'Active', 'Yes', '969ea8c9c7'),
(3, '8b4b3ef2', '4feef4b0', 'Carpenter', 'Level 02', 'Active', 'Yes', 'cf2ce1776d'),
(4, 'c631bca7', '5c7fa722', 'Carpenter', 'Level 02', 'Active', 'No', '39b65250eb'),
(5, 'd7c3de37', '07c88156', 'Electrician', 'Level 02', 'Active', 'Yes', '8bb2f51872'),
(6, '5859cce0', 'e0edc839', 'Electrician', 'Level 02', 'Active', 'Yes', '5cb52818ac'),
(7, '56b6eb70', '44c53444', 'Electrician', 'Level 03', 'Active', 'Yes', '7299cb8531'),
(8, '01a12281', '616e144b', 'Carpenter', 'Level 02', 'Active', 'Yes', '2581b4d796'),
(9, '700b5bea', 'dad0c470', 'Plumber', 'Level 01', 'Active', 'Yes', '581f414cf7'),
(10, 'a537c483', '6c732686', 'Plumber', 'Level 03', 'Active', 'Yes', '525d11bdcf'),
(11, '7f05a0d2', 'f55d43db', 'Plumber', 'Level 03', 'Active', 'Yes', 'b0082f5a4e'),
(12, 'cb5a5f64', '36bcdc1a', 'Plumber', 'Level 02', 'Active', 'Yes', 'd417db4168'),
(13, '387fe3db', '45f2ab2f', 'Worker', 'Level 02', 'Active', 'No', '7fd9e12766'),
(14, '78b2cc8d', 'f88dbaf3', 'Carpenter', 'Level 01', 'Active', 'Yes', '7d856a3bbe'),
(15, 'fe1e0700', 'afa037ac', 'Electrician', 'Level 02', 'Active', 'Yes', 'b1e5cb610b'),
(16, '4398545d', '96f34105', 'Plumber', 'Level 03', 'Active', 'Yes', '2a01e43e9b'),
(17, '502253cd', '60ce38cb', 'Carpenter', 'Level 01', 'Active', 'Yes', '62dc82edcd'),
(18, '8e902aa0', '8a9ea2bc', 'Mason', 'Level 02', 'Active', 'Yes', 'de2517ea8e'),
(19, '91b011f0', '65c0faaa', 'Site Engineer', 'Level 02', 'Active', 'Yes', '992e558796'),
(20, '57d1cad7', '8fb9bbad', 'Site Engineer', 'Level 02', 'Active', 'Yes', 'ea1f414c2c'),
(21, '342b4dc3', 'a719f505', 'Electrician', 'Level 03', 'Active', 'Yes', '5ca9ed48fd'),
(22, 'ea928ded', '1afa53ec', 'Electrician', 'Level 01', 'Active', 'Yes', '40d55f605e'),
(23, '8784279d', '05c7fa75', 'Carpenter', 'Level 02', 'Active', 'No', '3bd288fed4'),
(24, 'b8242c0a', '7bdc0504', 'Plumber', 'Level 01', 'Active', 'Yes', 'ae5cc47376'),
(25, '33449b82', 'e7aefbe4', 'Carpenter', 'Level 02', 'Active', 'Yes', 'aa0bf53ddc'),
(26, 'dae11418', '9f023487', 'Worker', 'Level 02', 'Active', 'Yes', 'ab88040da9'),
(27, '94ab7b81', 'bc24ed0e', 'Electrician', 'Level 03', 'Active', 'Yes', 'c9af5dab9d'),
(28, '8dc9f50c', '0f85b08f', 'Plumber', 'Level 02', 'Active', 'Yes', '3528650628'),
(29, '1e848894', '6f7625a9', 'Site Engineer', 'Level 02', 'Active', 'Yes', 'f66850839c'),
(30, '8d0b2c41', '81c04b1a', 'Electrician', 'Level 03', 'Active', 'Yes', '13662e4339'),
(31, '7690368a', '7a75cd7a', 'Mason', 'Level 02', 'Active', 'Yes', 'd4f7a72de9'),
(32, 'c88ffd31', '08e6a561', 'Plumber', 'Level 03', 'Active', 'Yes', '77bf6473d6'),
(33, '81ce487b', '6e95fa88', 'Mason', 'Level 02', 'Active', 'Yes', '5638d67729'),
(34, 'a8d2d370', 'fd05c6b1', 'Electrician', 'Level 03', 'Active', 'Yes', '3b9b337d1a'),
(35, '76a7345d', '030f1d17', 'Carpenter', 'Level 03', 'Active', 'Yes', '4072403ec3'),
(36, '517b530e', 'f000d2b5', 'Carpenter', 'Level 02', 'Active', 'Yes', '674562e72f'),
(37, '63a97d96', '9b4f21da', 'Site Engineer', 'Level 01', 'Active', 'Yes', 'e9b2482d56'),
(38, 'ae0ec5f8', 'f76fb6dd', 'Electrician', 'Level 03', 'Active', 'Yes', '0eeeb2cb08'),
(39, '49238910', '64ebe3a6', 'Plumber', 'Level 01', 'Active', 'Yes', '70ffdb552e'),
(40, '06730da2', '55e6d878', 'Electrician', 'Level 02', 'Active', 'Yes', '735cb1b483'),
(41, '600ede94', 'f6f02c43', 'Carpenter', 'Level 01', 'Active', 'Yes', 'd14d2aa76c'),
(42, 'b8c29520', 'd8532e01', 'Plumber', 'Level 01', 'Active', 'Yes', '6788895a42'),
(43, '8200681f', 'db89bf86', 'Carpenter', 'Level 01', 'Active', 'Yes', 'fb762114f4'),
(44, '8d0e99fb', '5b0751fd', 'Carpenter', 'Level 01', 'Active', 'Yes', '9289930b58'),
(45, 'b475740f', '1614a73c', 'Plumber', 'Level 01', 'Active', 'Yes', 'f40bc371da'),
(46, 'fed716ef', '69cf923e', 'Plumber', 'Level 01', 'Active', 'Yes', '559797ff15'),
(47, '3220908f', 'c1ba2c45', 'Electrician', 'Level 02', 'Active', 'Yes', 'f0b3cddc71'),
(48, 'bcab9ec2', 'd27246a2', 'Carpenter', 'Level 01', 'Active', 'Yes', 'e530712ced'),
(49, 'db21c4fe', '05b874c4', 'Mason', 'Level 02', 'Active', 'Yes', '566ce04b06'),
(50, '490d7cb5', '1baf42f2', 'Carpenter', 'Level 03', 'Active', 'Yes', '04dd822b74'),
(51, 'ad126960', '30d5b432', 'Plumber', 'Level 03', 'Active', 'Yes', '3b05e809ac'),
(52, 'c8ab9e57', '4f228b41', 'Carpenter', 'Level 03', 'Active', 'Yes', '0a55f9c5bf'),
(53, 'aede885f', '700af3d9', 'Site Engineer', 'Level 02', 'Active', 'Yes', '650abed209'),
(54, '3fa6b359', '8ec6f304', 'Carpenter', 'Level 02', 'Active', 'Yes', 'ad99ec7842'),
(55, 'd52fc80f', 'fa286223', 'Carpenter', 'Level 01', 'Active', 'Yes', '60dd04669f'),
(56, '408cbe24', '767deed0', 'Carpenter', 'Level 02', 'Active', 'Yes', 'e26c059825'),
(57, '74047bf8', 'b4743465', 'Electrician', 'Level 01', 'Active', 'Yes', 'f8191fabfc'),
(58, '69e66e51', '3a30b1a1', 'Site Engineer', 'Level 03', 'Active', 'Yes', 'dfe2a58258'),
(59, '7c5e038e', '62457cc4', 'Site Engineer', 'Level 01', 'Active', 'Yes', 'cc763a6d67'),
(60, '8187aa29', 'a6d209fe', 'Electrician', 'Level 02', 'Active', 'Yes', '77152ee84f'),
(61, 'b61eca60', '484916a3', 'Carpenter', 'Level 02', 'Active', 'Yes', '4d022cf600'),
(62, '1a1609d7', 'cf758b2c', 'Carpenter', 'Level 01', 'Active', 'Yes', '3977c3d392'),
(63, 'bca27cf1', '8b198493', 'Plumber', 'Level 03', 'Active', 'Yes', 'd918059ab8'),
(64, '6183aede', '9fecaa7c', 'Electrician', 'Level 01', 'Active', 'Yes', '7e21c40e32'),
(65, '4fa00696', '01bf5f8c', 'Carpenter', 'Level 03', 'Active', 'Yes', 'd3cae92825'),
(66, '2c4efd74', '4d7422b1', 'Electrician', 'Level 02', 'Active', 'Yes', '34b8df9b94'),
(67, '3a240f04', 'cfc07718', 'Site Engineer', 'Level 03', 'Active', 'Yes', '0e4687f313'),
(68, '7053d298', '0b48e7c1', 'Electrician', 'Level 01', 'Active', 'Yes', 'b4dd350044'),
(69, '671f3a65', '553a7212', 'Site Engineer', 'Level 03', 'Active', 'Yes', 'a7e2296c82'),
(70, 'd23b6507', 'e286ba25', 'Carpenter', 'Level 01', 'Active', 'Yes', 'feff3c4cc3'),
(71, '91b46bb7', '7a4e7850', 'Plumber', 'Level 02', 'Active', 'Yes', 'fc5e756922'),
(72, '55c4fc28', 'ae5ceb85', 'Electrician', 'Level 02', 'Active', 'Yes', 'c3a6579588'),
(73, '2b176399', '10634ffa', 'Site Engineer', 'Level 01', 'Active', 'Yes', 'b17b42bc78'),
(74, '9739d77e', '3edb1993', 'Electrician', 'Level 01', 'Active', 'Yes', '7cf6b8a30a'),
(75, 'f8f7e35a', 'e80a1722', 'Carpenter', 'Level 01', 'Active', 'No', '603cced1a8'),
(76, '92b4eed0', '706396d8', 'Carpenter', 'Level 01', 'Active', 'Yes', '47c573f1e9'),
(77, 'e71dbb5e', '36c54b01', 'Plumber', 'Level 01', 'Active', 'Yes', 'bd8feb79f0'),
(78, '646d78ed', 'b0e7eef1', 'Plumber', 'Level 01', 'Active', 'Yes', 'c18ad15585'),
(79, '5d55326e', '2a96ef42', 'Electrician', 'Level 02', 'Active', 'Yes', '8c05edd594'),
(80, '57d53d06', 'f9271519', 'Plumber', 'Level 02', 'Active', 'Yes', '8f6d69297e'),
(81, '6408763f', 'd13f1945', 'Site Engineer', 'Level 02', 'Active', 'Yes', 'c0f05a1779'),
(82, '94ff8cf2', '50587683', 'Plumber', 'Level 01', 'Active', 'Yes', '636e0eb8a7'),
(83, 'f85c39d2', '5034e9c2', 'Mason', 'Level 01', 'Active', 'Yes', '6543c222dc'),
(84, '90121a73', '59e43524', 'Carpenter', 'Level 01', 'Active', 'Yes', '20a61e357c'),
(85, '7f53284d', '47d84d33', 'Carpenter', 'Level 02', 'Active', 'Yes', 'f7dd6becbe'),
(86, '8b327718', 'bce2ff9d', 'Electrician', 'Level 02', 'Active', 'Yes', '6f04173c54'),
(87, '684caff9', '09d6694b', 'Electrician', 'Level 01', 'Active', 'Yes', 'fd80ab8591'),
(88, 'a92af69e', '53f74fe1', 'Electrician', 'Level 03', 'Active', 'Yes', '158f6e5ed0'),
(89, 'fe1f41a4', '3a5d5177', 'Site Engineer', 'Level 02', 'Active', 'Yes', '445f87a504'),
(90, '3543b236', '8fff58c4', 'Carpenter', 'Level 01', 'Active', 'Yes', '7fb07d0e61'),
(91, 'e1cbef77', '9b0e1c9c', 'Plumber', 'Level 02', 'Active', 'Yes', '648663e2f4'),
(92, 'e6790082', '1de744c9', 'Electrician', 'Level 01', 'Active', 'Yes', '6bf9206c92'),
(93, '1e9715cc', 'f58d34ad', 'Worker', 'Level 02', 'Active', 'No', 'c1a21505dc'),
(94, '2a52310c', 'd220c095', 'Electrician', 'Level 03', 'Active', 'Yes', '80cf7bc738'),
(95, '33f57883', 'c9955ad8', 'Carpenter', 'Level 02', 'Active', 'Yes', 'f000379645'),
(96, '371e1921', '5a98230a', 'Electrician', 'Level 02', 'Active', 'Yes', 'adc71f793a'),
(97, '53098b37', 'b71af75d', 'Site Engineer', 'Level 02', 'Active', 'Yes', 'f1da58cca6'),
(98, '8890275d', '162fa944', 'Plumber', 'Level 02', 'Active', 'Yes', 'f6ac110f17'),
(99, '742d669f', '3c9ee62b', 'Plumber', 'Level 03', 'Active', 'Yes', '61a7041d58'),
(100, '0dcae561', 'b535f5a8', 'Electrician', 'Level 01', 'Active', 'Yes', '2684f7ecf3'),
(101, '768eec1d', '5897f6cd', 'Electrician', 'Level 02', 'Active', 'Yes', '213cd5015e'),
(102, 'aa542bf9', '887ee540', 'Carpenter', 'Level 02', 'Active', 'Yes', '34ab96c324'),
(103, 'aa093f9e', 'b23af54f', 'Plumber', 'Level 01', 'Active', 'Yes', '908e17f481'),
(104, 'f34e5f85', 'f18866f1', 'Plumber', 'Level 01', 'Active', 'Yes', '3af9464dea'),
(105, '8cc717b9', 'c1e7c7f6', 'Site Engineer', 'Level 01', 'Active', 'Yes', 'b0fa017fc0'),
(106, '40067dd2', 'a70b10a5', 'Electrician', 'Level 03', 'Active', 'Yes', '0f89a24440'),
(107, '9ad43983', '4b3be24d', 'Carpenter', 'Level 01', 'Active', 'Yes', '83ca80eac3'),
(108, 'b276e8e5', 'a10a2732', 'Electrician', 'Level 01', 'Active', 'Yes', '34ac29c635'),
(109, '0737fc29', '53a99aee', 'Carpenter', 'Level 02', 'Active', 'Yes', '77f94efeb7'),
(110, '66ffc4e1', 'a0961921', 'Plumber', 'Level 02', 'Active', 'Yes', '9fa1e081b3'),
(111, '51f1c706', 'd8bbc6a7', 'Electrician', 'Level 01', 'Active', 'Yes', '0b3358aaf7'),
(112, '929071ee', '6abae57e', 'Site Engineer', 'Level 02', 'Active', 'Yes', '54e09d4848'),
(113, 'f65a2147', 'ce57337e', 'Plumber', 'Level 01', 'Active', 'Yes', 'e26c5d9d5f'),
(114, '38ac5b1a', '5fbc02ce', 'Carpenter', 'Level 02', 'Active', 'Yes', '115e46da3e'),
(115, '6dec5741', 'ea170e2d', 'Plumber', 'Level 02', 'Active', 'Yes', '59e0d91f41'),
(116, 'bc158be0', 'f0bea23f', 'Carpenter', 'Level 02', 'Active', 'Yes', 'dcb5721518'),
(117, '6472f4f8', 'd9144897', 'Site Engineer', 'Level 03', 'Active', 'Yes', 'dc001cb6a4'),
(118, 'b419a923', '57c2f3b1', 'Carpenter', 'Level 01', 'Active', 'Yes', '5a3a87cabd'),
(119, '93a960b3', '46db2e8e', 'Mason', 'Level 03', 'Active', 'Yes', '0e7cbb70f6'),
(120, '2b3c8aca', '1d5d5fdf', 'Plumber', 'Level 01', 'Active', 'Yes', 'a194bae507'),
(121, 'bbdf0999', '31f379e3', 'Electrician', 'Level 02', 'Active', 'Yes', '84312fa150'),
(122, '116a6d7d', '9629992c', 'Site Engineer', 'Level 01', 'Active', 'Yes', '00a31cd5be'),
(123, '94649825', '92b6e5a5', 'Plumber', 'Level 01', 'Active', 'Yes', '2ae095ca60'),
(124, '3520633c', 'ddb09c4b', 'Plumber', 'Level 01', 'Active', 'Yes', '5c5a271b66'),
(125, '2f9aabad', 'cb82d639', 'Mason', 'Level 01', 'Active', 'Yes', '70036b98e7'),
(126, 'bfc2c78b', 'f74b02a5', 'Electrician', 'Level 01', 'Active', 'Yes', '7968f89105'),
(127, '4945e0ec', '00f8d610', 'Carpenter', 'Level 03', 'Active', 'Yes', '4dfcf85058'),
(128, 'c9fb0d43', '2957e1d9', 'Plumber', 'Level 02', 'Active', 'Yes', '34c9fccea7'),
(129, '6be6d309', '6b67e82c', 'Electrician', 'Level 03', 'Active', 'Yes', '182b294a7f'),
(130, '20b99d1a', '23774131', 'Mason', 'Level 01', 'Active', 'Yes', '2f7bfe41cc'),
(131, 'ab031002', '92b32495', 'Carpenter', 'Level 01', 'Active', 'Yes', '009928adf3'),
(132, 'aaba0d25', '99d9c335', 'Plumber', 'Level 03', 'Active', 'Yes', '0e37bc41d9'),
(133, '778aa3f5', '290e9ab9', 'Site Engineer', 'Level 03', 'Active', 'Yes', '506bc7ebc6'),
(134, '5ba7232b', 'bab8a841', 'Carpenter', 'Level 03', 'Active', 'Yes', '176e90e187'),
(135, '7b0b6e10', 'e32c0b48', 'Electrician', 'Level 01', 'Active', 'Yes', '16640eca85'),
(136, 'cfee0185', '729bb427', 'Electrician', 'Level 03', 'Active', 'Yes', '7f8f762f08'),
(137, '47d24fc6', '2507e4df', 'Carpenter', 'Level 03', 'Active', 'Yes', '7192be91e2'),
(138, '7a803459', '8558bb6d', 'Carpenter', 'Level 01', 'Active', 'Yes', 'ea12df6bdf'),
(139, 'e29344dc', '98e43665', 'Site Engineer', 'Level 01', 'Active', 'Yes', '3298424597'),
(140, 'f815a0d8', 'd2aed5c6', 'Plumber', 'Level 02', 'Active', 'Yes', 'e3aad7987e'),
(141, 'b2fc24b5', 'f45fdb0d', 'Plumber', 'Level 02', 'Active', 'Yes', 'c2f2c7504a'),
(142, '20fe1e36', 'a0bfcf86', 'Electrician', 'Level 01', 'Active', 'Yes', '4a05f59fef'),
(143, '3799a750', '1201769a', 'Mason', 'Level 02', 'Active', 'Yes', '9de9a3d7dc'),
(144, '1517ef22', '00e3355e', 'Electrician', 'Level 03', 'Active', 'Yes', '8bbe165483'),
(145, 'b5e35b9d', '427f1c2c', 'Plumber', 'Level 01', 'Active', 'Yes', '3e30086c5b'),
(146, '4642f5b8', 'a001c4d2', 'Carpenter', 'Level 02', 'Active', 'Yes', '4fb7b94af1'),
(147, '1cccdaf5', '4c7d4c63', 'Carpenter', 'Level 02', 'Active', 'Yes', '9ae8d81ac4'),
(148, '57e0c08c', 'cd12aac5', 'Mason', 'Level 02', 'Active', 'Yes', '3f93289728'),
(149, '8a329807', '3d11b08d', 'Carpenter', 'Level 03', 'Active', 'Yes', '1199e77dcb'),
(150, 'ce16e4e5', '5c60dd86', 'Carpenter', 'Level 02', 'Active', 'Yes', '5daf50f9e2'),
(151, '8848d3f8', '3a307168', 'Site Engineer', 'Level 03', 'Active', 'Yes', 'fb72ecd1bb'),
(152, '85ca7ed6', 'df295a08', 'Site Engineer', 'Level 01', 'Active', 'Yes', '0adf6c3d36'),
(153, 'f46acfd5', '063af5c2', 'Electrician', 'Level 02', 'Active', 'Yes', '748b2c3356'),
(154, '4777acac', '318c6921', 'Electrician', 'Level 01', 'Active', 'Yes', 'd51ef558e1'),
(155, 'b096dc1e', 'c0f057bd', 'Carpenter', 'Level 03', 'Active', 'Yes', '730c1184f3'),
(156, '76864c99', '581d3723', 'Electrician', 'Level 01', 'Active', 'Yes', '968344d2b6'),
(157, '5d00134e', 'baac373a', 'Carpenter', 'Level 01', 'Active', 'Yes', 'baf4cdae0d'),
(158, '515ac84b', 'd41d3767', 'Site Engineer', 'Level 02', 'Active', 'Yes', '1eaadf8dd6'),
(159, 'a59370b6', '05dd25b3', 'Site Engineer', 'Level 02', 'Active', 'Yes', '540c968971'),
(160, '175b66d8', 'ad067d4d', 'Carpenter', 'Level 02', 'Active', 'Yes', 'f9306ef7fc'),
(161, 'f3be357b', '0843f6ef', 'Plumber', 'Level 02', 'Active', 'Yes', 'bfe6cc74b0'),
(162, '527d01b9', 'bf83d455', 'Electrician', 'Level 01', 'Active', 'Yes', 'ff8e6632fa'),
(163, 'e7c098b0', 'fd3585ed', 'Plumber', 'Level 02', 'Active', 'Yes', '5d94164227'),
(164, '5b08a0b7', '30c00c85', 'Electrician', 'Level 01', 'Active', 'Yes', 'eb4fac853e'),
(165, '13b2bb70', 'a369f027', 'Electrician', 'Level 03', 'Active', 'Yes', '224bd99600'),
(166, '09bac221', 'bd9b4228', 'Mason', 'Level 02', 'Active', 'Yes', '2f72db19b3'),
(167, 'ffea305b', 'bb9ba986', 'Electrician', 'Level 01', 'Active', 'Yes', '2ca75097b5'),
(168, '4a7f3bed', 'd2f28ac8', 'Electrician', 'Level 02', 'Active', 'Yes', '982aa7a71e'),
(169, '34376750', '440c440d', 'Carpenter', 'Level 03', 'Active', 'Yes', '0afbe243e7'),
(170, '7ce650d7', '6f00ecda', 'Carpenter', 'Level 02', 'Active', 'Yes', '57e59e74d7'),
(171, 'cc581fba', '6644acbb', 'Carpenter', 'Level 02', 'Active', 'Yes', '7296400d01'),
(172, '8e78ede2', '37af844e', 'Electrician', 'Level 03', 'Active', 'Yes', 'b471d2e9b7'),
(173, '900e887b', '85fb0225', 'Worker', 'Level 01', 'Active', 'No', 'ed7b0d3db9'),
(174, 'f6c42948', 'c4abe594', 'Site Engineer', 'Level 01', 'Active', 'Yes', '63d0ead4fc'),
(175, 'cbc6379f', 'dcaf6b1b', 'Carpenter', 'Level 03', 'Active', 'Yes', '69630909d7'),
(176, '55be30c8', '0ceba9b7', 'Plumber', 'Level 03', 'Active', 'Yes', '26fa2d0776'),
(177, '4d211c07', '13a4deed', 'Electrician', 'Level 01', 'Active', 'Yes', '75282be8ab'),
(178, 'efc0e6c5', 'f9faf234', 'Electrician', 'Level 02', 'Active', 'Yes', '8c21bc412d'),
(179, '3c1df4cf', 'b4000056', 'Worker', 'Level 01', 'Active', 'Yes', '6f98e47906'),
(180, '02e8b94e', '04d52869', 'Site Engineer', 'Level 02', 'Active', 'Yes', '6136640ae6'),
(181, '433b8ef9', 'a7013f2e', 'Worker', 'Level 02', 'Active', 'Yes', '935cee219e'),
(182, 'e71023c3', 'a71078d0', 'Plumber', 'Level 03', 'Active', 'Yes', 'e4f6ec3d6e'),
(183, '5df79c5a', '4989bb62', 'Plumber', 'Level 03', 'Active', 'Yes', '6b53f25fdd'),
(184, '415c0e20', 'd60f86b5', 'Mason', 'Level 02', 'Active', 'Yes', 'de9c111582'),
(185, '6973a34d', 'a3176961', 'Mason', 'Level 01', 'Active', 'Yes', '8cf849d2be'),
(186, 'a71e534b', '030152b0', 'Site Engineer', 'Level 02', 'Active', 'Yes', '5c0d9986ad'),
(187, '8400ca37', 'e5564efa', 'Electrician', 'Level 02', 'Active', 'Yes', 'e2b41aaf4c'),
(188, 'be516fea', '37257fb8', 'Mason', 'Level 02', 'Active', 'Yes', '7fac1c33b6'),
(189, '208e2ae7', '5258cd3c', 'Carpenter', 'Level 03', 'Active', 'Yes', 'b6c4181de6'),
(190, 'b2e47354', '504a1271', 'Electrician', 'Level 01', 'Active', 'Yes', 'eeed50b3de'),
(191, '413f9837', 'a61e8984', 'Plumber', 'Level 01', 'Active', 'Yes', '841969c961'),
(192, 'ec314246', '7c16da1c', 'Electrician', 'Level 01', 'Active', 'Yes', '6c06f05aa3'),
(193, 'ece1ed9e', '0e743fe3', 'Site Engineer', 'Level 03', 'Active', 'Yes', '8e5731f0ef'),
(194, '3a50d9e0', 'e8dc0beb', 'Electrician', 'Level 02', 'Active', 'Yes', 'f52d32c432'),
(195, '8455db25', 'fa23520f', 'Mason', 'Level 02', 'Active', 'Yes', '737e4705db'),
(196, '3ba35db6', 'bb77751d', 'Electrician', 'Level 03', 'Active', 'Yes', '3140ad568e'),
(197, '7bb7841b', '11f203c3', 'Plumber', 'Level 02', 'Active', 'Yes', '3e37cc661f'),
(198, 'a99b0709', '99e71207', 'Carpenter', 'Level 03', 'Active', 'Yes', '662906aa8e'),
(199, '00e5eaa5', '553c0a63', 'Mason', 'Level 03', 'Active', 'Yes', '9e668e251f'),
(200, '5380dad5', '108cc780', 'Electrician', 'Level 02', 'Active', 'Yes', '346db084dd'),
(201, 'fb0e561a', '3c2854f1', 'Carpenter', 'Level 03', 'Active', 'Yes', '8373ffba2f');

-- --------------------------------------------------------

--
-- Table structure for table `employee_allocation`
--

CREATE TABLE `employee_allocation` (
  `employee_allocation_id` int NOT NULL,
  `project_id` int NOT NULL,
  `employee_id` int NOT NULL,
  `emp_allocation_start_date` date NOT NULL,
  `emp_allocation_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_allocation`
--

INSERT INTO `employee_allocation` (`employee_allocation_id`, `project_id`, `employee_id`, `emp_allocation_start_date`, `emp_allocation_end_date`) VALUES
(1, 1, 1, '2024-02-07', '2024-02-09'),
(2, 1, 179, '2024-02-07', '2024-02-09'),
(3, 1, 173, '2024-02-07', NULL),
(4, 1, 13, '2024-02-07', NULL),
(5, 1, 93, '2024-02-07', NULL),
(6, 1, 75, '2024-02-09', NULL),
(7, 1, 23, '2024-02-09', NULL),
(8, 1, 4, '2024-02-09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_request`
--

CREATE TABLE `employee_request` (
  `emp_req_id` int NOT NULL,
  `employee_role` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `emp_count` int NOT NULL,
  `user_request_status` varchar(30) DEFAULT NULL,
  `emp_request_status` varchar(30) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `employee_request`
--

INSERT INTO `employee_request` (`emp_req_id`, `employee_role`, `emp_count`, `user_request_status`, `emp_request_status`, `user_id`, `project_id`) VALUES
(1, 'Worker', 50, NULL, 'Decline', NULL, 1),
(2, 'Driver', 2, NULL, 'Approve', NULL, 1),
(3, 'Worker', 2, 'Pending', NULL, 5, NULL),
(4, 'Driver', 5, 'Pending', NULL, 5, NULL),
(5, 'Worker', 20, 'Approve', NULL, 5, NULL),
(6, 'Driver', 1, 'Decline', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int NOT NULL,
  `feedback_comment` text NOT NULL,
  `feedback_date` date NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `feedback_comment`, `feedback_date`, `user_id`) VALUES
(1, 'Impressed with the project\'s progress', '2024-02-17', 9);

-- --------------------------------------------------------

--
-- Table structure for table `main_task`
--

CREATE TABLE `main_task` (
  `task_id` int NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `main_task`
--

INSERT INTO `main_task` (`task_id`, `task_name`, `task_description`) VALUES
(1, 'First Floor', 'Sample Main Task'),
(2, 'Second Floor', 'Sample Main Task 2'),
(3, 'Third Floor', 'Sample Main Task 3'),
(6, 'Site Preparation', 'Clear and level the construction site'),
(7, 'Make Foundation', 'Excavate, set up formwork, pour concrete'),
(8, '1st Floor Construction', 'Build the first-floor framework, install flooring, and enclose the space with walls, windows, and doors as per architectural plans.');

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `mat_id` int NOT NULL,
  `mat_name` varchar(100) NOT NULL,
  `mat_type` varchar(50) NOT NULL,
  `mat_quantity` int NOT NULL,
  `mat_unit` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`mat_id`, `mat_name`, `mat_type`, `mat_quantity`, `mat_unit`) VALUES
(1, 'Shovel', 'special', 240, 'nos'),
(2, 'Cement', 'normal', 1, 'nos'),
(3, 'Bricks', 'normal', 6, 'nos'),
(4, 'Steel Bars', 'normal', 94, 'nos'),
(5, 'Concrete Blocks', 'normal', 54, 'nos'),
(6, 'Sand', 'normal', 88, 'nos'),
(7, 'Gravel', 'normal', 80, 'nos'),
(8, 'Wood Planks', 'normal', 38, 'nos'),
(9, 'Paint', 'normal', 47, 'nos'),
(10, 'Roofing Shingles', 'normal', 24, 'nos'),
(11, 'Tiles', 'normal', 77, 'nos'),
(13, 'Test Material', 'normal', 100, 'nos'),
(15, 'Test Material 01', 'normal', 10, 'nos');

-- --------------------------------------------------------

--
-- Table structure for table `material_allocation`
--

CREATE TABLE `material_allocation` (
  `allocation_id` int NOT NULL,
  `allocation_description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `material_allocation`
--

INSERT INTO `material_allocation` (`allocation_id`, `allocation_description`) VALUES
(1, 'Added'),
(2, 'Added'),
(3, 'Added'),
(4, 'Added'),
(5, 'Added'),
(6, 'Added'),
(7, 'Test note'),
(8, 'Added'),
(9, 'Added'),
(10, 'Add');

-- --------------------------------------------------------

--
-- Table structure for table `material_request`
--

CREATE TABLE `material_request` (
  `mat_request_id` int NOT NULL,
  `mat_req_name` varchar(100) NOT NULL,
  `other_name` varchar(100) DEFAULT NULL,
  `mat_count` int NOT NULL,
  `user_mat_request_status` varchar(20) DEFAULT NULL,
  `mat_request_status` varchar(20) DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `project_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `material_request`
--

INSERT INTO `material_request` (`mat_request_id`, `mat_req_name`, `other_name`, `mat_count`, `user_mat_request_status`, `mat_request_status`, `user_id`, `project_id`) VALUES
(1, 'Shovel', NULL, 2, NULL, 'Decline', NULL, 1),
(2, 'Other', 'Wire', 1, NULL, 'Pending', NULL, 1),
(3, 'Bricks', NULL, 150, NULL, 'Pending', NULL, 1),
(4, 'Other', 'Test Other', 25, NULL, 'Pending', NULL, 1),
(5, 'Shovel', NULL, 11, 'Approve', NULL, 5, NULL),
(6, 'Other', 'Wire Roll', 1, 'Pending', NULL, 5, NULL),
(7, 'Concrete Blocks', NULL, 50, 'Approve', NULL, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `material_updation`
--

CREATE TABLE `material_updation` (
  `mat_updation _id` int NOT NULL,
  `mat_update_quantity` int NOT NULL,
  `mat_status` varchar(20) NOT NULL,
  `mat_update_date` date NOT NULL,
  `mat_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `material_updation`
--

INSERT INTO `material_updation` (`mat_updation _id`, `mat_update_quantity`, `mat_status`, `mat_update_date`, `mat_id`, `user_id`) VALUES
(1, 10, 'return', '2024-02-08', 1, 0),
(2, 10, 'return', '2024-02-08', 1, 0),
(3, 10, 'return', '2024-02-08', 1, 0),
(4, 10, 'return', '2024-02-08', 1, 0),
(5, 10, 'New', '2024-02-09', 15, 1),
(6, 10, 'return', '2024-02-09', 5, 1),
(7, 160, 'return', '2024-02-09', 1, 1),
(8, 210, 'return', '2024-02-09', 1, 1),
(9, 10, 'return', '2024-02-17', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `plan`
--

CREATE TABLE `plan` (
  `plan_id` int NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `plan_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `plan`
--

INSERT INTO `plan` (`plan_id`, `plan_name`, `plan_description`) VALUES
(5, 'Skyline Project - Colombo', 'Residential and commercial apartment ');

-- --------------------------------------------------------

--
-- Table structure for table `plan_sub_task`
--

CREATE TABLE `plan_sub_task` (
  `plan_sub_task_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `subtask_id` int NOT NULL,
  `sub_task_status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `plan_sub_task`
--

INSERT INTO `plan_sub_task` (`plan_sub_task_id`, `plan_id`, `subtask_id`, `sub_task_status`) VALUES
(1, 5, 5, 'Completed'),
(2, 5, 6, 'In_Progress'),
(3, 5, 7, 'Not_Yet'),
(4, 5, 8, 'Not_Yet'),
(5, 5, 9, 'Completed'),
(6, 5, 10, 'Not_Yet'),
(7, 5, 11, 'In_Progress'),
(8, 5, 12, 'Not_Yet');

-- --------------------------------------------------------

--
-- Table structure for table `plan_task`
--

CREATE TABLE `plan_task` (
  `plan_task_id` int NOT NULL,
  `plan_id` int NOT NULL,
  `task_id` int NOT NULL,
  `task_status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `plan_task`
--

INSERT INTO `plan_task` (`plan_task_id`, `plan_id`, `task_id`, `task_status`) VALUES
(2, 5, 6, 'Not_Yet'),
(3, 5, 7, 'Not_Yet'),
(4, 5, 8, 'Not_Yet');

-- --------------------------------------------------------

--
-- Table structure for table `plan_task_images`
--

CREATE TABLE `plan_task_images` (
  `image_id` int NOT NULL,
  `plan_task_id` int DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `plan_task_images`
--

INSERT INTO `plan_task_images` (`image_id`, `plan_task_id`, `image_path`) VALUES
(10, 3, 'C:/xampp/htdocs/final-project/image/task-img/img-05.jpg'),
(11, 2, 'C:/xampp/htdocs/final-project/image/task-img/img-01.jpg'),
(12, 2, 'C:/xampp/htdocs/final-project/image/task-img/img-02.jpg'),
(13, 3, 'C:/xampp/htdocs/final-project/image/task-img/img-05.jpg'),
(14, 3, 'C:/xampp/htdocs/final-project/image/task-img/img-04.jpeg'),
(15, 3, 'C:/xampp/htdocs/final-project/image/task-img/img-03.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `project_id` int NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `project_status` varchar(50) NOT NULL,
  `client_id` int NOT NULL,
  `pm_id` int NOT NULL,
  `plan_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`project_id`, `project_name`, `description`, `start_date`, `end_date`, `project_status`, `client_id`, `pm_id`, `plan_id`) VALUES
(1, 'Skyline - Colombo', 'Modern and sustainable urban development in Colombo, integrating residential and commercial spaces for a vibrant cityscape.', '2024-02-06', '2024-03-01', 'Ongoing', 9, 6, 5),
(2, 'Test Project', 'Description Sample', '2024-02-07', '2024-02-10', 'Ongoing', 11, 13, NULL),
(3, 'Road Project', 'description test', '2024-02-07', '2024-02-08', 'Ongoing', 15, 14, NULL),
(4, 'Home', 'description', '2024-02-07', '2024-02-29', 'Done', 11, 0, NULL),
(6, 'Project II', 'This is Project II', '2024-03-05', '2024-03-30', 'Ongoing', 15, 16, NULL),
(9, 'new', 'new', '2024-03-30', '2024-03-29', 'Ongoing', 20, 19, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_material_allocation`
--

CREATE TABLE `project_material_allocation` (
  `id` int NOT NULL,
  `project_id` int NOT NULL,
  `mat_id` int NOT NULL,
  `allocation_id` int NOT NULL,
  `material_allocation_quantity` int NOT NULL,
  `allocation_date` date NOT NULL,
  `allocation_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_material_allocation`
--

INSERT INTO `project_material_allocation` (`id`, `project_id`, `mat_id`, `allocation_id`, `material_allocation_quantity`, `allocation_date`, `allocation_status`) VALUES
(1, 1, 1, 1, 40, '2024-02-07', 'deallocated'),
(2, 2, 1, 2, 10, '2024-02-07', 'allocate'),
(3, 1, 1, 3, 40, '2024-02-07', 'deallocated'),
(4, 1, 1, 4, 40, '2024-02-07', 'deallocated'),
(5, 1, 1, 5, 40, '2024-02-07', 'deallocated'),
(6, 1, 2, 6, 10, '2024-02-07', 'allocate'),
(7, 1, 5, 7, 10, '2024-02-09', 'deallocated'),
(8, 1, 1, 8, 200, '2024-02-09', 'deallocated'),
(9, 1, 1, 9, 10, '2024-02-09', 'deallocated'),
(10, 1, 1, 10, 10, '2024-02-17', 'deallocated');

-- --------------------------------------------------------

--
-- Table structure for table `sub_task`
--

CREATE TABLE `sub_task` (
  `subtask_id` int NOT NULL,
  `subtask_name` varchar(255) NOT NULL,
  `sub_description` text NOT NULL,
  `task_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sub_task`
--

INSERT INTO `sub_task` (`subtask_id`, `subtask_name`, `sub_description`, `task_id`) VALUES
(1, 'Basement', 'Sample Sub-Task', 1),
(2, 'Floor', 'Sample Sub-Task', 2),
(3, 'Wall', 'Sample Sub-Task', 2),
(5, 'Clearing and Excavation', 'Clear the construction site of vegetation and debris', 6),
(6, 'Excavation', 'Dig and prepare the construction site', 7),
(7, 'Framing', 'walls, columns, and beams', 8),
(8, 'Flooring Installation', 'Lay the flooring materials on the first floor', 8),
(9, 'Utility Connections', 'Establish connections for utilities such as water, electricity, and sewage to the site', 6),
(10, 'Wall Enclosure', 'Enclose the first floor with exterior walls', 8),
(11, 'Formwork Setup', 'Construct formwork using materials like wood or metal to create molds that define the shape and structure of the foundation before pouring concrete.', 7),
(12, 'Concrete Pouring and Curing', 'Pour concrete into the formwork', 7);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `confirm_password` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `user_type` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `user_password`, `confirm_password`, `fname`, `lname`, `phone_number`, `user_type`, `company`, `status`) VALUES
(1, 'g', 'g', 'g', 'Dinith', 'Maleesha', '076000001', 'gm', 'In-company', 'Active'),
(5, 's', 's', 's', 'Amshiiieee', 'Costa', '076000000', 'spm', 'In-company', 'Active'),
(6, 'p', 'p', 'p', 'Amal', 'Perera', '076000002', 'pm', 'In-company', 'Active'),
(9, 'c', 'c', 'nave', 'Naveen', 'Shalitha', '076000003', 'Client', 'Naveen pvt', 'Active'),
(10, 'r', 'r', 'r', 'Thanuja', 'Perera', '0789456222', 'rm', 'In-company', 'Active'),
(11, 'q', 'q', 'q', 'Pathum', 'Madushanka', '0756721456', 'Client', 'No ', 'Active'),
(13, 'officialyakaproduction@gmail.com', 'd', 'd', 'Senal', 'Gamange', '0758989899', 'pm', 'In-company', 'Active'),
(14, 'dewmi@gmail.com', 'dewmi1234', 'dewmi1234', 'Dewmi', 'Desadi', '', 'pm', 'In-company', 'Active'),
(15, 'w', 'w', 'w', 'Chamindu', 'Perera', '0758989800', 'Client', 'Perera pvt ltd', 'Active'),
(16, 'p2', 'p2', 'p2', 'Kamal', 'Perera', '0758989855', 'pm', 'In-Company', 'Active'),
(17, 'hash@gmail.com', '$2y$10$wVJq84MNf5/1C5DLFOcByei/0lmfBV0yKurT6A9vTrLhWoyM5R6R2', '$2y$10$wVJq84MNf5/1C5DLFOcByei/0lmfBV0yKurT6A9vTrLhWoyM5R6R2', 'hash', 'hash', '0756878945', 'rm', 'In-company', 'Active'),
(19, 'p3', 'p3', 'p3', 'project ', 'manager 3', '0758989899', 'pm', 'in', 'Active'),
(20, 'new@gmail.com', 'new@1234', 'new@1234', 'new client', 'new client Last Name', '0750000002', 'new', 'new', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `user_mobile`
--

CREATE TABLE `user_mobile` (
  `mobile_id` int NOT NULL,
  `user_id` int NOT NULL,
  `phone_num` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_mobile`
--

INSERT INTO `user_mobile` (`mobile_id`, `user_id`, `phone_num`) VALUES
(4, 5, '123'),
(5, 5, '456');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `employee_allocation`
--
ALTER TABLE `employee_allocation`
  ADD PRIMARY KEY (`employee_allocation_id`);

--
-- Indexes for table `employee_request`
--
ALTER TABLE `employee_request`
  ADD PRIMARY KEY (`emp_req_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `main_task`
--
ALTER TABLE `main_task`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`mat_id`);

--
-- Indexes for table `material_allocation`
--
ALTER TABLE `material_allocation`
  ADD PRIMARY KEY (`allocation_id`);

--
-- Indexes for table `material_request`
--
ALTER TABLE `material_request`
  ADD PRIMARY KEY (`mat_request_id`);

--
-- Indexes for table `material_updation`
--
ALTER TABLE `material_updation`
  ADD PRIMARY KEY (`mat_updation _id`);

--
-- Indexes for table `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`plan_id`);

--
-- Indexes for table `plan_sub_task`
--
ALTER TABLE `plan_sub_task`
  ADD PRIMARY KEY (`plan_sub_task_id`);

--
-- Indexes for table `plan_task`
--
ALTER TABLE `plan_task`
  ADD PRIMARY KEY (`plan_task_id`);

--
-- Indexes for table `plan_task_images`
--
ALTER TABLE `plan_task_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`project_id`);

--
-- Indexes for table `project_material_allocation`
--
ALTER TABLE `project_material_allocation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_task`
--
ALTER TABLE `sub_task`
  ADD PRIMARY KEY (`subtask_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_mobile`
--
ALTER TABLE `user_mobile`
  ADD PRIMARY KEY (`mobile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `employee_allocation`
--
ALTER TABLE `employee_allocation`
  MODIFY `employee_allocation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee_request`
--
ALTER TABLE `employee_request`
  MODIFY `emp_req_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `main_task`
--
ALTER TABLE `main_task`
  MODIFY `task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `mat_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `material_allocation`
--
ALTER TABLE `material_allocation`
  MODIFY `allocation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `material_request`
--
ALTER TABLE `material_request`
  MODIFY `mat_request_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `material_updation`
--
ALTER TABLE `material_updation`
  MODIFY `mat_updation _id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `plan`
--
ALTER TABLE `plan`
  MODIFY `plan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `plan_sub_task`
--
ALTER TABLE `plan_sub_task`
  MODIFY `plan_sub_task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `plan_task`
--
ALTER TABLE `plan_task`
  MODIFY `plan_task_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `plan_task_images`
--
ALTER TABLE `plan_task_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `project_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `project_material_allocation`
--
ALTER TABLE `project_material_allocation`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `sub_task`
--
ALTER TABLE `sub_task`
  MODIFY `subtask_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `user_mobile`
--
ALTER TABLE `user_mobile`
  MODIFY `mobile_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
