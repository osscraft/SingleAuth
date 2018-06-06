-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 07 月 16 日 16:42
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
-- 转存表中的数据 `setting`
--

INSERT INTO `setting` (`k`, `v`, `info`) VALUES
('ldap_host', '192.168.0.22', 'ldap config host'),
('ldap_name', 'cn=admin,dc=ldap,dc=dcux,dc=com', 'ldap config admin dn'),
('ldap_pass', '123456', 'ldap config admin password'),
('ldap_port', '389', 'ldap config port'),
('ldap_show', '1', 'ldap config show log'),
('theme.admin', 'urban', ''),
('theme.main', 'fix', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
