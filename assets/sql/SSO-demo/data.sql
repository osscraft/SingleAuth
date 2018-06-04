-- portal app client
INSERT INTO `clients` (`client_name`, `client_describe`, `client_id`, `client_secret`, `client_type`, `redirect_uri`, `scope`, `client_location`, `logo_uri`, `is_show`, `visible`, `order_num`, `token_lifetime`, `owner`) VALUES ('Single Auth', 'open single authentication and authorization', 'ufsso_dcux_portal', '', 'jsApp', 'http://127.0.0.1:8800/index.html', 'uid,username,role', 'http://127.0.0.1:8800/index.html', '', '0', '0', '0', '0', '');
INSERT INTO `clients` (`client_name`, `client_describe`, `client_id`, `client_secret`, `client_type`, `redirect_uri`, `scope`, `client_location`, `logo_uri`, `is_show`, `visible`, `order_num`, `token_lifetime`, `owner`) VALUES ('管理后台', 'Single Auth管理后台', 'sso_admin_client', '9d4a75ff40757d45e51e1e60a07ed976', 'webApp', 'http://127.0.0.1:8800/admin/index.php', 'uid,username,role', 'http://127.0.0.1:8800/admin/index.php', '', '1', '3', '0', '0', '');
INSERT INTO `clients` (`client_name`, `client_describe`, `client_id`, `client_secret`, `client_type`, `redirect_uri`, `scope`, `client_location`, `logo_uri`, `is_show`, `visible`, `order_num`, `token_lifetime`, `owner`) VALUES ('密码维护', '密码维护', 'ufsso_dcux_changepass', '34598783e0823149326cee4cf67c6667', 'webApp', 'http://127.0.0.1:8800/ChangePass/index.php', 'uid,username,role', 'http://127.0.0.1:8800/ChangePass/index.php', '', '1', '0', '0', '0', '');
-- demo user
INSERT INTO `users` (`uid`, `username`, `password`, `role`, `gender`, `birthday`, `is_admin`) VALUES ('lay', 'lay', '96e79218965eb72c92a549dd5a330112', '0', '1', '2008-06-04', '1');
