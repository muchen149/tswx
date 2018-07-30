
-- 邀请好友有关奖品的列表（目前只有虚拟奖品，如积分，零钱、等）
CREATE TABLE `yyd_invite_friend_reward` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `reward_code` varchar(50) DEFAULT '' COMMENT '奖品标志码',
  `reward_name` varchar(50) DEFAULT '' COMMENT '奖品名字',
  `reward_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品数量（如 ：获得虚拟币500，零钱100等）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='邀请好友成功用户获得的奖励礼品表'


ALTER TABLE yyd_member
add   `inviter_member_id` int(10) unsigned NOT NULL  DEFAULT '0' COMMENT '邀请人的id（用户是通过点击邀请人(邀请好友)的链接进行注册的，标记该id进行奖励用）';

-- 获得奖品的记录表(记录邀请者和被邀请者获得的奖品记录)
CREATE TABLE `yyd_invite_friend_reward_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `is_inviter` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是邀请好友者 0：否（默认），1：是，（获奖有两种人，一个奖给邀请好友者，一个是被邀请者成功注册后获得的奖品）',
  `member_id` int(10) unsigned NOT NULL COMMENT '获得奖品人的id',
  `reward_code` varchar(50) DEFAULT '' COMMENT '奖品标志码',
  `reward_name` varchar(50) DEFAULT '' COMMENT '奖品名字',
  `reward_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品数量（如 ：获得虚拟币500，零钱100等）',
  `other_member_id` int(10) unsigned NOT NULL   COMMENT '和获奖人关联的id，若is_inviter为1说明是邀请者成功邀请别人注册而获得的奖品，该字段记录的是被邀请者id;若为0说明是被邀请人成功注册获得的奖品，该字段记录的是邀请人的id',
  `create_time` int(11) DEFAULT NULL COMMENT '领取时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='记录邀请者和被邀请者获得的奖品记录';

