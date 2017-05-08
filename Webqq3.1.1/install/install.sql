DROP TABLE IF EXISTS `web_role`;
DROP TABLE IF EXISTS `web_user`;
DROP TABLE IF EXISTS `web_robot`;
DROP TABLE IF EXISTS `web_robot_dat`;
DROP TABLE IF EXISTS `web_verification`;
DROP TABLE IF EXISTS `web_cookie`;
DROP TABLE IF EXISTS `web_plugin`;
DROP TABLE IF EXISTS `web_robot_friends`;
DROP TABLE IF EXISTS `web_run_log`;
DROP TABLE IF EXISTS `web_group_member`;
DROP TABLE IF EXISTS `web_group_member_info`;
DROP TABLE IF EXISTS `web_group_info`;
DROP TABLE IF EXISTS `web_friend_info`;
DROP TABLE IF EXISTS `web_system`;
DROP TABLE IF EXISTS `web_chat_content`;
DROP TABLE IF EXISTS `web_renewal`;

create table if not exists web_role (
	id int(4) not null primary key AUTO_INCREMENT,
	name varchar(12) not null,
	jurisdiction int(10) not null,
	add_robot_max_number int(4) not null default 0,
	init_gold int(11) not null default 0,
	sort int(6) not null default 0,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_user (
	id int(8) not null primary key AUTO_INCREMENT,
	role_id int(4) not null,
	username varchar(12) not null unique,
	password varchar(32) not null,
	mail varchar(32) null,
	phone varchar(11) not null unique,
	qq varchar(11) null,
	gold int(11) not null default 0,
	invitation varchar(8) not null,
	user_check varchar(64) null,
	reg_ip varchar(15) not null,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_robot (
	id int(10) not null primary key AUTO_INCREMENT,
	user_id int(8) not null,
	uin varchar(11) not null unique,
	pass varchar(32) not null,
	rsa_pass text null,
	secret varchar(8) not null,
	name varchar(16) not null,
	status int(1) not null default 1,
	is_run int(1) not null default 0,
	is_reconnection int(1) not null default 0,
	is_reply int(1) not null default 0,
	is_hook int(1) not null default 0,
	is_group_speech int(1) not null default 0,
	is_personal_speech int(1) not null default 0,
	create_uin varchar(11) null,
	verification varchar(4) null,
	verifysession text null,
	code varchar(4) null,
	cookie text null,
	skey varchar(16) null,
	bkn int(10) null,
	run_last_time int(11) null,
	limitdate int(11) not null default 0,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_robot_dat (
	robot_id int(10) not null unique,
	manager_uin text null,
	group_black_list text null,
	group_white_list text null,
	qq_black_list text null,
	qq_white_list text null,
	add_group_clues text null,
	agree_add_group_clues text null,
	refuse_add_group_clues text null,
	is_agree_add_group int(1) not null default 0,
	is_refuse_add_group int(1) not null default 0,
	is_group_black_list int(1) not null default 0,
	is_group_white_list int(1) not null default 0,
	is_qq_black_list int(1) not null default 0,
	is_qq_white_list int(1) not null default 0
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_verification (
	id int(6) not null primary key AUTO_INCREMENT,
	robot_id int(10) not null unique,
	verification blob not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_cookie (
	id int(6) not null primary key AUTO_INCREMENT,
	robot_id int(10) not null unique,
	cookie text not null,
	ptwebqq text not null,
	vfwebqq text not null,
	psessionid text not null,
	clientid text not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_plugin (
	id int(6) not null primary key AUTO_INCREMENT,
	plugin_name varchar(60) not null unique,
	class_name varchar(60) not null unique,
	author varchar(16) not null,
	author_url varchar(60) not null,
	description text not null,
	instruction text not null,
	instruction_type text not null,
	plugin_type text not null,
	version varchar(16) not null,
	is_able int(1) not null default 0,
	is_monitor_all_msg int(1) not null default 0
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_robot_friends (
	id int(10) not null primary key AUTO_INCREMENT,
	robot_id int(10) not null,
	friend_uin varchar(11) not null,
	nick_name varchar(64) not null,
	plugin_id int(5) not null default 0,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_run_log (
	id int(10) not null primary key AUTO_INCREMENT,
	robot_id int(10) not null,
	group_uin varchar(11) not null,
	msg text not null,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_group_member (
	id int(10) not null primary key AUTO_INCREMENT,
	robot_id int(10) not null,
	group_uin varchar(11) not null,
	member_uin varchar(11) not null,
	nick_name varchar(64) not null,
	card_name varchar(64) null,
	experience int(11) not null default 0,
	gold int(11) not null default 100,
	plugin_id int(5) not null default 0,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_group_member_info (
	id int(10) not null primary key AUTO_INCREMENT,
	group_uin varchar(11) not null,
	member_uin varchar(11) not null,
	nick_name varchar(64) not null,
	card_name varchar(64) null,
	role int(1) not null,
	qage int(3) not null,
	qsex int(3) not null,
	level int(1) not null,
	point int(8) not null,
	join_time int(11) not null,
	last_speak_time int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_group_info (
	id int(4) not null primary key AUTO_INCREMENT,
	uin int not null unique,
	name varchar(64) not null,
	owner int not null,
	adm_max int null,
	adm_num int null,
	level varchar(100) null,
	count int null,
	max_count int null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_friend_info (
	id int(4) not null primary key AUTO_INCREMENT,
	uin int not null unique,
	name varchar(64) not null,
	gtype int not null,
	gname varchar(24) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_system (
	id int(6) not null primary key AUTO_INCREMENT,
	mark varchar(32) not null,
	name varchar(64) not null,
	value text not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_chat_content(
	id int(11) not null primary key AUTO_INCREMENT,
	user_id int(11) not null,
	content text not null,
	ip varchar(15) not null,
	is_delete int(1) not null default 0,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

create table if not exists web_renewal (
	id int(4) not null primary key AUTO_INCREMENT,
	name varchar(12) not null,
	day_time int(11) not null,
	gold int(11) not null,
	sort int(6) not null default 0,
	createdate int(11) not null
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COLLATE utf8_general_ci;

alter table web_system add constraint web_system_unique UNIQUE(mark, name);
alter table web_group_member add constraint web_group_member_unique UNIQUE(robot_id, group_uin, member_uin);
alter table web_group_member_info add constraint web_group_member_info_unique UNIQUE(group_uin, member_uin);
alter table web_robot_friends add constraint web_robot_friends_unique UNIQUE(robot_id, friend_uin);
insert into web_role(id, name, jurisdiction, add_robot_max_number, sort, createdate) values(1, '超级管理员', 127006, 10, 9999, 1437107420);
insert into web_role(id, name, jurisdiction, add_robot_max_number, sort, createdate) values(2, '英勇黄铜', 14, 0, 1000, 1437107520);
insert into web_renewal(id, name, day_time, gold, sort, createdate) values(1, '试用卡', 1, 10, 1000, 1437107520);
insert into web_system(mark, name, value) values('style', 'style', 'style.css');
insert into web_system(mark, name, value) values('style_default', 'style_default', 'style.css');
insert into web_system(mark, name, value) values('cron', 'status', '1');
insert into web_system(mark, name, value) values('cron', 'pass', '123456');
insert into web_system(mark, name, value) values('control', 'is_stop_robot', '0');
insert into web_system(mark, name, value) values('control', 'is_reg', '1');
insert into web_system(mark, name, value) values('control', 'domain_name', '茉莉机器人');
insert into web_system(mark, name, value) values('control', 'invite_gold1', '10');
insert into web_system(mark, name, value) values('control', 'invite_gold2', '5');
insert into web_system(mark, name, value) values('admin', 'admin_role', '2');
insert into web_system(mark, name, value) values('config', 'version', '3.1.1');