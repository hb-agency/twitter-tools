<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009-2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: tl_news.php 147 2010-09-14 21:41:16Z aschempp $
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news']['twitter']			= array('Send Twitter', 'Please check here if you want to twitter this news.');
$GLOBALS['TL_LANG']['tl_news']['twitterMessage']	= array('Twitter message', 'Enter a twitter status. If you leave this field blank, the first 120 chars of news teaser or message will be taken.');
$GLOBALS['TL_LANG']['tl_news']['twitterStatus']		= array('Twitter status', 'Please set wether this message should be sent now or when the news is published (hourly). The status is automatically updated once the message is sent.');
$GLOBALS['TL_LANG']['tl_news']['twitterUrl']		= array('Include news link', 'Please check here if you want to include a url to the news item. Urls are shortened using the http://is.gd/ service.');


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_news']['twitterStatus_ref']['now']	= 'Send now';
$GLOBALS['TL_LANG']['tl_news']['twitterStatus_ref']['cron']	= 'Schedule';
$GLOBALS['TL_LANG']['tl_news']['twitterStatus_ref']['sent']	= 'Sent';


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news']['twitter_legend']			= 'Twitter';

