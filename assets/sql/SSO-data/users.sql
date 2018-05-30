-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 10 月 08 日 13:35
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

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `role`, `gender`, `birthday`, `is_admin`) VALUES
('administrator', 'administrator', '', 0, 0, '0000-00-00', 2),
('chunxin', '李春欣', 'ad9d88da0ba66c9a912241a6c86d6e4e', 0, 0, '0000-00-00', 1),
('liaiyong', '李爱勇', 'ad9d88da0ba66c9a912241a6c86d6e4e', 0, 0, '0000-00-00', 2),

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
