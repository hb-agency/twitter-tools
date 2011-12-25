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
 * Class ContentTwitterUserFeed
 *
 * @copyright  2011 Winans Creative
 * @author     Blair Winans <blair@winanscreative.com>
 * @package    Frontend
 */
class ContentTwitterUserFeed extends ContentElement
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_twitter_userfeed';
	
	/**
	 * Twitter authorization array
	 * @var array
	 */
	protected $arrTwitterAuth;

	
	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		$this->arrTwitterAuth = deserialize($this->twitterAuth);
		
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### TWITTER: USER FEED ###';
			$objTemplate->title = '@' . $this->arrTwitterAuth['screen_name'];

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
 			$objCacheFile = FileCache::getInstance( 'twitter.ce_twitter_userfeed.' . $this->id );
 			
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
			$intLimit = $this->twitterLimit > 0 ? $this->twitterLimit : false;
			$arrParams = array(
				'screen_name'		=>	$this->arrTwitterAuth['screen_name'], 
				'include_rts'		=>	true, 
				'include_entities'	=>	true
			);
			if($intLimit)
				$arrParams['count'] = $intLimit;
			$objTwitter = new Twitter($this->arrTwitterAuth['oauth_token'], $this->arrTwitterAuth['oauth_token_secret']);
			$arrResults = $objTwitter->getStatusUpdates( $arrParams );

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
			$objTemplate->from_user = $post->user->screen_name;
			$objTemplate->profile_image_url = $post->user->profile_image_url;
			$objTemplate->statusLink = 'http://twitter.com/#!/'.$post->user->screen_name .'/status/'.$post->id_str;
			$objTemplate->userLink = 'http://twitter.com/#!/'.$post->user->screen_name;
			
			$strBuffer .= $objTemplate->parse();
		}
		
		$this->Template->twitter = $strBuffer;
		
	}

}