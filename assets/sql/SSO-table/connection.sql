-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 07 月 16 日 16:27
-- 服务器版本: 5.1.63-0+squeeze1-log
-- PHP 版本: 5.3.3-7+squeeze19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `sso`
--

-- --------------------------------------------------------

--
-- 表的结构 `connection`
--

CREATE TABLE IF NOT EXISTS `connection` (
  `id` int(11) NOT NULL,
  `source` tinyint(4) NOT NULL,
  `sid` int(11) NOT NULL,
  `sidstr` varchar(100) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_sid` (`sid`),
  KEY `index_sidstr` (`sidstr`),
  KEY `index_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
