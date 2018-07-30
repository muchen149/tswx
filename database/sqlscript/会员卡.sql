CREATE TABLE `yyd_membership_activity` (
  `activity_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `activity_name` varchar(60) NOT NULL COMMENT '活动名称（标题）,唯一',
  `supplier_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属供应商ID(“0”为平台)',
  `card_prefix` varchar(5) NOT NULL COMMENT '会员卡编码前缀',
  `card_amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员卡数量',
  `activity_images` varchar(250) DEFAULT '' COMMENT '活动图片',
  `grade` tinyint(4) NOT NULL DEFAULT '10' COMMENT '会员类别【10:普通会员;20:一级会员;30:二级会员;40:三级会员】',
	`exp_date` int(10) unsigned NOT NULL COMMENT '有效时长',
	`exp_date_code` varchar(10) NOT NULL COMMENT '有效时长单位代码(day/month/year)',
	`exp_date_name` varchar(10) NOT NULL COMMENT '有效时长单位名称（日、月、年）',
	`price` decimal(10,2) unsigned NOT NULL COMMENT '售价',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `activity_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '活动状态(0:未启用;1:已启用;-1:已删除;)',
  `operator` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作者id',
  `created_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`activity_id`),
  UNIQUE KEY `membership_activity_unique1` (`supplier_id`,`activity_name`) USING BTREE,
  KEY `membership_activity_index1` (`created_time`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='会员卡活动表';

CREATE TABLE `yyd_membership_card` (
  `card_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员卡ID',
  `activity_id` int(10) unsigned NOT NULL COMMENT '活动ID',
	`activity_name` varchar(60) NOT NULL COMMENT '活动名称（标题）',
  `code_index` int(11) NOT NULL COMMENT '卡序号(索引)',
  `code_number` varchar(16) NOT NULL COMMENT '卡编码（前缀+6位序列号，形如：HY000001，制卡用）',
  `two_dimension_batch_id` int(11) DEFAULT NULL COMMENT '批次id',
	`two_dimension_batch_name` varchar(60) DEFAULT NULL COMMENT '批次名称',
  `two_dimension_id` bigint(20) DEFAULT NULL COMMENT '二维码id',
  `two_dimension_code` varchar(64) DEFAULT NULL COMMENT '二维码code',
	`two_dimension_number_code` varchar(16) DEFAULT NULL COMMENT '数字码',
  `card_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '卡状态(0:未兑换;1:已兑换;-1:作废)',
  `card_memo` varchar(250) DEFAULT '' COMMENT '备注',
  `updated_time` int(11) DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`card_id`),
  KEY `membership_card_index1` (`activity_id`,`code_index`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='会员卡表';

CREATE TABLE `yyd_member_ship` (
  `membership_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
	`pay_sn` varchar(64) NOT NULL COMMENT '支付号',
  `member_id` int(10) unsigned NOT NULL COMMENT '会员ID',
  `card_id` int(10) NOT NULL COMMENT '会员卡id',
  `activity_id` int(11) NOT NULL COMMENT '会员卡活动id',
	`grade` tinyint(4) NOT NULL DEFAULT '10' COMMENT '会员类别【10:普通会员;20:一级会员;30:二级会员;40:三级会员】',
	`exp_date` int(10) unsigned NOT NULL COMMENT '有效时长',
	`exp_date_code` varchar(10) NOT NULL COMMENT '有效时长单位代码(day/month/year)',
	`exp_date_name` varchar(10) NOT NULL COMMENT '有效时长单位名称（日、月、年）',
	`price` decimal(10,2) unsigned NOT NULL COMMENT '售价',
  `use_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '有效状态【0:有效;1:无效;-1:已删除】',
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`membership_id`),
  KEY `member_membership_index_1` (`member_id`,`created_at`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员级别信息表';
