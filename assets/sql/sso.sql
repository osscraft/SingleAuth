
---20150416---
ALTER TABLE  `sso`.`clients` ADD  `visible` INT NOT NULL DEFAULT  '0' AFTER  `is_show`;
ALTER TABLE  `sso`.`clients` CHANGE  `is_show`  `is_show` TINYINT( 4 ) NOT NULL DEFAULT  '0';

---20150422---
ALTER TABLE  `users` ADD  `role` TINYINT NOT NULL COMMENT  '0:未知，1:老师，2:学生，3:其他人员' AFTER  `username`;
ALTER TABLE  `clients` ADD  `order_num` INT UNSIGNED NOT NULL COMMENT  '排序值，通过访问量' AFTER  `visible` ,
ADD INDEX (  `order_num` )

ALTER TABLE  `sso`.`clients` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `id` );

ALTER TABLE `sso`.`clients` DROP INDEX `id`;
ALTER TABLE `sso`.`clients` ADD UNIQUE (`client_id`);

ALTER TABLE  `sso`.`clients` CHANGE  `visible`  `visible` TINYINT( 4 ) NOT NULL DEFAULT  '0';

ALTER TABLE  `sso`.`clients` CHANGE  `order_num`  `order_num` INT NOT NULL DEFAULT  '0' COMMENT  '排序值，通过访问量';

CREATE  TABLE `sso`.`setting` (
  `k` VARCHAR(255) NOT NULL ,
  `v` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `user_extension` (
  `uid` varchar(50) NOT NULL COMMENT '对应LDAP中的uid',
  `last_login` int(10) unsigned NOT NULL COMMENT '最后登录时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

---20150423---
CREATE TABLE `session` (
  `id` varchar(26) NOT NULL,
  `data` text NOT NULL,
  `online` tinyint(4) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `expires` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `expires` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8

ALTER TABLE `sso`.`user_extension` ADD COLUMN `last_client_id` VARCHAR(50) NOT NULL  AFTER `last_login` , ADD COLUMN `last_ip` INT UNSIGNED NOT NULL  AFTER `last_client_id` , ADD COLUMN `last_os` VARCHAR(200) NOT NULL  AFTER `last_ip` , ADD COLUMN `last_browser` VARCHAR(200) NOT NULL  AFTER `last_os` ;

---20150423---
ALTER TABLE `sso`.`user_extension` CHANGE COLUMN `last_login` `last_login` DATETIME NOT NULL COMMENT '最后登录时间'  ;

ALTER TABLE `sso`.`stat_user_detail` CHANGE COLUMN `time` `time` DATETIME NOT NULL  ;

ALTER TABLE `sso`.`session` CHANGE COLUMN `time` `time` DATETIME NOT NULL  ;

---20150504---
ALTER TABLE  `stat_user_detail` ADD INDEX (  `time` )

---20150505---
CREATE TABLE `sso`.`stat_browser` (
`id` INT NOT NULL AUTO_INCREMENT ,
`browser` VARCHAR(50) NOT NULL ,
`version` VARCHAR(50) NOT NULL ,
`count` INT NOT NULL ,
PRIMARY KEY (`id`) ,
UNIQUE INDEX `unique_browser_version` (`browser` ASC, `version` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE `sso`.`stat_failure` (
`id` INT NOT NULL AUTO_INCREMENT ,
`date` DATE NOT NULL ,
`ip` INT UNSIGNED NOT NULL ,
`client_id` VARCHAR(50) NOT NULL ,
`count` INT NOT NULL ,
PRIMARY KEY (`id`) ,
UNIQUE INDEX `unique_d_i_c` (`date` ASC, `ip` ASC, `client_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

---20150511---
ALTER TABLE  `auth_codes` ENGINE = INNODB;
ALTER TABLE  `auth_infos` ENGINE = INNODB;
ALTER TABLE  `clients` ENGINE = INNODB;
ALTER TABLE  `ldap` ENGINE = INNODB;
ALTER TABLE  `log_client` ENGINE = INNODB;
ALTER TABLE  `log_user` ENGINE = INNODB;
ALTER TABLE  `tokens` ENGINE = INNODB;
ALTER TABLE  `users` ENGINE = INNODB;
---20150514---
ALTER TABLE `sso`.`setting` ADD COLUMN `info` VARCHAR(1000) NOT NULL COMMENT '配置项说明'  AFTER `v` , CHANGE COLUMN `v` `v` VARCHAR(1000) NOT NULL  ;

---20150520---
ALTER TABLE  `auth_codes` ADD PRIMARY KEY (  `code` );
ALTER TABLE  `tokens` ADD PRIMARY KEY (  `oauth_token` );

ALTER TABLE  `auth_codes` ADD INDEX (  `expires` );
ALTER TABLE  `tokens` ADD INDEX (  `expires` );


ALTER TABLE  `auth_codes` CHANGE  `expires`  `expires` INT UNSIGNED NOT NULL;
ALTER TABLE  `tokens` CHANGE  `expires`  `expires` INT UNSIGNED NOT NULL;
--不客户端，不同的令牌有效时间，SQL如下：
ALTER TABLE `sso`.`clients` ADD COLUMN `token_lifetime` INT NOT NULL  AFTER `order_num` ;

--实时在线用户数 表，SQL如下：
CREATE TABLE `stat_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` datetime NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `time_UNIQUE` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
---20150601---
CREATE  TABLE `sso`.`client_extension` (
  `cid` VARCHAR(32) NOT NULL ,
  `total` INT NOT NULL ,
  `security_level` TINYINT NOT NULL ,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  `client_extension` ENGINE = INNODB;

ALTER TABLE  `users` ADD  `password` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `username`
---20150626---
CREATE  TABLE `sso`.`user_grant` (
  `uid` VARCHAR(50) NOT NULL ,
  `is_super` TINYINT NOT NULL ,
  `grants` VARCHAR(2000) NOT NULL ,
  PRIMARY KEY (`uid`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

---20150709---
CREATE  TABLE `sso`.`connection` (
  `id` INT NOT NULL ,
  `source` TINYINT NOT NULL ,
  `sid` INT NOT NULL ,
  `sidstr` VARCHAR(100) NOT NULL ,
  `uid` VARCHAR(50) NOT NULL ,
  `token` VARCHAR(100) NOT NULL ,
  `expires` INT UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `index_sid` (`sid` ASC) ,
  INDEX `index_sidstr` (`sidstr` ASC) ,
  INDEX `index_uid` (`uid` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

---20150713---
ALTER TABLE `sso`.`stat_user_detail` ADD COLUMN `ua` VARCHAR(2000) NOT NULL  AFTER `browser` ;

---20150715---
CREATE  TABLE `sso`.`stat_referer` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `type` TINYINT NOT NULL COMMENT '0：未知，1：用户手动输入，2：从SSO首 页，3：从客户端引导，4：本页' ,
  `referer` VARCHAR(2000) NOT NULL ,
  `ip` INT ZEROFILL NOT NULL ,
  `os` VARCHAR(50) NOT NULL ,
  `browser` VARCHAR(50) NOT NULL ,
  `ua` VARCHAR(2000) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '用户进入登录页来源（referer）';

ALTER TABLE `sso`.`stat_referer` CHANGE COLUMN `referer` `referer` VARCHAR(2000) NOT NULL  AFTER `browser` ;
ALTER TABLE  `stat_referer` CHANGE  `ip`  `ip` INT( 10 ) UNSIGNED NOT NULL

---20150717---
ALTER TABLE  `setting` CHANGE  `v`  `v` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

CREATE  TABLE `sso`.`user_election` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `uid` VARCHAR(50) NOT NULL ,
  `client_id` VARCHAR(32) NOT NULL ,
  `time` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `unique_uid_client_id` (`uid` ASC, `client_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

---20150727---
ALTER TABLE  `clients` ADD  `owner` VARCHAR( 50 ) NOT NULL ,
ADD INDEX (  `owner` )

---20150804---
ALTER TABLE  `session` ADD INDEX (  `online` )

---20150811---
CREATE TABLE `transfer`.`setting` (
  `k` VARCHAR(255) NOT NULL,
  `v` TEXT NOT NULL,
  `i` VARCHAR(1000) NOT NULL,
  PRIMARY KEY (`k`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;
--transfer--
CREATE TABLE IF NOT EXISTS `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `command` varchar(2000) NOT NULL,
  `info` varchar(2000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
--transfer--
CREATE TABLE IF NOT EXISTS `uid_email` (
  `uid` varchar(32) NOT NULL,
  `mailid` varchar(100) NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `mailid_UNIQUE` (`mailid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

---20150915---
CREATE TABLE `sso`.`user_student` (
  `uid` VARCHAR(50) NOT NULL COMMENT '',
  `gender` TINYINT(1) NOT NULL COMMENT '',
  `birthday` DATE NOT NULL COMMENT '',
  `o` VARCHAR(200) NOT NULL COMMENT '组织（学校）',
  `ou` VARCHAR(200) NOT NULL COMMENT '组织单位（学院）',
  `grade` VARCHAR(200) NOT NULL COMMENT '年级（以年为单位）',
  `class` VARCHAR(200) NOT NULL COMMENT '班级',
  `dorm` VARCHAR(200) NOT NULL COMMENT '',
  PRIMARY KEY (`uid`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '学生表';

CREATE TABLE `sso`.`user_teacher` (
  `uid` INT NOT NULL COMMENT '',
  `gender` TINYINT(1) NOT NULL COMMENT '',
  `birthday` DATE NOT NULL COMMENT '',
  `o` VARCHAR(200) NOT NULL COMMENT '组织（学校）',
  `ou` VARCHAR(200) NOT NULL COMMENT '组织单位（学院）',
  `title` VARCHAR(200) NOT NULL COMMENT '职称',
  PRIMARY KEY (`uid`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '教师表';

ALTER TABLE  `user_teacher` CHANGE  `uid`  `uid` VARCHAR( 50 ) NOT NULL

ALTER TABLE `sso`.`users` 
ADD COLUMN `avatar` VARCHAR(200) NOT NULL COMMENT '' AFTER `is_admin`;

CREATE TABLE `sso`.`user_setting` (
  `uid` VARCHAR(50) NOT NULL COMMENT '',
  `privacy` VARCHAR(2000) NOT NULL COMMENT '个人隐私信息设置项（JSON结构）',
  PRIMARY KEY (`uid`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '用户个人设置项';

ALTER TABLE `sso`.`user_extension` 
ADD COLUMN `last_status` TINYINT(4) NOT NULL COMMENT '在线状态' AFTER `last_browser`, 
COMMENT = '个人信息扩展表' ;

CREATE TABLE `sso`.`user_block` (
  `uid` VARCHAR(50) NOT NULL COMMENT '',
  `uid_block` VARCHAR(50) NOT NULL COMMENT '用户阻止（屏蔽）表',
  PRIMARY KEY (`uid`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci
COMMENT = '用户阻止（屏蔽）表';

ALTER TABLE `sso`.`user_block` 
ADD COLUMN `id` INT NOT NULL COMMENT '' FIRST,
DROP PRIMARY KEY,
ADD PRIMARY KEY (`id`) ,
ADD UNIQUE INDEX `u_uid_block` (`uid` ASC, `uid_block` ASC) ;

ALTER TABLE `sso`.`user_block` 
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '' ;

ALTER TABLE `sso`.`user_block` 
ADD COLUMN `time` DATETIME NOT NULL COMMENT '' AFTER `uid_block`;

---20150918---
ALTER TABLE `sso`.`stat_user_detail` 
ADD INDEX `ip` (`ip` ASC),
ADD INDEX `os` (`os` ASC),
ADD INDEX `browser` (`browser` ASC);

ALTER TABLE  `info` CHANGE  `info`  `info` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;

ALTER TABLE `transfer`.`info` 
ADD COLUMN `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '' AFTER `id`;

ALTER TABLE `sso`.`users` 
ADD COLUMN `gender` TINYINT(4) NOT NULL COMMENT '' AFTER `role`,
ADD COLUMN `birthday` DATE NOT NULL COMMENT '' AFTER `gender`;

ALTER TABLE `sso`.`user_student` 
DROP COLUMN `birthday`,
DROP COLUMN `gender`;

ALTER TABLE `sso`.`user_teacher` 
DROP COLUMN `birthday`,
DROP COLUMN `gender`;

---20151008---
--用户登录日志增加“来源”字段，SQL如下：
ALTER TABLE  `stat_user_detail` ADD  `referer` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL

---20151009---
--用户个人设置项增加前端和后端设置项字段，SQL如下：
ALTER TABLE  `user_setting` ADD  `frontend` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '前端设置项',
ADD  `backend` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT  '后端设置项'

--用户扩展表中增加最后登录UA，SQL如下：
ALTER TABLE  `user_extension` ADD  `last_ua` VARCHAR( 2000 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `last_browser`

--用户登录明细表增加是否是密码验证的，SQL如下：
ALTER TABLE  `stat_user_detail` ADD  `is_password` TINYINT NOT NULL COMMENT  '是否是通过密码验证' AFTER  `success`

---20151010---
--用户扩展项表增加最后登录时间索引，SQL如下：
ALTER TABLE  `user_extension` ADD INDEX (  `last_login` )

---20151020---
ALTER TABLE  `stat_user_detail` ADD  `login_by` TINYINT NOT NULL COMMENT  '登录方式(0:密码，1:session，2:SID，3:二维码)' AFTER  `success`

---20151021---
--新建qr_code表
CREATE TABLE `sso`.`qr_code` (
  `code` VARCHAR(32) NOT NULL COMMENT '',
  `time` DATETIME NOT NULL COMMENT '',
  `expires` INT UNSIGNED NOT NULL COMMENT '',
  `status` TINYINT NOT NULL COMMENT '0：未使用，1:已扫描，2：已完成',
  PRIMARY KEY (`code`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

---20151027---
--主消息表
CREATE TABLE `sso`.`msgs` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '',
  `sender_id` VARCHAR(50) NOT NULL COMMENT '发送者UID',
  `receiver_id` VARCHAR(50) NOT NULL COMMENT '接收者UID',
  `type` TINYINT NOT NULL COMMENT '消息类型',
  `time` DATETIME NOT NULL COMMENT '',
  `content` VARCHAR(140) NOT NULL COMMENT '消息内容',
  `status` TINYINT NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`),
  INDEX `idx_s_id` (`sender_id` ASC),
  INDEX `idx_r_id` (`receiver_id` ASC));