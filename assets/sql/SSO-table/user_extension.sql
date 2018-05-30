-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 10 月 10 日 10:56
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
-- 表的结构 `user_extension`
--

CREATE TABLE IF NOT EXISTS `user_extension` (
  `uid` varchar(50) NOT NULL COMMENT '对应LDAP中的uid',
  `last_login` datetime NOT NULL COMMENT '最后登录时间',
  `last_client_id` varchar(50) NOT NULL,
  `last_ip` int(10) unsigned NOT NULL,
  `last_os` varchar(200) NOT NULL,
  `last_browser` varchar(200) NOT NULL,
  `last_ua` varchar(2000) NOT NULL,
  `last_status` tinyint(4) NOT NULL COMMENT '在线状态',
  PRIMARY KEY (`uid`),
  KEY `last_login` (`last_login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='个人信息扩展表';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
