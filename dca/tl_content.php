<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  2010 Winans Creative 
 * @author     Blair Winans <blair@winanscreative.com>
 * @package    Vkernel Homepage
 * @license    LGPL 
 * @filesource
 */


/**
 * Table tl_content 
 */
 
$this->loadLanguageFile('tl_page');

$GLOBALS['TL_DCA']['tl_content']['palettes']['twittersearch'] = '{type_legend},type,headline;{twitter_legend},twitterSearch,twitterLimit,twitterCache,twitterTemplate;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['palettes']['twitteruserfeed'] = '{type_legend},type,headline;{twitter_legend},twitterAuth,twitterLimit,twitterCache,twitterTemplate;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


$GLOBALS['TL_DCA']['tl_content']['fields']['twitterAuth'] = array
(
	'label'					=> &$GLOBALS['TL_LANG']['tl_content']['twitterAuth'],
	'input_field_callback'	=> array('tl_content_twitter', 'authenticate'),
	'eval'					=> array('tl_class'=>'clr'),
);

$GLOBALS['TL_DCA']['tl_content']['fields']['twitterSearch'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['twitterSearch'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true,'maxlength'=>255, 'tl_class'=>'long', 'helpwizard'=>true),
	'explanation'		=> 'twitter_search',
);

$GLOBALS['TL_DCA']['tl_content']['fields']['twitterLimit'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['twitterLimit'],
	'exclude'                 => true,
	'default'                 => 0,
	'inputType'               => 'text',
	'eval'                    => array('maxlength'=>5, 'rgxp'=>'digit', 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['twitterTemplate'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['twitterTemplate'],
	'exclude'                 => true,
	'inputType'               => 'select',
	'options_callback'        => array('tl_content_twitter', 'getTwitterTemplates')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['twitterCache'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['twitterCache'],
	'default'                 => 0,
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => array(0, 5, 15, 30, 60, 300, 900, 1800, 3600, 10800, 21600, 43200, 86400, 259200, 604800, 2592000),
	'reference'               => &$GLOBALS['TL_LANG']['CACHE']
);


class tl_content_twitter extends Backend
{
	
	/**
	 * Return all gallery twitter as array
	 * @param object
	 * @return array
	 */
	public function getTwitterTemplates(DataContainer $dc)
	{
		// Get the page ID
		$objArticle = $this->Database->prepare("SELECT pid FROM tl_article WHERE id=?")
									 ->limit(1)
									 ->execute($dc->activeRecord->pid);

		// Inherit the page settings
		$objPage = $this->getPageDetails($objArticle->pid);

		// Get the theme ID
		$objLayout = $this->Database->prepare("SELECT pid FROM tl_layout WHERE id=?")
									->limit(1)
									->execute($objPage->layout);

		// Return all twitter templates
		return $this->getTemplateGroup('tweet_', $objLayout->pid);
	}
	
	/**
	 * Authenticate with Twitter
	 * @param DataContainer
	 * @param string
	 * @return string
	 */
	public function authenticate( $dc, $label )
	{
		$this->import('Twitter');
		$strRedirect = $this->Environment->script . '?do=article&table=tl_content&act=edit&id=' . $dc->id;
		return $this->Twitter->getAuthenticateButton( $dc, $label, $strRedirect );
	}
}
 
 ?>