-------------- 数据库触发器 ---------------
-------------- 使用SHELL脚本 --------------
-- transfer.users --
DELIMITER |
DROP TRIGGER IF EXISTS `users_insert` |
CREATE TRIGGER `users_insert` BEFORE INSERT ON `users`
 FOR EACH ROW BEGIN
    set @command = CONCAT_WS(' ','/var/lib/mysql/mysql-ldap/mysql-ldap.sh','add',CONCAT_WS('','"',NEW.userid,'"'),CONCAT_WS('','"',NEW.password,'"'),CONCAT_WS('','"',NEW.role,'"'),CONCAT_WS('','"',NEW.name,'"'),CONCAT_WS('','"',NEW.status,'"'),CONCAT_WS('','"',NEW.create_time,'"'));
    set @done = sys_eval(@command);
    IF  @done != 0 then
        INSERT INTO `transfer`.`user`(`userid`,`password`,`role`,`name`,`create_time`,`status`) VALUES (NEW.userid,NEW.password,NEW.role,NEW.name,NEW.create_time,NEW.status);
    end if;
END
|
DELIMITER ;

DELIMITER |
DROP TRIGGER IF EXISTS `users_update` |
CREATE TRIGGER `users_update` BEFORE UPDATE ON `users`
 FOR EACH ROW BEGIN
    IF NEW.status = 0 AND OLD.status = 1 then
        set @command = CONCAT_WS(' ','/var/lib/mysql/mysql-ldap/mysql-ldap.sh','delete',CONCAT_WS('','"',NEW.userid,'"'),CONCAT_WS('','"',NEW.password,'"'),CONCAT_WS('','"',NEW.role,'"'),CONCAT_WS('','"',NEW.name,'"'),CONCAT_WS('','"',NEW.status,'"'),CONCAT_WS('','"',NEW.create_time,'"'));
        set @done = sys_eval(@command);
    ELSEIF NEW.status = 1 AND OLD.status = 0 then
        set @command = CONCAT_WS(' ','/var/lib/mysql/mysql-ldap/mysql-ldap.sh','add',CONCAT_WS('','"',NEW.userid,'"'),CONCAT_WS('','"',NEW.password,'"'),CONCAT_WS('','"',NEW.role,'"'),CONCAT_WS('','"',NEW.name,'"'),CONCAT_WS('','"',NEW.status,'"'),CONCAT_WS('','"',NEW.create_time,'"'));
        set @done = sys_eval(@command);
    end if;
        INSERT INTO `transfer`.`info`(`command`,`info`) VALUES (@command,@done);
    IF  @done != 0 then
        INSERT INTO `transfer`.`user`(`userid`,`password`,`role`,`name`,`status`,`create_time`) VALUES (OLD.userid,OLD.password,OLD.role,OLD.name,OLD.status,OLD.create_time);
    end if;
END
|
DELIMITER ;

-------------- 使用PHP脚本 --------------
-- transfer.users 1.1 --
DELIMITER |
DROP TRIGGER IF EXISTS `users_insert` |
create trigger `users_insert` BEFORE INSERT on `transfer`.`users` 
for each row BEGIN
    set @userid = CONCAT_WS('','"',NEW.userid,'"');
    set @password = CONCAT_WS('','"',NEW.password,'"');
    set @role = CONCAT_WS('','"',NEW.role,'"');
    set @name = CONCAT_WS('','"',NEW.name,'"');
    set @create_time = CONCAT_WS('','"',NEW.create_time,'"');
    set @status = CONCAT_WS('','"',NEW.status,'"');
    set @command = CONCAT_WS(' ','/var/lib/mysql/mysql-ldap/mysql_ldap.php', '-o', 'add',
        '-u', @userid, '-p', @password, '-r', @role, '-n', @name, '-s', @status, '-t', @create_time,
        '>>', '/var/lib/mysql/mysql-ldap/mysql_ldap.log');
    IF New.status = 1 then
        set @done = sys_eval(@command);
    END IF;
    /*set @done = 0;*/
    INSERT INTO `transfer`.`info`(`command`, `info`) VALUES(@command, @done);
END;
|
DELIMITER ;

DELIMITER |
DROP TRIGGER IF EXISTS `users_update` |
create trigger `users_update` BEFORE UPDATE on `transfer`.`users` 
for each row BEGIN
    set @userid = CONCAT_WS('','"',NEW.userid,'"');
    set @password = CONCAT_WS('','"',NEW.password,'"');
    set @role = CONCAT_WS('','"',NEW.role,'"');
    set @name = CONCAT_WS('','"',NEW.name,'"');
    set @create_time = CONCAT_WS('','"',NEW.create_time,'"');
    set @status = CONCAT_WS('','"',NEW.status,'"');
    IF NEW.status = 0 AND OLD.status = 1 then
        set @command = CONCAT_WS(' ','/var/lib/mysql/mysql-ldap/mysql_ldap.php', '-o', 'delete',
            '-u', @userid, '-p', @password, '-r', @role, '-n', @name, '-s', @status, '-t', @create_time,
            '>>', '/var/lib/mysql/mysql-ldap/mysql_ldap.log');
        set @done = sys_eval(@command);
        /*set @done = 0;*/
    ELSEIF NEW.status = 1 AND OLD.status = 0 then
        set @command = CONCAT_WS(' ','/var/lib/mysql/mysql-ldap/mysql_ldap.php', '-o', 'add',
            '-u', @userid, '-p', @password, '-r', @role, '-n', @name, '-s', @status, '-t', @create_time,
            '>>', '/var/lib/mysql/mysql-ldap/mysql_ldap.log');
        set @done = sys_eval(@command);
    end if;
    INSERT INTO `transfer`.`info`(`command`, `info`) VALUES(@command, @done);
END;
|
DELIMITER ;
