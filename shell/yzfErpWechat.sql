/*
Navicat MySQL Data Transfer

Source Server         : ali yzfErpWechat
Source Server Version : 50635
Source Host           : demo.mikkle.cn:3306
Source Database       : yzfErpWechat

Target Server Type    : MYSQL
Target Server Version : 50635
File Encoding         : 65001

Date: 2017-12-04 09:21:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mk_agent_apply
-- ----------------------------
DROP TABLE IF EXISTS `mk_agent_apply`;
CREATE TABLE `mk_agent_apply` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL COMMENT '申请人姓名',
  `mobile` varchar(50) DEFAULT NULL COMMENT '申请人手机号',
  `mail` varchar(50) DEFAULT NULL COMMENT '申请人邮箱',
  `company` varchar(100) DEFAULT NULL COMMENT '申请人公司',
  `address` varchar(100) DEFAULT NULL COMMENT '公司地址',
  `type` int(2) DEFAULT NULL COMMENT '1：七搜ERP，2：七搜同城',
  `desc` varchar(500) DEFAULT NULL COMMENT '留言',
  `status` varchar(20) DEFAULT NULL,
  `create_time` int(50) DEFAULT NULL,
  `update_time` int(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_client
-- ----------------------------
DROP TABLE IF EXISTS `mk_client`;
CREATE TABLE `mk_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `agent_id` varchar(40) DEFAULT NULL,
  `apply_id` int(11) DEFAULT NULL,
  `client_type` tinyint(6) DEFAULT '1' COMMENT '顾客类型  1公司顾客 2非公司顾客',
  `company` varchar(100) DEFAULT NULL COMMENT '公司名称',
  `address` varchar(200) DEFAULT NULL COMMENT '公司地址',
  `contacts` varchar(100) DEFAULT NULL COMMENT '联系人',
  `jobs` varchar(100) DEFAULT NULL COMMENT '职务',
  `mobile` varchar(100) DEFAULT NULL COMMENT '联系手机',
  `tencent_code` varchar(100) DEFAULT NULL COMMENT 'QQ 微信',
  `desc` text COMMENT '备注',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8 COMMENT='试用申请';

-- ----------------------------
-- Table structure for mk_common_files
-- ----------------------------
DROP TABLE IF EXISTS `mk_common_files`;
CREATE TABLE `mk_common_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `guid` char(40) DEFAULT NULL,
  `uuid` char(40) DEFAULT NULL,
  `name` varchar(100) DEFAULT '' COMMENT '原始文件名',
  `type` varchar(100) DEFAULT '' COMMENT '保存名称',
  `path` varchar(255) DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) unsigned DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) unsigned DEFAULT '0' COMMENT '文件保存位置',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '上传时间',
  `status` int(10) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_md5` (`md5`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='文件表';

-- ----------------------------
-- Table structure for mk_common_images
-- ----------------------------
DROP TABLE IF EXISTS `mk_common_images`;
CREATE TABLE `mk_common_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id自增',
  `uuid` char(40) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `path` varchar(255) DEFAULT '' COMMENT '路径',
  `url` varchar(255) DEFAULT '' COMMENT '图片链接',
  `md5` char(32) DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `width` int(11) DEFAULT NULL,
  `height` int(11) DEFAULT NULL,
  `channels` tinyint(4) DEFAULT NULL,
  `dpi` int(11) DEFAULT NULL,
  `exif` text,
  `size` int(11) DEFAULT NULL,
  `star` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_orders
-- ----------------------------
DROP TABLE IF EXISTS `mk_orders`;
CREATE TABLE `mk_orders` (
  `id` int(11) NOT NULL,
  `order_no` varchar(20) DEFAULT NULL,
  `amount` varchar(20) DEFAULT NULL,
  `is_pay` int(6) DEFAULT NULL,
  `status` int(6) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_department
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_department`;
CREATE TABLE `mk_personnel_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `department_type` varchar(80) DEFAULT NULL COMMENT '部门类型',
  `department_name` varchar(80) DEFAULT NULL COMMENT '部门名称',
  `department_desc` varchar(255) DEFAULT NULL COMMENT '部门备注',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_jobs
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_jobs`;
CREATE TABLE `mk_personnel_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `department_id` varchar(40) DEFAULT NULL COMMENT '所属部门ID',
  `jobs_name` varchar(60) DEFAULT NULL COMMENT '职位名称',
  `jobs_desc` varchar(255) DEFAULT NULL COMMENT '职位备注',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_log_record
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_log_record`;
CREATE TABLE `mk_personnel_log_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(40) DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `desc` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  `ip` varchar(20) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_node
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_node`;
CREATE TABLE `mk_personnel_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `pid` varchar(40) DEFAULT NULL COMMENT '父ID',
  `node_name` varchar(50) DEFAULT NULL COMMENT '节点名称',
  `module_name` varchar(80) DEFAULT NULL COMMENT '模块名称',
  `control_name` varchar(80) DEFAULT NULL COMMENT '控制器名称',
  `action_name` varchar(80) DEFAULT NULL COMMENT '方法名称',
  `icon` varchar(100) DEFAULT NULL COMMENT '图标',
  `auth_grade` smallint(6) DEFAULT '1' COMMENT '浏览权限 1 需要登陆  0 不用登陆',
  `is_menu` smallint(6) DEFAULT '0' COMMENT '是否菜单',
  `is_mobile` smallint(6) unsigned DEFAULT '0' COMMENT '是否手机端 1为是',
  `group` varchar(80) DEFAULT NULL COMMENT '节点分组',
  `sort` smallint(6) DEFAULT '0' COMMENT '节点排序',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_role
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_role`;
CREATE TABLE `mk_personnel_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `role_name` varchar(60) DEFAULT NULL COMMENT '角色名称',
  `role_desc` varchar(80) DEFAULT NULL COMMENT '角色备注',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_role_node_access
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_role_node_access`;
CREATE TABLE `mk_personnel_role_node_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` varchar(40) DEFAULT NULL COMMENT '角色ID',
  `node_id` varchar(40) DEFAULT NULL COMMENT '节点ID',
  `is_mobile` tinyint(6) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_user
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_user`;
CREATE TABLE `mk_personnel_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(50) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL COMMENT '用户姓名',
  `username` varchar(50) DEFAULT NULL COMMENT '用户名称',
  `password` varchar(80) DEFAULT NULL COMMENT '用户密码',
  `department_id` varchar(40) DEFAULT NULL COMMENT '所属部门ID',
  `jobs_id` varchar(40) DEFAULT NULL COMMENT '所属职位ID',
  `role_id` varchar(40) DEFAULT NULL COMMENT '所属角色ID',
  `mobile` varchar(20) DEFAULT NULL COMMENT '用户手机',
  `we_openid` varchar(40) DEFAULT NULL COMMENT '微信的识别码',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `user_sex` varchar(5) DEFAULT NULL,
  `user_family` varchar(255) DEFAULT NULL COMMENT '家庭情况',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_system_apply
-- ----------------------------
DROP TABLE IF EXISTS `mk_system_apply`;
CREATE TABLE `mk_system_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `originate` varchar(100) DEFAULT NULL,
  `event_key` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL COMMENT '公司名称',
  `address` varchar(200) DEFAULT NULL COMMENT '公司地址',
  `contacts` varchar(100) DEFAULT NULL COMMENT '联系人',
  `jobs` varchar(100) DEFAULT NULL COMMENT '职务',
  `mobile` varchar(100) DEFAULT NULL COMMENT '联系手机',
  `tencent_code` varchar(100) DEFAULT NULL COMMENT 'QQ 微信',
  `desc` text COMMENT '备注',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `open_id` varchar(200) DEFAULT NULL COMMENT '使用者id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8 COMMENT='试用申请';

-- ----------------------------
-- Table structure for mk_system_apply_chat
-- ----------------------------
DROP TABLE IF EXISTS `mk_system_apply_chat`;
CREATE TABLE `mk_system_apply_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '消息交流表',
  `guid` varchar(40) DEFAULT NULL,
  `msg_id` varchar(200) DEFAULT NULL COMMENT '回复消息的id',
  `info` text COMMENT '消息',
  `open_id` varchar(200) DEFAULT NULL COMMENT '用户open_id',
  `type` int(2) DEFAULT NULL COMMENT '1：管理员发送给用户信息，2：用户给管理员发送的信息',
  `status` int(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL COMMENT '消息创建时间',
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_system_log
-- ----------------------------
DROP TABLE IF EXISTS `mk_system_log`;
CREATE TABLE `mk_system_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(40) DEFAULT '',
  `url` varchar(255) DEFAULT NULL,
  `model_name` varchar(40) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `type` varchar(40) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `param` text,
  `content` text,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43539 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_system_sign
-- ----------------------------
DROP TABLE IF EXISTS `mk_system_sign`;
CREATE TABLE `mk_system_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appid` varchar(20) DEFAULT NULL,
  `openid` varchar(40) DEFAULT NULL COMMENT 'OpenId',
  `user_uuid` varchar(40) DEFAULT NULL COMMENT '签到人',
  `domain` varchar(40) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL COMMENT '签到说明',
  `image` varchar(100) DEFAULT NULL COMMENT '签到图片路径',
  `media_id` varchar(255) DEFAULT NULL,
  `latitude` varchar(40) DEFAULT NULL COMMENT '纬度',
  `longitude` varchar(40) DEFAULT NULL COMMENT '经度',
  `speed` varchar(40) DEFAULT NULL COMMENT '速度',
  `accuracy` varchar(40) DEFAULT NULL COMMENT '位置精度',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1287 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_system_sign_images
-- ----------------------------
DROP TABLE IF EXISTS `mk_system_sign_images`;
CREATE TABLE `mk_system_sign_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `md5` varchar(40) DEFAULT NULL,
  `media_id` varchar(200) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `status` int(6) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1275 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_temp_site_id
-- ----------------------------
DROP TABLE IF EXISTS `mk_temp_site_id`;
CREATE TABLE `mk_temp_site_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_update_version
-- ----------------------------
DROP TABLE IF EXISTS `mk_update_version`;
CREATE TABLE `mk_update_version` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `desc` text COMMENT '更新说明',
  `version` varchar(50) DEFAULT NULL COMMENT '版本管理列表',
  `create_time` int(15) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_we
-- ----------------------------
DROP TABLE IF EXISTS `mk_we`;
CREATE TABLE `mk_we` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(32) DEFAULT NULL COMMENT '微信名称，用于识别企业多个微信',
  `qrcode` int(11) DEFAULT NULL,
  `description` varchar(64) DEFAULT NULL,
  `we_type` tinyint(4) NOT NULL COMMENT '公众账号类型 1订阅号，2服务号 3应用号',
  `token` char(255) NOT NULL,
  `encodingaeskey` char(255) NOT NULL,
  `appid` char(255) NOT NULL,
  `appsecret` char(255) DEFAULT NULL,
  `mchid` int(11) DEFAULT NULL COMMENT '微信支付商户号',
  `pay_key` varchar(32) DEFAULT NULL COMMENT '微信支付',
  `apiclient_cert` varchar(255) DEFAULT NULL COMMENT '微信支付',
  `apiclient_key` varchar(255) DEFAULT NULL COMMENT '微信支付',
  `status` int(11) NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `rob` text,
  `app` text COMMENT '可以使用的微信app',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_we_binding_code
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_binding_code`;
CREATE TABLE `mk_we_binding_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_name` varchar(40) DEFAULT NULL,
  `binding_code` varchar(15) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=448 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_we_fans
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_fans`;
CREATE TABLE `mk_we_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `appid` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `openid` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `domain` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `unionid` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `nickname` varchar(191) DEFAULT '' COMMENT '昵称',
  `nickname_json` varchar(500) DEFAULT NULL,
  `remark` varchar(100) CHARACTER SET utf8 DEFAULT '' COMMENT '昵称',
  `headimgurl` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `sex` tinyint(3) unsigned DEFAULT '0' COMMENT '性别',
  `status` tinyint(4) DEFAULT '1' COMMENT '会员状态',
  `province` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `city` char(32) CHARACTER SET utf8 DEFAULT NULL,
  `subscribe` tinyint(4) DEFAULT NULL,
  `subscribe_qrcode` int(11) DEFAULT '0',
  `subscribe_time` int(11) DEFAULT NULL,
  `unsubscribe_time` int(11) DEFAULT NULL,
  `language` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `email` char(32) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户邮箱',
  `mobile` char(15) CHARACTER SET utf8 DEFAULT NULL COMMENT '用户手机',
  `describe` text CHARACTER SET utf8,
  `score` int(11) DEFAULT '0',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `tagid_list` text CHARACTER SET utf8,
  `create_time` int(11) DEFAULT NULL COMMENT '绑定一个内部员工的userid',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `name` (`nickname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1678 DEFAULT CHARSET=utf8mb4 COMMENT='会员表';

-- ----------------------------
-- Table structure for mk_we_fans_domain
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_fans_domain`;
CREATE TABLE `mk_we_fans_domain` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) NOT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `status` int(6) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_we_location
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_location`;
CREATE TABLE `mk_we_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` char(40) CHARACTER SET utf8 NOT NULL,
  `nickname` varchar(255) DEFAULT NULL COMMENT '昵称',
  `ToUserName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `FromUserName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `MsgType` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `Event` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `Latitude` varchar(40) CHARACTER SET utf8 DEFAULT NULL COMMENT '地理位置纬度',
  `Longitude` varchar(40) CHARACTER SET utf8 DEFAULT NULL COMMENT '地理位置经度',
  `Precision` varchar(40) CHARACTER SET utf8 DEFAULT NULL COMMENT '地理位置精度',
  `CreateTime` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '0 未处理 1 无效 2.差评 3. 好评',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5652 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for mk_we_menu
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_menu`;
CREATE TABLE `mk_we_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `appid` varchar(32) DEFAULT NULL,
  `title` varchar(64) DEFAULT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `sex` tinyint(4) DEFAULT NULL,
  `language` varchar(64) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `province` varchar(64) DEFAULT NULL,
  `city` varchar(64) DEFAULT NULL,
  `client_platform_type` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`language`) USING BTREE,
  KEY `sort` (`client_platform_type`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='微信菜单';

-- ----------------------------
-- Table structure for mk_we_menu_button
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_menu_button`;
CREATE TABLE `mk_we_menu_button` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `menu` text COMMENT '所属菜单',
  `name` varchar(20) DEFAULT NULL,
  `pid` varchar(40) DEFAULT NULL COMMENT '父按钮',
  `type` varchar(50) DEFAULT NULL COMMENT '按钮类型',
  `url` varchar(255) DEFAULT NULL,
  `key` varchar(255) DEFAULT NULL COMMENT '关键字',
  `sort` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`) USING BTREE,
  KEY `sort` (`sort`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='微信菜单';

-- ----------------------------
-- Table structure for mk_we_message
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_message`;
CREATE TABLE `mk_we_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` char(40) NOT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '昵称',
  `ToUserName` varchar(255) NOT NULL,
  `FromUserName` varchar(255) NOT NULL,
  `CreateTime` int(11) DEFAULT NULL,
  `MsgType` text NOT NULL,
  `MsgId` varchar(20) DEFAULT NULL,
  `Content` text CHARACTER SET utf8mb4,
  `PicUrl` varchar(255) DEFAULT NULL,
  `Event` varchar(100) DEFAULT NULL,
  `EventKey` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '0 未处理 1 无效 2.差评 3. 好评',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5754 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_we_template_message
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_template_message`;
CREATE TABLE `mk_we_template_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` char(40) NOT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '昵称',
  `ToUserName` varchar(255) NOT NULL,
  `CreateTime` int(11) DEFAULT NULL,
  `MsgType` text NOT NULL,
  `MsgID` varchar(20) DEFAULT NULL,
  `Content` text CHARACTER SET utf8mb4,
  `Event` varchar(100) DEFAULT NULL,
  `EventKey` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '0 未处理 1 无效 2.差评 3. 好评',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1124 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_web_mysql
-- ----------------------------
DROP TABLE IF EXISTS `mk_web_mysql`;
CREATE TABLE `mk_web_mysql` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `mysql_type` tinyint(6) DEFAULT NULL COMMENT '数据库类型 1 本机',
  `mysql_hostname` varchar(200) DEFAULT '127.0.0.1' COMMENT '数据库IP',
  `mysql_database` varchar(50) DEFAULT NULL COMMENT '数据库用户名',
  `mysql_username` varchar(50) DEFAULT NULL,
  `mysql_password` varchar(255) DEFAULT NULL COMMENT '数据库密码 加密值',
  `mysql_status` tinyint(6) DEFAULT NULL COMMENT '数据库状态 0 未生成  1 已运行 2 已暂停',
  `status` tinyint(6) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_web_servers
-- ----------------------------
DROP TABLE IF EXISTS `mk_web_servers`;
CREATE TABLE `mk_web_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) NOT NULL DEFAULT '',
  `server_name` varchar(40) DEFAULT NULL COMMENT '服务器名称',
  `server_number` varchar(40) DEFAULT NULL,
  `ip_inner` varchar(40) DEFAULT NULL COMMENT '内网IP',
  `ip_outer` varchar(255) DEFAULT NULL COMMENT '外网IP',
  `domain` varchar(255) DEFAULT NULL COMMENT '绑定域名',
  `status` smallint(6) DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`guid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='服务器数据表';

-- ----------------------------
-- Table structure for mk_web_shell_worker
-- ----------------------------
DROP TABLE IF EXISTS `mk_web_shell_worker`;
CREATE TABLE `mk_web_shell_worker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `worker_name` varchar(80) DEFAULT NULL COMMENT '任务名称',
  `handle_name` varchar(80) DEFAULT NULL COMMENT '命令名称',
  `server_id` varchar(40) DEFAULT NULL COMMENT '服务器ID',
  `server_number` varchar(50) DEFAULT NULL COMMENT '服务器编号',
  `site_id` varchar(40) DEFAULT NULL COMMENT '网站ID',
  `s1` varchar(255) DEFAULT NULL COMMENT '命令参数1',
  `s2` varchar(255) DEFAULT NULL,
  `s3` varchar(255) DEFAULT NULL,
  `s4` varchar(255) DEFAULT NULL,
  `s5` varchar(255) DEFAULT NULL,
  `s6` varchar(255) DEFAULT NULL,
  `s7` varchar(255) DEFAULT NULL,
  `s8` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_web_sites
-- ----------------------------
DROP TABLE IF EXISTS `mk_web_sites`;
CREATE TABLE `mk_web_sites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `site_code` int(11) DEFAULT NULL,
  `client_id` varchar(40) DEFAULT NULL,
  `server_id` varchar(40) DEFAULT NULL,
  `mysql_id` varchar(40) DEFAULT NULL COMMENT '数据库id',
  `domain` varchar(100) DEFAULT NULL COMMENT '主域名',
  `domains` varchar(255) DEFAULT NULL COMMENT '绑定域名',
  `vhost_dir` varchar(50) DEFAULT NULL COMMENT '自定义目录',
  `conn` int(11) DEFAULT '300' COMMENT '并发限制',
  `bw` int(11) DEFAULT '200' COMMENT '网速限制 KB/S',
  `site_status` int(11) DEFAULT NULL COMMENT '0 未创建  1 已创建 2 已停止',
  `status` int(6) DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for my_web_domains
-- ----------------------------
DROP TABLE IF EXISTS `my_web_domains`;
CREATE TABLE `my_web_domains` (
  `id` int(11) NOT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `domain_name` varchar(40) DEFAULT NULL COMMENT '域名名称',
  `domain_type` varchar(40) DEFAULT NULL COMMENT '域名类型',
  `status` smallint(6) DEFAULT '1',
  `create``_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
