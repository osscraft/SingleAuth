-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 10 月 13 日 10:04
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
-- 表的结构 `auth_codes`
--

CREATE TABLE IF NOT EXISTS `auth_codes` (
  `code` varchar(32) NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `redirect_uri` varchar(255) NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  `scope` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(50) NOT NULL,
  `client_describe` varchar(1000) NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `client_secret` varchar(32) NOT NULL,
  `client_type` varchar(10) NOT NULL,
  `redirect_uri` varchar(255) NOT NULL,
  `scope` varchar(255) NOT NULL,
  `client_location` varchar(255) NOT NULL COMMENT '客户端首页地址',
  `logo_uri` varchar(255) NOT NULL,
  `is_show` tinyint(4) NOT NULL DEFAULT '0',
  `visible` tinyint(4) NOT NULL DEFAULT '0',
  `order_num` int(11) NOT NULL DEFAULT '0' COMMENT '排序值，通过访问量',
  `token_lifetime` int(11) NOT NULL DEFAULT '0',
  `owner` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id` (`client_id`),
  KEY `order_num` (`order_num`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1165 ;

-- --------------------------------------------------------

--
-- 表的结构 `client_extension`
--

CREATE TABLE IF NOT EXISTS `client_extension` (
  `cid` varchar(32) NOT NULL,
  `total` int(11) NOT NULL,
  `security_level` tinyint(4) NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` varchar(26) NOT NULL,
  `data` text NOT NULL,
  `online` tinyint(4) NOT NULL,
  `time` datetime NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `expires` (`expires`),
  KEY `online` (`online`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `k` varchar(255) NOT NULL,
  `v` text NOT NULL,
  `info` varchar(1000) NOT NULL COMMENT '配置项说明',
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `stat_browser`
--

CREATE TABLE IF NOT EXISTS `stat_browser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `browser` varchar(50) NOT NULL,
  `version` varchar(50) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_browser_version` (`browser`,`version`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=96 ;

-- --------------------------------------------------------

--
-- 表的结构 `stat_client`
--

CREATE TABLE IF NOT EXISTS `stat_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `count` int(11) NOT NULL,
  `count_visit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_date_client` (`client_id`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1053 ;

-- --------------------------------------------------------

--
-- 表的结构 `stat_failure`
--

CREATE TABLE IF NOT EXISTS `stat_failure` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `client_id` varchar(50) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_d_i_c` (`date`,`ip`,`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8683 ;

-- --------------------------------------------------------

--
-- 表的结构 `stat_online`
--

CREATE TABLE IF NOT EXISTS `stat_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `time_UNIQUE` (`time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1244178 ;

-- --------------------------------------------------------

--
-- 表的结构 `stat_referer`
--

CREATE TABLE IF NOT EXISTS `stat_referer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL COMMENT '0：未知，1：用户手动输入，2：从SSO首页，3：从客户端引导，4：本页',
  `ip` int(10) unsigned NOT NULL,
  `os` varchar(50) NOT NULL,
  `browser` varchar(50) NOT NULL,
  `referer` varchar(2000) NOT NULL,
  `ua` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户进入登录页来源（referer）' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `stat_user`
--

CREATE TABLE IF NOT EXISTS `stat_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `count` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_date_user` (`username`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=605 ;

-- --------------------------------------------------------

--
-- 表的结构 `stat_user_detail`
--

CREATE TABLE IF NOT EXISTS `stat_user_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `username` varchar(50) NOT NULL,
  `client_id` varchar(50) NOT NULL,
  `success` tinyint(4) NOT NULL,
  `is_password` tinyint(4) NOT NULL COMMENT '是否是通过密码验证',
  `ip` int(10) unsigned NOT NULL,
  `os` varchar(50) NOT NULL,
  `browser` varchar(50) NOT NULL,
  `ua` varchar(2000) NOT NULL,
  `referer` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `index_username` (`username`),
  KEY `index_client_id` (`client_id`),
  KEY `time` (`time`),
  KEY `ip` (`ip`),
  KEY `os` (`os`),
  KEY `browser` (`browser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2370 ;

-- --------------------------------------------------------

--
-- 表的结构 `tokens`
--

CREATE TABLE IF NOT EXISTS `tokens` (
  `oauth_token` varchar(32) NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  `username` varchar(50) NOT NULL,
  `scope` varchar(255) NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`oauth_token`),
  UNIQUE KEY `client_id` (`client_id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `role` tinyint(4) NOT NULL COMMENT '0:未知，1:老师，2:学生，3:其他人员',
  `gender` tinyint(4) NOT NULL,
  `birthday` date NOT NULL,
  `is_admin` tinyint(4) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_election`
--

CREATE TABLE IF NOT EXISTS `user_election` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(50) NOT NULL,
  `client_id` varchar(32) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_uid_client_id` (`uid`,`client_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

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

-- --------------------------------------------------------

--
-- 表的结构 `user_grant`
--

CREATE TABLE IF NOT EXISTS `user_grant` (
  `uid` varchar(50) NOT NULL,
  `is_super` tinyint(4) NOT NULL,
  `grants` varchar(2000) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user_setting`
--

CREATE TABLE IF NOT EXISTS `user_setting` (
  `uid` varchar(50) NOT NULL,
  `privacy` varchar(2000) NOT NULL COMMENT '个人隐私信息设置项（JSON结构）',
  `frontend` varchar(2000) NOT NULL COMMENT '前端设置项',
  `backend` varchar(2000) NOT NULL COMMENT '后端设置项',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户个人设置项';

-- 增加login_by
ALTER TABLE  `stat_user_detail` ADD  `login_by` TINYINT NOT NULL COMMENT  '登录方式(0:密码，1:session，2:SID，3:二维码)' AFTER  `success`


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
