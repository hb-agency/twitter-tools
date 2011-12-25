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
 * Configuration
 */
$GLOBALS['TL_DCA']['tl_news']['config']['onload_callback'][] = array('NewsTwitter', 'injectField');
$GLOBALS['TL_DCA']['tl_news']['config']['onsubmit_callback'][] = array('NewsTwitter', 'sendNow');
$GLOBALS['TL_DCA']['tl_news']['list']['sorting']['headerFields'][] = 'twitter';


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_news']['palettes']['__selector__'][] = 'twitter';
$GLOBALS['TL_DCA']['tl_news']['subpalettes']['twitter'] = 'twitterMessage,twitterStatus,twitterUrl';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['twitter'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_news']['twitter'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'eval'			=> array('submitOnChange'=>true),
);

$GLOBALS['TL_DCA']['tl_news']['fields']['twitterMessage'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_news']['twitterMessage'],
	'exclude'		=> true,
	'inputType'		=> 'text',
	'eval'			=> array('maxlength'=>120, 'decodeEntities'=>true, 'tl_class'=>'long'),
);

$GLOBALS['TL_DCA']['tl_news']['fields']['twitterStatus'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_news']['twitterStatus'],
	'exclude'		=> true,
	'inputType'		=> 'radio',
	'default'		=> 'cron',
	'options'		=> array('now', 'cron', 'sent'),
	'reference'		=> &$GLOBALS['TL_LANG']['tl_news']['twitterStatus_ref'],
	'eval'			=> array('mandatory'=>true),
);

$GLOBALS['TL_DCA']['tl_news']['fields']['twitterUrl'] = array
(
	'label'			=> &$GLOBALS['TL_LANG']['tl_news']['twitterUrl'],
	'exclude'		=> true,
	'inputType'		=> 'checkbox',
	'default'		=> '1',
);

