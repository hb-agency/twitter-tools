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
 * Class Twitter - provides basic methods for working with Twitter API
 *
 * @copyright  Andreas Schempp 2009-2010, Winans Creative 2011 
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @author     Blair Winans <blair@winanscreative.com>
 * @package    Controller
 */
 
 require_once('TwitterOAuth.php');
 
 class Twitter extends Controller
 {
 	
 	/**
	 * OAuth Object
	 */
 	protected $OAuth;
 	
 	/**
	 * Error message
	 */
 	protected $strErrorHTML = '';
 	
 	/**
	 * Default consumer key fallback to Twitter OAuth configuration for application "Contao Open Source CMS"
	 */
 	protected $strConsumerKey  = 'WehyUu32jxf2mJmN9ijeDw';
 	
 	/**
	 * Default consumer secret fallback to Twitter OAuth configuration for application "Contao Open Source CMS"
	 */
 	protected $strConsumerSecret  = 'hwnYKGAlySg5eXJGtJU9RoOHQLyArRa2KN4zO3oBQ';
 	
 
 	/**
	 * Load database object and default OAuth object
	 */
	public function __construct($strOAuthToken = NULL, $strOAuthSecret = NULL)
	{
		parent::__construct();
		$this->import('Database');
		
		if(strlen($GLOBALS['TL_CONFIG']['twitter_key'])  && strlen($GLOBALS['TL_CONFIG']['twitter_secret']) )
		{
			$this->strConsumerKey = $GLOBALS['TL_CONFIG']['twitter_key'];
			$this->strConsumerSecret = $GLOBALS['TL_CONFIG']['twitter_secret'];
		}
		else
		{	
			$this->strErrorHTML = '<p class="tl_gerror">'.$GLOBALS['TL_LANG']['TWIT']['twitter_auth_insecure'].'</p>';
		}
		
		$this->OAuth = new TwitterOAuth($this->strConsumerKey, $this->strConsumerSecret, $strOAuthToken, $strOAuthSecret );
	}
	
	
	
	/**
	 * Verify credentials
	 */
 	public function verifyCredentials()
 	{
		$strResponse = $this->OAuth->get('account/verify_credentials');
		
		if( $this->wasValidRequest() )
			return true;
			
		$this->log('Error verifying Twitter credentials', __METHOD__, TL_ERROR);
		return false;
 	}
 	
 	
 	
 	/**
	 * Check the HTTP response code of the last request
	 */
 	public function wasValidRequest()
 	{
 		if($this->OAuth->http_code == 200)
 			return true;
 			
 		return false;
 	}
 	
 	
 	
 	/**
	 * Get a request token with optional callback URL
	 */
 	public function getRequestToken( $strCallback='' )
 	{
 		return $this->OAuth->getRequestToken( $strCallback );
  	}
  	
  	

 	/**
	 * Get a request token with optional callback URL
	 */
 	public function getAccessToken( $blnAuthVerifier= FALSE )
 	{
 		return $this->OAuth->getAccessToken( $blnAuthVerifier );
  	}
  	
  	
  	
  	/**
	 * Get an authorization URL from a request token with optional 
	 */
  	public function getAuthorizeURL( $strToken, $blnSigninWithTwitter = true )
  	{
  		return $this->OAuth->getAuthorizeURL( $strToken, $blnSigninWithTwitter );
  	}
  	
 	
 	/**
 	 * Send a status update
	 */
 	public function sendStatusUpdate( $strStatus, $strURL='', $strParams='', $arrParams=array() )
 	{
 		$arrParams['status'] = $this->preparePost($strStatus, $strURL, $strParams);
 		return $this->sendTwitterRequest('statuses/update', 'post', $arrParams );
 	}
 	
 	/**
 	 * Get status updates
	 */
 	public function getStatusUpdates( $arrParams=array() )
 	{
 		return $this->sendTwitterRequest('statuses/user_timeline', 'get', $arrParams );
 	}
 	
 	
 	
  	/**
 	 * Send either a GET or POST request to Twitter
	 */
 	public function sendTwitterRequest( $strRequest, $strMethod, $arrParams )
 	{ 	
 		if( $this->verifyCredentials() )
 		{
 			switch($strMethod)
 			{
 				case 'POST':
 				case 'post':
 					$objResponse = $this->OAuth->post($strRequest, $arrParams );
 					break;
 				
 				case 'GET':
 				case 'get':
 				default:
 					$objResponse = $this->OAuth->get($strRequest, $arrParams );
 					break;
			}

			if ($this->wasValidRequest() )
			{
				return $objResponse;
			}
		}
		$this->log('Error send/receiving from Twitter: [' . $strRequest . ']', __METHOD__, TL_ERROR);
		
		return array();
 	} 	
 	
 	
 	/**
 	 * Get an array of Twitter posts based on a search string
	 */
	 public function getSearchResults( $strSearch, $intLimit=0 )
 	{ 	 
 		$strLimit = $intLimit > 0 ? '&rpp='. $intLimit : '';
 		
 		$objRequest = new Request();
		$objRequest->send('http://search.twitter.com/search.json?q=' . urlencode($strSearch) . $strLimit);
		
		$objResult= json_decode($objRequest->response);
 		
 		return count($objResult->results) ? $objResult->results : array();
 	}
 	
 	
 	
	/**
 	 * Prepare text and URL for posting to Twitter
	 */ 
 	protected function preparePost( $strStatus, $strURL, $strURLParams )
 	{
 		$this->import('String');
			
		// Decode entities, replace insert tags
		$strStatus = $this->String->decodeEntities($strStatus);
		$strStatus = $this->restoreBasicEntities($strStatus);
		$strStatus = $this->replaceInsertTags($strStatus);
		
		// Shorten message
		if (strlen($strStatus) > 120)
		{
			$strStatus = $this->String->substr($strStatus, 110) . ' ...';
		}
				
		if (strlen($strUrl))
		{
			// Make sure url has protocol and domain
			if (substr($strUrl, 0, 4) != 'http')
			{
				$strUrl = $this->Environment->base . $strUrl;
			}
			
			if (strlen($strUrlParams))
			{
			    $strUrl .= (strpos($strUrl, '?') === false ? '?' : '&') . $strUrlParams;
			}
		
			$strUrl = $this->shortUrl($strUrl);
		}
		
		return urlencode($strStatus . ' ' . $strUrl);
 	}
 	
 	
 	
 	/**
 	 * Shorten a URL
	 * Short url using is.gd (http://is.gd/api_info.php)
	 */
	private function shortUrl($strUrl)
	{
		$objRequest = new Request();
		$objRequest->send('http://is.gd/api.php?longurl='.$strUrl);
		
		if ($objRequest->hasError())
			return $strUrl;
		
		return $objRequest->response;
	}
	
	
	
	/**
	 * Return a DCA button that allows authentication on Twitter using OAuth
	 */
	public function getAuthenticateButton($dc, $label, $strRedirectURL)
	{
		$strButton = $this->strErrorHTML .'<div class="' . $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['eval']['tl_class'] . '"><h3><label>'.$GLOBALS['TL_LANG'][$dc->table][$dc->field][0].'</label></h3><a href="' . $this->addToUrl('twitterauth=1') . '">' . $this->generateImage('system/modules/twitter-tools/html/connect.png', 'Sign in with Twitter') . '</a>';
		
		// Start Twitter authentication
		if ($this->Input->get('twitterauth'))
		{
			if (!$_SESSION['oauth_token'] || !$_SESSION['oauth_token_secret'])
			{				
				// Get temporary credentials.
				$arrRequestToken = $this->getRequestToken($this->Environment->base . $this->Environment->request);
				
				if (!$this->wasValidRequest())
				{
					$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['TWIT']['twitter_auth_token'];
					$this->redirect($strRedirectURL);
				}
				
				// Save temporary credentials to session.
				$_SESSION['oauth_token'] = $arrRequestToken['oauth_token'];
				$_SESSION['oauth_token_secret'] = $arrRequestToken['oauth_token_secret'];
				
				$this->redirect($this->getAuthorizeURL($arrRequestToken['oauth_token']));
			}
			elseif ($this->Input->get('denied') != '')
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['TWIT']['twitter_auth_denied'];
				$this->redirect($strRedirectURL);
			}
			elseif ($this->Input->get('oauth_token') != '' && $this->Input->get('oauth_token') == $_SESSION['oauth_token'])
			{
				// Create Twitter object with app key/secret and token key/secret from default phase
				$this->OAuth = new TwitterOAuth($this->strConsumerKey, $this->strConsumerSecret, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
				
				// Request access tokens from twitter
				$arrAccessToken = $this->getAccessToken($_REQUEST['oauth_verifier']);
								
				if (!$this->wasValidRequest())
				{
					$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['TWIT']['twitter_auth_token'];
					$this->redirect($strRedirectURL);
				}
				
				// Save the access tokens. Normally these would be saved in a database for future use.
				$this->Database->query("UPDATE " . $dc->table . " SET " . $dc->field . "='" . serialize($arrAccessToken) . "' WHERE id={$dc->id}");
				
				// Remove no longer needed request tokens
				unset($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
				
				$_SESSION['TL_CONFIRM'][] = $GLOBALS['TL_LANG']['TWIT']['twitter_auth_success'];
				$this->redirect($strRedirectURL);
			}
			else
			{
				// Remove session, try again
				unset($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
				$this->reload();
			}
		}
		
		$arrAccessToken  = deserialize($dc->activeRecord->{$dc->field});
				
		// Create a Twitter object with consumer/user tokens.
		$this->OAuth = new TwitterOAuth($this->strConsumerKey, $this->strConsumerSecret, $arrAccessToken['oauth_token'], $arrAccessToken['oauth_token_secret']);
		
		// If method is set change API call made. Test is called by default.
		$this->verifyCredentials();
		
		if ($this->wasValidRequest())
		{
			return $strButton . '<p class="tl_help tl_tip">'.$GLOBALS['TL_LANG']['TWIT']['twitter_auth_ok'].'</p></div>';
		}
		else
		{
			return $strButton . '<p class="tl_error">'.$GLOBALS['TL_LANG']['TWIT']['twitter_auth_error'].'</p></div>';
		}
	}
 
 }