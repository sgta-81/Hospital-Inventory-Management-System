-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2021 at 03:44 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dep_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `1617173911`
--

CREATE TABLE `1617173911` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617173911`
--

INSERT INTO `1617173911` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1A', 1, '0', '0', ''),
(1, '1A', 1, '0022', '010', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617261248`
--

CREATE TABLE `1617261248` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `1617261287`
--

CREATE TABLE `1617261287` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617261287`
--

INSERT INTO `1617261287` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '0', 1, '0', '0', ''),
(1, '2', 1, '0', '000', ''),
(1, '7', 1, '00', '0000', ''),
(1, '51', 1, '00', '00', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617261549`
--

CREATE TABLE `1617261549` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617261549`
--

INSERT INTO `1617261549` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '0', 1, '0', '0', ''),
(1, '2', 1, '0', '110', ''),
(1, '7', 1, '02', '0110', ''),
(1, '51', 1, '20', '10', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617261684`
--

CREATE TABLE `1617261684` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617261684`
--

INSERT INTO `1617261684` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '023', '00', ''),
(1, '2', 1, '0', '011', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617564034`
--

CREATE TABLE `1617564034` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617564034`
--

INSERT INTO `1617564034` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '2', 1, '020', '01', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617564165`
--

CREATE TABLE `1617564165` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617564165`
--

INSERT INTO `1617564165` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', ''),
(1, '2', 1, '200', '01', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617614156`
--

CREATE TABLE `1617614156` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617614156`
--

INSERT INTO `1617614156` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', 'None'),
(2, '1', 1, '00', '000', 'Not any'),
(3, '1', 1, '0', '0', ''),
(4, '1', 1, '0', '0', ''),
(5, '1', 1, '0', '00', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617614670`
--

CREATE TABLE `1617614670` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617614670`
--

INSERT INTO `1617614670` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(5, '1', 1, '0', '00', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `1617615019`
--

CREATE TABLE `1617615019` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617615019`
--

INSERT INTO `1617615019` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(5, '1', 1, '0', '00', 'none');

-- --------------------------------------------------------

--
-- Table structure for table `1617621689`
--

CREATE TABLE `1617621689` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617621689`
--

INSERT INTO `1617621689` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', ''),
(2, '3', 1, '00000000', '000', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `1617635441`
--

CREATE TABLE `1617635441` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617635441`
--

INSERT INTO `1617635441` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', 'Testing');

-- --------------------------------------------------------

--
-- Table structure for table `1617635593`
--

CREATE TABLE `1617635593` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617635593`
--

INSERT INTO `1617635593` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', 'Testing');

-- --------------------------------------------------------

--
-- Table structure for table `1617635742`
--

CREATE TABLE `1617635742` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617635742`
--

INSERT INTO `1617635742` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '2', 1, '022', '01', 'Testing again');

-- --------------------------------------------------------

--
-- Table structure for table `1617635829`
--

CREATE TABLE `1617635829` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617635829`
--

INSERT INTO `1617635829` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '2', 1, '023', '01', ''),
(2, '2', 1, '00', '000', ''),
(3, '5', 1, '0000', '00', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617641568`
--

CREATE TABLE `1617641568` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617641568`
--

INSERT INTO `1617641568` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '4', '0', 'R1'),
(1, '2', 1, '030', '10', 'R2'),
(1, '3', 1, '0', '011', 'R3'),
(1, '4', 1, '0', '10', 'R4'),
(1, '5', 1, '0', '0', 'R5'),
(1, '6', 1, '30', '00', 'R6'),
(1, '7', 1, '0', '000', 'R7'),
(1, '8', 1, '04', '0000', 'R8'),
(1, '9', 1, '0', '00', ''),
(1, '10', 1, '03000', '01', ''),
(1, '11', 1, '02300', '10', ''),
(1, '12', 1, '20', '010', ''),
(2, '1', 1, '00', '000', 'fgh');

-- --------------------------------------------------------

--
-- Table structure for table `1617645016`
--

CREATE TABLE `1617645016` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `1617645046`
--

CREATE TABLE `1617645046` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617645046`
--

INSERT INTO `1617645046` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', 'none'),
(2, '3', 1, '00000000', '000', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617654484`
--

CREATE TABLE `1617654484` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617654484`
--

INSERT INTO `1617654484` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', 'Remark 1'),
(1, '2', 1, '020', '01', 'R2'),
(1, '3', 1, '0', '111', 'R3'),
(2, '2', 1, '00', '000', 'Sec2');

-- --------------------------------------------------------

--
-- Table structure for table `1617688049`
--

CREATE TABLE `1617688049` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617688049`
--

INSERT INTO `1617688049` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617693586`
--

CREATE TABLE `1617693586` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `1617693630`
--

CREATE TABLE `1617693630` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `1617693637`
--

CREATE TABLE `1617693637` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `1617693685`
--

CREATE TABLE `1617693685` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617693685`
--

INSERT INTO `1617693685` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '2', 1, '020', '01', 'ffff');

-- --------------------------------------------------------

--
-- Table structure for table `1617695684`
--

CREATE TABLE `1617695684` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617695684`
--

INSERT INTO `1617695684` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', ''),
(1, '2', 1, '023', '11', 'R2'),
(1, '4', 1, '0', '01', '');

-- --------------------------------------------------------

--
-- Table structure for table `1617703828`
--

CREATE TABLE `1617703828` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1617703828`
--

INSERT INTO `1617703828` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '0', '0', 'R1'),
(1, '2', 1, '022', '01', 'R2'),
(3, '2', 1, '0', '0', 'R3');

-- --------------------------------------------------------

--
-- Table structure for table `1618595496`
--

CREATE TABLE `1618595496` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618595496`
--

INSERT INTO `1618595496` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '4', '0', 'R1'),
(1, '2', 1, '030', '10', 'R2'),
(1, '3', 1, '0', '011', 'R3'),
(1, '4', 1, '0', '10', 'R4'),
(1, '5', 1, '0', '0', 'R5'),
(1, '6', 1, '30', '00', 'R6'),
(1, '7', 1, '0', '000', 'R7'),
(1, '8', 1, '04', '0000', 'R8'),
(1, '9', 1, '0', '00', ''),
(1, '10', 1, '03000', '01', ''),
(1, '11', 1, '02300', '10', ''),
(1, '12', 1, '20', '010', '');

-- --------------------------------------------------------

--
-- Table structure for table `1618595594`
--

CREATE TABLE `1618595594` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618595594`
--

INSERT INTO `1618595594` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '2', '0', 'R123'),
(1, '2', 1, '030', '10', 'R2'),
(1, '3', 1, '0', '011', 'R3'),
(1, '4', 1, '0', '10', 'R4'),
(1, '5', 1, '0', '0', 'R5'),
(1, '6', 1, '30', '00', 'R6'),
(1, '7', 1, '0', '000', 'R7'),
(1, '8', 1, '04', '0000', 'R8'),
(1, '9', 1, '0', '00', ''),
(1, '10', 1, '03000', '01', ''),
(1, '11', 1, '02300', '10', ''),
(1, '12', 1, '20', '010', '');

-- --------------------------------------------------------

--
-- Table structure for table `1618642506`
--

CREATE TABLE `1618642506` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618642506`
--

INSERT INTO `1618642506` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '2', 1, '030', '10', 'R2'),
(1, '3', 1, '0', '011', 'R3'),
(1, '4', 1, '0', '10', 'R4'),
(1, '5', 1, '0', '0', 'R5'),
(1, '6', 1, '30', '00', 'R6'),
(1, '7', 1, '0', '000', 'R7'),
(1, '8', 1, '04', '0000', 'R8'),
(1, '9', 1, '0', '00', ''),
(1, '10', 1, '03000', '01', ''),
(1, '11', 1, '02300', '10', ''),
(1, '12', 1, '20', '010', '');

-- --------------------------------------------------------

--
-- Table structure for table `1618664152`
--

CREATE TABLE `1618664152` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618664152`
--

INSERT INTO `1618664152` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '2', '0', 'r1'),
(2, '1', 1, '00', '000', 'R2'),
(2, '2', 1, '00', '000', 'r3');

-- --------------------------------------------------------

--
-- Table structure for table `1618677990`
--

CREATE TABLE `1618677990` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618677990`
--

INSERT INTO `1618677990` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '2', '0', NULL),
(1, '3', 1, '3', '100', NULL),
(1, '4', 1, '3', '10', NULL),
(1, '5', 1, '3', '0', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `1618677990_um`
--

CREATE TABLE `1618677990_um` (
  `srno` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `remark` text DEFAULT NULL,
  `qty_c` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618677990_um`
--

INSERT INTO `1618677990_um` (`srno`, `qty`, `remark`, `qty_c`) VALUES
(1, 2, '--', 2),
(2, 2, '--', 1),
(3, 1, '--', 1),
(4, 1, '--', 1);

-- --------------------------------------------------------

--
-- Table structure for table `1618690074`
--

CREATE TABLE `1618690074` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618690074`
--

INSERT INTO `1618690074` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '2', '0', ''),
(1, '3', 1, '3', '100', ''),
(1, '4', 1, '3', '10', ''),
(1, '5', 1, '3', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `1618690074_um`
--

CREATE TABLE `1618690074_um` (
  `srno` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `remark` text DEFAULT NULL,
  `qty_c` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618690074_um`
--

INSERT INTO `1618690074_um` (`srno`, `qty`, `remark`, `qty_c`) VALUES
(1, 2, '', NULL),
(2, 7, '', NULL),
(3, 3, '', NULL),
(4, 3, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `1618726403`
--

CREATE TABLE `1618726403` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618726403`
--

INSERT INTO `1618726403` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '3', '0', 'r1'),
(1, '2', 1, '030', '01', ''),
(1, '3', 1, '3', '000', 'r3');

-- --------------------------------------------------------

--
-- Table structure for table `1618727544`
--

CREATE TABLE `1618727544` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1618727544`
--

INSERT INTO `1618727544` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '3', '0', 'r1'),
(1, '2', 1, '030', '01', ''),
(1, '3', 1, '2', '010', 'r3');

-- --------------------------------------------------------

--
-- Table structure for table `1620198669`
--

CREATE TABLE `1620198669` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1620198669`
--

INSERT INTO `1620198669` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '2', '0', ''),
(1, '2', 1, '202', '10', ''),
(1, '3', 1, '2', '100', ''),
(1, '4', 1, '3', '01', ''),
(2, '1', 1, '00', '000', ''),
(3, '1', 1, '0', '0', 'df');

-- --------------------------------------------------------

--
-- Table structure for table `1620198669_um`
--

CREATE TABLE `1620198669_um` (
  `srno` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `remark` text DEFAULT NULL,
  `qty_c` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1620198669_um`
--

INSERT INTO `1620198669_um` (`srno`, `qty`, `remark`, `qty_c`) VALUES
(1, 3, '--', 0),
(2, 2, '--', 0),
(3, 1, '--', 0),
(4, 1, '--', 0),
(5, 2, '--', 0),
(6, 1, '--', 0);

-- --------------------------------------------------------

--
-- Table structure for table `1620201073`
--

CREATE TABLE `1620201073` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1620201073`
--

INSERT INTO `1620201073` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '3', '0', ''),
(1, '2', 1, '030', '01', ''),
(1, '3', 1, '3', '010', '');

-- --------------------------------------------------------

--
-- Table structure for table `1620201073_um`
--

CREATE TABLE `1620201073_um` (
  `srno` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `remark` text DEFAULT NULL,
  `qty_c` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `1620201073_um`
--

INSERT INTO `1620201073_um` (`srno`, `qty`, `remark`, `qty_c`) VALUES
(1, 3, '--', 2),
(2, 2, '--', 1),
(3, 3, '--', 3);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `eid` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `ename` varchar(40) NOT NULL,
  `etype` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`eid`, `password`, `ename`, `etype`) VALUES
('', '$2y$10$4lg1Qi11F3QpeAC/odDYueBExHgMVSTL1Omf3AvBtKwBtxL6KdLvK', '', 'Doctor'),
('gs123', '$2y$10$N2K6IoUd38G5qDtFXC9vDue4Xs6zz9rnBCWNpcvFdJK0bBuCp6GM2', 'Gurpreet Singh', 'Nurse'),
('perf123', '$2y$10$0kjqcDSHoEs2wAEEtnoPherULLXUFqG9isHoN4ICPB4.QU1Bw1//C', 'Bhuvan', 'Perfusionist'),
('pg123', '$2y$10$Ry1cmynL1E8su2FbQ57RMOVQI5Px/38La4z00PD5i9kCX5SV/OHH2', 'Puneet Goyal', 'Doctor'),
('sg123', '$2y$10$19bt4opzD.JeyS3aCOIOhufwAGmhA1IBG2ehs3XFCH0t3iho0Ohde', 'Sachsham Gupta', 'Doctor'),
('tech123', '$2y$10$rCStmDKMPyS6kY9HPdmX9uozv83HSZ55MKQ9ev3c.lVrK/JvcMVHe', 'Amit ', 'Technician'),
('uy123', '$2y$10$KiBSsxAX40i638nmO4uQcejnBkiXxb83JvueFmzckUAuHTiq0fXUq', 'Ujjwal Yadav', 'Nurse');

-- --------------------------------------------------------

--
-- Table structure for table `formhistory`
--

CREATE TABLE `formhistory` (
  `FormID` varchar(500) NOT NULL,
  `PatientID` varchar(15) NOT NULL,
  `DateRequired` date DEFAULT NULL,
  `DateCreated` text DEFAULT NULL,
  `createdby` varchar(256) NOT NULL,
  `Status` varchar(1000) DEFAULT NULL,
  `StatusCon` varchar(1000) NOT NULL,
  `StatusUM` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `formhistory`
--

INSERT INTO `formhistory` (`FormID`, `PatientID`, `DateRequired`, `DateCreated`, `createdby`, `Status`, `StatusCon`, `StatusUM`) VALUES
('1617693586', '123456789012', '2021-04-10', '2021-04-06 - 12:49:46', 'Gurpreet Singh', 'In Progress', 'In Progress', 'NA'),
('1617693630', '123456789012', '2021-04-10', '2021-04-06 - 12:50:30', 'Gurpreet Singh', 'In Progress', 'In Progress', 'NA'),
('1617693637', '123456789012', '2021-04-10', '2021-04-06 - 12:50:37', 'Gurpreet Singh', 'In Progress', 'In Progress', 'NA'),
('1617693685', '123456789012', '2021-04-10', '2021-04-06 - 12:51:25', 'Gurpreet Singh', 'In Progress', 'In Progress', 'NA'),
('1617695684', '123456789012', '2021-04-10', '2021-04-06 - 01:24:44', 'Gurpreet Singh', 'In Progress', 'In Progress', 'NA'),
('1617703828', '123456789012', '2021-04-10', '2021-04-06 - 03:40:28', 'Gurpreet Singh', 'In Progress', 'In Progress', 'NA'),
('1618595496', '123456789012', '2021-04-10', '2021-04-16 - 11:21:36', 'Gurpreet Singh', 'In Progress', 'In Progress', 'NA'),
('1618595594', '123456789012', '2021-04-10', '2021-04-16 - 11:23:14', 'Gurpreet Singh', 'Sent for Approval', 'Approval Pending', 'NA'),
('1618642506', '123456789012', '2021-04-10', '2021-04-17 - 12:25:06', 'Gurpreet Singh', 'Sent for Approval', 'Approval Pending', 'NA'),
('1618664152', '123456654321', '2021-04-30', '2021-04-17 - 06:25:52', 'Gurpreet Singh', 'Sent for Approval', 'Approval Pending', 'NA'),
('1618677990', '123456654329', '2021-04-24', '2021-04-17 - 10:16:30', 'Gurpreet Singh', 'Completed', 'Approved', 'Stock Rcvd and Fwd'),
('1618690074', '123456654328', '2021-04-30', '2021-04-18 - 01:37:54', 'Gurpreet Singh', 'Completed', 'Approved', 'Stock Rcvd'),
('1618726403', '123456789888', '2021-04-23', '2021-04-18 - 11:43:23', 'Gurpreet Singh', 'Dispatched to Pharma', 'Approved', 'Dispatched to Pharma'),
('1618727544', '123456789787', '2021-04-22', '2021-04-18 - 12:02:24', 'Gurpreet Singh', 'Approved by Consultant', 'Approved', 'Ready for Dispatch'),
('1620198669', '123456654333', '2021-05-09', '2021-05-05 - 12:41:09', 'Gurpreet Singh', 'Completed', 'Approved', 'Stock Rcvd and fwd'),
('1620201073', '123456654252', '2021-05-12', '2021-05-05 - 01:21:13', 'Gurpreet Singh', 'Completed', 'Approved', 'Stock Rcvd and fwd');

-- --------------------------------------------------------

--
-- Table structure for table `item_entry`
--

CREATE TABLE `item_entry` (
  `idx` int(11) NOT NULL,
  `item_type` char(1) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `descr` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `spec` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_entry`
--

INSERT INTO `item_entry` (`idx`, `item_type`, `name`, `descr`, `brand`, `qty`, `spec`) VALUES
(1, 'A', 'Dial Flow', 'None', 'Leventon', 6, 'None'),
(20, 'C', 'ETHILON 2-O', 'None', 'ETHICON', 3, 'ETHILON 2-O Copde: NW 3336'),
(21, 'C', 'ETHILON 3-O', 'None', 'ETHICON', 5, 'ETHILON 2-O Copde: NW 3328'),
(34, 'B', 'CPB Custom tubing pack, Adult', 'None', 'B L Lifescience', 6, 'None'),
(45, 'A', 'Dial Flow', 'None', 'Leventon', 6, 'None'),
(457, 'A', 'Dial Flow', 'None', 'Leventon', 6, 'None');

-- --------------------------------------------------------

--
-- Table structure for table `new_form`
--

CREATE TABLE `new_form` (
  `section` int(3) NOT NULL,
  `srno` varchar(10) NOT NULL,
  `selected` int(2) NOT NULL DEFAULT 0,
  `qty` varchar(100) NOT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT '-'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `new_form`
--

INSERT INTO `new_form` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(0, '', 0, '0', '', ''),
(0, '1', 0, '0', '', ''),
(1, '', 0, '', '', ''),
(1, '1A', 1, '220', '01', ''),
(1, 'N', 1, '0', '0', '');

-- --------------------------------------------------------

--
-- Table structure for table `patient_details`
--

CREATE TABLE `patient_details` (
  `name` varchar(80) NOT NULL,
  `cr_no` text NOT NULL,
  `consultant_name` varchar(80) NOT NULL,
  `age` int(3) NOT NULL,
  `gender` text NOT NULL,
  `weight` int(3) NOT NULL,
  `bsa` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient_details`
--

INSERT INTO `patient_details` (`name`, `cr_no`, `consultant_name`, `age`, `gender`, `weight`, `bsa`) VALUES
('Ahmed', '123456654252', 'Dr Harish Suri', 45, 'M', 77, 170),
('arun', '123456654321', 'Dr Harish', 56, 'M', 77, 160),
('Bhavesh', '123456654328', 'Dr Harish Suri', 88, 'M', 89, 170),
('Aman Kumar', '123456654329', 'Dr Harish Suri', 88, 'M', 89, 170),
('Waseem', '123456654333', 'Dr Harish Suri', 66, 'M', 77, 170),
('arun', '123456789012', 'Dr Harish', 77, 'on', 77, 180),
('Bhavesh', '123456789787', 'Dr Harish Suri', 88, 'M', 89, 180),
('Aman Kumar', '123456789888', 'Dr Harish Suri', 99, 'M', 89, 180);

-- --------------------------------------------------------

--
-- Table structure for table `sec1`
--

CREATE TABLE `sec1` (
  `sr_no` int(2) DEFAULT NULL,
  `specs` int(1) DEFAULT NULL,
  `companies` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec1`
--

INSERT INTO `sec1` (`sr_no`, `specs`, `companies`) VALUES
(1, 1, 1),
(2, 3, 2),
(3, 1, 3),
(4, 1, 2),
(5, 1, 1),
(6, 2, 2),
(7, 1, 3),
(8, 2, 4),
(9, 1, 2),
(10, 5, 2),
(11, 5, 2),
(12, 2, 3),
(13, 1, 3),
(14, 1, 2),
(15, 1, 2),
(16, 1, 1),
(17, 1, 1),
(18, 1, 5),
(19, 1, 5),
(20, 1, 2),
(21, 9, 3),
(22, 8, 3),
(23, 5, 1),
(24, 1, 2),
(25, 2, 3),
(26, 1, 2),
(27, 2, 2),
(28, 2, 2),
(29, 2, 1),
(30, 1, 3),
(31, 6, 1),
(32, 1, 3),
(33, 1, 2),
(34, 1, 2),
(35, 1, 1),
(36, 1, 2),
(37, 3, 1),
(38, 3, 2),
(39, 1, 1),
(40, 1, 3),
(41, 1, 3),
(42, 7, 1),
(43, 1, 1),
(44, 1, 1),
(45, 3, 2),
(46, 3, 1),
(47, 1, 1),
(48, 1, 3),
(49, 6, 3),
(50, 6, 3),
(51, 2, 3),
(52, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `sec2`
--

CREATE TABLE `sec2` (
  `sr_no` int(2) DEFAULT NULL,
  `specs` int(1) DEFAULT NULL,
  `companies` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec2`
--

INSERT INTO `sec2` (`sr_no`, `specs`, `companies`) VALUES
(1, 2, 3),
(2, 2, 3),
(3, 8, 3),
(4, 1, 3),
(5, 1, 3),
(6, 1, 4),
(7, 1, 1),
(8, 1, 2),
(9, 1, 2),
(10, 1, 2),
(11, 1, 2),
(12, 1, 1),
(13, 1, 1),
(14, 1, 2),
(15, 1, 2),
(16, 1, 2),
(17, 1, 2),
(18, 2, 1),
(19, 3, 2),
(20, 3, 3),
(21, 2, 1),
(22, 1, 3),
(23, 1, 3),
(24, 9, 3),
(25, 9, 3),
(26, 2, 3),
(27, 4, 4),
(28, 1, 3),
(29, 1, 3),
(30, 1, 3),
(31, 1, 2),
(32, 2, 2),
(33, 2, 2),
(34, 5, 3),
(35, 1, 4),
(36, 1, 2),
(37, 1, 2),
(38, 1, 2),
(39, 1, 2),
(40, 1, 2),
(41, 4, 2),
(42, 4, 2),
(43, 4, 1),
(44, 4, 2),
(45, 4, 2),
(46, 2, 2),
(47, 3, 2),
(48, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sec3`
--

CREATE TABLE `sec3` (
  `sr_no` int(2) DEFAULT NULL,
  `specs` int(1) DEFAULT NULL,
  `companies` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec3`
--

INSERT INTO `sec3` (`sr_no`, `specs`, `companies`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 1),
(4, 1, 1),
(5, 4, 2),
(6, 2, 3),
(7, 1, 3),
(8, 4, 1),
(9, 1, 1),
(10, 2, 1),
(11, 1, 3),
(12, 1, 1),
(13, 1, 2),
(14, 1, 2),
(15, 1, 1),
(16, 1, 1),
(17, 1, 1),
(18, 1, 1),
(19, 1, 1),
(20, 1, 2),
(21, 1, 2),
(22, 1, 2),
(23, 1, 1),
(24, 1, 1),
(25, 1, 1),
(26, 1, 1),
(27, 1, 1),
(28, 1, 1),
(29, 1, 1),
(30, 1, 1),
(31, 1, 1),
(32, 1, 3),
(33, 1, 1),
(34, 1, 1),
(35, 1, 3),
(36, 1, 3),
(37, 2, 3),
(38, 1, 3),
(39, 1, 1),
(40, 2, 1),
(41, 1, 2),
(42, 1, 1),
(43, 2, 1),
(44, 1, 1),
(45, 1, 1),
(46, 5, 2),
(47, 4, 1),
(48, 1, 1),
(49, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sec4`
--

CREATE TABLE `sec4` (
  `sr_no` int(2) DEFAULT NULL,
  `specs` int(1) DEFAULT NULL,
  `companies` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec4`
--

INSERT INTO `sec4` (`sr_no`, `specs`, `companies`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 2),
(4, 1, 1),
(5, 1, 1),
(6, 1, 1),
(7, 1, 1),
(8, 1, 2),
(9, 1, 2),
(10, 1, 1),
(11, 1, 1),
(12, 1, 1),
(13, 1, 1),
(14, 1, 1),
(15, 1, 1),
(16, 1, 1),
(17, 1, 1),
(18, 1, 1),
(19, 1, 1),
(20, 1, 1),
(21, 1, 1),
(22, 1, 1),
(23, 1, 1),
(24, 1, 2),
(25, 1, 2),
(26, 1, 1),
(27, 1, 1),
(28, 1, 1),
(29, 1, 1),
(30, 1, 1),
(31, 1, 1),
(32, 1, 1),
(33, 1, 1),
(34, 1, 1),
(35, 1, 2),
(36, 1, 2),
(37, 1, 1),
(38, 1, 1),
(39, 1, 1),
(40, 1, 1),
(41, 1, 1),
(42, 1, 1),
(43, 1, 1),
(44, 1, 1),
(45, 1, 1),
(46, 1, 1),
(47, 1, 1),
(48, 1, 1),
(49, 1, 1),
(50, 1, 1),
(51, 1, 1),
(52, 1, 1),
(53, 1, 1),
(54, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sec5`
--

CREATE TABLE `sec5` (
  `sr_no` int(2) DEFAULT NULL,
  `specs` int(1) DEFAULT NULL,
  `companies` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sec5`
--

INSERT INTO `sec5` (`sr_no`, `specs`, `companies`) VALUES
(1, 1, 2),
(2, 3, 1),
(3, 1, 1),
(4, 1, 1),
(5, 1, 1),
(6, 1, 3),
(7, 1, 1),
(8, 2, 3),
(9, 1, 2),
(10, 3, 2),
(11, 1, 2),
(12, 1, 3),
(13, 1, 2),
(14, 2, 3),
(15, 2, 3),
(16, 4, 4),
(17, 2, 3),
(18, 2, 2),
(19, 1, 1),
(20, 1, 1),
(21, 1, 4),
(22, 1, 1),
(23, 1, 2),
(24, 1, 2),
(25, 2, 4),
(26, 1, 1),
(27, 2, 1),
(28, 1, 2),
(29, 1, 1),
(30, 1, 1),
(31, 1, 1),
(32, 1, 2),
(33, 1, 3),
(34, 1, 2),
(35, 1, 2),
(36, 1, 4),
(37, 1, 1),
(38, 1, 2),
(39, 1, 2),
(40, 1, 2),
(41, 1, 1),
(42, 1, 3),
(43, 1, 2),
(44, 1, 2),
(45, 1, 2),
(46, 1, 1),
(47, 1, 2),
(48, 1, 1),
(49, 1, 2),
(50, 2, 4),
(51, 2, 3),
(52, 1, 3),
(53, 2, 4),
(54, 2, 3),
(55, 1, 2),
(56, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `seca_items`
--

CREATE TABLE `seca_items` (
  `srno` int(11) NOT NULL,
  `item_id` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seca_items`
--

INSERT INTO `seca_items` (`srno`, `item_id`, `name`, `spec`, `brand`) VALUES
(1, '1', 'ACT tubes', 'Compatible to our machine', 'Helena Lab-Beaumount Texas'),
(2, '2', 'Arterial line cannula', '18 G, 20 G, 22 G', 'Becton Dickinson(BD), Vygon'),
(3, '3A', 'Blood glucose strip', 'Compatible to our machine', 'Arkay factory, Nipro, Optium'),
(4, '3B', 'Blood transfusion Set ', 'with leur lock', 'BD, Romson'),
(5, '4', 'BIS Sensor', 'Adult  Pediatric', 'Medtronic'),
(6, '5', 'Bronchial blocker', '5F, 7F', 'Portex, Rusch'),
(7, '6', 'Camera cover', '', 'Surgiwear, Teleflex, Niko'),
(8, '7', 'Central Venous line', 'Triple lumen, Four Lumen', 'Arrow, Edward Lifesciences, Vygon, 3M'),
(9, '8', 'Dial flow', '', 'Leventon, Romson'),
(10, '9A', 'Disposable Syringe with leur lock', '2 ml, 5 ml, 10 ml, 20 ml, 50 ml', 'Dispovan, BD'),
(11, '9B', 'Disposable Syringe without leur lock', '1 ml, 2ml, 5ml, 20ml, 50 ml', 'Dispovan, BD'),
(12, '10A', 'Disposable Ventilator tubing, Adult', 'with water trap, without water trap', 'Portex, Intersurgical, Life line'),
(13, '10B', 'Disposable Ventilator tubing, Pediatric', '-', 'Portex, Intersurgical, Life line'),
(14, '11A', 'Disposable Nasal Prong, Adult', '-', 'Romson, Medisafe'),
(15, '11B', 'Disposable nasal prong, Pediatric', '-', 'Romson, Medisafe'),
(16, '12', 'Disposable IV Canula Fixation Dressing', '', 'Primapore'),
(17, '13', 'Durapore', '4 inch', '3M'),
(18, '14A', 'ECG elctrodes, Adult', '', '3M, ARBO, Niko, Kenny, Swaromed'),
(19, '14B', 'ECG elctrodes, Pediatric', '', '3M, ARBO, Niko, Kenny, Swaromed'),
(20, '15', 'Elastoplast ( Dynaplast)', '4 inch', '3M, Johnson & johnson'),
(21, '16A', 'Endotracheal tube with cuffed', '4.5mm, 5mm, 5.5mm, 6mm, 6.5mm, 7mm, 7.5mm, 8mm, 8.5mm', 'Portex, Rusch, Vygon'),
(22, '16B', 'Endotracheal tube with Uncuffed', '4.5mm, 5mm, 5.5mm, 6mm, 6.5mm, 3mm, 3.5mm, 4mm', 'Portex, Rusch, Vygon'),
(23, '16C', 'Endotracheal tube, Double lumen tube ( DLT)', '26Fr, 28Fr, 32Fr, 37Fr, 39Fr', 'Portex'),
(24, '17', 'Epidural set ( MINI PACK)', '', 'Portex, Vygon'),
(25, '18', 'Heat and Moisture Exchanger (HME)', 'Adult, Pediatric', 'Portex, Intersurgical, Life line'),
(26, '19', 'High Pressure Bag', ' for rapid infusion', 'Ethox, SunMed'),
(27, '20', 'High Pressure tubing, Male to Male', '150cm, 200cm', 'Romson, Vygon'),
(28, '21', 'High Pressure tubing, Male to Female', '150cm, 200cm', 'Romson, Vygon'),
(29, '22', 'Hypodermic needle', '26 G- 0.5 inch, 26 G- 1.5 inch', 'BD'),
(30, '23', 'IV infusion set, with leur lock', '', 'Romson, BD, Braun'),
(31, '24', 'Infant feeding tube', '5 fr, 6 Fr, 7 Fr, 8 Fr, 9 Fr, 10 Fr', 'Romson'),
(32, '25', 'IV infusion set, without leur lock', '', 'Romson, BD, Braun'),
(33, '26', 'J R circuit ( Jackson Rees) Pediatric', '', 'Medisafe, Lifeline'),
(34, '27', 'Microdrip set', '', 'Baxter, B. Braun'),
(35, '28', 'Multifunctional Electrode Pads/ AED pads', 'Supplied as a pair', 'Philips'),
(36, '29', 'Multilumen extension', 'to be connected peripheral IV cannula', 'Vygon, BD'),
(37, '30', 'NIRS Sensor', 'Neonatal, Pediatric, Adult', 'Covidein'),
(38, '31', 'NIV mask', 'Small, Medium, Large', 'Pneumocare, Resmed'),
(39, '32', 'NOX BOX circuit', '', 'Bedfont (Scientific Ltd)'),
(40, '33', 'Pediatric drip set ( Burrete set) with leur lock', '', 'Baxter, B. Braun, Romson'),
(41, '34', 'Pediatric drip set ( Burrete set) without leur lock', '', 'Baxter, B. Braun, Romson'),
(42, '35', 'Peripheral Venous cannula', '14G, 16G, 18G, 20G, 22G, 24G, 26G', 'Becton Dickinson(BD)'),
(43, '36', 'Pressure Monitoring kit with Dome, Double', 'Compatible with our machine', 'Edward life sciences'),
(44, '37', 'Pressure Monitoring kit with Dome, Single', 'Compatible with our machine', 'Edward life sciences'),
(45, '38', 'RAMS Cannula', 'Neonate, Pediatric, Adult', 'Romson, Polymed'),
(46, '39', 'Ryles tube', '10Fr, 12 Fr, 14 Fr', 'Romson'),
(47, '40', 'SIPAP circuit', 'Neonate', 'SIPAP'),
(48, '41', 'Sticking plaster ( Leukoplast)', '4 inch', '3M, Johnson & Johnson, Romson'),
(49, '42', 'Suction Catheter with eye', '6 Fr, 8 Fr, 10 fr, 12 Fr, 14Fr, 16 Fr', 'Romson, Portex, Top'),
(50, '43', 'Suction Catheter without eye', '6 Fr, 8 Fr, 10 fr, 12 Fr, 14Fr ,16 Fr', 'Romson, Portex, Top'),
(51, '44', 'Three way with extension', '10 cm, 100 cm', 'Becton Dickinson(BD), Vygon, Top'),
(52, '45', 'Three way without extension', '10cm, 100cm', 'Becton Dickinson ( BD), Vygon');

-- --------------------------------------------------------

--
-- Table structure for table `secb_items`
--

CREATE TABLE `secb_items` (
  `srno` int(11) NOT NULL DEFAULT 0,
  `item_id` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `secb_items`
--

INSERT INTO `secb_items` (`srno`, `item_id`, `name`, `spec`, `brand`) VALUES
(1, '1', 'Antegrade cardioplegia Cannula, with vent', '14 G ( Code: 20014), 16G ( Code: 20016)', 'Medtronic, Edward, Sarns'),
(2, '2A', 'Aortic perfusion cannula, wire reinforced, Angled', '20Fr, 22 Fr', 'Medtronic, Edward, Sarns'),
(3, '2B', 'Aortic perfusion cannula, wire reinforced, straight', '6 Fr, 8 Fr, 10 Fr, 12 Fr, 14 Fr, 16 fr, 18 FR, 20 Fr', 'Medtronic (BioMedicus), Edward, Sarns'),
(4, '3A', 'Autovent cum Filter, Adult', 'for Adult CPB circuit', ' B L Lifesciences, Dideco, Life line'),
(5, '3B', 'Autovent cum Filter, Pediatric', 'for pediatric CPB', ' B L Lifesciences, Dideco, Life line'),
(6, '3C', 'Autovent cum filter, Neonate and infant', 'Neonate or infant for 3 to 10 kg ', 'B L Lifesciences, Dideco, life line,Medtronic'),
(7, '3D', 'Autovent cum Filter, Neonate', 'Neonate baby with  less than 3 kg ', 'Pall'),
(8, '4', 'Blower/ Mister', '', 'Medtronic, Guidant'),
(9, '5A', 'Cardiovascular Patch, ePTFE', 'PTFE W 50mm L 75mm thickness 0.6 mm', 'Bard, Goretex'),
(10, '5B', 'Cardiovascualr Patch, e PTFE membrane', 'PTFE, W- 60mm L -120mm, thickness 0.1 mm', 'Bard, Goretex'),
(11, '5C', 'Cardiovascular Patch, Dacron', 'Dacron', 'Bard, Goretex'),
(12, '5D', 'Cardiovascular Patch, Pericardial, Bovine', 'Bovine Pericardium, Dimensions: 9cm X 14 cm, thickness 0.20-0.40 mm', 'SJM '),
(13, '5E', 'Cardiovascular Patch, Pericardial, Bovine', 'Bovine pericardium', 'SynkroMax'),
(14, '6', 'Cardiovascular Felt', 'Soft- 6 inch X 6 inch', 'BARD, Goretex'),
(15, '7A', 'Cardiac Tissue Stabliser, Octupus evolution', '', 'Medtronic, Guidant'),
(16, '7B', 'Cardiac tissue stabliser, Starfish', '', 'Medtronic, Guidant'),
(17, '7C', 'Cardiac Tissue Stabliser, Urchin', '', 'Medtronic, Guidant'),
(18, '8', 'Cell Saver Kit', '125 cc, 225 cc', 'Hemonetics'),
(19, '9A', 'Coronary artery ostial cardioplegia cannula, basket type, Basket tip 45 degree angle', ' 10 Fr, 12 Fr, 14 Fr', 'Medtronic, Sarns'),
(20, '9B', 'Coronary artery ostial cardioplegia cannula,  flexible silicon tip', '15 Fr, 17 Fr, 20 Fr', 'Edward, Medtronic, Sarns'),
(21, '9C', 'Coronary artery ostial cardioplegia cannula, for pediatric', '2 mm, 3 mm ', 'Medtronic'),
(22, '10A', 'CPB Custom tubing pack, Adult', '', 'B L Lifescience, life line, Medtronic'),
(23, '10B', 'CPB Custom tubing pack, Pediatric', '', 'B L Lifescience, life line, Medtronic'),
(24, '11A', 'CPB, Peripheral, Femoral Arterial cannula', '12 Fr, 14 fr, 16 fr, 17 fr, 18 fr, 19 Fr, 20 fr, 21 fr, 22 fr', 'Edward Lifesciences, Medtronic, Maquet'),
(25, '11B', 'CPB, Peripheral, Femoral Venousl cannula', '15 fr, 17 fr, 24 fr, 23 fr, 18 fr, 19 fr, 20 fr, 21 fr, 22 fr', 'Edward Lifesciences, Medtronic, Maquet'),
(26, '11C', 'CPB, Peripheral cannula with insertion kit', '', 'Edward Lifesciences, Medtronic, Maquet'),
(27, '12', 'Femoral arterial sheath', '3 Fr, 4 Fr, 5 Fr, 6 Fr', 'Arrow, Johnson & Johnson, Medtronic, Vygon'),
(28, '13A', 'Hemofilter, Adult', '', 'B L lifescience, Nipro, Sorin'),
(29, '13B', 'Hemofilter, Pediatric', '', 'B L lifescience, Nipro, Sorin'),
(30, '13C', 'Hemofilter, Neonate   & infant        ', 'Nenate and infant for 3 to 10 kg', 'B L lifescience, Nipro, Sorin'),
(31, '13D', 'Hemofilter, Neonate         ', 'Neonate for less than 3 kg', 'B L lifescience, Sorin'),
(32, '14', 'Intra Cardiac sump ', '12 Fr, 20 Fr', 'BL Lifescience, Medtronic'),
(33, '15', 'Intra Aortic ballon pump Catheter', '34 cc, 40 cc', 'Arrow, Macquet'),
(34, '16', 'Intra Coronary Shunt', '1mm, 1.25mm, 1.5mm, 1.75mm, 2mm', 'Medtronic, Baxter, Chase'),
(35, '17A', 'Membrane oxygenator, Adult', 'for more than 30 kg', 'Medtronic, Maquet, Nipro, Sorin'),
(36, '17B', 'Membrane oxygenator, Pediatric', 'for 10kg to 30 kg', 'BL lifesceince, Maquet '),
(37, '17C', 'Membrane oxygenator, Neonate and infant', 'for 3 kg to 10 kg', 'Medos ( Infant), Macquet'),
(38, '17D', 'Membrane oxygenator, Neonate', 'for less than 3 kg', 'Medos (Infant), Sorin( Kids 100)'),
(39, '18A', 'Multiple Perfusion Set with vessel cannula', 'with 4 channel', 'Edward lifesceinces, Medtronic'),
(40, '18B', 'Multiple Perfusion Set without vessel cannula', 'with 4 channel', 'Edward lifesceinces, Medtronic'),
(41, '19A', 'Vascular tube graft; ePTFE', '3mm, 3.5 mm, 4 mm, 5 mm', 'BARD, Goretex'),
(42, '19B', 'Vascular tube graft; ePTFE', '12 mm, 13mm, 14 mm, 16 mm', 'BARD, Goretex'),
(43, '19C', 'Vascular tube graft;  Dacron', '8mm,10mm, 12mm, 14mm', 'Maquet'),
(44, '20A', 'Vena cava cannula, single stage, straight', '12Fr, 14Fr, 16Fr, 18Fr', 'Edward lifesciences, Medtronic'),
(45, '20B', 'Vena cava cannula, single stage, Angled', '12Fr, 14Fr, 16Fr, 18Fr', 'Edward lifesciences, Medtronic'),
(46, '20C', 'Vena cava cannula, Dual stage', '34-46, 32-40', 'Edward lifesciences, Medtronic'),
(47, '21', 'Vent catheter', '10 fr, 13 Fr, 16 Fr', 'Edward lifesciences, Medtronic'),
(48, '22', 'Vein Cannula', '', 'B L lifescience, Medtronic, life line');

-- --------------------------------------------------------

--
-- Table structure for table `secc_items`
--

CREATE TABLE `secc_items` (
  `srno` int(11) NOT NULL DEFAULT 0,
  `item_id` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `secc_items`
--

INSERT INTO `secc_items` (`srno`, `item_id`, `name`, `spec`, `brand`) VALUES
(1, '1', 'Asepto pump', 'PVC silicon bulb with syringe', 'Romson'),
(2, '2A', 'Chest drain kit, Bag', '', 'Romson'),
(3, '2B', 'Chest drain Kit , Bottle', '', 'Romson'),
(4, '2C', 'Chest drain kit, dry seal and dry suction', '', 'Sinapi Biomedical'),
(5, '3', 'Chest tube', '16 Fr, 20 Fr, 24 Fr, 28 Fr', 'Romson, Portex'),
(6, '4', 'Crepe Bandage', '4 inch, 6 inch', '3M, Romson, Smith Nephew'),
(7, '5', 'Disposable caps', '', 'Genex, 3M, Rakshak'),
(8, '6', 'Disposable Gloves, Powdered', '6, 6.5, 7, 7.5', 'Gammex'),
(9, '7', 'Disposable Gloves, Powder free', '', 'Ansell Encore'),
(10, '8', 'disposable gloves, latex free, powder free ( PI Hybrid)', '7.0, 7.5', ''),
(11, '9', 'Disposable Mask', '', 'Royal Surgical, 3M, Rakshak '),
(12, '10', 'Disposable Patient electrocautery plate', 'Code 9165', '3M'),
(13, '11', 'Disposable Surgical Gown', '', 'Surgiwear, 3M'),
(14, '12', 'Disposable Suction kit', '', 'Romson, Top'),
(15, '13', 'Disposable Dressing, Tegaderm', '10cmX12cm', '3M'),
(16, '14', 'Dispoasble Dressing, Primapore', 'large, medium and small', 'Smith & Nephew'),
(17, '15A', 'Electro Cautery lead/ pencil ( Teflon Coated E-Z Clean)', '', 'Megadyne E-Z'),
(18, '15B', 'Electro Cautery lead/pencil tip', 'long', 'Megadyne '),
(19, '16', 'Giggli wire', 'braided steel wire for bone cutting', ''),
(20, '17A', 'Hemostatic Clip, Small (code- 001204)', ' Small (code- 001204)', 'Horizon ( Telefex), Ethicon'),
(21, '17B', 'Hemostatic Clip, Medium (code- 002204)', ' Medium (code- 002204)', 'Horizon ( Telefex), Ethicon'),
(22, '17C', 'Hemostatic Clip, Large (code- LT 400)', ' Large (code- LT 400)', 'Horizon ( Telefex), Ethicon'),
(23, '18', 'Hemostatic agent: Absorbable hemostat: SURGICELL', ' Oxidised regenreated cellulose, Size 4inX 8in', 'Ethicon'),
(24, '19', 'Hemostatic agent: Absorbable hemostat; FIBRILLAR', 'Oxidised regenerated cellulose,2 in X 4in, 4in X4in ', 'Ethicon'),
(25, '20', 'Hemostatic agent: Absorbable gelatin; SPONGOSTAN', 'absorbable hemostatic gelatin sponge, 7cm X 5cm X 1cm', 'Ethicon'),
(26, '21', 'Hemostatic agent: Hemostatic matrix; SURGIFLOW ', 'Hemosatatic Gelatin matrix, 8 ml', 'Ethicon'),
(27, '22', 'Hemostatic agent: surgical sealent; COSEAL', 'surgical sealent, 4ml', 'Baxter'),
(28, '23', 'Hemostatic agent:  Fibrin Sealent;TISSEEL ', 'Fibrin sealent, 4 ml', 'Baxter'),
(29, '24', 'hemostatic agent: FLOWSEAL', 'Gelatin and thrombin, 10 ml', 'Baxter'),
(30, '25', 'Hemostatic agent: Surgical adhesive; BIOGLUE', '', 'Cryolife'),
(31, '26', 'IOBAN', 'Small, Medium and large 56 cmX45 cm', '3M'),
(32, '27', 'Latex Examination Gloves', 'Medium size,Box of 100 pieces', 'Nulife, Sara+ care, Fit-n-safe'),
(33, '28', 'Plastic Apron, sterile pack', 'Half size, Full size', 'Surgiwear'),
(34, '29', 'Polydrapes', '120 cmX 210 cm ', 'Surgiwear'),
(35, '30', 'Patient Scrub: Povidone Iodine, Scrub 10 %', '500 ml', 'Johnson & Johnson, Win Medicare, Mirowin'),
(36, '31', 'Patient Scrub: Povidone Iodine, lotion 10 %', '500ml', 'Johnson & Johnson, Win Medicare, Mirowin'),
(37, '32A', 'Rub in hand disinfectant; Propanolol', '2-propanol,1- propanol solution, 500ml', 'Raman & Weil, 3M, Microwin'),
(38, '32B', 'Rub in hand disinfectant; Chlorhexidine solution', 'chlorhexidine 10%, 500ml', '3M, Raman Weil, Microwin'),
(39, '33', 'Pacing leads Temporary', '', 'lifeline'),
(40, '34', 'Romoseal Drain', '12 , 14', 'Romson'),
(41, '35', 'Skin Marker', '', 'surgiwear/ Romson'),
(42, '36', 'Skin Stapler', '', 'ETHICON'),
(43, '37', 'Snugger Set', 'Adult Pediatric', 'Romson'),
(44, '38', 'Specican', '100 ml', ''),
(45, '39', 'Sternal Saw Blade ', 'Stainless steel sternal saw blade', 'Stryker'),
(46, '40', 'Surgical Blade', '10, 11, 15, 22, 23', 'Bard, Surgeon'),
(47, '41', 'Tissue Stapler cartidges', 'ECR 60B, ECR 60D, ECR 60G, ECR 60W', 'ETHICON'),
(48, '42', 'Urometer', '', 'Romsons ( RajVijay)'),
(49, '43', 'Vascular tape', '', 'BL Lifesciences, Scanlon, Aspen Surgical');

-- --------------------------------------------------------

--
-- Table structure for table `secd_items`
--

CREATE TABLE `secd_items` (
  `srno` int(11) NOT NULL,
  `item_id` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `secd_items`
--

INSERT INTO `secd_items` (`srno`, `item_id`, `name`, `spec`, `brand`) VALUES
(1, '1', 'Bone Wax', 'W81001', 'ETHICON'),
(2, '2', 'ETHIBOND 2-O', 'ETHIBOND 2-O half circle, taper cut17/16 mm double needle, PTFE pladget ( 6x3x1.5/7X3x 1.5)75cm ( Aortic) Code: NMW 6555/ 3219-56,', 'ETHICON, TYCO'),
(3, '3', 'ETHIBOND 2-O', 'ETHIBOND 2-O, half circle taper cut 17 mm double needle PTFE pledget ( 3X3X1.5) 75 cm, ( Aortic) Code: MNW6556/ 3218-56', 'ETHICON, TYCO'),
(4, '4', 'ETHIBOD 2-O ', 'ETHIBOND ( green) 2-O, double needle 17 mm with pledget ( Aortic) Code: PX 54 H', 'ETHICON'),
(5, '5', 'ETHIBOND 2-O', 'ETHIBOND (white) 2-O double needle17 mm with pledget ( Aortic) Code: PX17', 'ETHICON'),
(6, '6', 'ETHIBOND 2-O', 'ETHIBOND ( green) 2-O, double needle 17 mm, non pledget ( Aortic) Code: W6937', 'ETHICON'),
(7, '7', 'ETHIBOND 2-O', 'ETHIBOND (white) 2-O, double needle 17 mm, non pledget ( Aortic) Code: W6917', 'ETHICON'),
(8, '8', 'ETHIBOND 2-O', 'ETHIBOND 2-O half circle taper cut 25 mm double needle PTFE pledget ( 3X3X1.5) 75 cm ( Mitral) Code: MNW 6578/ 3218-56', 'ETHICON, TYCO'),
(9, '9', 'ETHIBOND 2-O', 'ETHIBOND 2-O half circle taper cut 25 mm double needle PTFE pledget ( 6X3X1.5) 75 cm ( Mitral) Code: MNW 6577/ 3324-56', 'ETHICON, TYCO'),
(10, '10', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( Green) half circle taper cut 26 mm double needle PTFE pledget (Mitral) Code: PX 62H ', 'ETHICON'),
(11, '11', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( white) half circle taper cut 26 mm double needle PTFE pledget (Mitral) Code: PX 64H ', 'ETHICON'),
(12, '12', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( Green) half circle taper cut double needle 25 mm non pledget ( Mitral ) code: W6977', 'ETHICON'),
(13, '13', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( White) half circle taper cut double needle 25 mm non pledget ( Mitral ) code: W6987', 'ETHICON'),
(14, '14', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( Green) half circle round body single needle 26 mm, 75 cm (Mitral) Code: X833H', 'ETHICON'),
(15, '15', 'ETHIBOND 3-O', 'ETHIBOND 3-O 1/2 Circle round body, double needle, 100cm. Code: W 6552', 'ETHICON'),
(16, '16', 'ETHIBOND 3-O ', 'ETHIBOND 3-O 1/2 Circle round body Single SH needle, 75cm. Code: X 832 H ', 'ETHICON'),
(17, '17', 'ETHIBOND no-2 ', 'ETHIBOND no-2, taper cut 1/2 circle (heavy). Code: W 4843', 'ETHICON'),
(18, '18', 'ETHIBOND no-5 ', 'ETHIBOND no-5, taper cut 1/2 circle (heavy). Code: W 4846', 'ETHICON'),
(19, '19', 'ETHISTEEL no-5 ', 'ETHISTEEL no-5, 1/2 circle CCS conventional cutting. Code: M 653', 'ETHICON'),
(20, '20', 'ETHILON 2-O', 'ETHILON 2-O Copde: NW 3336', 'ETHICON'),
(21, '21', 'ETHILON 3-O', 'Ethilon 3-O, Code: NW 3328', 'ETHICON'),
(22, '22', 'Mersilene Tape ', 'Mersilene Tape 1/2 circle round body blunt (heavy) double needle, 65 mm. Code: RS21', 'ETHICON'),
(23, '23', 'MONOCRYL 3-O ', 'Monocryl 3-O, Code:  NW 1326', 'ETHICON'),
(24, '24A', 'Pacing wire', 'FEP 15E/TPW30', 'ETHICON, TYCO'),
(25, '24B', 'Pacing wire', 'FEP 13E/ TPW10', 'ETHICON, TYCO'),
(26, '25', 'PROLENE 1-O ', 'PROLENE 1-O 1/2 circle round body, Code: NW 846 ', 'ETHICON'),
(27, '26', 'PROLENE 2-O ', 'PROLENE 2-O 1/2 circle round body, Code: NW 844', 'ETHICON'),
(28, '27', 'PROLENE 3-O', 'PROLENE 3-O half circle taper point double needle 26 mm 90 cm. Code: 8522H', 'ETHICON'),
(29, '28', 'PROLENE 4-O', 'PROLENE 4-O half circle taper point double needle 26 mm 90 cm. Code: 8521H', 'ETHICON'),
(30, '29', 'PROLENE 4-O  ', 'PROLENE 4-O 1/2 Circle Round body,12.5 mm  TF double needle, 60cms  Code: 8204 H', 'ETHICON'),
(31, '30', 'PROLENE 4-O ', 'PROLENE 4-O 1/2 circle round body, 17mm double needle, 90cm. Code: W 8557', 'ETHICON'),
(32, '31', 'PROLENE 5-O ', ' PROLENE 5-O 1/2 Circle Round body-2, 13 mm double needle, 75cms. code: W 8710', 'ETHICON'),
(33, '32', 'PROLENE 5-O', 'PROLENE 5-O 1/2 Circle Round body  double needle, 60 cms. Code:  TF8205 H ', 'ETHICON'),
(34, '33', 'PROLENE 6-O ', 'PROLENE 6-O 3/8 Circle Round body double needle, 9.3mm 60cms. Code: W 8712', 'ETHICON '),
(35, '34', 'PROLENE 6-O ', 'PROLENE 6-O Circle Round body  double needle,10mm/13mm, 60 cms. Code: W 8597/ VP 706X', 'ETHICON, TYCO'),
(36, '35', 'PROLENE 7-O', 'PROLENE 7-O 3/8 circle curved round body taper point double needle BV 175-6, 7.6 mm/ 8 mm, 60 cm Code: 8735H/ VP 630X', 'ETHICON, TYCO'),
(37, '36', 'PROLENE 7-O ', 'PROLENE 7-O 3/8 Circle  taper point double needle BV 175-6, 8mm, 60cms. Code: EP 8735H ', 'ETHICON'),
(38, '37', 'PROLINE 8-O', 'PROLENE 8-O 3/8 circle taper point double needle BV 175-6, 8 mm, 60 cm. Code: 8741H', 'ETHICON'),
(39, '38', 'SILK 1-O ', 'MERSILK 1-O Round Body, Code: NW 5332', 'ETHICON'),
(40, '39', 'SILK 1-O reverse cutting', 'MERSILK 1-O reverse cutting, Code: NW 5037', 'ETHICON'),
(41, '40', 'SILK 2-O  ', 'MERSILK 2-O Round Body, Code:  NW 5331', 'ETHICON'),
(42, '41', 'SILK 2-O reverse cutting ', 'MERSILK 2-O reverse cutting, Code: NW 5036', 'ETHICON'),
(43, '42', 'SILK 3-O  ', 'MERSILK 3-O round body 25 mm needle Code; NW 5087', 'ETHICON'),
(44, '43', 'SILK 3-O  ', 'MERSILK 3-O round body 20 mm needle Code:  NW 5085', ''),
(45, '44', 'SILK 3-O reverse cutting', 'MERSILK 3-O reverse cutting, Code: NW 5028', ''),
(46, '45', 'SILK SUTUPACK 3-O', 'SILK SUTUPACK 3-O code: SW 222', 'ETHICON'),
(47, '46', 'SILK REEL NO. 1', 'R825', 'ETHICON'),
(48, '47', 'SILK REEL NO. 2', 'R826', 'ETHICON'),
(49, '48', 'SILK REEL NO. 1-O', 'R824', 'ETHICON'),
(50, '49', 'Umblical tape', 'W2760', 'ETHICON'),
(51, '50', 'VICRYL 1-O  ', 'VICRYL 1-O round body, Code: NW 2546/2346', 'ETHICON'),
(52, '51', 'VICRYL 2-O  ', 'NW 2341/2317', 'ETHICON'),
(53, '52', 'VICRYL 3-O ', 'VICRYL 3-O round body, Code: NW 2437', 'ETHICON'),
(54, '53', 'VICRYL rapide 3-O', 'VICRYL rapide 3-o cutting undyed, Code:  W9932', 'ETHICON');

-- --------------------------------------------------------

--
-- Table structure for table `sece_items`
--

CREATE TABLE `sece_items` (
  `srno` int(11) NOT NULL,
  `item_id` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sece_items`
--

INSERT INTO `sece_items` (`srno`, `item_id`, `name`, `spec`, `brand`) VALUES
(1, '1', 'Inj. Amino Acid sol.', '', 'Claris, Fresnius Kabi'),
(2, '2', 'Inj. Dextrose ', '5 %, 10%, 25%', 'Baxter'),
(3, '3', 'Inj. Plasma Lyte A', '', ''),
(4, '4', 'Inj. Normal saline', 'Plastic bottle, 500 ml', ''),
(5, '5', 'Inj Normal saline', 'Bag- 500 ml', 'Baxter'),
(6, '6', 'Inj Ringer Lactate', 'Plastic bottle- 500 ml', 'Baxter, Clairs, SKKL'),
(7, '7', 'Inj Ringer Lactate', 'Bag- 500 ml', ''),
(8, '8', 'Adenosine Inj', '6mg, 12mg', 'Neon, Toirrent, Sun'),
(9, '9', 'Albumin Inj 20%', '100 ml ', 'Bharat Serum, Reliance'),
(10, '10', 'Amikacin Inj', '100mg, 500mg, 1g ', 'Bristol-myers, Cipla'),
(11, '11', 'Aminocaproic Acid Inj (Hemostat)', '250mg/ml(20ml)', 'GSK, Samarth Pharma'),
(12, '12', 'Amiodarone Inj', '50mg/ml-3ml', 'Cipla, Sanofi, Neon'),
(13, '13', 'Atracurium Inj ', '10mg/ml', 'Sanartg, Neon'),
(14, '14', 'Bupivacaine/Rupivac  Inj', '0.25% Plain 4ml, Heavy 0.5% 20ml', 'Astrazeneca, Sun, Neon'),
(15, '15', 'Cefazolin', '250mg, 500mg', 'Cadilla, Lupin, Ranbaxy'),
(16, '16', 'Cefuroxime Inj', '250mg, 500mg, 750mg, 1.5g', 'Glenmark, Glaxo, Aristo, Supacef'),
(17, '17', 'Cefipime Inj', '500mg, 1mg', 'Bristol-myers, Cipla, Cadilla'),
(18, '18', 'Ceftizoxime Inj', '500mg, 1mg', 'Elder, GSK'),
(19, '19', 'Diltiazem Inj', '25mg/5ml', 'Torrent'),
(20, '20', 'Dexmed Inj', '200mg/2ml', 'Neon'),
(21, '21', 'Dobutamine Inj', '250mg/5ml', 'Torrent, Sun, Samarth, Neon'),
(22, '22', 'Duolin Puff', '', 'Cipla'),
(23, '23', 'Esmolol Inj', '10mg/ml', 'Neon, USV'),
(24, '24', 'Glycopyrrolate Inj', '0.2mg/ml', 'Neon, Piramal'),
(25, '25', 'Heparin Inj', '5000 U, 25000 U ', 'Gland, Neon, VHB, Abbot'),
(26, '26', 'Insulin Inj', '', ''),
(27, '27', 'Ketamine Inj', '10 mg, 50 mg', 'Neon'),
(28, '28', 'Labetolol Inj', '5mg/2ml', 'Mercury Lab, Samarth'),
(29, '29', 'Levosimendan', '10 mg/ml', ''),
(30, '30', 'Lignocain Spray ', '10 %', 'Neon'),
(31, '31', 'Majnesium Sulphate Inj', '500mg/ml', 'Neon'),
(32, '32', 'Mannitol Inj', '20%-100ml', 'Cadilla, Venus'),
(33, '33', 'Metaprolol Inj', '1mg/ml', 'Astrazeneca, Cipla, Dr. Reddy'),
(34, '34', 'Metoclopramide Inj', '1mg/ml', 'Astrazeneca, IPCA '),
(35, '35', 'Midazolam Inj Ampule', '5mg/ml', 'Ranbaxy, Neon'),
(36, '36', 'Milrinone Inj', '1mg/ml', 'Neon, Cleon, Samarth, VHB'),
(37, '37', 'Neostigmine+ Glycopyrrolate Inj', '0.5mg+2.5mg', 'Neon'),
(38, '38', 'Nitroglycerine Inj (NTG)', '5mg/ml', 'Neon, Samarth'),
(39, '39', 'Pancuronium Bromide Inj.', '2mg/ml', 'Neon, Piramal'),
(40, '40', 'Paracetamol Inj', '150mg/ml', 'Lupin, Neon'),
(41, '41', 'Phenylephrine Inj', '10mg/ml', 'Neon '),
(42, '42', 'Priperacillin Tazobactum Inj', '4.5mg', 'Cipla, Lupin, VHB'),
(43, '43', 'Plegiocard Inj', '20ml ', 'Samarth, Neon'),
(44, '44', 'Potassium Chloride Inj', '20mg/10ml', 'Baxter, Neon'),
(45, '45', 'Propofol Inj', '10mg/ml', 'Fresnius Kabi, Neon'),
(46, '46', 'Protamine Inj', '10mg/ml', 'Samath'),
(47, '47', 'Ranitidine Inj', '25mg/ml', 'Cadilla, GSK'),
(48, '48', 'Soda-Bicarbonate Inj', '4%', '-'),
(49, '49', 'Sodium Nitroprusside Inj', '50mg/vial', 'Neon, Samarth'),
(50, '50', 'Teicoplanin Inj', '200mg, 400mg', 'Aventis, Cipla, Glenmark, Neon'),
(51, '51', 'Thyopentone inj', '0.5gm, 1gm', 'Abott, Biocare, Neon'),
(52, '52', 'Tranexemic Acid Inj', '100mg/ml', 'Cadilla, Macleod, Samarth'),
(53, '53', 'Vacomycin Inj', '500mg, 1g', 'AstraZeneca, Alkem, Elilily, VHB'),
(54, '54', 'Vecuronium Inj', '4mg, 10mg', 'Organon, Sun, Neon'),
(55, '55', 'Xylocard Inj/ Lignocain Inj', '2%, 50ml/30ml', 'Astrazeneca, Neon'),
(56, '56', 'Vasopresin Inj', '20 units/ ml ', 'Samarth, Neon');

-- --------------------------------------------------------

--
-- Table structure for table `tablea`
--

CREATE TABLE `tablea` (
  `sr.no` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tablea`
--

INSERT INTO `tablea` (`sr.no`, `name`, `spec`, `brand`) VALUES
('1', 'ACT tubes', 'Compatible to our machine', 'Helena Lab, Beaumount Texas'),
('10A', 'Disposable Ventilator tubing, Adult', 'with water trap, without water trap', 'Portex, Intersurgical, Life line'),
('10B', 'Disposable Ventilator tubing, Pediatric', '', 'Portex, Intersurgical, Life line'),
('11A', 'Disposable Nasal Prong, Adult', '', 'Romson, Medisafe'),
('11B', 'Disposable nasal prong, Pediatric', '', 'Romson, Medisafe'),
('12', 'Disposable IV Canula Fixation Dressing', '', 'Primapore'),
('13', 'Durapore', '4 inch', '3M'),
('14A', 'ECG elctrodes, Adult', '', '3M, ARBO, Niko, Kenny, Swaromed'),
('14B', 'ECG elctrodes, Pediatric', '', '3M, ARBO, Niko, Kenny, Swaromed'),
('15', 'Elastoplast ( Dynaplast)', '4 inch', '3M, Johnson & johnson'),
('16A', 'Endotracheal tube with cuffed', '4.5, 5, 5.5, 6, 6.5, 7, 7.5, 8, 8.5 mm', 'Portex, Rusch, Vygon'),
('16B', 'Endotracheal tube with Uncuffed', '3, 3.5, 4, 4.5, 5, 5.5, 6, 6.5 mm', 'Portex, Rusch, Vygon'),
('16C', 'Endotracheal tube, Double lumen tube ( DLT)', '26, 28, 32, 37, 39 Fr, LEFT or RIGHT', 'Portex'),
('17', 'Epidural set ( MINI PACK)', '', 'Portex, Vygon'),
('18', 'Heat and Moisture Exchanger (HME)', 'Adult Pediatric', 'Portex, Intersurgical, Life line'),
('19', 'High Pressure Bag', ' for rapid infusion', 'Ethox, SunMed'),
('20', 'High Pressure tubing, Male to Male', '150cm, 200cm', 'Romson, Vygon'),
('21', 'High Pressure tubing, Male to Female', '150cm, 200cm', 'Vygon, Romson'),
('22', 'Hypodermic needle', '26 G, 0.5 inch, 26 G 1.5 inch', 'BD,'),
('23', 'IV infusion set, with leur lock', '', 'Romson, BD, Braun'),
('24', 'Infant feeding tube', '5 fr, 6 Fr, 7 Fr, 8 Fr, 9 Fr, 10 Fr', 'Romson'),
('25', 'IV infusion set, without leur lock', '', 'Romson, BD, Braun'),
('26', 'J R circuit ( Jackson Rees) Pediatric', '', 'Medisafe, Lifeline'),
('27', 'Microdrip set', '', 'Baxter, B. Braun'),
('28', 'Multifunctional Electrode Pads/ AED pads', '', 'Philips'),
('29', 'Multilumen extension', 'to be connected peripheral IV cannula', 'Vygon, BD'),
('2A', 'Arterial line cannula (Adult)', '18 G  20 G', 'Becton Dickinson(BD)\nVygon'),
('2B', 'Arterial line cannula ( Pediatric)', '20 G  22 G, 80mm length', 'Vygon'),
('30', 'NIRS Sensor', 'Neonatal, Pediatric, Adult', 'Covidein'),
('31', 'NIV mask', 'Small, Medium, Large', 'Pneumocare, Resmed'),
('32', 'NOX BOX circuit', '', 'Bedfont (Scientific Ltd)'),
('33', 'Pediatric drip set ( Burrete set) with leur lock', '', 'Baxter, B. Braun, Romson'),
('35', 'Pediatric drip set ( Burrete set) without leur lock', '', 'Baxter, B. Braun, Romson'),
('36', 'Peripheral Venous cannula', '14G, 16G, 18G, 20G, 22G, 24G, 26G', 'Becton Dickinson(BD)'),
('37A', 'Pressure Monitoring kit with Dome, Double', 'Compatible with our machine', 'Edward life sciences'),
('37B', 'Pressure Monitoring kit with Dome, Single', 'Compatible with our machine', 'Edward life sciences'),
('38', 'RAMS Cannula', 'Neonate, Pediatric, Adult', 'Romson, Polymed'),
('39', 'Ryles tube', '10Fr, 12 Fr, 14 Fr', 'Romson'),
('3A', 'Blood glucose strip', 'Compatible to our machine', 'Arkay factory, Nipro, Optium'),
('3B', 'Blood transfusion Set ', 'with leur lock', 'BD, Romson'),
('4', 'BIS Sensor', 'Adult  Pediatric', 'Medtronic'),
('40', 'SIPAP circuit', 'Neonate', 'sipap'),
('41', 'Sticking plaster ( Leukoplast)', '4 inch', '3M, Johnson & Johnson'),
('42', 'Suction Catheter with eye', '6 Fr, 8 Fr, 10 fr, 12 Fr, 14Fr ,16 Fr', 'Romson, Portex, Top'),
('43', 'Suction Catheter without eye', '6 Fr, 8 Fr, 10 fr, 12 Fr, 14Fr ,16 Fr', 'Romson, Portex, Top'),
('44', 'Three way with extension', '10 cm, 100 cm,', 'Becton Dickinson ( BD), Vygon, Top'),
('45', 'Three way without extension', '', 'Becton Dickinson ( BD), Vygon'),
('5', 'Bronchial blocker', '5F, 7F', 'Portex, Rusch'),
('6', 'Camera cover', '', 'Surgiwear, Teleflex, Niko'),
('7', 'Central Venous line', 'Triple lumen, Four Lumen', 'Arrow, Edward Lifesciences, Vygon, 3M'),
('8', 'Dial flow', '', 'Leventon, Romson'),
('9', 'Disposable Syringe with leur lock', '2 ml, 5 ml, 10 ml, 20 ml, 50 ml', 'Dispovan, BD'),
('9B', 'Disposable Syringe without leur lock', '1 ml, 2ml, 5ml, 20ml, 50 ml', 'Dispovan, BD');

-- --------------------------------------------------------

--
-- Table structure for table `tableb`
--

CREATE TABLE `tableb` (
  `sr.no` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tableb`
--

INSERT INTO `tableb` (`sr.no`, `name`, `spec`, `brand`) VALUES
('1', 'Antegrade cardioplegia Cannula, with vent', '14 G ( Code: 20014), 16G ( Code: 20016)', 'Medtronic, Edward, Sarns'),
('10A', 'CPB Custom tubing pack, Adult', '', 'B L Lifescience, life line,Medtronic'),
('10B', 'CPB Custom tubing pack, Pediatric', '', 'B L Lifescience, life line, Medtronic'),
('11A', 'CPB, Peripheral, Femoral Arterial cannula', '12 Fr, 14 fr, 16 fr, 17 fr, 18 fr, 19 Fr, 20 fr, 21 fr, 22 fr', 'Edward Lifesciences, Medtronic, Maquet'),
('11B', 'CPB, Peripheral, Femoral Venousl cannula', '15 fr, 17 fr, 18 fr, 19 fr, 20 fr, 21 fr, 22 fr, 23 fr, 24 fr, 25 fr, 26 fr, 27 fr, 28 fr', 'Edward Lifesciences, Medtronic, Maquet'),
('11C', 'CPB, Peripheral cannula with insertion kit', '', 'Edward Lifesciences, Medtronic, Maquet'),
('12', 'Femoral arterial sheath', '3 Fr, 4 Fr, 5 Fr, 6 Fr', 'Arrow, Johnson & Johnson, Medtronic, Vygon'),
('13A', 'Hemofilter, Adult', '', 'B L lifescience, Nipro, Sorin'),
('13B', 'Hemofilter, Pediatric', '', 'B L lifescience, Nipro, Sorin'),
('13C', 'Hemofilter, Neonate   & infant        ', 'Nenate and infant for 3 to 10 kg', 'B L lifescience, Nipro, Sorin'),
('13D', 'Hemofilter, Neonate         ', 'Neonate for less than 3 kg', 'B L lifescience, Sorin'),
('14', 'Intra Cardiac sump ', '12 Fr, 20 Fr', 'Medtronic, BL Lifescience'),
('15', 'Intra Aortic ballon pump Catheter', 'Ballon size 34 cc, 40 cc', 'Arrow, Macquet'),
('16', 'Intra Coronary Shunt', '1mm,1.25mm, 1.5mm, 1.75mm, 2mm', 'Medtronic, Baxter, Chase'),
('17A', 'Membrane oxygenator, Adult', 'for more than 30 kg', 'Medtronic, Maquet, Nipro, Sorin'),
('17B', 'Membrane oxygenator, Pediatric', 'for 10kg to 30 kg', 'Maquet, BL lifesceince, '),
('17C', 'Membrane oxygenator, Neonate and infant', 'for 3 kg to 10 kg', 'Medos ( Infant), Macquet'),
('17D', 'Membrane oxygenator, Neonate', 'for less than 3 kg', 'Medos (Infant), Sorin( Kids 100)'),
('18A', 'Multiple Perfusion Set with vessel cannula', 'with 4 channel', 'Edward lifesceinces, Medtronic'),
('18B', 'Multiple Perfusion Set without vessel cannula', 'with 4 channel', 'Edward lifesceinces, Medtronic'),
('19A', 'Vascular tube graft; ePTFE', 'PTFE 3mm, 3.5 mm, 4 mm, 5 mm', 'BARD, Goretex'),
('19B', 'Vascular tube graft; ePTFE', 'PTFE 12 mm, 13mm, 14 mm, 16 mm, 18 mm, 19 mm', 'BARD, goretex'),
('19C', 'Vascular tube graft;  Dacron', '8mm,10mm, 12mm, 14mm, 16mm, 18mm, 20mm,22mm, 24mm, 26mm, 28mm, 30mm,32mm', 'Maquet'),
('20A', 'Vena cava cannula, single stage, straight', 'single stage, straight, 12/14/16/18/20/22/24/26/28/30/32 Fr', 'Edward lifesciences, Medtronic'),
('20B', 'Vena cava cannula, single stage, Angled', ' single stage, Angled12/14/16/18/20/22 Fr', 'Edward lifesciences, Medtronic'),
('20C', 'Vena cava cannula, Dual stage', ' Dual stage, 34-46, 32-40', 'Edward lifesciences, Medtronic'),
('21', 'Vent catheter', '10 fr, 13 Fr, 16 Fr', 'Edward lifesciences, Medtronic'),
('22', 'Vein Cannula', '', 'B L lifescience, Medtronic, life line'),
('2A', 'Aortic perfusion cannula, wire reinforced, Angled', '20, 22 Fr', 'Medtronic, Edward, Sarns'),
('2B', 'Aortic perfusion cannula, wire reinforced, straight', '6 Fr, 8 Fr, 10 Fr, 12 Fr, 14 Fr, 16 fr, 18 FR, 20 Fr', 'Medtronic ( BioMedicus), Edward, Sarns'),
('3A', 'Autovent cum Filter, Adult', 'for Adult CPB circuit', ' B L Lifesciences, Dideco, Life line'),
('3B', 'Autovent cum Filter, Pediatric', 'for pediatric CPB', ' B L Lifesciences, Dideco, Life line'),
('3C', 'Autovent cum filter, Neonate and infant', 'Neonate or infant for 3 to 10 kg ', 'B L Lifesciences, Dideco, life line,Medtronic'),
('3D', 'Autovent cum Filter, Neonate', 'Neonate baby with  less than 3 kg ', 'Pall'),
('4', 'Blower/ Mister', '', 'Medtronic, Guidant'),
('5A', 'Cardiovascular Patch, ePTFE', 'PTFE, W 50mm L 75mm, thickness 0.6 mm', 'Bard, Goretex'),
('5B', 'Cardiovascualr Patch, e PTFE membrane', 'PTFE, W- 60mm L -120mm, thickness 0.1 mm', 'Bard, Goretex'),
('5C', 'Cardiovascular Patch, Dacron', 'Dacron', 'Bard, Goretex'),
('5D', 'Cardiovascular Patch, Pericardial, Bovine', 'Bovine Pericardium, Dimensions: 9cm X 14 cm, thickness 0.20-0.40 mm', 'SJM '),
('5E', 'Cardiovascular Patch, Pericardial, Bovine', 'Bovine pericardium', 'SynkroMax'),
('6', 'Cardiovascular Felt', 'Soft- 6 inch X 6 inch', 'BARD, Goretex'),
('7A', 'Cardiac Tissue Stabliser, Octupus evolution', '', 'Medtronic, Guidant'),
('7B', 'Cardiac tissue stabliser, Starfish', '', 'Medtronic, Guidant'),
('7C', 'Cardiac Tissue Stabliser, Urchin', '', 'Medtronic, Guidant'),
('8', 'Cell Saver Kit', '125 cc, 225 cc', 'Hemonetics'),
('9A', 'Coronary artery ostial cardioplegia cannula, basket type', 'Basket tip 45 degree angle, 10 Fr, 12 Fr, 14 Fr', 'Medtronic, Sarns'),
('9B', 'Coronary artery ostial cardioplegia cannula,  flexible silicon tip', '15 Fr, 17 Fr, 20 Fr', 'Edward, Medtronic, Sarns'),
('9C', 'Coronary artery ostial cardioplegia cannula, for pediatric', ' Atriotomy cannula, 2 mm, 3 mm ', 'Medtronic');

-- --------------------------------------------------------

--
-- Table structure for table `tablec`
--

CREATE TABLE `tablec` (
  `sr.no` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tablec`
--

INSERT INTO `tablec` (`sr.no`, `name`, `spec`, `brand`) VALUES
('1', 'Asepto pump', 'PVC silicon bulb with syringe', 'Romson'),
('10', 'Disposable Patient electrocautery plate', 'Code 9165', '3M'),
('11', 'Disposable Surgical Gown', '', '3M, Surgiwear'),
('12', 'Disposable Suction kit', '', 'Romson, Top'),
('13', 'Disposable Dressing, Tegaderm', '10cmX12cm', '3M'),
('14', 'Dispoasble Dressing, Primapore', 'large, medium and small', 'Smith & Nephew'),
('15A', 'Electro Cautery lead/ pencil ( Teflon Coated E-Z Clean)', '', 'Megadyne E-Z'),
('15B', 'Electro Cautery lead/pencil tip', 'long', 'Megadyne '),
('16', 'Giggli wire', 'braided steel wire for bone cutting', ''),
('17A', 'Hemostatic Clip, Small (code- 001204)', ' Small (code- 001204)', 'Horizon ( Telefex), Ethicon'),
('17B', 'Hemostatic Clip, Medium (code- 002204)', ' Medium (code- 002204)', 'Horizon ( Telefex), Ethicon'),
('17C', 'Hemostatic Clip, Large (code- LT 400)', ' Large (code- LT 400)', 'Horizon ( Telefex), Ethicon'),
('18', 'Hemostatic agent: Absorbable hemostat: SURGICELL', ' Oxidised regenreated cellulose, Size 4inX 8in', 'Ethicon'),
('19', 'Hemostatic agent: Absorbable hemostat; FIBRILLAR', 'Oxidised regenerated cellulose,2 in X 4in, 4in X4in ', 'Ethicon'),
('20', 'Hemostatic agent: Absorbable gelatin; SPONGOSTAN', 'absorbable hemostatic gelatin sponge, 7cm X 5cm X 1cm', 'Ethicon'),
('21', 'Hemostatic agent: Hemostatic matrix; SURGIFLOW ', 'Hemosatatic Gelatin matrix, 8 ml', 'Ethicon'),
('22', 'Hemostatic agent: surgical sealent; COSEAL', 'surgical sealent, 4ml', 'Baxter'),
('23', 'Hemostatic agent:  Fibrin Sealent;TISSEEL ', 'Fibrin sealent, 4 ml', 'Baxter'),
('24', 'hemostatic agent: FLOWSEAL', 'Gelatin and thrombin, 10 ml', 'Baxter'),
('25', 'Hemostatic agent: Surgical adhesive; BIOGLUE', '', 'Cryolife'),
('26', 'IOBAN', 'Small, Medium and large 56 cmX45 cm', '3M'),
('27', 'Latex Examination Gloves', 'Medium size,Box of 100 pieces', 'Nulife, Sara+ care, Fit-n-safe'),
('28', 'Plastic Apron, sterile pack', 'Half size, Full size', 'Surgiwear'),
('29', 'Polydrapes', '120 cmX 210 cm ', 'Surgiwear'),
('2A', 'Chest drain kit, Bag', '', 'Romson'),
('2B', 'Chest drain Kit , Bottle', '', 'Romson'),
('2C', 'Chest drain kit, dry seal and dry suction', '', 'Sinapi Biomedical'),
('3', 'Chest tube', 'staight or Angled 16 Fr, 20 Fr, 24 Fr, 28 Fr, 32 Fr', 'Romson, Portex'),
('30', 'Patient Scrub: Povidone Iodine, Scrub 10 %', '500 ml', 'Johnson & Johnson, Win Medicare, Mirowin'),
('31', 'Patient Scrub: Povidone Iodine, lotion 10 %', '500ml', 'Johnson & Johnson, Win Medicare, Mirowin'),
('32A', 'Rub in hand disinfectant; Propanolol', '2-propanol,1- propanol solution, 500ml', 'Raman & Weil, 3M, Microwin'),
('32B', 'Rub in hand disinfectant; Chlorhexidine solution', 'chlorhexidine 10%, 500ml', '3M, Raman Weil, Microwin'),
('33', 'Pacing leads Temporary', '', 'lifeline'),
('34', 'Romoseal Drain', '12 , 14', 'Romson'),
('35', 'Skin Marker', '', 'surgiwear/ Romson'),
('36', 'Skin Stapler', '', 'ETHICON'),
('37', 'Snugger Set', 'Adult Pediatric', 'Romson'),
('38', 'Specican', '100 ml', ''),
('39', 'Sternal Saw Blade ', 'Stainless steel sternal saw blade', 'Stryker'),
('4', 'Crepe Bandage', '4 inch 6 inch', '3M, Romson, Smith Nephew'),
('40', 'Surgical Blade', '10, 11, 15, 22, 23', 'Bard, Surgeon'),
('41', 'Tissue Stapler cartidges', 'ECR 60B, ECR 60D, ECR 60G, ECR 60W', 'ETHICON'),
('42', 'Urometer', '', 'Romsons ( RajVijay)'),
('43', 'Vascular tape', '', 'Scanlon, BL Lifesciences, Aspen Surgical'),
('5', 'Disposable caps', '', '3M, Genex, Rakshak'),
('6', 'Disposable Gloves, Powdered', '6, 6.5, 7, 7.5', 'Gammex'),
('7', 'Disposable Gloves, Powder free', '', 'Ansell Encore'),
('8', 'disposable gloves, latex free, powder free ( PI Hybrid)', '7.0 7.5', ''),
('9', 'Disposable Mask', '', '3M, Rakshak, Royal Surgical');

-- --------------------------------------------------------

--
-- Table structure for table `tabled`
--

CREATE TABLE `tabled` (
  `sr.no` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tabled`
--

INSERT INTO `tabled` (`sr.no`, `name`, `spec`, `brand`) VALUES
('1', 'Bone Wax', 'W81001', 'ETHICON'),
('10', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( Green) half circle taper cut 26 mm double needle PTFE pledget (Mitral) Code: PX 62H ', 'ETHICON'),
('11', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( white) half circle taper cut 26 mm double needle PTFE pledget (Mitral) Code: PX 64H ', 'ETHICON'),
('12', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( Green) half circle taper cut double needle 25 mm non pledget ( Mitral ) code: W6977', 'ETHICON'),
('13', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( White) half circle taper cut double needle 25 mm non pledget ( Mitral ) code: W6987', 'ETHICON'),
('14', 'ETHIBOND 2-O', 'ETHIBOND 2-O ( Green) half circle round body single needle 26 mm, 75 cm (Mitral) Code: X833H', 'ETHICON'),
('15', 'ETHIBOND 3-O', 'ETHIBOND 3-O 1/2 Circle round body, double needle, 100cm. Code: W 6552', 'ETHICON'),
('16', 'ETHIBOND 3-O ', 'ETHIBOND 3-O 1/2 Circle round body Single SH needle, 75cm. Code: X 832 H ', 'ETHICON'),
('17', 'ETHIBOND no-2 ', 'ETHIBOND no-2, taper cut 1/2 circle (heavy). Code: W 4843', 'ETHICON'),
('18', 'ETHIBOND no-5 ', 'ETHIBOND no-5, taper cut 1/2 circle (heavy). Code: W 4846', 'ETHICON'),
('19', 'ETHISTEEL no-5 ', 'ETHISTEEL no-5, 1/2 circle CCS conventional cutting. Code: M 653', 'ETHICON'),
('2', 'ETHIBOND 2-O', 'ETHIBOND 2-O half circle, taper cut17/16 mm double needle, PTFE pladget ( 6x3x1.5/7X3x 1.5)75cm ( Aortic) Code: NMW 6555/ 3219-56,', 'ETHICON/ TYCO'),
('20', 'ETHILON 2-O', 'ETHILON 2-O Copde: NW 3336', 'ETHICON'),
('21', 'ETHILON 3-O', 'Ethilon 3-O, Code: NW 3328', 'ETHICON'),
('22', 'Mersilene Tape ', 'Mersilene Tape 1/2 circle round body blunt (heavy) double needle, 65 mm. Code: RS21', 'ETHICON'),
('23', 'MONOCRYL 3-O ', 'Monocryl 3-O, Code:  NW 1326', 'ETHICON'),
('24A', 'Pacing wire', 'FEP 15E/TPW30', 'ETHICON/ TYCO'),
('24B', 'Pacing wire', 'FEP 13E/ TPW10', 'ETHICON/ TYCO'),
('25', 'PROLENE 1-O ', 'PROLENE 1-O 1/2 circle round body, Code: NW 846 ', 'ETHICON'),
('26', 'PROLENE 2-O ', 'PROLENE 2-O 1/2 circle round body, Code: NW 844', 'ETHICON'),
('27', 'PROLENE 3-O', 'PROLENE 3-O half circle taper point double needle 26 mm 90 cm. Code: 8522H', 'ETHICON'),
('28', 'PROLENE 4-O', 'PROLENE 4-O half circle taper point double needle 26 mm 90 cm. Code: 8521H', 'ETHICON'),
('29', 'PROLENE 4-O  ', 'PROLENE 4-O 1/2 Circle Round body,12.5 mm  TF double needle, 60cms  Code: 8204 H', 'ETHICON'),
('3', 'ETHIBOND 2-O', 'ETHIBOND 2-O, half circle taper cut 17 mm double needle PTFE pledget ( 3X3X1.5) 75 cm, ( Aortic) Code: MNW6556/ 3218-56', 'ETHICON/TYCO'),
('30', 'PROLENE 4-O ', 'PROLENE 4-O 1/2 circle round body, 17mm double needle, 90cm. Code: W 8557', 'ETHICON'),
('31', 'PROLENE 5-O ', ' PROLENE 5-O 1/2 Circle Round body-2, 13 mm double needle, 75cms. code: W 8710', 'ETHICON'),
('32', 'PROLENE 5-O', 'PROLENE 5-O 1/2 Circle Round body  double needle, 60 cms. Code:  TF8205 H ', 'ETHICON'),
('33', 'PROLENE 6-O ', 'PROLENE 6-O 3/8 Circle Round body double needle, 9.3mm 60cms. Code: W 8712', 'ETHICON '),
('34', 'PROLENE 6-O ', 'PROLENE 6-O Circle Round body  double needle,10mm/13mm, 60 cms. Code: W 8597/ VP 706X', 'ETHICON/ TYCO'),
('35', 'PROLENE 7-O', 'PROLENE 7-O 3/8 circle curved round body taper point double needle BV 175-6, 7.6 mm/ 8 mm, 60 cm Code: 8735H/ VP 630X', 'ETHICON/TYCO'),
('36', 'PROLENE 7-O ', 'PROLENE 7-O 3/8 Circle  taper point double needle BV 175-6, 8mm, 60cms. Code: EP 8735H ', 'ETHICON'),
('37', 'PROLINE 8-O', 'PROLENE 8-O 3/8 circle taper point double needle BV 175-6, 8 mm, 60 cm. Code: 8741H', 'ETHICON'),
('38', 'SILK 1-O ', 'MERSILK 1-O Round Body, Code: NW 5332', 'ETHICON'),
('39', 'SILK 1-O reverse cutting', 'MERSILK 1-O reverse cutting, Code: NW 5037', 'ETHICON'),
('4', 'ETHIBOD 2-O ', 'ETHIBOND ( green) 2-O, double needle 17 mm with pledget ( Aortic) Code: PX 54 H', 'ETHICON'),
('40', 'SILK 2-O  ', 'MERSILK 2-O Round Body, Code:  NW 5331', 'ETHICON'),
('41', 'SILK 2-O reverse cutting ', 'MERSILK 2-O reverse cutting, Code: NW 5036', 'ETHICON'),
('42', 'SILK 3-O  ', 'MERSILK 3-O round body 25 mm needle Code; NW 5087', 'ETHICON'),
('43', 'SILK 3-O  ', 'MERSILK 3-O round body 20 mm needle Code:  NW 5085', ''),
('44', 'SILK 3-O reverse cutting', 'MERSILK 3-O reverse cutting, Code: NW 5028', ''),
('45', 'SILK SUTUPACK 3-O', 'SILK SUTUPACK 3-O code: SW 222', 'ETHICON'),
('46', 'SILK REEL NO. 1', 'R825', 'ETHICON'),
('47', 'SILK REEL NO. 2', 'R826', 'ETHICON'),
('48', 'SILK REEL NO. 1-O', 'R824', 'ETHICON'),
('49', 'Umblical tape', 'W2760', 'ETHICON'),
('5', 'ETHIBOND 2-O', 'ETHIBOND (white) 2-O double needle17 mm with pledget ( Aortic) Code: PX17', 'ETHICON'),
('50', 'VICRYL 1-O  ', 'VICRYL 1-O round body, Code: NW 2546/2346', 'ETHICON'),
('51', 'VICRYL 2-O  ', 'NW 2341/2317', 'ETHICON'),
('52', 'VICRYL 3-O ', 'VICRYL 3-O round body, Code: NW 2437', 'ETHICON'),
('53', 'VICRYL rapide 3-O', 'VICRYL rapide 3-o cutting undyed, Code:  W9932', 'ETHICON'),
('6', 'ETHIBOND 2-O', 'ETHIBOND ( green) 2-O, double needle 17 mm, non pledget ( Aortic) Code: W6937', 'ETHICON'),
('7', 'ETHIBOND 2-O', 'ETHIBOND (white) 2-O, double needle 17 mm, non pledget ( Aortic) Code: W6917', 'ETHICON'),
('8', 'ETHIBOND 2-O', 'ETHIBOND 2-O half circle taper cut 25 mm double needle PTFE pledget ( 3X3X1.5) 75 cm ( Mitral) Code: MNW 6578/ 3218-56', 'ETHICON/ TYCO'),
('9', 'ETHIBOND 2-O', 'ETHIBOND 2-O half circle taper cut 25 mm double needle PTFE pledget ( 6X3X1.5) 75 cm ( Mitral) Code: MNW 6577/ 3324-56', 'ETHICON/ TYCO');

-- --------------------------------------------------------

--
-- Table structure for table `tablee`
--

CREATE TABLE `tablee` (
  `sr.no` varchar(7) NOT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `spec` varchar(1000) DEFAULT NULL,
  `brand` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tablee`
--

INSERT INTO `tablee` (`sr.no`, `name`, `spec`, `brand`) VALUES
('1', 'Inj. Amino Acid sol.', '', 'Claris/Fresnius Kabi'),
('10', 'Amikacin Inj', '100mg/500mg/1g ', 'Bristol-myers/Cipla/'),
('11', 'Aminocaproic Acid Inj (Hemostat)', '250mg/ml(20ml)', 'GSK/Samarth Pharma'),
('12', 'Amiodarone Inj', '50mg/ml-3ml', 'Cipla/Sanofi/Neon'),
('13', 'Atracurium Inj ', '10mg/ml', 'Neon/Sanartg'),
('14', 'Bupivacaine/Rupivac  Inj', '0.25%/0.5%  Plain/Heavy    4ml/20ml', 'Astrazeneca/Sun/Neon'),
('15', 'Cefazolin', '250mg, 500mg', 'Cadilla/Lupin/Ranbaxy'),
('16', 'Cefuroxime Inj', '250mg, 500mg,750mg, 1.5g', 'Glenmark/Glaxo/Aristo/Supacef'),
('17', 'Cefipime Inj', '500mg/1mg', 'Bristol-myers/Cipla/Cadilla'),
('18', 'Ceftizoxime Inj', '500mg/1mg', 'Elder/GSK'),
('19', 'Diltiazem Inj', '25mg/5ml', 'Torrent'),
('2', 'Inj. Dextrose ', '5 %, 10%, 25%', 'Baxter'),
('20', 'Dexmed Inj', '200mg/2ml', 'Neon'),
('21', 'Dobutamine Inj', '250mg/5ml', 'torrent/sun/Samarht/Neon'),
('22', 'Duolin Puff', '', 'Cipla'),
('23', 'Esmolol Inj', '10mg/ml', 'Neon/USV'),
('24', 'Glycopyrrolate Inj', '0.2mg/ml', 'Neon/Piramal'),
('25', 'Heparin Inj', '5000 U /25000 U ', 'Gland/Neon/VHB/Abbot'),
('26', 'Insulin Inj', '', ''),
('27', 'Ketamine Inj', '10 mg, 50 mg', 'Neon'),
('28', 'Labetolol Inj', '5mg/2ml', 'Mercury Lab, Samarth'),
('29', 'Levosimendan', '10 mg/ml', ''),
('3', 'Inj. Plasma Lyte A', '', ''),
('30', 'Lignocain Spray ', '10 % , ', 'Neon'),
('31', 'Majnesium Sulphate Inj', '500mg/ml', 'Neon'),
('32', 'Mannitol Inj', '20%-100ml', 'Cadilla/Venus'),
('33', 'Metaprolol Inj', '1mg/ml', 'Astrazeneca/Cipla/Dr, Reddy'),
('34', 'Metoclopramide Inj', '1mg/ml', 'Astrazeneca/IPCA '),
('35', 'Midazolam Inj Ampule', '5mg/ml', 'Ranbaxy/Neon'),
('36', 'Milrinone Inj', '1mg/ml', 'Neon/Cleon/Samarth/VHB'),
('37', 'Neostigmine+ Glycopyrrolate Inj', '0.5mg+2.5mg', 'Neon'),
('38', 'Nitroglycerine Inj (NTG)', '5mg/ml', 'Neon/Samarth'),
('39', 'Pancuronium Bromide Inj.', '2mg/ml', 'Neon/Piramal'),
('4', 'Inj. Normal saline', 'Plastic bottle, 500 ml', ''),
('40', 'Paracetamol Inj', '150mg/ml', 'Lupin/ Neon'),
('41', 'Phenylephrine Inj', '10mg/ml', 'Neon '),
('42', 'Priperacillin Tazobactum Inj', '4.5mg', 'Cipla/Lupin/VHB'),
('43', 'Plegiocard Inj', '20ml ', 'Samarth/ Neon'),
('44', 'Potassium Chloride Inj', '20meg/10ml', 'Baxter, Neon'),
('45', 'Propofol Inj', '10mg/ml', 'Fresnius Kabi/Neon'),
('46', 'Protamine Inj', '10mg/ml', 'Samath'),
('47', 'Ranitidine Inj', '25mg/ml', 'Cadilla/GSK'),
('48', 'Soda-Bicarbonate Inj', '4%, ', ''),
('49', 'Sodium Nitroprusside Inj', '50mg/vial', 'Neon/Samarth'),
('5', 'Inj Normal saline', 'Bag, 500 ml', 'Baxter'),
('50', 'Teicoplanin Inj', '200mg/400mg', 'Aventis/Cipla/Glenmark/neon'),
('51', 'Thyopentone inj', '0.5gm, 1gm', 'Abott, Biocare, Neon'),
('52', 'Tranexemic Acid Inj', '100mg/ml', 'Cadilla/Macleod/Samarth'),
('53', 'Vacomycin Inj', '500mg/1g', 'Astra/Xeneca/Alkem/Elilily/VHB'),
('54', 'Vecuronium Inj', '4mg/10mg', 'Organon/Sun/Neon'),
('55', 'Xylocard Inj/ Lignocain Inj', '2%, 50ml/30ml', 'Astrazeneca/Neon'),
('56', 'Vasopresin Inj', '20 units/ ml ', 'Samarth/ Neon'),
('6', 'Inj Ringer Lactate', 'Plastic bottle, 500 ml', 'Baxter/Clairs/SKKL'),
('7', 'Inj Ringer Lactate', 'Bag, 500 ml', ''),
('8', 'Adenosine Inj', '6mg/12mg', 'Neon/Toirrent/Sun'),
('9', 'Albumin Inj 20%', '100 ml ', 'Bharat Serum/Reliance');

-- --------------------------------------------------------

--
-- Table structure for table `testform1_um_view`
--

CREATE TABLE `testform1_um_view` (
  `srno` int(11) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `testform1_um_view`
--

INSERT INTO `testform1_um_view` (`srno`, `qty`, `remark`) VALUES
(1, 0, '--'),
(2, 0, '--'),
(3, 0, '--'),
(4, 0, '--'),
(5, 0, '--'),
(6, 0, '--'),
(7, 0, '--'),
(8, 0, '--'),
(9, 0, '--'),
(10, 0, '--'),
(11, 0, '--'),
(12, 0, '--'),
(13, 0, '--'),
(14, 0, '--'),
(15, 0, '--'),
(16, 0, '--'),
(17, 0, '--'),
(18, 0, '--'),
(19, 0, '--'),
(20, 0, '--'),
(21, 0, '--'),
(22, 0, '--'),
(23, 0, '--'),
(24, 0, '--'),
(25, 0, '--'),
(26, 0, '--'),
(27, 0, '--'),
(28, 0, '--'),
(29, 0, '--'),
(30, 0, '--'),
(31, 0, '--'),
(32, 0, '--'),
(33, 0, '--'),
(34, 0, '--'),
(35, 0, '--'),
(36, 0, '--'),
(37, 0, '--'),
(38, 0, '--'),
(39, 0, '--'),
(40, 0, '--'),
(41, 0, '--'),
(42, 0, '--'),
(43, 0, '--');

-- --------------------------------------------------------

--
-- Table structure for table `testform_view`
--

CREATE TABLE `testform_view` (
  `section` int(3) DEFAULT NULL,
  `srno` varchar(10) DEFAULT NULL,
  `selected` int(2) DEFAULT NULL,
  `qty` varchar(100) DEFAULT NULL,
  `brand` varchar(40) DEFAULT NULL,
  `remark` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `testform_view`
--

INSERT INTO `testform_view` (`section`, `srno`, `selected`, `qty`, `brand`, `remark`) VALUES
(1, '1', 1, '2', '0', 'R1'),
(1, '2', 1, '030', '10', 'R2'),
(1, '3', 1, '0', '011', 'R3'),
(1, '4', 1, '0', '10', 'R4'),
(1, '5', 1, '0', '0', 'R5'),
(1, '6', 1, '30', '10', 'R6'),
(1, '7', 1, '0', '101', 'R7'),
(1, '8', 1, '04', '0101', 'R8'),
(1, '9', 1, '0', '01', ''),
(1, '10', 1, '03000', '01', ''),
(1, '11', 1, '02300', '10', ''),
(1, '12', 1, '20', '010', ''),
(2, '3', 1, '20203057', '011', 'R13'),
(2, '4', 1, '5', '010', 'R14'),
(2, '19', 1, '356', '11', 'R15'),
(2, '41', 1, '0450', '01', 'R16'),
(3, '4', 1, '4', '1', 'R17'),
(3, '5', 1, '3030', '10', 'R18'),
(3, '8', 1, '0111', '1', 'R19'),
(3, '49', 1, '5', '101', 'R20'),
(4, '2', 1, '7', '11', 'R21'),
(4, '4', 1, '6', '0', 'R22'),
(4, '9', 1, '0', '11', 'R23'),
(4, '54', 1, '2', '1', 'Dispatched'),
(5, '1', 1, '5', '01', ''),
(5, '2', 1, '033', '1', ''),
(5, '8', 1, '85', '101', ''),
(5, '16', 1, '1202', '1010', 'R28');

-- --------------------------------------------------------

--
-- Table structure for table `testing`
--

CREATE TABLE `testing` (
  `d1` int(3) DEFAULT NULL,
  `d2` int(3) DEFAULT NULL,
  `d3` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `um_history`
--

CREATE TABLE `um_history` (
  `srno` int(11) NOT NULL,
  `formid` varchar(500) NOT NULL,
  `date_recv` date DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `um_history`
--

INSERT INTO `um_history` (`srno`, `formid`, `date_recv`, `status`) VALUES
(1, '\r\n8888888888', '0000-00-00', 'Not Dispatched'),
(2, '5555555555', '0000-00-00', 'Not Dispatched');

-- --------------------------------------------------------

--
-- Table structure for table `verified_by`
--

CREATE TABLE `verified_by` (
  `formid` varchar(200) NOT NULL,
  `nurse_send` text NOT NULL,
  `techn_send` text NOT NULL,
  `perf_send` text NOT NULL,
  `consultant_send` text NOT NULL,
  `um_disp` text NOT NULL DEFAULT 'N',
  `um_rec` text NOT NULL DEFAULT 'N',
  `um_fwd` text NOT NULL DEFAULT 'N',
  `nurse_rec` text NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `verified_by`
--

INSERT INTO `verified_by` (`formid`, `nurse_send`, `techn_send`, `perf_send`, `consultant_send`, `um_disp`, `um_rec`, `um_fwd`, `nurse_rec`) VALUES
('1111111111', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N'),
('1618677990', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N'),
('1618690074', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N'),
('1618726403', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N'),
('1618727544', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N'),
('1620198669', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N'),
('1620201073', 'Y', 'Y', 'Y', 'Y', 'N', 'N', 'N', 'N');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`eid`);

--
-- Indexes for table `formhistory`
--
ALTER TABLE `formhistory`
  ADD PRIMARY KEY (`FormID`);

--
-- Indexes for table `item_entry`
--
ALTER TABLE `item_entry`
  ADD PRIMARY KEY (`idx`);

--
-- Indexes for table `new_form`
--
ALTER TABLE `new_form`
  ADD PRIMARY KEY (`section`,`srno`);

--
-- Indexes for table `patient_details`
--
ALTER TABLE `patient_details`
  ADD PRIMARY KEY (`cr_no`(12));

--
-- Indexes for table `seca_items`
--
ALTER TABLE `seca_items`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `secb_items`
--
ALTER TABLE `secb_items`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `secc_items`
--
ALTER TABLE `secc_items`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `secd_items`
--
ALTER TABLE `secd_items`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `sece_items`
--
ALTER TABLE `sece_items`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `tablea`
--
ALTER TABLE `tablea`
  ADD PRIMARY KEY (`sr.no`);

--
-- Indexes for table `tableb`
--
ALTER TABLE `tableb`
  ADD PRIMARY KEY (`sr.no`);

--
-- Indexes for table `tablec`
--
ALTER TABLE `tablec`
  ADD PRIMARY KEY (`sr.no`);

--
-- Indexes for table `tabled`
--
ALTER TABLE `tabled`
  ADD PRIMARY KEY (`sr.no`);

--
-- Indexes for table `tablee`
--
ALTER TABLE `tablee`
  ADD PRIMARY KEY (`sr.no`);

--
-- Indexes for table `testform1_um_view`
--
ALTER TABLE `testform1_um_view`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `um_history`
--
ALTER TABLE `um_history`
  ADD PRIMARY KEY (`srno`);

--
-- Indexes for table `verified_by`
--
ALTER TABLE `verified_by`
  ADD PRIMARY KEY (`formid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `testform1_um_view`
--
ALTER TABLE `testform1_um_view`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `um_history`
--
ALTER TABLE `um_history`
  MODIFY `srno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
