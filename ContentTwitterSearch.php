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
 * @copyright  Leo Feyer 2005-2010
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Frontend
 * @license    LGPL
 * @filesource
 */

/**
 * Class ContentTwitterSearch
 *
 * @copyright  2011 Winans Creative
 * @author     Blair Winans <blair@winanscreative.com>
 * @package    Frontend
 */
class ContentTwitterSearch extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_twitter_search';
	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### TWITTER: SEARCH ###';
			$objTemplate->title = $this->twitterSearch;

			return $objTemplate->parse();
		}

		return parent::generate();
	}


	/**
	 * Generate content element
	 */
	protected function compile()
	{
		$time = time();
		$strBuffer = '';
		$arrResults = array();
		$blnRunSearch = true;
 		 		
 		if($this->twitterCache > 0 )
 		{
 			//First create/load existing cache file
 			$objCacheFile = FileCache::getInstance( 'twitter.ce_twitter_search.' . $this->id );
 			
 			//Get the last cache timestamp
 			$intCacheDate = $objCacheFile->tstamp;
 			
			if(strlen($objCacheFile->content) && $intCacheDate && ($time - $intCacheDate < $this->twitterCache))
			{
				$arrResults =  $objCacheFile->content;
				$blnRunSearch = false;
 			}
 		}
		
		if( $blnRunSearch  ) //Run the search
		{		
			$this->import('Twitter'); //Do not need to pass authorization params
			$arrResults = $this->Twitter->getSearchResults( $this->twitterSearch,  $this->twitterLimit);

			if($this->twitterCache > 0 )
			{
				$objCacheFile->content = $arrResults; //Write to cache file. The cachefile object was created earlier
				$objCacheFile->tstamp = time();
			}
		}
		
		$strTemplate = $this->twitterTemplate ? $this->twitterTemplate : 'tweet_default';
		
		foreach($arrResults as $post)
		{
			$objTemplate = new FrontendTemplate($strTemplate);
			$objTemplate->setData(get_object_vars($post));

			//Extra data assembled from result values
			$objTemplate->statusLink = 'http://twitter.com/#!/'.$post->from_user.'/status/'.$post->id_str;
			$objTemplate->userLink = 'http://twitter.com/#!/'.$post->from_user;
			
			$strBuffer .= $objTemplate->parse();
		}
		
		$this->Template->twitter = $strBuffer;
		
	}

}