/*
Navicat MySQL Data Transfer

Source Server         : 咸亨国际
Source Server Version : 50638
Source Host           : 47.96.8.108:3306
Source Database       : xhgj

Target Server Type    : MYSQL
Target Server Version : 50638
File Encoding         : 65001

Date: 2018-09-03 20:24:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '区域名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for device
-- ----------------------------
DROP TABLE IF EXISTS `device`;
CREATE TABLE `device` (
  `device_id` char(32) NOT NULL COMMENT '设备id',
  `project_id` int(10) NOT NULL COMMENT '项目id',
  `ip` char(64) NOT NULL,
  `last_time` int(10) NOT NULL COMMENT '最后通讯时间',
  `longitude` decimal(10,6) NOT NULL COMMENT '经度',
  `latitude` decimal(10,6) NOT NULL COMMENT '纬度',
  `maintain_last_worker` varchar(255) NOT NULL COMMENT '最后维护人',
  `install_last_time` int(10) NOT NULL COMMENT '最后安装时间',
  `device_name` varchar(255) NOT NULL COMMENT '设备名称',
  `status` tinyint(1) NOT NULL COMMENT '设备状态  0正常  1 数据异常 2 通讯失败',
  `electric_type` varchar(255) NOT NULL COMMENT '供电方式',
  `voltage` varchar(10) NOT NULL DEFAULT '' COMMENT '电压',
  `protocol` tinyint(1) NOT NULL COMMENT '通讯协议:0=长连接,1=短链接',
  `environment` varchar(255) NOT NULL COMMENT '设备检测环境',
  `accendant_name` varchar(50) NOT NULL DEFAULT '' COMMENT '维护人姓名',
  `accendant_department` varchar(50) NOT NULL DEFAULT '' COMMENT '维护人部门',
  `accendant_email` varchar(64) NOT NULL DEFAULT '' COMMENT '维护人邮箱',
  `accendant_mobile` varchar(12) NOT NULL DEFAULT '' COMMENT '维护人电话',
  `mark` char(4) NOT NULL DEFAULT '' COMMENT '单元标识符',
  `alarm_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '报警类型:0=正常,1=仅网管可见',
  `alarm_communication_time` int(10) NOT NULL DEFAULT '1' COMMENT '报警通讯时间(秒)',
  `communicationdistance` int(10) NOT NULL DEFAULT '0' COMMENT '通讯间隔(秒)',
  `port_number` varchar(10) NOT NULL DEFAULT '' COMMENT '端口号',
  PRIMARY KEY (`device_id`),
  KEY `ip` (`ip`),
  KEY `device_id` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='设备表(站点表)';

-- ----------------------------
-- Table structure for device_data
-- ----------------------------
DROP TABLE IF EXISTS `device_data`;
CREATE TABLE `device_data` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备id',
  `dra_id` int(10) NOT NULL COMMENT '通道id',
  `data` char(32) NOT NULL COMMENT '数据内容',
  `time` int(10) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 COMMENT='设备数据表';

-- ----------------------------
-- Table structure for device_log
-- ----------------------------
DROP TABLE IF EXISTS `device_log`;
CREATE TABLE `device_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备(站点)id',
  `project_id` int(10) NOT NULL COMMENT '设备管理员id',
  `time` int(10) NOT NULL COMMENT '添加时间',
  `log_info` text NOT NULL COMMENT '日志信息',
  `img` varchar(255) NOT NULL DEFAULT '' COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='设备(站点)日志表';

-- ----------------------------
-- Table structure for device_register_alias
-- ----------------------------
DROP TABLE IF EXISTS `device_register_alias`;
CREATE TABLE `device_register_alias` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备编号',
  `passageway_id` char(128) NOT NULL DEFAULT '' COMMENT '通道id',
  `starting_address` char(32) NOT NULL DEFAULT '' COMMENT '寄存器表示 起始地址',
  `end_address` char(32) NOT NULL DEFAULT '' COMMENT '结束编码',
  `register_number` char(32) NOT NULL DEFAULT '' COMMENT '寄存器数量',
  `change_range` varchar(255) NOT NULL DEFAULT '' COMMENT '变化范围',
  `max_range` varchar(255) NOT NULL DEFAULT '' COMMENT '限制范围',
  `count_time` int(10) NOT NULL DEFAULT '0' COMMENT '统计时间',
  `value` char(32) DEFAULT NULL COMMENT '监测值',
  `change_value` char(32) DEFAULT NULL COMMENT '数据变频值',
  `alias` varchar(255) NOT NULL DEFAULT '' COMMENT '数据别名',
  `a` char(32) DEFAULT NULL COMMENT '计算公式a值',
  `b` char(32) DEFAULT NULL COMMENT '计算公式b值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='设备下发指令表';

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `log` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for passageway
-- ----------------------------
DROP TABLE IF EXISTS `passageway`;
CREATE TABLE `passageway` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备id',
  `name` varchar(255) NOT NULL COMMENT '通道名称',
  `change_range_max` char(32) NOT NULL COMMENT '变化范围 最大值',
  `change_range_min` char(32) NOT NULL COMMENT '变化范围 最小值',
  `max_range` char(32) NOT NULL COMMENT '最大限值范围',
  `min_range` char(32) NOT NULL COMMENT '最小限值范围',
  `count_time` int(10) NOT NULL COMMENT '统计时间',
  `value` char(32) NOT NULL COMMENT '监测值',
  `change_value` char(32) NOT NULL COMMENT '数据变频值',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态:0=正常,1=数据异常,2=通讯失败',
  `a` decimal(5,2) NOT NULL DEFAULT '1.00' COMMENT 'a的值',
  `b` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT 'b的值',
  `switch_alarm` tinyint(1) DEFAULT NULL COMMENT '开关量报警值(0/1)',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '通道类别',
  `start_coding` varchar(10) NOT NULL DEFAULT '' COMMENT '起始编码',
  `end_coding` varchar(10) NOT NULL DEFAULT '' COMMENT '结束编码',
  `group_count` int(10) NOT NULL DEFAULT '1' COMMENT '获取数据组数',
  `register_number` char(32) NOT NULL COMMENT '寄存器数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='通道表';

-- ----------------------------
-- Table structure for passageway_category
-- ----------------------------
DROP TABLE IF EXISTS `passageway_category`;
CREATE TABLE `passageway_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT '通道类别名称',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '数据类型:0=模拟量,1=开关量',
  `data_address` varchar(255) NOT NULL DEFAULT '' COMMENT '数据地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for passageway_correlation
-- ----------------------------
DROP TABLE IF EXISTS `passageway_correlation`;
CREATE TABLE `passageway_correlation` (
  `passageway1_id` int(11) NOT NULL,
  `passageway2_id` int(11) NOT NULL,
  `alarm_limit` varchar(10) DEFAULT NULL COMMENT '报警限值',
  PRIMARY KEY (`passageway1_id`,`passageway2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for passageway_start_address
-- ----------------------------
DROP TABLE IF EXISTS `passageway_start_address`;
CREATE TABLE `passageway_start_address` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '起始地址数据对应表',
  `address_y` char(10) DEFAULT NULL COMMENT 'y轴坐标',
  `address_x` char(10) DEFAULT NULL COMMENT 'x轴坐标',
  `data` char(10) DEFAULT NULL COMMENT '数据内容',
  `is_delete` tinyint(1) DEFAULT NULL COMMENT '删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for project_admin
-- ----------------------------
DROP TABLE IF EXISTS `project_admin`;
CREATE TABLE `project_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `p_id` int(10) DEFAULT NULL COMMENT '项目id',
  `account_number` char(32) NOT NULL COMMENT '账号',
  `password` char(64) NOT NULL COMMENT '密码',
  `name` char(64) NOT NULL COMMENT '姓名',
  `department` char(64) NOT NULL COMMENT '部门',
  `email` char(32) DEFAULT '' COMMENT '邮箱',
  `phone_number` char(11) NOT NULL COMMENT '手机号',
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1管理员 2巡逻员 0网管',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='项目管理员表';

-- ----------------------------
-- Table structure for project_admin_device
-- ----------------------------
DROP TABLE IF EXISTS `project_admin_device`;
CREATE TABLE `project_admin_device` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备id',
  `project_admin_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for project_data
-- ----------------------------
DROP TABLE IF EXISTS `project_data`;
CREATE TABLE `project_data` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(36) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属区域',
  `title` char(128) NOT NULL COMMENT '资料标题',
  `cover` char(128) NOT NULL COMMENT '资料封面',
  `content` text NOT NULL COMMENT '资料内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='项目资料表';

-- ----------------------------
-- Table structure for project_inspect_log
-- ----------------------------
DROP TABLE IF EXISTS `project_inspect_log`;
CREATE TABLE `project_inspect_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_admin_id` int(10) NOT NULL COMMENT '巡检员id',
  `create_time` int(10) NOT NULL COMMENT '创建时间',
  `content` text NOT NULL COMMENT '巡检记录内容',
  `img` varchar(255) NOT NULL COMMENT '巡检记录图片内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for projects
-- ----------------------------
DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(255) NOT NULL COMMENT '项目名称',
  `build_start_time` int(10) NOT NULL COMMENT '项目施工开始时间',
  `maintain_last_time` int(10) NOT NULL COMMENT '项目最后维护日期',
  `worker` char(64) NOT NULL COMMENT '现场施工人员',
  `project_explain` text NOT NULL COMMENT '项目说明',
  `version` text NOT NULL COMMENT '项目版本信息',
  `logo` char(128) NOT NULL COMMENT '项目logo',
  `longitude` decimal(10,6) NOT NULL COMMENT '经度',
  `latitude` decimal(10,6) NOT NULL COMMENT '纬度',
  `province` char(32) NOT NULL COMMENT '省份',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='项目表';

-- ----------------------------
-- Table structure for read_device
-- ----------------------------
DROP TABLE IF EXISTS `read_device`;
CREATE TABLE `read_device` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `device_id` char(32) NOT NULL COMMENT '设备编号',
  `mark` char(2) NOT NULL DEFAULT '01' COMMENT '单元标识符',
  `start_address` char(64) DEFAULT NULL COMMENT '起始地址',
  `register_number` char(16) DEFAULT NULL COMMENT '寄存器数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='设备读指令表';

-- ----------------------------
-- Table structure for update_device
-- ----------------------------
DROP TABLE IF EXISTS `update_device`;
CREATE TABLE `update_device` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '修改设备信息指令表',
  `mark` char(4) NOT NULL COMMENT '单元标识符',
  `device_id` char(32) NOT NULL,
  `start_address` char(32) NOT NULL COMMENT '起始位置',
  `register_number` char(32) NOT NULL COMMENT '寄存器数量',
  `byte` char(32) NOT NULL,
  `request_data` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '设备状态 0 未发送 1发送 2设备修改成功',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='设备写指令表';
