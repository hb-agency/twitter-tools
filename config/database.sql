-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the TYPOlight *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************


-- 
-- Table `tl_news`
-- 

CREATE TABLE `tl_news` (
  `twitter` char(1) NOT NULL default '',
  `twitterMessage` varchar(120) NOT NULL default '',
  `twitterStatus` varchar(10) NOT NULL default '',
  `twitterUrl` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_news_archive`
-- 

CREATE TABLE `tl_news_archive` (
  `twitter` char(1) NOT NULL default '',
  `twitterAuth` varchar(255) NOT NULL default '',
  `twitterParams` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

-- 
-- Table `tl_content`
-- 

CREATE TABLE `tl_content` (
  `twitterAuth` varchar(255) NOT NULL default '',
  `twitterSearch` varchar(255) NOT NULL default '',
  `twitterLimit` int(10) NOT NULL default '0',
  `twitterTemplate` varchar(255) NOT NULL default '',
  `twitterCache` varchar(255) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
