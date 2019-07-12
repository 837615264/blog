/*
Navicat MySQL Data Transfer

Source Server         : mysql
Source Server Version : 50617
Source Host           : 127.0.0.1:3306
Source Database       : blog

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2019-07-12 13:48:21
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `article_title` varchar(255) DEFAULT NULL COMMENT '文章标题',
  `type_id` int(11) DEFAULT NULL COMMENT '分类id',
  `article_content` text COMMENT '文章内容',
  `article_add_time` varchar(255) DEFAULT NULL COMMENT '文章更新时间',
  `article_hits` int(11) DEFAULT '0' COMMENT '点击量',
  `article_image` varchar(255) DEFAULT NULL COMMENT '缩略图',
  `article_up` int(255) DEFAULT '0' COMMENT '点赞',
  `article_trash` tinyint(4) DEFAULT '1' COMMENT '0放入回收站，1不放入',
  `article_tag` varchar(255) DEFAULT NULL COMMENT '文章标签',
  PRIMARY KEY (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for cloud
-- ----------------------------
DROP TABLE IF EXISTS `cloud`;
CREATE TABLE `cloud` (
  `tag_id` int(11) DEFAULT NULL COMMENT '标签id',
  `article_id` int(11) DEFAULT NULL COMMENT '文章id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for collect
-- ----------------------------
DROP TABLE IF EXISTS `collect`;
CREATE TABLE `collect` (
  `collect_id` int(11) NOT NULL AUTO_INCREMENT,
  `collect_title` varchar(255) DEFAULT NULL COMMENT '收藏标题',
  `collect_content` text COMMENT '收藏内容',
  `collect_add_time` int(11) DEFAULT NULL COMMENT '收藏添加时间',
  `collect_hits` int(11) DEFAULT '0' COMMENT '点击量',
  `collect_image` varchar(255) DEFAULT NULL COMMENT '收藏展示图片',
  `collect_accessory` varchar(255) DEFAULT NULL COMMENT '附件',
  `collect_up` int(11) DEFAULT '0' COMMENT '点赞量',
  `collect_trash` varchar(255) DEFAULT '1' COMMENT '是否放入回收站',
  PRIMARY KEY (`collect_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论id',
  `article_id` int(11) DEFAULT NULL,
  `comment_type` varchar(255) DEFAULT NULL COMMENT '评论类型',
  `comment_user` varchar(255) DEFAULT NULL COMMENT '评论用户名',
  `comment_content` text,
  `comment_add_time` int(255) DEFAULT NULL COMMENT '评论时间',
  `comment_email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `comment_relation` varchar(255) DEFAULT NULL COMMENT 'QQ或微信',
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for game
-- ----------------------------
DROP TABLE IF EXISTS `game`;
CREATE TABLE `game` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '游戏id',
  `game_title` varchar(255) DEFAULT NULL COMMENT '游戏标题',
  `game_content` text COMMENT '游戏内容',
  `game_add_time` varchar(255) DEFAULT NULL COMMENT '添加时间',
  `game_hits` int(11) DEFAULT '0' COMMENT '点击量',
  `game_image` varchar(255) DEFAULT NULL COMMENT '游戏图片',
  `game_url` varchar(255) DEFAULT NULL COMMENT '游戏路径',
  `game_up` int(11) DEFAULT '0' COMMENT '点赞量',
  `game_trash` tinyint(4) DEFAULT '1' COMMENT '是否放入回收站',
  PRIMARY KEY (`game_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mood
-- ----------------------------
DROP TABLE IF EXISTS `mood`;
CREATE TABLE `mood` (
  `mood_id` int(11) NOT NULL AUTO_INCREMENT,
  `mood_title` varchar(255) DEFAULT NULL,
  `mood_content` text,
  `mood_add_time` varchar(255) DEFAULT NULL,
  `mood_hits` int(255) DEFAULT '0',
  `mood_image` varchar(255) DEFAULT NULL,
  `mood_up` int(11) DEFAULT '0',
  `mood_trash` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`mood_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for project
-- ----------------------------
DROP TABLE IF EXISTS `project`;
CREATE TABLE `project` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目id',
  `project_title` varchar(255) DEFAULT NULL COMMENT '项目标题',
  `project_type` varchar(255) DEFAULT NULL COMMENT '项目类型',
  `project_content` text COMMENT '项目内容',
  `project_add_time` varchar(255) DEFAULT NULL COMMENT '添加时间',
  `project_hits` int(11) DEFAULT NULL COMMENT '点击量',
  `project_image` varchar(255) DEFAULT NULL COMMENT '项目展示图片',
  `project_url` varchar(255) DEFAULT NULL COMMENT '项目路径',
  `project_up` int(11) DEFAULT NULL COMMENT '点赞量',
  `project_trash` tinyint(4) DEFAULT NULL COMMENT '是否放入回收站',
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(255) DEFAULT NULL COMMENT '标签名称',
  `tag_hits` int(11) DEFAULT '0' COMMENT '标签点击量',
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for trash
-- ----------------------------
DROP TABLE IF EXISTS `trash`;
CREATE TABLE `trash` (
  `trash_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回收站id',
  `trash_old_id` int(11) DEFAULT NULL,
  `trash_type` varchar(255) DEFAULT NULL COMMENT '信息对应类型',
  `trash_title` varchar(255) DEFAULT NULL COMMENT '标题',
  `trash_content` text COMMENT '内容',
  `trash_image` varchar(255) DEFAULT NULL,
  `trash_del_time` varchar(255) DEFAULT NULL COMMENT '删除日期',
  PRIMARY KEY (`trash_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for type
-- ----------------------------
DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id',
  `type_name` varchar(255) DEFAULT NULL COMMENT '分类名称',
  `type_parent` int(11) DEFAULT NULL COMMENT '父级id',
  `type_path` varchar(255) DEFAULT NULL COMMENT '分类路径',
  `type_sort` varchar(255) DEFAULT NULL COMMENT '分类排序',
  `type_count` varchar(255) DEFAULT NULL COMMENT '所属分类下文章数量',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;
