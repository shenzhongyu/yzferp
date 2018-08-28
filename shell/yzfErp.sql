/*
Navicat MySQL Data Transfer

Source Server         : ali yzferp
Source Server Version : 50635
Source Host           : demo.mikkle.cn:3306
Source Database       : yzfErp

Target Server Type    : MYSQL
Target Server Version : 50635
File Encoding         : 65001

Date: 2017-12-04 09:21:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mk_budget_default_book
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_book`;
CREATE TABLE `mk_budget_default_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `default_book_style` varchar(255) DEFAULT NULL COMMENT '预算书名字',
  `default_book_com_name` varchar(255) DEFAULT NULL COMMENT '预算书的公司名字',
  `address` varchar(255) DEFAULT NULL COMMENT '公司地址',
  `telephone` varchar(100) DEFAULT NULL COMMENT '公司电话',
  `desc` longtext COMMENT '协议说明',
  `logo` varchar(255) DEFAULT NULL COMMENT '公司logo',
  `status` varchar(10) DEFAULT NULL,
  `budget_style` varchar(255) DEFAULT NULL COMMENT '设计风格',
  `zx_area` varchar(255) DEFAULT NULL COMMENT '装修面积',
  `gc_adress` varchar(255) DEFAULT NULL COMMENT '工程地址',
  `cont_name` varchar(255) DEFAULT NULL COMMENT '业主名字',
  `cont_pone` varchar(255) DEFAULT NULL COMMENT '业主电话',
  `sj_name` varchar(255) DEFAULT NULL COMMENT '设计师名字',
  `sj_tel` varchar(255) DEFAULT NULL COMMENT '设计师电话',
  `budget_type` varchar(2) DEFAULT '1' COMMENT '预算类型( 1 家装 2 工装)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_default_book_copy
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_book_copy`;
CREATE TABLE `mk_budget_default_book_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_number` int(11) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `default_book_style` varchar(255) DEFAULT NULL COMMENT '预算书名字',
  `default_book_com_name` varchar(255) DEFAULT NULL COMMENT '预算书的公司名字',
  `address` varchar(255) DEFAULT NULL COMMENT '公司地址',
  `telephone` varchar(100) DEFAULT NULL COMMENT '公司电话',
  `desc` longtext COMMENT '协议说明',
  `logo` varchar(255) DEFAULT NULL COMMENT '公司logo',
  `status` varchar(10) DEFAULT NULL,
  `budget_style` varchar(255) DEFAULT NULL COMMENT '设计风格',
  `zx_area` varchar(255) DEFAULT NULL COMMENT '装修面积',
  `gc_adress` varchar(255) DEFAULT NULL COMMENT '工程地址',
  `cont_name` varchar(255) DEFAULT NULL COMMENT '业主名字',
  `cont_pone` varchar(255) DEFAULT NULL COMMENT '业主电话',
  `sj_name` varchar(255) DEFAULT NULL COMMENT '设计师名字',
  `sj_tel` varchar(255) DEFAULT NULL COMMENT '设计师电话',
  `budget_type` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=190 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_default_book_number
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_book_number`;
CREATE TABLE `mk_budget_default_book_number` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_number` int(11) DEFAULT NULL,
  `project_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT '0.00' COMMENT '项目总价(含费率)',
  `project_id` varchar(40) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_default_material
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_material`;
CREATE TABLE `mk_budget_default_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(30) DEFAULT NULL,
  `old_id` varchar(40) DEFAULT NULL COMMENT '原来处于的位置',
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `de_pro_id` varchar(40) DEFAULT NULL COMMENT '属于关联的哪个guid（default_project 表）',
  `supplier_id` varchar(40) DEFAULT NULL COMMENT '供应商id',
  `material_name` varchar(100) DEFAULT NULL COMMENT '材料名称',
  `specifications` varchar(30) DEFAULT NULL COMMENT '规格',
  `material_pin` varchar(100) DEFAULT NULL COMMENT '品牌',
  `material_version` varchar(100) DEFAULT NULL COMMENT '材料型号',
  `material_stock` varchar(30) DEFAULT NULL COMMENT '库存',
  `unit_name` varchar(3) DEFAULT NULL COMMENT '单位',
  `category_id` varchar(40) DEFAULT NULL COMMENT '材料类别',
  `material_price` varchar(11) DEFAULT NULL COMMENT '售价',
  `inside_price` varchar(11) DEFAULT NULL COMMENT '内部价格',
  `floor_price` varchar(11) DEFAULT '0' COMMENT '底价',
  `material_desc` varchar(255) DEFAULT NULL COMMENT '材料描述',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `active_price` varchar(11) DEFAULT NULL COMMENT '活动价格',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_default_project
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_project`;
CREATE TABLE `mk_budget_default_project` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目id',
  `old_guid` varchar(40) DEFAULT NULL COMMENT '原来所在的表id(可用来判断是否存在)',
  `category_id` varchar(40) DEFAULT NULL COMMENT '类别',
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `pid` varchar(40) DEFAULT NULL COMMENT '属于哪个空间下',
  `name` varchar(255) DEFAULT NULL COMMENT '空间名或者主材名或者装饰项名字',
  `unit` varchar(30) DEFAULT NULL COMMENT '单位',
  `number` decimal(11,2) DEFAULT '0.00' COMMENT '数量',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `control_price` decimal(15,2) DEFAULT '0.00' COMMENT '内控单价',
  `xuhao` varchar(100) DEFAULT NULL COMMENT '编写的序号',
  `zc_dj` decimal(15,2) DEFAULT '0.00' COMMENT '主材单价',
  `zc_mlr` decimal(10,2) DEFAULT '0.00' COMMENT '主材毛利润',
  `fc_dj` decimal(15,2) DEFAULT '0.00' COMMENT '辅材单价',
  `fc_mlr` decimal(15,2) DEFAULT '0.00' COMMENT '辅材毛利润',
  `rg_dj` decimal(15,2) DEFAULT '0.00' COMMENT '人工单价',
  `rg_mlr` decimal(10,2) DEFAULT '0.00',
  `sh_xs` decimal(10,2) DEFAULT '0.00' COMMENT '损耗系数',
  `jx_xs` decimal(10,2) DEFAULT '0.00' COMMENT '机械系数',
  `desc` text COMMENT '说明描述',
  `type` varchar(10) DEFAULT NULL COMMENT '类型（判断装饰项目为 1 家装  2 工装  3 套餐  ）',
  `status` varchar(10) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL COMMENT '设计师名字',
  `user_tel` varchar(255) DEFAULT NULL COMMENT '设计师电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9112 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_default_project_copy
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_project_copy`;
CREATE TABLE `mk_budget_default_project_copy` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `book_number` int(11) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目id',
  `category_id` varchar(40) DEFAULT NULL COMMENT '类别',
  `old_guid` varchar(40) DEFAULT NULL COMMENT '原来所在的表id(可用来判断是否存在)',
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `pid` varchar(40) DEFAULT NULL COMMENT '属于哪个空间下',
  `name` varchar(255) DEFAULT NULL COMMENT '空间名或者主材名或者装饰项名字',
  `unit` varchar(30) DEFAULT NULL COMMENT '单位',
  `number` decimal(11,2) DEFAULT '0.00' COMMENT '数量',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `control_price` decimal(15,2) DEFAULT '0.00' COMMENT '内控单价',
  `xuhao` varchar(100) DEFAULT NULL COMMENT '编写的序号',
  `zc_dj` decimal(15,2) DEFAULT NULL COMMENT '主材单价',
  `zc_mlr` decimal(15,2) DEFAULT '0.00' COMMENT '主材毛利润',
  `fc_dj` decimal(15,2) DEFAULT '0.00' COMMENT '辅材单价',
  `fc_mlr` decimal(10,2) DEFAULT '0.00' COMMENT '辅材毛利润',
  `rg_dj` decimal(15,2) DEFAULT '0.00' COMMENT '人工单价',
  `rg_mlr` decimal(10,2) DEFAULT '0.00',
  `sh_xs` decimal(10,2) DEFAULT '0.00' COMMENT '损耗系数',
  `jx_xs` decimal(10,2) DEFAULT '0.00' COMMENT '机械系数',
  `desc` text COMMENT '说明描述',
  `type` varchar(10) DEFAULT NULL COMMENT '类型（判断装饰项目为 1 家装  2 工装  ）',
  `status` varchar(10) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL COMMENT '设计师名字',
  `user_tel` varchar(255) DEFAULT NULL COMMENT '设计师电话',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6607 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_default_rate
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_rate`;
CREATE TABLE `mk_budget_default_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `rate_default_name` varchar(255) DEFAULT NULL COMMENT '默认的固定费率名字',
  `rate_default_value` varchar(100) DEFAULT NULL COMMENT '默认的固定费率值',
  `rate_default_type` varchar(10) DEFAULT '*' COMMENT '默认费率的类型（+ - * /）',
  `status` varchar(10) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=685 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_default_rate_copy
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_default_rate_copy`;
CREATE TABLE `mk_budget_default_rate_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_number` int(11) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `rate_default_name` varchar(255) DEFAULT NULL COMMENT '默认的固定费率名字',
  `rate_default_value` varchar(100) DEFAULT NULL COMMENT '默认的固定费率值',
  `rate_default_type` varchar(10) DEFAULT '*' COMMENT '默认费率的类型（+ - * /）',
  `project_rate_price` decimal(10,2) DEFAULT '0.00' COMMENT '项目费用金额',
  `status` varchar(10) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=696 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_list_edit
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_list_edit`;
CREATE TABLE `mk_budget_list_edit` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `com_name` varchar(255) DEFAULT NULL COMMENT '公司名称',
  `address` varchar(100) DEFAULT NULL COMMENT '公司封面地址',
  `company_id` varchar(40) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `telephone` varchar(40) DEFAULT NULL COMMENT '封面电话',
  `desc` longtext COMMENT '协议说明',
  `logo` varchar(255) DEFAULT NULL COMMENT '公司logo',
  `style` varchar(100) DEFAULT NULL COMMENT '预算书名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_print_request
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_print_request`;
CREATE TABLE `mk_budget_print_request` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `company_id` varchar(40) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `project_id` varchar(40) DEFAULT NULL,
  `uuid_name` varchar(255) DEFAULT NULL COMMENT '申请人姓名',
  `uuid` varchar(40) DEFAULT NULL COMMENT '申请打印的人',
  `request_desc` varchar(255) DEFAULT NULL COMMENT '申请打印的原因',
  `examine_uuid_name` varchar(255) DEFAULT NULL COMMENT '审批人姓名',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核的人',
  `examine_desc` varchar(40) DEFAULT NULL COMMENT '审核的原因',
  `examine_status` varchar(10) DEFAULT '0' COMMENT '审核通过的判断(1为通过，-1为不通过，0为需要审核)',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_revise
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_revise`;
CREATE TABLE `mk_budget_revise` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `project_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '申请预算修改的人',
  `uuid_name` varchar(255) DEFAULT NULL COMMENT '申请人姓名',
  `request_desc` varchar(255) DEFAULT NULL COMMENT '申请打印的原因',
  `examine_uuid_name` varchar(255) DEFAULT NULL COMMENT '审批人姓名',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核的人',
  `examine_desc` varchar(40) DEFAULT NULL COMMENT '审核的原因',
  `examine_status` varchar(10) DEFAULT '0' COMMENT '审核通过的判断(1为通过，-1为不通过，0为需要审核)',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_sheet
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_sheet`;
CREATE TABLE `mk_budget_sheet` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `project_guid` varchar(40) DEFAULT NULL,
  `oldTemp_guid` varchar(40) DEFAULT NULL COMMENT '原先的预算空间模板ID',
  `new_name` varchar(255) DEFAULT NULL COMMENT '重新命名的空间名称',
  `budget_name` varchar(255) DEFAULT NULL COMMENT '原先预算空间名称',
  `budget_desc` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `company_id` varchar(40) DEFAULT NULL COMMENT '所属公司',
  `add_uuid` varchar(40) DEFAULT NULL COMMENT '创建预算的人',
  `status` varchar(10) DEFAULT NULL COMMENT '当前预算的状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_sheet_list_access
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_sheet_list_access`;
CREATE TABLE `mk_budget_sheet_list_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目ID',
  `budget_sheet_guid` varchar(40) DEFAULT NULL COMMENT '空间名称ID',
  `budget_id` varchar(40) DEFAULT NULL COMMENT '原先所属空间的ID',
  `material_id` varchar(40) DEFAULT NULL COMMENT '原先所在表的guid',
  `guid` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '材料名称',
  `unit` varchar(255) DEFAULT NULL COMMENT '单位',
  `unit_price` varchar(100) DEFAULT NULL COMMENT '主材单价',
  `unit_profit` varchar(100) DEFAULT NULL COMMENT '主材毛利润',
  `auxiliary_unit` varchar(100) DEFAULT NULL COMMENT '辅材单价',
  `auxiliary_profit` varchar(100) DEFAULT NULL COMMENT '辅材毛利润',
  `artificial_price` varchar(100) DEFAULT NULL COMMENT '人工单价',
  `artificial_profit` varchar(100) DEFAULT NULL COMMENT '人工毛利润',
  `loss_coefficient` varchar(100) DEFAULT NULL COMMENT '损耗系数',
  `mechanical_coefficient` varchar(100) DEFAULT NULL COMMENT '机械费系数',
  `status` varchar(255) DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL COMMENT '材料类别',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `xuhao` varchar(255) DEFAULT NULL COMMENT '序号',
  `specifications` varchar(255) DEFAULT NULL COMMENT '规格（已停用）',
  `pinpai` varchar(255) DEFAULT NULL COMMENT '品牌（已停用）',
  `version` varchar(255) DEFAULT NULL COMMENT '型号（已停用）',
  `type` varchar(10) DEFAULT NULL COMMENT '2为 主材  1为基装',
  `number` varchar(100) DEFAULT NULL COMMENT '数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_space_style
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_space_style`;
CREATE TABLE `mk_budget_space_style` (
  `id` int(40) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '当类型为1是  该值为模板所属人',
  `name` varchar(255) DEFAULT NULL COMMENT '模板名字',
  `desc` text COMMENT '备注',
  `status` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT '模板类型（1为个人，2为公司）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_space_template
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_space_template`;
CREATE TABLE `mk_budget_space_template` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL COMMENT '类别',
  `old_guid` varchar(40) DEFAULT NULL COMMENT '原来所在的表id(可用来判断是否存在)',
  `company_id` varchar(40) DEFAULT NULL,
  `space_id` varchar(40) DEFAULT NULL COMMENT '属于那个模板下面的内容',
  `pid` varchar(40) DEFAULT NULL COMMENT '属于哪个空间下',
  `name` varchar(255) DEFAULT NULL COMMENT '空间名或者主材名或者装饰项名字',
  `unit` varchar(30) DEFAULT NULL COMMENT '单位',
  `number` decimal(10,2) DEFAULT '0.00' COMMENT '数量',
  `price` decimal(15,2) DEFAULT '0.00' COMMENT '单价',
  `control_price` decimal(15,2) DEFAULT '0.00' COMMENT '内控单价',
  `xuhao` varchar(100) DEFAULT NULL COMMENT '编写的序号',
  `zc_dj` decimal(15,2) DEFAULT '0.00' COMMENT '主材单价',
  `zc_mlr` decimal(15,2) DEFAULT '0.00' COMMENT '主材毛利润',
  `fc_dj` decimal(15,2) DEFAULT '0.00' COMMENT '辅材单价',
  `fc_mlr` decimal(15,2) DEFAULT '0.00' COMMENT '辅材毛利润',
  `rg_dj` decimal(15,2) DEFAULT '0.00' COMMENT '人工单价',
  `rg_mlr` decimal(15,2) DEFAULT '0.00',
  `sh_xs` decimal(15,2) DEFAULT '0.00' COMMENT '损耗系数',
  `jx_xs` decimal(15,2) DEFAULT '0.00' COMMENT '机械系数',
  `desc` text COMMENT '说明描述',
  `type` varchar(10) DEFAULT '0' COMMENT '类型（判断装饰项目为 1 家装  2 工装  ）',
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2745 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_budget_space_template_copy
-- ----------------------------
DROP TABLE IF EXISTS `mk_budget_space_template_copy`;
CREATE TABLE `mk_budget_space_template_copy` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL COMMENT '类别',
  `old_guid` varchar(40) DEFAULT NULL COMMENT '原来所在的表id(可用来判断是否存在)',
  `company_id` varchar(40) DEFAULT NULL,
  `pid` varchar(40) DEFAULT NULL COMMENT '属于哪个空间下',
  `name` varchar(255) DEFAULT NULL COMMENT '空间名或者主材名或者装饰项名字',
  `unit` varchar(30) DEFAULT NULL COMMENT '单位',
  `number` decimal(20,2) DEFAULT '0.00' COMMENT '数量',
  `price` decimal(15,2) DEFAULT '0.00' COMMENT '单价',
  `control_price` decimal(15,2) DEFAULT '0.00',
  `xuhao` varchar(100) DEFAULT NULL COMMENT '编写的序号',
  `zc_dj` decimal(15,2) DEFAULT '0.00' COMMENT '主材单价',
  `zc_mlr` decimal(15,2) DEFAULT '0.00' COMMENT '主材毛利润',
  `fc_dj` decimal(15,2) DEFAULT '0.00' COMMENT '辅材单价',
  `fc_mlr` decimal(15,2) DEFAULT '0.00' COMMENT '辅材毛利润',
  `rg_dj` decimal(15,2) DEFAULT '0.00' COMMENT '人工单价',
  `rg_mlr` decimal(15,2) DEFAULT '0.00',
  `sh_xs` decimal(15,2) DEFAULT '0.00' COMMENT '损耗系数',
  `jx_xs` decimal(15,2) DEFAULT '0.00' COMMENT '机械系数',
  `desc` text COMMENT '说明描述',
  `type` varchar(10) DEFAULT '0' COMMENT '类型（判断装饰项目为 1 家装  2 工装  ）',
  `status` varchar(10) DEFAULT NULL,
  `space_type` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1534 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_build_contract_rint
-- ----------------------------
DROP TABLE IF EXISTS `mk_build_contract_rint`;
CREATE TABLE `mk_build_contract_rint` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目的发包打印表--项目',
  `guid` varchar(40) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL COMMENT '打印出来的编号',
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` varchar(15) DEFAULT NULL,
  `category_id` text COMMENT '发包的工程id',
  `rate_id` text COMMENT '打印的计算费率',
  `uuid` varchar(40) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_build_project_category_photo
-- ----------------------------
DROP TABLE IF EXISTS `mk_build_project_category_photo`;
CREATE TABLE `mk_build_project_category_photo` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目工程类别的施工图片',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` varchar(15) DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL,
  `photo_name` varchar(255) DEFAULT NULL,
  `photo_address` varchar(255) DEFAULT NULL,
  `photo_desc` varchar(255) DEFAULT NULL,
  `photo_m` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  `add_uuid_name` varchar(255) DEFAULT NULL,
  `add_uuid` varchar(40) DEFAULT NULL COMMENT '数据录入者',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_build_project_category_time
-- ----------------------------
DROP TABLE IF EXISTS `mk_build_project_category_time`;
CREATE TABLE `mk_build_project_category_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目的工程阶段施工时间',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '数据录入者',
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` varchar(15) DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL COMMENT '项目工程阶段',
  `build_time` int(15) DEFAULT NULL COMMENT '施工开始时间',
  `build_days` int(15) DEFAULT NULL COMMENT '施工总天数',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  `type` int(5) DEFAULT '0' COMMENT '用来判断该施工阶段是否完成.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_build_project_percentage
-- ----------------------------
DROP TABLE IF EXISTS `mk_build_project_percentage`;
CREATE TABLE `mk_build_project_percentage` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目阶段的领款百分比',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `cetegory_uuid` varchar(40) DEFAULT NULL COMMENT '这个款属于谁的',
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` varchar(15) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '数据录入者',
  `category_id` varchar(40) DEFAULT NULL COMMENT '项目阶段id',
  `category_price` decimal(15,2) DEFAULT '0.00',
  `value` decimal(12,4) DEFAULT '0.0000' COMMENT '百分比',
  `desc` varchar(255) DEFAULT NULL,
  `status` varchar(11) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_build_project_time
-- ----------------------------
DROP TABLE IF EXISTS `mk_build_project_time`;
CREATE TABLE `mk_build_project_time` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目的施工总时间',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '数据录入者',
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` varchar(15) DEFAULT NULL,
  `build_time` int(15) DEFAULT NULL COMMENT '施工开始时间',
  `build_days` int(15) DEFAULT NULL COMMENT '施工总天数',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_build_supervision
-- ----------------------------
DROP TABLE IF EXISTS `mk_build_supervision`;
CREATE TABLE `mk_build_supervision` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '工程监理的表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '数据录入者',
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目id',
  `book_number` varchar(15) DEFAULT NULL,
  `type` varchar(10) DEFAULT '0' COMMENT '用于判断该监理的项目是否完工(0 没有  1 完成 )',
  `user_id` varchar(40) DEFAULT NULL COMMENT '项目的工程监理',
  `status` varchar(5) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_build_supervision_tem
-- ----------------------------
DROP TABLE IF EXISTS `mk_build_supervision_tem`;
CREATE TABLE `mk_build_supervision_tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '工程经理的表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '数据录入者',
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目id',
  `category_id` varchar(40) DEFAULT NULL COMMENT '项目工程类别',
  `book_number` varchar(15) DEFAULT NULL,
  `user_id` varchar(40) DEFAULT NULL COMMENT '项目的工程经理',
  `status` varchar(5) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT '类型 （1 发包经理  2 普通的施工人员） ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='文件表';

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
) ENGINE=InnoDB AUTO_INCREMENT=148 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_contract_field
-- ----------------------------
DROP TABLE IF EXISTS `mk_contract_field`;
CREATE TABLE `mk_contract_field` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目合同附件',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `photo_name` varchar(255) DEFAULT NULL,
  `photo_address` varchar(255) DEFAULT NULL,
  `photo_m` varchar(255) DEFAULT NULL,
  `photo_desc` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_contract_tem
-- ----------------------------
DROP TABLE IF EXISTS `mk_contract_tem`;
CREATE TABLE `mk_contract_tem` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '合同模板表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '创建合同模板的用户',
  `title` varchar(255) DEFAULT NULL COMMENT '合同标题',
  `content` longtext COMMENT '合同内容',
  `mobile` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_default_rate
-- ----------------------------
DROP TABLE IF EXISTS `mk_default_rate`;
CREATE TABLE `mk_default_rate` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `loss_coefficient` varchar(10) DEFAULT NULL COMMENT '默认的损耗率',
  `mechanical_coefficient` varchar(10) DEFAULT NULL COMMENT '默认的机械费率',
  `status` varchar(10) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '费率名',
  `artificial_profit` varchar(100) DEFAULT NULL COMMENT '默认的人工毛利润',
  `auxiliary_profit` varchar(100) DEFAULT NULL COMMENT '默认的辅材毛利润',
  `unit_profit` varchar(100) DEFAULT NULL COMMENT '主材毛利润',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=543 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_design_apply_turning
-- ----------------------------
DROP TABLE IF EXISTS `mk_design_apply_turning`;
CREATE TABLE `mk_design_apply_turning` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '设计合同签订后申请转入工程部',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `book_number` int(15) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(40) DEFAULT NULL,
  `transfer_desc` varchar(255) DEFAULT NULL,
  `transfer_uuid` varchar(40) DEFAULT NULL,
  `transfer_name` varchar(255) DEFAULT NULL COMMENT '申请人姓名',
  `examine_name` varchar(255) DEFAULT NULL,
  `examine_uuid` varchar(40) DEFAULT NULL,
  `examine_desc` varchar(255) DEFAULT NULL,
  `examine_status` int(10) DEFAULT '0',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_design_sheet
-- ----------------------------
DROP TABLE IF EXISTS `mk_design_sheet`;
CREATE TABLE `mk_design_sheet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `design_type` varchar(10) DEFAULT NULL COMMENT '进度类型( 1量房进度 2设计进度 )',
  `uuid` varchar(40) DEFAULT NULL COMMENT '负责人',
  `design_phase` varchar(100) DEFAULT NULL COMMENT '设计项目名称',
  `start_data` varchar(200) DEFAULT NULL COMMENT '开始日期',
  `expected_days` varchar(200) DEFAULT NULL COMMENT '预计天数',
  `design_desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` varchar(10) DEFAULT NULL COMMENT ' 1 =》 未完工    2 =》 已完工',
  `acceptance_date` varchar(100) DEFAULT NULL COMMENT '验收日期=开始日期+预计天数',
  `completion_date` varchar(100) DEFAULT NULL COMMENT '完工日期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_design_sheet_check
-- ----------------------------
DROP TABLE IF EXISTS `mk_design_sheet_check`;
CREATE TABLE `mk_design_sheet_check` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `sheet_id` varchar(40) DEFAULT NULL,
  `apply_uuid` varchar(40) DEFAULT NULL,
  `apply_name` varchar(255) DEFAULT NULL COMMENT '申请验收人',
  `apply_desc` varchar(255) DEFAULT NULL COMMENT '申请备注',
  `check_uuid` varchar(40) DEFAULT NULL,
  `check_name` varchar(255) DEFAULT NULL COMMENT '验收人',
  `check_status` varchar(10) DEFAULT '0' COMMENT '审核状态',
  `check_desc` varchar(255) DEFAULT NULL COMMENT '验收备注',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_design_sheet_field
-- ----------------------------
DROP TABLE IF EXISTS `mk_design_sheet_field`;
CREATE TABLE `mk_design_sheet_field` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `uuid_name` varchar(255) DEFAULT NULL COMMENT '上传图片的用户名字',
  `design_id` varchar(40) DEFAULT NULL COMMENT '对应的设计进度的ID (      )',
  `field_name` varchar(255) DEFAULT NULL COMMENT '文件名称',
  `field_address` varchar(255) DEFAULT NULL COMMENT '文件路径',
  `create_time` int(11) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `field_m` varchar(255) DEFAULT NULL COMMENT '文件的md5值',
  `field_desc` text COMMENT '文件说明',
  `update_time` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_design_sheet_photo
-- ----------------------------
DROP TABLE IF EXISTS `mk_design_sheet_photo`;
CREATE TABLE `mk_design_sheet_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `uuid_name` varchar(255) DEFAULT NULL COMMENT '上传图片的用户名字',
  `design_id` varchar(40) DEFAULT NULL COMMENT '对应的设计进度的ID (      )',
  `photo_name` varchar(255) DEFAULT NULL COMMENT '图片名称',
  `photo_address` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `create_time` int(11) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `photo_m` varchar(255) DEFAULT NULL COMMENT '图片的md5值',
  `photo_desc` text COMMENT '图片说明',
  `update_time` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_material_address_access
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_material_address_access`;
CREATE TABLE `mk_engin_material_address_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `material_id` varchar(40) DEFAULT NULL COMMENT '记录需要购买的材料',
  `receipt_id` varchar(40) DEFAULT NULL COMMENT '储存收货地址id',
  `number` decimal(10,2) DEFAULT NULL COMMENT '需要采购的数量',
  `price` decimal(15,2) DEFAULT '0.00' COMMENT '材料的价格',
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_date
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_date`;
CREATE TABLE `mk_engin_project_build_date` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '项目的施工时间表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '存一下谁添加的',
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` int(15) DEFAULT NULL,
  `project_address` varchar(255) DEFAULT NULL COMMENT '工程地址',
  `project_name` varchar(255) DEFAULT NULL COMMENT '工程名称',
  `build_time` int(15) DEFAULT NULL COMMENT '施工开始时间',
  `build_days` int(15) DEFAULT NULL COMMENT '施工总天数',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_type
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_type`;
CREATE TABLE `mk_engin_project_build_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '项目的施工时间表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `build_id` varchar(40) DEFAULT NULL COMMENT '阶段类型',
  `build_name` varchar(255) DEFAULT NULL COMMENT '阶段名字',
  `uuid` varchar(40) DEFAULT NULL COMMENT '存一下谁添加的',
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` int(15) DEFAULT NULL,
  `build_time` int(15) DEFAULT NULL COMMENT '施工开始时间',
  `build_days` int(15) DEFAULT NULL COMMENT '施工总天数',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `type` int(10) DEFAULT '0' COMMENT '该施工阶段处在的位置(0 未完成  1 申请等待验收 2 已完成)',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_type_audit
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_type_audit`;
CREATE TABLE `mk_engin_project_build_type_audit` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '项目施工阶段的完工申请',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `book_number` varchar(15) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `build_id` varchar(40) DEFAULT NULL COMMENT '对应的施工阶段',
  `build_name` varchar(255) DEFAULT NULL,
  `apply_uuid` varchar(40) DEFAULT NULL COMMENT '申请人',
  `apply_name` varchar(40) DEFAULT NULL,
  `apply_desc` text COMMENT '申请原因',
  `examine_status` int(5) DEFAULT '0' COMMENT '审核状态',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_name` varchar(255) DEFAULT NULL,
  `examine_desc` text,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_type_day
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_type_day`;
CREATE TABLE `mk_engin_project_build_type_day` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '阶段所对应的天数',
  `guid` varchar(40) DEFAULT NULL,
  `build_type_id` varchar(40) DEFAULT NULL,
  `day_name` varchar(255) DEFAULT NULL COMMENT '第一天（第二天.....）',
  `day_time` int(15) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15202 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_type_day_a_photo
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_type_day_a_photo`;
CREATE TABLE `mk_engin_project_build_type_day_a_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '每天的施工图片',
  `guid` varchar(40) DEFAULT NULL,
  `build_con_id` varchar(40) DEFAULT NULL COMMENT '对应的施工内容的id',
  `add_uuid` varchar(40) DEFAULT NULL,
  `add_uuid_name` varchar(255) DEFAULT NULL COMMENT '添加人',
  `photo_name` varchar(255) DEFAULT NULL COMMENT '图片名称',
  `photo_address` varchar(255) DEFAULT NULL COMMENT '图片地址',
  `photo_m` varchar(255) DEFAULT NULL,
  `photo_desc` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_type_day_access
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_type_day_access`;
CREATE TABLE `mk_engin_project_build_type_day_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '添加的人',
  `uuid_name` varchar(255) DEFAULT NULL COMMENT '添加人的姓名',
  `day_id` varchar(40) DEFAULT NULL COMMENT '对应的天数',
  `data_id` varchar(40) DEFAULT NULL COMMENT '对应的装饰项id',
  `data_name` varchar(255) DEFAULT NULL COMMENT '装饰项名字',
  `data_user_num` int(10) DEFAULT NULL COMMENT '施工人数',
  `data_desc` text COMMENT '装饰项工艺说明',
  `desc` text COMMENT '工作备注',
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_type_remind
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_type_remind`;
CREATE TABLE `mk_engin_project_build_type_remind` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `build_type_id` varchar(40) DEFAULT NULL COMMENT '施工阶段id',
  `uuid` varchar(40) DEFAULT NULL COMMENT '添加提醒的人',
  `uuid_name` varchar(255) DEFAULT NULL,
  `remind_time` int(15) DEFAULT NULL COMMENT '提醒时间',
  `remind_content` varchar(255) DEFAULT NULL COMMENT '提醒内容',
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_build_type_user
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_build_type_user`;
CREATE TABLE `mk_engin_project_build_type_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `build_type_id` varchar(40) DEFAULT NULL COMMENT '施工阶段id',
  `build_uuid` varchar(40) DEFAULT NULL COMMENT '施工阶段的负责人',
  `build_uuid_name` varchar(255) DEFAULT NULL,
  `build_uuid_department` varchar(255) DEFAULT NULL COMMENT '部门',
  `build_uuid_jobs` varchar(255) DEFAULT NULL COMMENT '职位',
  `mobile` varchar(20) DEFAULT NULL,
  `type` int(5) DEFAULT '0' COMMENT '1为负责人  ',
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_completion_audit
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_completion_audit`;
CREATE TABLE `mk_engin_project_completion_audit` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '项目完工申请表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `apply_uuid` varchar(40) DEFAULT NULL COMMENT '申请人',
  `apply_name` varchar(255) DEFAULT NULL,
  `apply_desc` text COMMENT '申请原因',
  `examine_status` int(5) DEFAULT '0' COMMENT '申请状态',
  `examine_uuid` varchar(40) DEFAULT NULL,
  `examine_name` varchar(255) DEFAULT NULL,
  `examine_desc` text,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_detailed_list_number
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_detailed_list_number`;
CREATE TABLE `mk_engin_project_detailed_list_number` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `book_number` int(15) DEFAULT NULL,
  `number` int(15) DEFAULT NULL COMMENT '项目选购材料的清单编号',
  `project_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT '0.00' COMMENT '项目总价(含费率)',
  `project_id` varchar(40) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_material
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_material`;
CREATE TABLE `mk_engin_project_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '施工项目下的装饰项目需要的材料',
  `guid` varchar(40) DEFAULT NULL,
  `old_id` varchar(40) DEFAULT NULL COMMENT '原来所在的材料表id，为了判断是否是同一个材料',
  `project_id` varchar(40) DEFAULT NULL,
  `project_data_id` varchar(40) DEFAULT NULL COMMENT '项目下的装饰项目id',
  `book_number` int(15) DEFAULT NULL COMMENT '合同编号',
  `company_id` varchar(40) DEFAULT NULL,
  `supplier_id` varchar(40) DEFAULT NULL COMMENT '供应商id',
  `number` int(11) DEFAULT NULL COMMENT '材料数量',
  `material_name` varchar(100) DEFAULT NULL COMMENT '材料名称',
  `specifications` varchar(30) DEFAULT NULL COMMENT '规格',
  `material_pin` varchar(100) DEFAULT NULL COMMENT '品牌',
  `material_version` varchar(100) DEFAULT NULL COMMENT '材料型号',
  `material_stock` varchar(30) DEFAULT NULL COMMENT '库存',
  `unit_name` varchar(3) DEFAULT NULL COMMENT '单位',
  `category_id` varchar(40) DEFAULT NULL COMMENT '材料类别',
  `material_price` decimal(11,2) DEFAULT '0.00' COMMENT '售价',
  `inside_price` decimal(11,2) DEFAULT '0.00' COMMENT '内部价格',
  `floor_price` decimal(11,2) DEFAULT '0.00' COMMENT '底价',
  `active_price` decimal(11,2) DEFAULT '0.00' COMMENT '活动价格',
  `material_desc` varchar(255) DEFAULT NULL COMMENT '材料描述',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=384 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_material_address
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_material_address`;
CREATE TABLE `mk_engin_project_material_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '记录项目购买的材料收货人和地址',
  `dj_number` varchar(30) DEFAULT NULL COMMENT '自然单据号',
  `guid` varchar(40) DEFAULT NULL,
  `apply_uuid` varchar(40) DEFAULT NULL COMMENT '申请人',
  `apply_name` varchar(255) DEFAULT NULL COMMENT '申请人姓名',
  `company_id` varchar(40) DEFAULT NULL,
  `collect_type` varchar(40) DEFAULT NULL COMMENT '款项类型',
  `collect_type_name` varchar(255) DEFAULT NULL,
  `collect_name` varchar(255) DEFAULT NULL COMMENT '款项名称',
  `invoice_value` int(10) DEFAULT NULL COMMENT '是否带发票(0,1)',
  `invoice_price` decimal(10,2) DEFAULT '0.00' COMMENT '发票金额',
  `collect_date` int(15) DEFAULT NULL COMMENT '计划发货时间',
  `consignee` varchar(40) DEFAULT NULL COMMENT '收货人id',
  `consignee_name` varchar(255) DEFAULT NULL COMMENT '收货人姓名',
  `contact_number` varchar(20) DEFAULT NULL COMMENT '收货人联系电话',
  `address` varchar(255) DEFAULT NULL COMMENT '收货地址',
  `desc` varchar(255) DEFAULT NULL COMMENT '说明',
  `project_id` varchar(40) DEFAULT NULL COMMENT '对应的项目',
  `number` int(15) DEFAULT NULL COMMENT 'book_number编号',
  `price` decimal(15,2) DEFAULT '0.00' COMMENT '该单据的金额',
  `status` int(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `examine_status` int(5) DEFAULT '0' COMMENT '审核状态',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核的人',
  `examine_uuid_name` varchar(255) DEFAULT NULL COMMENT '审核人姓名',
  `examine_desc` varchar(255) DEFAULT NULL COMMENT '审核说明',
  `accepatance_uuid` varchar(40) DEFAULT NULL COMMENT '验收人',
  `accepatance_uuid_name` varchar(255) DEFAULT NULL,
  `accepatance_status` varchar(255) DEFAULT '0',
  `accepatance_desc` varchar(255) DEFAULT NULL COMMENT '验收说明',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_material_apply_audit
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_material_apply_audit`;
CREATE TABLE `mk_engin_project_material_apply_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资金库',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` int(15) DEFAULT NULL,
  `apply_id` varchar(40) DEFAULT NULL COMMENT '材料选购申请的申请人',
  `apply_name` varchar(255) DEFAULT NULL,
  `apply_desc` varchar(255) DEFAULT NULL COMMENT '申请说明',
  `examine_status` varchar(10) DEFAULT '0' COMMENT '审核状态',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_name` varchar(40) DEFAULT NULL,
  `examine_desc` varchar(255) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_engin_project_material_copy
-- ----------------------------
DROP TABLE IF EXISTS `mk_engin_project_material_copy`;
CREATE TABLE `mk_engin_project_material_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '施工项目下的装饰项目需要的材料',
  `guid` varchar(40) DEFAULT NULL,
  `old_id` varchar(40) DEFAULT NULL COMMENT '原来所在的材料表id，为了判断是否是同一个材料',
  `project_id` varchar(40) DEFAULT NULL,
  `project_data_id` varchar(40) DEFAULT NULL COMMENT '项目下的装饰项目id',
  `book_number` int(15) DEFAULT NULL COMMENT '合同编号',
  `list_number` int(15) DEFAULT NULL COMMENT '对应的清单编号',
  `company_id` varchar(40) DEFAULT NULL,
  `supplier_id` varchar(40) DEFAULT NULL COMMENT '供应商id',
  `number` decimal(11,2) DEFAULT '0.00' COMMENT '材料数量',
  `buy_number` decimal(11,2) DEFAULT '0.00' COMMENT '已采购量',
  `nobuy_number` decimal(11,2) DEFAULT '0.00' COMMENT '提交采购申请的数量',
  `material_name` varchar(100) DEFAULT NULL COMMENT '材料名称',
  `specifications` varchar(30) DEFAULT NULL COMMENT '规格',
  `material_pin` varchar(100) DEFAULT NULL COMMENT '品牌',
  `material_version` varchar(100) DEFAULT NULL COMMENT '材料型号',
  `material_stock` varchar(30) DEFAULT NULL COMMENT '库存',
  `unit_name` varchar(3) DEFAULT NULL COMMENT '单位',
  `category_id` varchar(40) DEFAULT NULL COMMENT '材料类别',
  `material_price` decimal(11,2) DEFAULT '0.00' COMMENT '售价',
  `inside_price` decimal(11,2) DEFAULT '0.00' COMMENT '内部价格',
  `floor_price` decimal(11,2) DEFAULT '0.00' COMMENT '底价',
  `active_price` decimal(11,2) DEFAULT '0.00' COMMENT '活动价格',
  `material_desc` varchar(255) DEFAULT NULL COMMENT '材料描述',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=394 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_excellent_opus
-- ----------------------------
DROP TABLE IF EXISTS `mk_excellent_opus`;
CREATE TABLE `mk_excellent_opus` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '设计师的个人优秀作品',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `photo_name` varchar(255) DEFAULT NULL,
  `photo_address` varchar(255) DEFAULT NULL,
  `photo_desc` varchar(255) DEFAULT NULL,
  `photo_m` varchar(255) DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_finance_bank
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_bank`;
CREATE TABLE `mk_finance_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资金库',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `payment_id` varchar(40) DEFAULT NULL COMMENT '收付款方式',
  `name` varchar(255) DEFAULT NULL COMMENT '账号名称',
  `uuid` varchar(40) DEFAULT NULL COMMENT '资金账号管理者',
  `uuid_name` varchar(255) DEFAULT NULL COMMENT '资金管理者姓名',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `balance_price` decimal(15,2) DEFAULT '0.00' COMMENT '资金账号余额',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='资金库';

-- ----------------------------
-- Table structure for mk_finance_bank_log
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_bank_log`;
CREATE TABLE `mk_finance_bank_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资金库',
  `guid` varchar(40) DEFAULT NULL,
  `bank_guid` varchar(40) DEFAULT NULL COMMENT '所属账户的',
  `log_desc` varchar(255) DEFAULT NULL COMMENT '账户的说明变更',
  `name` varchar(255) DEFAULT NULL COMMENT '账户的名字变更',
  `bank_uuid` varchar(40) DEFAULT NULL COMMENT '账户的管理者变更',
  `bank_payStyle` varchar(40) DEFAULT NULL COMMENT '账户的支付方式变更',
  `price` varchar(255) DEFAULT NULL COMMENT '账户的余额变更',
  `uuid` varchar(40) DEFAULT NULL COMMENT '变更账户信息的人',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_finance_bank_log_copy
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_bank_log_copy`;
CREATE TABLE `mk_finance_bank_log_copy` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资金库',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_finance_bank_transaction_record
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_bank_transaction_record`;
CREATE TABLE `mk_finance_bank_transaction_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '资金库',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` int(11) DEFAULT NULL,
  `bank_id` varchar(40) DEFAULT NULL COMMENT '对应的资金账户',
  `finance_mode` tinyint(6) NOT NULL COMMENT '资金方式 0创建 1 进 2出  3更改',
  `income_price` decimal(15,2) DEFAULT '0.00' COMMENT '收入金额',
  `expenditure_price` decimal(15,2) DEFAULT '0.00' COMMENT '支出金额',
  `balance_price` decimal(15,2) DEFAULT '0.00' COMMENT '剩余金额',
  `desc` varchar(255) DEFAULT NULL COMMENT '该记录的备注',
  `uuid` varchar(40) DEFAULT NULL COMMENT '记录的人',
  `type` varchar(10) DEFAULT NULL COMMENT '收入或支出的类型 0 财务操作 1定金 2工程款 3材料款 4 退款 5 收款撤销',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_finance_collect_plan
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_collect_plan`;
CREATE TABLE `mk_finance_collect_plan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `company_id` varchar(40) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `collect_plan_name` varchar(255) DEFAULT NULL COMMENT '收款计划名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_finance_collect_plan_access
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_collect_plan_access`;
CREATE TABLE `mk_finance_collect_plan_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `collect_plan_id` varchar(40) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '收款计划子项名称',
  `collect_value` decimal(4,2) DEFAULT '0.00' COMMENT '收款的比例',
  `collect_days` int(10) DEFAULT NULL COMMENT '收款时间的间隔天数',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_finance_collect_style
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_collect_style`;
CREATE TABLE `mk_finance_collect_style` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `company_id` varchar(40) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `collect_name` varchar(255) DEFAULT NULL COMMENT '收款类型名称',
  `collect_value` decimal(4,2) DEFAULT NULL COMMENT '收款比例',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_finance_payment_style
-- ----------------------------
DROP TABLE IF EXISTS `mk_finance_payment_style`;
CREATE TABLE `mk_finance_payment_style` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `company_id` varchar(40) DEFAULT NULL,
  `guid` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '付款类型名称',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material
-- ----------------------------
DROP TABLE IF EXISTS `mk_material`;
CREATE TABLE `mk_material` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `supplier_id` varchar(40) DEFAULT NULL COMMENT '供应商id',
  `material_name` varchar(100) DEFAULT NULL COMMENT '材料名称',
  `specifications` varchar(30) DEFAULT NULL COMMENT '规格',
  `material_pin` varchar(100) DEFAULT NULL COMMENT '品牌',
  `material_version` varchar(100) DEFAULT NULL COMMENT '材料型号',
  `material_stock` int(30) DEFAULT '0' COMMENT '库存',
  `unit_name` varchar(3) DEFAULT NULL COMMENT '单位',
  `category_id` varchar(40) DEFAULT NULL COMMENT '材料类别',
  `material_price` varchar(11) DEFAULT NULL COMMENT '售价',
  `inside_price` varchar(11) DEFAULT NULL COMMENT '内部价格',
  `floor_price` varchar(11) DEFAULT '0' COMMENT '底价',
  `material_desc` varchar(255) DEFAULT NULL COMMENT '材料描述',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `active_price` varchar(11) DEFAULT NULL COMMENT '活动价格',
  `type` varchar(10) DEFAULT NULL COMMENT '材料类型 ( 1 主材 2 辅材)',
  `show_mark` varchar(255) DEFAULT NULL COMMENT '展厅展示(1 是  -1 否)',
  `photo_m` varchar(255) DEFAULT NULL,
  `material_photo` varchar(255) DEFAULT NULL COMMENT '材料图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=591 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_category
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_category`;
CREATE TABLE `mk_material_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `category_name` varchar(100) DEFAULT NULL COMMENT '材料类别名称',
  `category_desc` varchar(255) DEFAULT NULL COMMENT '类别说明',
  `status` varchar(10) DEFAULT NULL,
  `pid` varchar(40) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_data_categories_style
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_data_categories_style`;
CREATE TABLE `mk_material_data_categories_style` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `categories_name` varchar(200) DEFAULT NULL COMMENT '数据类别名称',
  `categories_desc` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_data_item
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_data_item`;
CREATE TABLE `mk_material_data_item` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `data_id` varchar(40) DEFAULT NULL COMMENT '数据的类别ID',
  `company_id` varchar(40) DEFAULT NULL,
  `dataitem_name` varchar(100) DEFAULT NULL COMMENT '数据项名称',
  `item_unit_name` varchar(20) DEFAULT NULL COMMENT '单位',
  `item_specifications` varchar(100) DEFAULT NULL COMMENT '规格',
  `item_pin` varchar(200) DEFAULT NULL COMMENT '品牌',
  `unit_price_sum` varchar(100) DEFAULT NULL COMMENT '总和的单价',
  `mater_price` varchar(100) DEFAULT NULL COMMENT '主材单价',
  `auxiliary_price` varchar(100) DEFAULT NULL COMMENT '辅材单价',
  `user_price` varchar(100) DEFAULT NULL COMMENT '人工单价',
  `base_price` varchar(100) DEFAULT NULL COMMENT '底价',
  `loss_ratio` varchar(30) DEFAULT NULL COMMENT '损耗比例',
  `loss_style` varchar(10) DEFAULT NULL COMMENT '损耗类型',
  `item_desc` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `material_desc` varchar(255) DEFAULT NULL COMMENT '材料描述',
  `status` varchar(10) DEFAULT NULL,
  `base_id` varchar(40) DEFAULT NULL COMMENT '所属定额类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_list_edit
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_list_edit`;
CREATE TABLE `mk_material_list_edit` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `xuhao` varchar(255) DEFAULT NULL COMMENT '序号',
  `guid` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '材料名称',
  `company_id` varchar(40) DEFAULT NULL,
  `category_id` varchar(40) DEFAULT NULL COMMENT '装修工程类别',
  `unit` varchar(255) DEFAULT NULL COMMENT '单位',
  `unit_price` varchar(100) DEFAULT NULL COMMENT '主材单价',
  `unit_profit` varchar(100) DEFAULT NULL COMMENT '主材毛利润',
  `auxiliary_unit` varchar(100) DEFAULT NULL COMMENT '辅材单价',
  `auxiliary_profit` varchar(100) DEFAULT NULL COMMENT '辅材毛利润',
  `artificial_price` varchar(100) DEFAULT NULL COMMENT '人工单价',
  `artificial_profit` varchar(100) DEFAULT NULL COMMENT '人工毛利润',
  `loss_coefficient` varchar(100) DEFAULT NULL COMMENT '主材损耗系数',
  `mechanical_coefficient` varchar(100) DEFAULT NULL COMMENT '机械费系数',
  `status` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL COMMENT '备注说明',
  `specifications` varchar(255) DEFAULT NULL COMMENT '规格（已停用）',
  `pinpai` varchar(255) DEFAULT NULL COMMENT '品牌（已停用）',
  `version` varchar(255) DEFAULT NULL COMMENT '型号（已停用）',
  `type` decimal(2,0) DEFAULT '1' COMMENT '类型（判断装饰项目为 1 家装  2 工装  3套餐 ）',
  `package_id` varchar(40) DEFAULT '1' COMMENT '套餐定额的有内容( 1 不是套餐类型      ) 套餐种类下级的一个类型',
  `package_type` varchar(40) DEFAULT NULL COMMENT '套餐种类（1 不属于）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2500 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_package_category
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_package_category`;
CREATE TABLE `mk_material_package_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `package_id` varchar(40) DEFAULT NULL COMMENT '套餐类型',
  `package_name` varchar(200) DEFAULT NULL COMMENT '数据类别名称',
  `package_desc` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_package_type
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_package_type`;
CREATE TABLE `mk_material_package_type` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `type_name` varchar(200) DEFAULT NULL COMMENT '数据类别名称',
  `type_desc` varchar(255) DEFAULT NULL COMMENT '套餐种类',
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_rate_item
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_rate_item`;
CREATE TABLE `mk_material_rate_item` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `rate_name` varchar(255) DEFAULT NULL COMMENT '费率项名称',
  `rate_value` varchar(10) DEFAULT NULL COMMENT '费率值',
  `rate_desc` varchar(255) DEFAULT NULL COMMENT '费率项备注',
  `status` varchar(10) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL COMMENT '费率类型（1为固定费率，2为可选费率）',
  `budget_type` varchar(2) DEFAULT '1' COMMENT '费率的所属( 1 家装  2 工装 )',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_supplier
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_supplier`;
CREATE TABLE `mk_material_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `supplier_name` varchar(100) DEFAULT NULL COMMENT '供应商名称',
  `supplier_brand` varchar(255) DEFAULT NULL COMMENT '供应商品牌',
  `supplier_discount` varchar(40) DEFAULT NULL COMMENT '供应商折扣',
  `profit_margin` varchar(10) DEFAULT NULL COMMENT '利润率',
  `supplier_desc` varchar(255) DEFAULT NULL COMMENT '供应商说明',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_template_budget
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_template_budget`;
CREATE TABLE `mk_material_template_budget` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `budget_name` varchar(255) DEFAULT NULL COMMENT '空间名称',
  `budget_desc` varchar(255) DEFAULT NULL COMMENT '空间备注',
  `template_id` varchar(40) DEFAULT NULL COMMENT '所属的模板类型',
  `template_style` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_template_budget_access
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_template_budget_access`;
CREATE TABLE `mk_material_template_budget_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `budget_id` varchar(40) DEFAULT NULL COMMENT '空间名称id',
  `material_id` varchar(40) DEFAULT NULL COMMENT '数据项id或材料id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_material_template_style
-- ----------------------------
DROP TABLE IF EXISTS `mk_material_template_style`;
CREATE TABLE `mk_material_template_style` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `template_name` varchar(100) DEFAULT NULL COMMENT '模板类型',
  `company_id` varchar(40) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `template_desc` varchar(200) DEFAULT NULL COMMENT '模板类型备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_complaint
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_complaint`;
CREATE TABLE `mk_office_complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `status` varchar(5) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL COMMENT '客户姓名',
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目ID',
  `customer_phone` varchar(100) DEFAULT NULL COMMENT '客户联系电话',
  `project_uuid` varchar(40) DEFAULT NULL COMMENT '项目的原项目经理',
  `complaint_content` varchar(255) DEFAULT NULL COMMENT '维修投诉内容',
  `uuid` varchar(40) DEFAULT NULL COMMENT '维修单录入人',
  `processing_status` varchar(10) DEFAULT '-1' COMMENT '处理状态',
  `complaint_uuid` varchar(40) DEFAULT NULL COMMENT '维修处理人',
  `complaint_desc` varchar(255) DEFAULT NULL COMMENT '完毕回访',
  `complaint_id` varchar(30) DEFAULT NULL COMMENT '维修单编号',
  `time` varchar(30) DEFAULT NULL COMMENT '登记日期',
  `end_time` varchar(30) DEFAULT NULL COMMENT '验收日期',
  `company_id` varchar(40) DEFAULT NULL COMMENT '公司的',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_leave
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_leave`;
CREATE TABLE `mk_office_leave` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `leave_username` varchar(255) DEFAULT NULL,
  `leave_name` varchar(40) DEFAULT NULL COMMENT '请假人',
  `company_id` varchar(40) DEFAULT NULL COMMENT '公司',
  `company_name` varchar(255) DEFAULT NULL,
  `department_id` varchar(40) DEFAULT NULL COMMENT '部门',
  `department_name` varchar(255) DEFAULT NULL,
  `jobs_id` varchar(40) DEFAULT NULL COMMENT '职位',
  `jobs_name` varchar(255) DEFAULT NULL,
  `leave_type` varchar(20) DEFAULT NULL COMMENT '请假类型',
  `leave_content` varchar(80) DEFAULT NULL COMMENT '请假事由',
  `start_time` varchar(20) DEFAULT NULL COMMENT '开始日期',
  `timelog` varchar(30) DEFAULT NULL,
  `end_time` varchar(20) DEFAULT NULL COMMENT '结束时间',
  `manager_content` varchar(255) DEFAULT NULL COMMENT '审核意见',
  `dep_manager` varchar(10) DEFAULT '0' COMMENT '部门经理是否审核',
  `admin_supervisor` varchar(10) DEFAULT '0' COMMENT '行政主管是否审核',
  `total_manager` varchar(10) DEFAULT '0' COMMENT '总经理是否审核',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_manager_line
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_manager_line`;
CREATE TABLE `mk_office_manager_line` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '汇报人',
  `company_id` varchar(40) DEFAULT NULL,
  `department_id` varchar(40) DEFAULT NULL,
  `jobs_id` varchar(40) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '汇报主题',
  `content` varchar(255) DEFAULT NULL COMMENT '汇报内容',
  `status` varchar(5) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `manager_status` varchar(10) DEFAULT NULL COMMENT '审阅状态',
  `manager_desc` varchar(255) DEFAULT NULL COMMENT '审阅的建议',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_notice
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_notice`;
CREATE TABLE `mk_office_notice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '通知公告内容',
  `type` varchar(10) DEFAULT NULL COMMENT '公告类型(1 公司 2 部门)',
  `uuid` varchar(40) DEFAULT NULL COMMENT '写入人',
  `company_id` varchar(40) DEFAULT NULL,
  `department_id` varchar(40) DEFAULT NULL,
  `jobs_id` varchar(40) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_notice_access
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_notice_access`;
CREATE TABLE `mk_office_notice_access` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `notice_id` varchar(40) DEFAULT NULL,
  `department_id` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_plan
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_plan`;
CREATE TABLE `mk_office_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '计划执行人',
  `company_id` varchar(40) DEFAULT NULL,
  `department_id` varchar(40) DEFAULT NULL,
  `jobs_id` varchar(40) DEFAULT NULL,
  `plan_name` varchar(50) DEFAULT NULL COMMENT '计划名称',
  `plan_grade` varchar(10) DEFAULT NULL COMMENT '计划等级',
  `start_time` varchar(20) DEFAULT NULL COMMENT '计划开始时间',
  `end_time` varchar(20) DEFAULT NULL COMMENT '计划结束时间',
  `plan_content` varchar(255) DEFAULT NULL COMMENT '计划内容',
  `manager_content` varchar(255) DEFAULT NULL COMMENT '审核备注',
  `work_schedule` varchar(10) DEFAULT NULL COMMENT '工作进度',
  `dep_manager` varchar(10) DEFAULT NULL COMMENT '审核状态',
  `status` varchar(10) DEFAULT NULL,
  `plan_desc` varchar(255) DEFAULT NULL COMMENT '计划备注',
  `update_time` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_plan_log
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_plan_log`;
CREATE TABLE `mk_office_plan_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `plan_id` varchar(40) DEFAULT NULL COMMENT '计划Guid',
  `uuid` varchar(40) DEFAULT NULL COMMENT '添加人',
  `log_content` varchar(255) DEFAULT NULL COMMENT '每日一记内容',
  `log_desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `log_time` varchar(30) DEFAULT NULL COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_sign
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_sign`;
CREATE TABLE `mk_office_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `user_uuid` varchar(40) DEFAULT NULL COMMENT '签到人',
  `company_id` varchar(40) DEFAULT NULL,
  `department_id` varchar(40) DEFAULT NULL,
  `jobs_id` varchar(40) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `latitude` varchar(40) DEFAULT NULL COMMENT '纬度',
  `longitude` varchar(40) DEFAULT NULL COMMENT '经度',
  `speed` varchar(40) DEFAULT NULL COMMENT '速度',
  `accuracy` varchar(40) DEFAULT NULL COMMENT '位置精度',
  `message` varchar(255) DEFAULT NULL COMMENT '签到说明',
  `image` varchar(100) DEFAULT NULL COMMENT '签到图片路径',
  `photo_m` varchar(100) DEFAULT NULL COMMENT 'md5',
  `user_name` varchar(100) DEFAULT NULL,
  `type` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1211 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_supplies
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_supplies`;
CREATE TABLE `mk_office_supplies` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '办公用品表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL COMMENT '用品名称',
  `number` decimal(10,2) DEFAULT '0.00' COMMENT '数量',
  `unit` varchar(255) DEFAULT NULL COMMENT '单位',
  `price` decimal(10,2) DEFAULT '0.00' COMMENT '单价',
  `desc` varchar(255) DEFAULT NULL COMMENT '备注描述',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_supplies_apply
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_supplies_apply`;
CREATE TABLE `mk_office_supplies_apply` (
  `id` int(15) NOT NULL DEFAULT '0',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '申请领用的用户',
  `supplies_id` varchar(40) DEFAULT NULL COMMENT '办公用品id',
  `supplies_name` varchar(255) DEFAULT NULL,
  `number` decimal(10,2) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL COMMENT '领取说明',
  `examine_status` varchar(255) DEFAULT NULL,
  `examine_uuid` varchar(40) DEFAULT NULL,
  `examine_desc` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_vehicle
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_vehicle`;
CREATE TABLE `mk_office_vehicle` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '车辆库',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL COMMENT '车牌号',
  `name` varchar(255) DEFAULT NULL COMMENT '品牌型号',
  `type` varchar(255) DEFAULT NULL COMMENT '类型',
  `color` varchar(255) DEFAULT NULL COMMENT '颜色',
  `photo` varchar(255) DEFAULT NULL COMMENT '车辆图片',
  `photo_m` varchar(255) DEFAULT NULL,
  `desc` varchar(255) DEFAULT NULL COMMENT '备注',
  `use_status` int(2) DEFAULT '0' COMMENT '使用状态（0 可使用 1 使用中）',
  `status` int(15) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_office_vehicle_apply
-- ----------------------------
DROP TABLE IF EXISTS `mk_office_vehicle_apply`;
CREATE TABLE `mk_office_vehicle_apply` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '用车申请表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `vehicle_id` varchar(40) DEFAULT NULL COMMENT '车辆id',
  `uuid` varchar(40) DEFAULT NULL COMMENT '用车申请人',
  `start_time` int(15) DEFAULT NULL COMMENT '用车时间',
  `end_time` int(15) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL COMMENT '用车事由',
  `amount` int(10) DEFAULT NULL COMMENT '随车人数',
  `desc` varchar(255) DEFAULT NULL COMMENT '用车备注',
  `examine_status` varchar(2) DEFAULT NULL COMMENT '申请状态',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_desc` varchar(255) DEFAULT NULL COMMENT '审核原因',
  `status` varchar(15) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_company
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_company`;
CREATE TABLE `mk_personnel_company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_name` varchar(80) DEFAULT NULL COMMENT '公司名称',
  `company_desc` varchar(255) DEFAULT NULL COMMENT '公司备注',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_department
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_department`;
CREATE TABLE `mk_personnel_department` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL COMMENT '公司ID',
  `department_type` varchar(80) DEFAULT NULL COMMENT '部门类型',
  `department_name` varchar(80) DEFAULT NULL COMMENT '部门名称',
  `department_desc` varchar(255) DEFAULT NULL COMMENT '部门备注',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_jobs
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_jobs`;
CREATE TABLE `mk_personnel_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL COMMENT '所属公司ID',
  `department_type` varchar(40) DEFAULT NULL COMMENT '部门类型',
  `department_id` varchar(40) DEFAULT NULL COMMENT '所属部门ID',
  `jobs_name` varchar(60) DEFAULT NULL COMMENT '职位名称',
  `jobs_desc` varchar(255) DEFAULT NULL COMMENT '职位备注',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=257 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=9261 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_node
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_node`;
CREATE TABLE `mk_personnel_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `pid` varchar(40) DEFAULT NULL COMMENT '父ID',
  `node_name` varchar(50) DEFAULT NULL COMMENT '节点名称',
  `node_desc` varchar(50) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=849 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_role
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_role`;
CREATE TABLE `mk_personnel_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `role_name` varchar(60) DEFAULT NULL COMMENT '角色名称',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `role_desc` varchar(80) DEFAULT NULL COMMENT '角色备注',
  `company_id` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=295957 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_role_tem
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_role_tem`;
CREATE TABLE `mk_personnel_role_tem` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '角色权限模板',
  `guid` varchar(40) DEFAULT NULL,
  `role_id` varchar(40) DEFAULT NULL COMMENT '设置为模板的ID',
  `role_name` varchar(255) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_trackstatus
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_trackstatus`;
CREATE TABLE `mk_personnel_trackstatus` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `guid` varchar(30) DEFAULT NULL,
  `strack_name` varchar(25) DEFAULT NULL COMMENT '类别名称',
  `font_color` varchar(20) DEFAULT NULL COMMENT '字体颜色',
  `department_type` varchar(20) DEFAULT NULL COMMENT '部门类型',
  `statistics` varchar(20) DEFAULT NULL COMMENT '是否统计',
  `sort_value` varchar(20) DEFAULT NULL COMMENT '排序值',
  `status` varchar(20) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

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
  `company_id` varchar(40) DEFAULT NULL COMMENT '所属公司ID',
  `department_type` varchar(20) DEFAULT NULL COMMENT '部门类型',
  `department_id` varchar(40) DEFAULT NULL COMMENT '所属部门ID',
  `jobs_id` varchar(40) DEFAULT NULL COMMENT '所属职位ID',
  `role_id` varchar(40) DEFAULT NULL COMMENT '所属角色ID',
  `binding_code` varchar(15) DEFAULT NULL COMMENT '绑定码',
  `mobile` varchar(20) DEFAULT NULL COMMENT '用户手机',
  `we_openid` varchar(40) DEFAULT NULL COMMENT '微信的识别码',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '更新时间',
  `user_sex` varchar(5) DEFAULT NULL,
  `user_family` varchar(255) DEFAULT NULL COMMENT '家庭情况',
  `photo` varchar(255) DEFAULT NULL COMMENT '员工图片',
  `photo_m` varchar(255) DEFAULT NULL,
  `xue_l` varchar(255) DEFAULT NULL COMMENT '学历证',
  `sfz_z` varchar(255) DEFAULT NULL COMMENT '身份证（正面）',
  `sfz_f` varchar(255) DEFAULT NULL COMMENT '身份证（反面）',
  `gz_jl` varchar(255) DEFAULT NULL COMMENT '工作简历',
  `desc` varchar(255) DEFAULT NULL COMMENT '座右铭',
  `hide` int(2) DEFAULT '1' COMMENT '是否在优秀团队显示( -1 不显示  ,1 显示)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=320 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_personnel_user_info
-- ----------------------------
DROP TABLE IF EXISTS `mk_personnel_user_info`;
CREATE TABLE `mk_personnel_user_info` (
  `uuid` int(10) NOT NULL AUTO_INCREMENT,
  `userinfo_name` varchar(50) DEFAULT NULL COMMENT '姓名',
  `job_number` varchar(50) DEFAULT NULL COMMENT '工号',
  `department_type` varchar(50) DEFAULT NULL COMMENT '部门类型',
  `department_id` varchar(80) DEFAULT NULL COMMENT '所属部门',
  `jobs_id` varchar(80) DEFAULT NULL COMMENT '所属职位',
  `birth_time` int(80) DEFAULT NULL COMMENT '出生日期',
  `sex` tinyint(3) DEFAULT NULL COMMENT '性别',
  `entry_time` int(80) DEFAULT NULL COMMENT '入职日期',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号码',
  `emergency_contact` varchar(40) DEFAULT NULL COMMENT '紧急联系人',
  `contact_phone` varchar(30) DEFAULT NULL COMMENT '联系人电话',
  `qq_number` varchar(20) DEFAULT NULL COMMENT 'QQ账号',
  `we_chat` varchar(20) DEFAULT NULL COMMENT '微信号',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `bank_name` varchar(20) DEFAULT NULL COMMENT '开户行名称',
  `bank_number` varchar(25) DEFAULT NULL COMMENT '开户行账号',
  `id_url` varchar(50) DEFAULT NULL COMMENT '身份证存放URL',
  `diploma_url` varchar(50) DEFAULT NULL COMMENT '毕业证存放URL',
  `certificate_url` varchar(50) DEFAULT NULL COMMENT '资格证存放URL',
  `resume_url` varchar(50) DEFAULT NULL COMMENT '个人简历URL',
  `contract_url` varchar(50) DEFAULT NULL COMMENT '劳动合同URL',
  `status` smallint(6) DEFAULT NULL COMMENT '状态',
  `create_time` int(30) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(30) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project
-- ----------------------------
DROP TABLE IF EXISTS `mk_project`;
CREATE TABLE `mk_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(50) DEFAULT NULL,
  `uuid` varchar(30) DEFAULT NULL,
  `company_id` varchar(30) DEFAULT NULL COMMENT '所属公司',
  `department_id` varchar(30) DEFAULT NULL COMMENT '业务阶段归属部门',
  `design_department` varchar(40) DEFAULT NULL COMMENT '设计阶段归属的部门',
  `project_name` varchar(50) DEFAULT NULL COMMENT '项目名称',
  `expected_duration` varchar(50) DEFAULT NULL COMMENT '预计总工期',
  `decoration_grade` varchar(30) DEFAULT NULL COMMENT '装修档次',
  `project_budget` varchar(30) DEFAULT NULL COMMENT '工程预算',
  `decoration_area` varchar(50) DEFAULT NULL COMMENT '装修面积',
  `color_orientation` varchar(30) DEFAULT '' COMMENT '色彩取向',
  `decoration_style` varchar(30) DEFAULT NULL COMMENT '装修风格',
  `decoration_type` varchar(40) DEFAULT NULL COMMENT '装修类型',
  `customer_source` varchar(40) DEFAULT NULL COMMENT '客户来源',
  `project_description` varchar(255) DEFAULT NULL COMMENT '项目要求',
  `status` varchar(10) DEFAULT NULL COMMENT '状态( 1 正常  -1 删除   -2 废单)',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `feedback_stage` varchar(10) DEFAULT '1' COMMENT '项目当前所属阶段',
  `project_address` varchar(255) DEFAULT NULL COMMENT '项目地址',
  `payment_phase` varchar(100) DEFAULT NULL COMMENT '项目的付款阶段( 1 已付定金    )',
  `engin_status` int(10) DEFAULT '0' COMMENT '项目的施工阶段(0 待建  1 在建  2 完工 )',
  `project_type` varchar(2) DEFAULT NULL COMMENT '项目录入的状态（0 市场部 1 客服部）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=598 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_audit
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_audit`;
CREATE TABLE `mk_project_audit` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `into_department_id` varchar(40) DEFAULT NULL COMMENT '从业务部转入到哪个设计部',
  `into_department_name` varchar(255) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目id',
  `transfer_uuid` varchar(40) DEFAULT NULL COMMENT '转部申请人',
  `transfer_desc` varchar(255) DEFAULT NULL COMMENT '转部说明',
  `transfer_status` varchar(10) DEFAULT NULL COMMENT '转部状态',
  `examine_status` varchar(10) DEFAULT NULL COMMENT '审核状态',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_desc` varchar(255) DEFAULT NULL COMMENT '审核说明',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `transfer_type` varchar(10) DEFAULT NULL COMMENT '转部类型( 1 业务  2  设计)',
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=288 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_building
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_building`;
CREATE TABLE `mk_project_building` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `guid` varchar(30) DEFAULT NULL,
  `project_guid` varchar(30) DEFAULT NULL COMMENT '项目ID',
  `building_name` varchar(30) DEFAULT NULL COMMENT '楼盘名称',
  `building_adress` varchar(30) DEFAULT NULL COMMENT '楼盘地址',
  `building_price` varchar(30) DEFAULT NULL COMMENT '楼盘均价',
  `room_number` varchar(60) DEFAULT NULL COMMENT '房产信息',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(15) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_collect_photo
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_collect_photo`;
CREATE TABLE `mk_project_collect_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `price_id` varchar(40) DEFAULT NULL COMMENT '归属哪个款项的图片',
  `uuid` varchar(255) DEFAULT NULL,
  `uuid_name` varchar(255) DEFAULT NULL,
  `photo_name` varchar(255) DEFAULT NULL COMMENT '图片名字',
  `photo_address` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `photo_desc` text,
  `photo_m` varchar(255) DEFAULT NULL,
  `photo_type` varchar(2) DEFAULT NULL COMMENT '图片属于付款类型还是收款类型(1 收 2 支)',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_collect_plan
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_collect_plan`;
CREATE TABLE `mk_project_collect_plan` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` int(15) DEFAULT NULL COMMENT '合同编号',
  `name` varchar(255) DEFAULT NULL COMMENT '收款名称',
  `collect_value` decimal(5,2) DEFAULT NULL COMMENT '收款比例',
  `collect_days` int(10) DEFAULT NULL COMMENT '收款时间间隔',
  `desc` varchar(255) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_collect_price
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_collect_price`;
CREATE TABLE `mk_project_collect_price` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '项目交纳的金额',
  `guid` varchar(40) DEFAULT NULL COMMENT '项目交纳的定金',
  `company_id` varchar(40) DEFAULT NULL,
  `book_number` int(11) DEFAULT NULL,
  `collect_uuid` varchar(40) DEFAULT NULL COMMENT '款项申请人',
  `collect_uuid_name` varchar(255) DEFAULT NULL COMMENT '款项申请人姓名',
  `project_id` varchar(40) DEFAULT NULL,
  `collect_type` varchar(40) DEFAULT NULL COMMENT '收款类型id',
  `collect_type_name` varchar(255) DEFAULT NULL COMMENT '收款类型名称',
  `number` varchar(50) DEFAULT NULL COMMENT '自然单据号',
  `collect_name` varchar(255) DEFAULT NULL COMMENT '款项名称',
  `collect_price` decimal(15,2) DEFAULT '0.00' COMMENT '计划缴纳的款项金额',
  `price` decimal(15,2) DEFAULT NULL COMMENT '剩余金额',
  `invoice_value` varchar(2) DEFAULT '0' COMMENT '是否开发票  0无  1 开发票',
  `invoice_price` decimal(15,2) DEFAULT '0.00' COMMENT '发票金额',
  `collect_date` varchar(30) DEFAULT NULL COMMENT '计划收款日期',
  `cashier_uuid` varchar(40) DEFAULT NULL COMMENT '出纳人',
  `cashier_uuid_name` varchar(255) DEFAULT NULL COMMENT '出纳人姓名',
  `desc` varchar(255) DEFAULT NULL COMMENT '款项说明',
  `examine_status` varchar(10) DEFAULT '0' COMMENT '审核状态 ( 1通过 -1不通过  当不通过修改信息后重置 )',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_uuid_name` varchar(255) DEFAULT NULL COMMENT '审核人姓名',
  `examine_desc` varchar(255) DEFAULT NULL COMMENT '审核说明',
  `collect_status` varchar(10) DEFAULT '0' COMMENT '收款状态',
  `payment_id` varchar(40) DEFAULT NULL COMMENT '支付方式',
  `bank_id` varchar(40) DEFAULT NULL COMMENT '资金库',
  `collect_desc` varchar(255) DEFAULT NULL COMMENT '收款说明',
  `collected_uuid` varchar(40) DEFAULT NULL COMMENT '收款人',
  `collected_uuid_name` varchar(255) DEFAULT NULL COMMENT '收款人姓名',
  `status` int(10) DEFAULT NULL COMMENT '1 为 实际收款  2 为收款申请   3 为 撤销收款',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=464 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_collect_price_pay
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_collect_price_pay`;
CREATE TABLE `mk_project_collect_price_pay` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '项目交纳的金额',
  `guid` varchar(40) DEFAULT NULL COMMENT '项目交纳的定金',
  `old_guid` varchar(40) DEFAULT NULL COMMENT '用于查看详细或者判断该款项是否有存在的了',
  `company_id` varchar(40) DEFAULT NULL,
  `book_number` int(11) DEFAULT NULL,
  `collect_uuid` varchar(40) DEFAULT NULL COMMENT '款项申请人',
  `collect_uuid_name` varchar(255) DEFAULT NULL COMMENT '款项申请人姓名',
  `project_id` varchar(40) DEFAULT NULL,
  `collect_type` varchar(40) DEFAULT NULL COMMENT '付款类型id',
  `collect_type_name` varchar(255) DEFAULT NULL COMMENT '付款类型名称',
  `number` varchar(50) DEFAULT NULL COMMENT '自然单据号',
  `collect_name` varchar(255) DEFAULT NULL COMMENT '款项名称',
  `collect_price` decimal(15,2) DEFAULT '0.00' COMMENT '计划要付款的金额',
  `price` decimal(15,2) DEFAULT '0.00' COMMENT '剩余金额',
  `invoice_value` varchar(2) DEFAULT '0' COMMENT '是否开发票  0无  1 开发票',
  `invoice_price` decimal(15,2) DEFAULT '0.00' COMMENT '发票金额',
  `collect_date` varchar(30) DEFAULT NULL COMMENT '付款日期',
  `desc` varchar(255) DEFAULT NULL COMMENT '款项说明',
  `examine_status` varchar(10) DEFAULT '0' COMMENT '审核状态 ( 1通过 -1不通过  当不通过修改信息后重置 )',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_uuid_name` varchar(255) DEFAULT NULL COMMENT '审核人姓名',
  `examine_desc` varchar(255) DEFAULT NULL COMMENT '审核说明',
  `collect_status` varchar(10) DEFAULT '0' COMMENT '付款状态',
  `payment_id` varchar(40) DEFAULT NULL COMMENT '支付方式',
  `bank_id` varchar(40) DEFAULT NULL COMMENT '资金库',
  `collect_desc` varchar(255) DEFAULT NULL COMMENT '付款说明',
  `collected_uuid` varchar(40) DEFAULT NULL COMMENT '付款人',
  `collected_uuid_name` varchar(255) DEFAULT NULL COMMENT '付款人姓名',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL COMMENT '付款时间',
  `accepatance_uuid` varchar(40) DEFAULT NULL COMMENT '验收人',
  `accepatance_uuid_name` varchar(255) DEFAULT NULL COMMENT '验收人姓名',
  `accepatance_desc` varchar(255) DEFAULT NULL COMMENT '验收说明',
  `accepatance_status` varchar(255) DEFAULT '0' COMMENT '验收状态',
  `type` varchar(5) DEFAULT '0' COMMENT '类型（1为 工程领款(用来判断是否存在)  2为退款申请）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_collect_price_refund
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_collect_price_refund`;
CREATE TABLE `mk_project_collect_price_refund` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '项目交纳的金额',
  `guid` varchar(40) DEFAULT NULL COMMENT '项目交纳的定金',
  `old_guid` varchar(40) DEFAULT NULL COMMENT '用于查看详细或者判断该款项是否有存在的了',
  `company_id` varchar(40) DEFAULT NULL,
  `book_number` int(11) DEFAULT NULL,
  `collect_uuid` varchar(40) DEFAULT NULL COMMENT '款项申请人',
  `collect_uuid_name` varchar(255) DEFAULT NULL COMMENT '款项申请人姓名',
  `project_id` varchar(40) DEFAULT NULL,
  `collect_type` varchar(40) DEFAULT NULL COMMENT '付款类型id',
  `collect_type_name` varchar(255) DEFAULT NULL COMMENT '付款类型名称',
  `number` varchar(50) DEFAULT NULL COMMENT '自然单据号',
  `collect_name` varchar(255) DEFAULT NULL COMMENT '款项名称',
  `collect_price` decimal(15,2) DEFAULT '0.00' COMMENT '计划要付款的金额',
  `price` decimal(15,2) DEFAULT '0.00' COMMENT '剩余金额',
  `invoice_value` varchar(2) DEFAULT '0' COMMENT '是否开发票  0无  1 开发票',
  `invoice_price` decimal(15,2) DEFAULT '0.00' COMMENT '发票金额',
  `collect_date` varchar(30) DEFAULT NULL COMMENT '付款日期',
  `desc` varchar(255) DEFAULT NULL COMMENT '款项说明',
  `examine_status` varchar(10) DEFAULT '0' COMMENT '审核状态 ( 1通过 -1不通过  当不通过修改信息后重置 )',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_uuid_name` varchar(255) DEFAULT NULL COMMENT '审核人姓名',
  `examine_desc` varchar(255) DEFAULT NULL COMMENT '审核说明',
  `collect_status` varchar(10) DEFAULT '0' COMMENT '付款状态',
  `payment_id` varchar(40) DEFAULT NULL COMMENT '支付方式',
  `bank_id` varchar(40) DEFAULT NULL COMMENT '资金库',
  `collect_desc` varchar(255) DEFAULT NULL COMMENT '付款说明',
  `collected_uuid` varchar(40) DEFAULT NULL COMMENT '付款人',
  `collected_uuid_name` varchar(255) DEFAULT NULL COMMENT '付款人姓名',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL COMMENT '付款时间',
  `accepatance_uuid` varchar(40) DEFAULT NULL COMMENT '验收人',
  `accepatance_uuid_name` varchar(255) DEFAULT NULL COMMENT '验收人姓名',
  `accepatance_desc` varchar(255) DEFAULT NULL COMMENT '验收说明',
  `accepatance_status` varchar(255) DEFAULT '0' COMMENT '验收状态',
  `type` varchar(5) DEFAULT '0' COMMENT '类型（1为 工程领款(用来判断是否存在)  2为退款申请）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_contacts
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_contacts`;
CREATE TABLE `mk_project_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uuid` varchar(30) DEFAULT NULL,
  `guid` varchar(30) DEFAULT NULL,
  `project_guid` varchar(30) DEFAULT NULL COMMENT '项目ID',
  `contact_name` varchar(30) DEFAULT NULL COMMENT '客户姓名',
  `contact_number` varchar(20) DEFAULT NULL COMMENT '客户电话',
  `sex` varchar(10) DEFAULT NULL COMMENT '客户性别',
  `customer_qq` varchar(30) DEFAULT NULL COMMENT '客户QQ',
  `customer_email` varchar(30) DEFAULT NULL COMMENT '客户邮箱',
  `householder_relation` varchar(20) DEFAULT NULL COMMENT '与户主关系',
  `status` varchar(10) DEFAULT NULL,
  `other_phone` int(20) DEFAULT NULL COMMENT '备用电话',
  `create_time` int(20) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=594 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_contract
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_contract`;
CREATE TABLE `mk_project_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '所属谁的项目',
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `book_number` varchar(50) DEFAULT NULL COMMENT '合同编号',
  `project_name` varchar(50) DEFAULT NULL COMMENT '项目名称',
  `project_amount` decimal(15,2) DEFAULT '0.00',
  `contract_amount` decimal(15,2) DEFAULT '0.00' COMMENT '合同总金额',
  `price` decimal(15,2) DEFAULT '0.00' COMMENT '实际收入的金额 ( 如果已付定金则最开始显示收入的定金金额 )',
  `pay_price` decimal(15,2) DEFAULT '0.00' COMMENT '合同的支出金额',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_contract_audit
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_contract_audit`;
CREATE TABLE `mk_project_contract_audit` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '合同签订审核',
  `guid` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `transfer_name` varchar(255) DEFAULT NULL COMMENT '申请人',
  `transfer_uuid` varchar(40) DEFAULT NULL,
  `examine_status` varchar(40) DEFAULT NULL,
  `transfer_desc` varchar(255) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `examine_desc` varchar(255) DEFAULT NULL,
  `examine_uuid` varchar(40) DEFAULT NULL,
  `book_number` varchar(255) DEFAULT NULL COMMENT '合同编号',
  `examine_uuid_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_deposit_price
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_deposit_price`;
CREATE TABLE `mk_project_deposit_price` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL COMMENT '项目交纳的定金',
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `payment_uuid` varchar(40) DEFAULT NULL COMMENT '该款项录入者',
  `payment_uuid_name` varchar(255) DEFAULT NULL COMMENT '录入人姓名(方便查找)',
  `payment_id` varchar(40) DEFAULT NULL COMMENT '付款方式',
  `payment_name` varchar(255) DEFAULT NULL COMMENT '付款方式（name）',
  `payment_price` decimal(15,2) DEFAULT '0.00' COMMENT '付款金额',
  `payment_date` int(15) DEFAULT NULL COMMENT '付款时间',
  `collected_price` decimal(15,2) DEFAULT '0.00' COMMENT '实际收款金额',
  `collected_uuid` varchar(40) DEFAULT NULL COMMENT '收款金额确认人',
  `collected_uuid_name` varchar(255) DEFAULT NULL COMMENT '收款人姓名',
  `bank_id` varchar(40) DEFAULT NULL COMMENT '收款的账号',
  `collected_date` int(15) DEFAULT NULL COMMENT '收款时间',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_deposit_price_a_copy
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_deposit_price_a_copy`;
CREATE TABLE `mk_project_deposit_price_a_copy` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL COMMENT '项目交纳的定金',
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_deposit_price_audit
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_deposit_price_audit`;
CREATE TABLE `mk_project_deposit_price_audit` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL COMMENT '项目交纳的定金',
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `payment_uuid` varchar(40) DEFAULT NULL COMMENT '该款项录入者',
  `payment_uuid_name` varchar(255) DEFAULT NULL COMMENT '录入人姓名(方便查找)',
  `payment_id` varchar(40) DEFAULT NULL COMMENT '付款方式',
  `payment_name` varchar(255) DEFAULT NULL COMMENT '付款方式（name）',
  `payment_price` decimal(15,2) DEFAULT '0.00' COMMENT '付款金额',
  `payment_date` int(15) DEFAULT NULL COMMENT '付款时间',
  `collected_price` decimal(15,2) DEFAULT '0.00' COMMENT '实际收款金额',
  `collected_uuid` varchar(40) DEFAULT NULL COMMENT '收款金额确认人',
  `collected_uuid_name` varchar(255) DEFAULT NULL COMMENT '收款人姓名',
  `bank_id` varchar(40) DEFAULT NULL COMMENT '收款的账号',
  `collected_date` int(15) DEFAULT NULL COMMENT '收款时间',
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_field
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_field`;
CREATE TABLE `mk_project_field` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `uuid_name` varchar(255) DEFAULT NULL,
  `field_name` varchar(255) DEFAULT NULL COMMENT '附件名字',
  `field_address` varchar(255) DEFAULT NULL COMMENT '附件地址',
  `field_m` varchar(255) DEFAULT NULL,
  `field_desc` text,
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_photo
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_photo`;
CREATE TABLE `mk_project_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(255) DEFAULT NULL,
  `uuid_name` varchar(255) DEFAULT NULL,
  `photo_name` varchar(255) DEFAULT NULL COMMENT '图片名字',
  `photo_address` varchar(255) DEFAULT NULL COMMENT '图片路径',
  `photo_desc` text,
  `photo_m` varchar(255) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_related_person
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_related_person`;
CREATE TABLE `mk_project_related_person` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(40) DEFAULT NULL COMMENT '项目',
  `company_id` varchar(40) DEFAULT NULL COMMENT '公司',
  `department_type` varchar(10) DEFAULT NULL COMMENT '部门类型()',
  `uuid` varchar(40) DEFAULT NULL COMMENT '关系人',
  `status` varchar(10) DEFAULT '1',
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=357 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_remind_time
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_remind_time`;
CREATE TABLE `mk_project_remind_time` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `guid` varchar(30) DEFAULT NULL,
  `uuid` varchar(30) DEFAULT NULL,
  `jobs_id` varchar(30) DEFAULT NULL,
  `department_id` varchar(30) DEFAULT NULL,
  `project_guid` varchar(30) DEFAULT NULL COMMENT '项目ID',
  `remind_content` varchar(255) DEFAULT NULL COMMENT '提醒内容',
  `remind_time` int(30) DEFAULT NULL COMMENT '提醒时间',
  `status` varchar(20) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_structure
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_structure`;
CREATE TABLE `mk_project_structure` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `guid` varchar(30) DEFAULT NULL,
  `project_guid` varchar(30) DEFAULT NULL COMMENT '项目ID',
  `living_room_structure` varchar(30) DEFAULT '0' COMMENT '居室结构',
  `housing_use` varchar(30) DEFAULT '0' COMMENT '房屋用途',
  `house_orientation` varchar(30) DEFAULT '0' COMMENT '房屋朝向',
  `lighting` varchar(30) DEFAULT '0' COMMENT '采光',
  `house_type` varchar(30) DEFAULT '0' COMMENT '房屋类型',
  `floor` varchar(30) DEFAULT NULL COMMENT '楼层',
  `status` varchar(11) DEFAULT NULL,
  `create_time` int(20) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(20) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_success_time
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_success_time`;
CREATE TABLE `mk_project_success_time` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '项目的业绩关联表',
  `guid` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  `type` int(10) DEFAULT NULL COMMENT '类型( 1 市场部 转入设计部的时间  2 客服部转入设计部的时间 3 设计部 )',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_trace_log
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_trace_log`;
CREATE TABLE `mk_project_trace_log` (
  `id` int(30) NOT NULL AUTO_INCREMENT,
  `guid` varchar(30) DEFAULT NULL,
  `uuid` varchar(30) DEFAULT NULL COMMENT '跟踪人姓名',
  `project_guid` varchar(30) DEFAULT NULL COMMENT '项目ID',
  `jobs_id` varchar(30) DEFAULT NULL,
  `log_type` varchar(11) DEFAULT NULL COMMENT '日志类型',
  `department_id` varchar(30) DEFAULT NULL COMMENT '部门',
  `log_content` varchar(255) DEFAULT NULL COMMENT '跟踪内容',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(20) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(20) DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2985 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_project_waste_apply
-- ----------------------------
DROP TABLE IF EXISTS `mk_project_waste_apply`;
CREATE TABLE `mk_project_waste_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '废单申请表',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `project_type` varchar(255) DEFAULT NULL COMMENT '0 市场部的单   1 客服部的单',
  `apply_uuid` varchar(40) DEFAULT NULL,
  `apply_name` varchar(255) DEFAULT NULL,
  `apply_desc` varchar(255) DEFAULT NULL,
  `apply_type` int(10) DEFAULT NULL COMMENT '废单类型',
  `examine_status` varchar(255) DEFAULT '0' COMMENT '审核状态',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_desc` varchar(255) DEFAULT NULL,
  `status` int(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_revoke_money
-- ----------------------------
DROP TABLE IF EXISTS `mk_revoke_money`;
CREATE TABLE `mk_revoke_money` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '实际收款撤销申请',
  `guid` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL COMMENT '要撤销的收款所属的项目名称',
  `collect_id` varchar(40) DEFAULT NULL COMMENT '需要撤销的收款id',
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '申请人',
  `apply_desc` varchar(255) DEFAULT NULL COMMENT '申请原因',
  `examine_status` varchar(10) DEFAULT NULL COMMENT '审核状态',
  `examine_uuid` varchar(40) DEFAULT NULL COMMENT '审核人',
  `examine_name` varchar(255) DEFAULT NULL,
  `examine_desc` varchar(255) DEFAULT NULL COMMENT '审核说明',
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=64730 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_waste_project_apply
-- ----------------------------
DROP TABLE IF EXISTS `mk_waste_project_apply`;
CREATE TABLE `mk_waste_project_apply` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT '废单项目跟踪申请',
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `project_id` varchar(40) DEFAULT NULL,
  `project_name` varchar(255) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL,
  `apply_desc` varchar(255) DEFAULT NULL,
  `examine_status` int(2) DEFAULT '0' COMMENT '审核状态',
  `examine_uuid` varchar(40) DEFAULT NULL,
  `examine_name` varchar(255) DEFAULT NULL,
  `examine_desc` varchar(255) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
-- Table structure for mk_we_fans
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_fans`;
CREATE TABLE `mk_we_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `appid` char(32) DEFAULT NULL,
  `openid` char(32) DEFAULT NULL,
  `unionid` char(32) DEFAULT NULL,
  `domain` varchar(50) DEFAULT NULL,
  `nickname` char(100) CHARACTER SET utf8mb4 DEFAULT '' COMMENT '昵称',
  `remark` char(32) DEFAULT '' COMMENT '昵称',
  `headimgurl` varchar(255) DEFAULT NULL,
  `sex` tinyint(3) unsigned DEFAULT '0' COMMENT '性别',
  `status` tinyint(4) DEFAULT '1' COMMENT '会员状态',
  `country` varchar(32) DEFAULT NULL,
  `province` char(32) DEFAULT NULL,
  `city` char(32) DEFAULT NULL,
  `subscribe` tinyint(4) DEFAULT NULL,
  `subscribe_qrcode` int(11) DEFAULT '0',
  `subscribe_time` int(11) DEFAULT NULL,
  `unsubscribe_time` int(11) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `email` char(32) DEFAULT NULL COMMENT '用户邮箱',
  `mobile` char(15) DEFAULT NULL COMMENT '用户手机',
  `describe` text,
  `score` int(11) DEFAULT '0',
  `amount` decimal(10,2) DEFAULT '0.00' COMMENT '余额',
  `tagid_list` text,
  `create_time` int(11) DEFAULT NULL COMMENT '绑定一个内部员工的userid',
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE,
  KEY `name` (`nickname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 COMMENT='会员表';

-- ----------------------------
-- Table structure for mk_we_location
-- ----------------------------
DROP TABLE IF EXISTS `mk_we_location`;
CREATE TABLE `mk_we_location` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `appid` char(40) NOT NULL,
  `nickname` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '昵称',
  `ToUserName` varchar(255) NOT NULL,
  `FromUserName` varchar(255) NOT NULL,
  `MsgType` varchar(40) DEFAULT NULL,
  `Event` varchar(40) DEFAULT NULL,
  `Latitude` varchar(40) DEFAULT NULL COMMENT '地理位置纬度',
  `Longitude` varchar(40) DEFAULT NULL COMMENT '地理位置经度',
  `Precision` varchar(40) DEFAULT NULL COMMENT '地理位置精度',
  `CreateTime` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0' COMMENT '0 未处理 1 无效 2.差评 3. 好评',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=802 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='微信菜单';

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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='微信菜单';

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
) ENGINE=InnoDB AUTO_INCREMENT=1170 DEFAULT CHARSET=utf8;

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
-- Table structure for mk_work_target
-- ----------------------------
DROP TABLE IF EXISTS `mk_work_target`;
CREATE TABLE `mk_work_target` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `guid` varchar(40) DEFAULT NULL,
  `company_id` varchar(40) DEFAULT NULL,
  `uuid` varchar(40) DEFAULT NULL COMMENT '录入者id',
  `dep_id` varchar(40) DEFAULT NULL COMMENT '任务指标的部门id',
  `work_number` varchar(40) DEFAULT NULL,
  `year` int(10) DEFAULT NULL COMMENT '年',
  `month` int(5) DEFAULT NULL COMMENT '月',
  `status` varchar(15) DEFAULT NULL,
  `create_time` int(15) DEFAULT NULL,
  `update_time` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mk_wx_url
-- ----------------------------
DROP TABLE IF EXISTS `mk_wx_url`;
CREATE TABLE `mk_wx_url` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code_url` varchar(255) DEFAULT NULL COMMENT '申请试用的二维码url',
  `sid` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
