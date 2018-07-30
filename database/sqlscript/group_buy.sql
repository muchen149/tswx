-- #有关团购数据库表

-- yyd_group_buy_type表示每个sku对应的多少人团购单价为多少的数据表,比如白色鼠标这个sku,三人团购时单价为80,五人团购时单价40等信息
-- 也即每个sku对应可能有多条记录

CREATE TABLE `yyd_group_buy_type` (
  `group_buy_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '团购表类别id',
  `people_num` smallint(5) unsigned NOT NULL  COMMENT '团购人数，如3人团，5人团等',
  `group_buy_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '团购时该商品的单价，如3人团时单价为80',
  `sku_id` bigint(20) unsigned NOT NULL COMMENT '平台SKUid',
  `spu_id` int(10) unsigned NOT NULL COMMENT '商品SPUid',
  PRIMARY KEY (`group_buy_type_id`),
  KEY `group_buy_type_sku_id_index` (`sku_id`),
  KEY `group_buy_type_spu_id_index` (`spu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='sku对应的团购类别表，如3人团购是时单价80等';


-- #发起团购信息group_buy_info,该表记录某人发起团购时,记录该团购信息
-- 对于虚拟商品发起团购时，团购人数为用户自己输入的，价格就是看到的价格，不再有所谓不同的团购价，所以group_buy_type_id也就不存在
CREATE TABLE `yyd_group_buy_info` (
  `group_buy_info_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '团购信息id',
  `group_buy_type_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '团购类别id,当发起团购的为虚拟商品时，默认为0',
  `people_num` smallint(5) unsigned NOT NULL  COMMENT '团购人数，如3人团，5人团等',
  `group_buy_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '团购时该商品的单价，如3人团时单价为80，若为虚拟商品，保存的就是购买的价格',
  `member_id` int(10) unsigned NOT NULL COMMENT '发起团购的会员id',
  `spu_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '与该团购有关商品SPUid',
  `sku_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '发起团购人购买商品的SKUid，对于实物团购，这个值其实是没用的，因为参与团购的人可以买其他相同团购人数规格的sku，对于虚拟商品，参团的只能买相同sku商品',
  `current_people_num` smallint(5) unsigned NOT NULL DEFAULT '1'  COMMENT '当前参加该团购的人数，默认为1，current_people_num等于people_num代表该团购人数凑齐',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `expiration_time` int(11) DEFAULT NULL  COMMENT '失效时间',
  `is_virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发起团购的商品是否为虚拟商品，如电影票等（0：实物（默认），1：虚拟）',
  `invitation_code` varchar(16) DEFAULT NULL COMMENT '邀请码，如设置，某人参团时必须输入邀请码进行验证（从发起人中获得码）。若未设置，任何人均可参团不再验证',
  PRIMARY KEY (`group_buy_info_id`),
  KEY `group_buy_info_member_id_index` (`member_id`),
  KEY `group_buy_info_group_buy_type_id_index` (`group_buy_type_id`),
  KEY `group_buy_info_is_virtual_index` (`is_virtual`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='记录某人发起团购时,记录该团购信息';


-- #订单和发起团购信息的表之间的中间表member_group_buy,
CREATE TABLE `yyd_member_group_buy` (
   `member_group_buy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
   `plat_order_id` bigint(20) unsigned NOT NULL COMMENT'平台订单ID',
   `group_buy_info_id` bigint(20) unsigned NOT NULL COMMENT '用户参加团购信息id',
   `member_id` int(10) unsigned NOT NULL  COMMENT'参加团购的会员id',
   `nick_name` varchar(60) DEFAULT '' COMMENT '昵称',
   `avatar` varchar(200) DEFAULT '' COMMENT '头像地址',
   `sex` tinyint(4) DEFAULT '0' COMMENT '性别(0:未知;1:男;2:女)',
   `is_sponsor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为该团购的发起人',
  PRIMARY KEY (`member_group_buy_id`),
  KEY `member_group_buy_member_id_index` (`member_id`),
  KEY `member_group_buy_plat_order_id_index` (`plat_order_id`)

) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='记录某人参加团购的信息，每条记录代表着该用户参加的团购信息';

-- 团购中关于订单是否能分单，参与团购的人生成订单后判断参与该团购的人数是否达到团购人数，若达到，这更新所有
-- 有关该团购的订单能分单的标志
ALTER TABLE yyd_order
add `group_is_send` tinyint(1) NOT NULL DEFAULT '1' COMMENT '该订单(对于团购)是否可以派单，1：可以派单（默认），0：否。只有达到相应的参团人数，则所有与该团购的订单状态改为1可派单';




-- ALTER TABLE yyd_order
-- `group_buy_info_id` bigint(20) unsigned NOT NULL COMMENT '用户参加团购信息id';


--礼品分享

--  分享礼品信息表share_gifts_info记录分享信息的基本信息
CREATE TABLE `yyd_share_gifts_info` (
  `share_gifts_info_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '分享礼品信息id',
  `member_id` int(10) unsigned NOT NULL  COMMENT'发起分享礼品的会员id',
  `sku_id` bigint(20) unsigned NOT NULL COMMENT '平台SKUid',
  `gifts_num` smallint(5) unsigned NOT NULL  COMMENT '分享的礼品数量',
  `current_num` smallint(5) unsigned NOT NULL DEFAULT '0'  COMMENT '当前礼品被领取的数量，current_num等于gifts_num代表该礼品已被领取完',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `expiration_time` int(11) DEFAULT NULL  COMMENT '失效时间，若current_num大于零说明已有人领取，则到了失效时间该分享的礼品就不能再撤单',
  `plat_order_id` bigint(20) unsigned NOT NULL COMMENT '平台订单ID',
  `gifts_title` varchar(100) DEFAULT '' COMMENT '礼品分享标题',
  `gifts_message` text  COMMENT '礼品分享附加信息',
  PRIMARY KEY (`share_gifts_info_id`),
  KEY `share_gifts_info_member_id_index` (`member_id`),
  KEY `share_gifts_info_plat_order_id_index` (`plat_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='记录某人进行分享的礼品信息';

ALTER TABLE yyd_share_gifts_info
add  `nick_name` varchar(60) DEFAULT '' COMMENT '昵称',
add   `avatar` varchar(200) DEFAULT '' COMMENT '头像地址';

-- 分享礼品的名字
ALTER TABLE yyd_share_gifts_info
add  `sku_name` varchar(200) NOT NULL COMMENT '商品SKU名称【含规格】',
add `sku_image` text COMMENT 'SKU主图地址';

ALTER TABLE yyd_share_gifts_info
add `sku_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买该礼品时，该商品的结算单价';


-- 领取礼品的记录表

CREATE TABLE `yyd_get_gifts_info` (
   `get_gifts_info_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
   `share_gifts_info_id` bigint(20) unsigned NOT NULL  COMMENT '分享礼品信息id',
   `member_id` int(10) unsigned NOT NULL COMMENT '参加团购的会员id',
   `plat_order_id` bigint(20) unsigned NOT NULL COMMENT '平台订单ID',
   `sku_id` bigint(20) unsigned NOT NULL COMMENT '平台SKUid',
   `is_share_sponsor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为礼品分享的的发起人，0:否（默认），1：是',
   `create_time` int(11) DEFAULT NULL COMMENT '领取时间',
   `nick_name` varchar(60) DEFAULT '' COMMENT '昵称',
   `avatar` varchar(200) DEFAULT '' COMMENT '头像地址',
   `sex` tinyint(4) DEFAULT '0' COMMENT '性别(0:未知;1:男;2:女)',
  PRIMARY KEY (`get_gifts_info_id`),
  KEY `get_gifts_info_member_id_index` (`member_id`),
  KEY `get_gifts_info_share_gifts_info_id_index` (`share_gifts_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='记录某人领取礼品的信息';

-- 领取者留言或感谢语
ALTER TABLE yyd_get_gifts_info
add `thanks_content` text  COMMENT '领取者留言或感谢语';



ALTER TABLE yyd_order
`is_share_gifts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该订单是否是用于分享的礼品订单，0普通订单(默认)，1用于分享的礼品订单，为1时该订单不能派单';

-- 用于微信分享的礼品有个固定运费，当分享的商品spu中运费为0，免运费，若运费不为零则运费读取设置的固定值
-- wx_share_gifts_freight 系统表yyd_plat_setting中设置

--
ALTER TABLE yyd_order
add `is_get_gift` tinyint(1) NOT NULL DEFAULT '0' COMMENT '该订单是否为得到微信礼品的订单，1:是， 0：否，默认为0';



-- CMS
CREATE TABLE `yyd_article_info` (
   `article_info_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
   `member_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发表文章的会员id，0代表平台发表',
   `nick_name` varchar(60) DEFAULT '' COMMENT '昵称',
  `avatar` varchar(200) DEFAULT '' COMMENT '头像地址',
  `create_time` int(11) DEFAULT NULL COMMENT '发表时间',
  `article_title` varchar(100) DEFAULT '' COMMENT '文章标题',
  `reading` smallint(5) unsigned DEFAULT '0'  COMMENT '阅读量',
  `upvote` smallint(5) unsigned DEFAULT '0'  COMMENT '点赞量',
  `forwarding` smallint(5) unsigned DEFAULT '0'  COMMENT '转发量',
  `article_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '文章类别,如有关美食，服饰等类别（目前没有，以后可能会有一个类别表让用户选择该文章属于的类别）',
  `article_type_name` varchar(60) DEFAULT '' COMMENT '文章类别名称，如美食；（目前没有）',
  `content` text NOT NULL COMMENT '文章内容',
  `image_url_1` text COMMENT '显示文章列表时用的图片',
  `image_url_2` text COMMENT '显示文章整个内容时用的图片',
  `is_show` tinyint(1)  DEFAULT '1' COMMENT '该文章是否显示进行发表，0不可显示，1可显示（默认）',
  PRIMARY KEY (`article_info_id`),
  KEY `article_info_member_id_index` (`member_id`),
  KEY `article_info_article_type_index` (`article_type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='发表文章内容';

-- 记录对发表文章的留言信息
CREATE TABLE `yyd_message_board` (
   `message_board_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
   `article_info_id` bigint(20) unsigned NOT NULL COMMENT '关联的文章id',
   `member_id` int(10) unsigned NOT NULL  COMMENT'进行评论的会员id',
   `nick_name` varchar(60) DEFAULT '' COMMENT '昵称',
  `avatar` varchar(200) DEFAULT '' COMMENT '头像地址',
  `create_time` int(11) DEFAULT NULL COMMENT '留言时间',
  `message_content` text NOT NULL COMMENT '留言内容',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '后台设置留言内容是否可以显示，0不可显示，1可显示（默认）',
  PRIMARY KEY (`message_board_id`),
  KEY `message_board_article_info_id_index` (`article_info_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='文章的留言信息列表';

-- 系统文章（会员协议等）
CREATE TABLE `yyd_document` (
  `doc_id` mediumint(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `doc_code` varchar(255) NOT NULL COMMENT '调用标识码',
  `doc_title` varchar(255) NOT NULL COMMENT '标题',
  `doc_content` text NOT NULL COMMENT '内容',
  `doc_time` int(10) unsigned NOT NULL COMMENT '添加时间/修改时间',
  PRIMARY KEY (`doc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='系统文章表';


-- 微信送礼页面中有关商品列表和标签的关系表

CREATE TABLE `yyd_share_gifts_label_list` (
 `share_gifts_label_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
 `share_gifts_label_name` varchar(60) DEFAULT '' COMMENT '标签名字',
 `gifts_list` text COMMENT '该标签下的商品列表序列化',
 `is_usable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '该标签是否可用，0不可用，1可用（默认）',
  PRIMARY KEY (`share_gifts_label_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='微信送礼标签与商品列表';





