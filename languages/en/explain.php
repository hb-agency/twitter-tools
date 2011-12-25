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
 * @copyright  Andreas Schempp 2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: explain.php 147 2010-09-14 21:41:16Z aschempp $
 */


/**
 * Help Wizard
 */
$GLOBALS['TL_LANG']['XPL']['twitter_auth'] = '<p class="tl_help_table">To securely link your website with your twitter account, it is HIGHLY recommended to register a custom "Browser" application! You can do this at <a href="http://dev.twitter.com/apps/new" onclick="window.open(this.href); return false">http://dev.twitter.com/apps/new</a>.<br /><br />
The callback url does not matter, just make sure you provide something or Twitter will fall back to a "Client" application type.<br /><br />After successfully registering your application, please add the "Consumer key" and "Consumer secret" to your system configuration settings.</p>';

$GLOBALS['TL_LANG']['XPL']['twitter_search'] = '
<p class="tl_help_table">In addition to the parameters listed in the <a href="https://dev.twitter.com/doc/get/search" onclick="window.open(this.href); return false;">Twitter Search API documentation</a>, there are a number of operators you can use to modify the behavior of query.</p>
<table class="tl_help_table">
    <thead>
      <tr>
        <th>Example</th>
        <th>Finds tweets...</th>
      </tr><tr>
    </tr></thead>
    <tbody>
      <tr>
        <td>twitter search</td>
        <td>containing both "twitter" and "search". This is the default operator</td>
      </tr>
      <tr>
        <td>"happy hour"</td>
        <td>containing the exact phrase "happy hour"</td>
      </tr>
      <tr>
        <td>love OR hate</td>
        <td>containing either "love" or "hate" (or both)</td>
      </tr>
      <tr>
        <td>beer -root</td>
        <td>containing "beer" but not "root"</td>
      </tr>
      <tr>
        <td>#haiku</td>
        <td>containing the hashtag "haiku"</td>
      </tr>
      <tr>
        <td>from:twitterapi</td>
        <td>sent from the user @twitterapi</td>
      </tr>
      <tr>
        <td>to:twitterapi</td>
        <td>sent to the user @twitterapi</td>
      </tr>
      <tr>
        <td>place:opentable:2</td>
        <td>about the place with OpenTable ID 2</td>
      </tr>
      <tr>
        <td>place:247f43d441defc03</td>
        <td>about the place with Twitter ID 247f43d441defc03</td>
      </tr>
      <tr>
        <td>@twitterapi</td>
        <td>mentioning @twitterapi</td>
      </tr>
      <tr>
        <td style="white-space:nowrap;padding-right:8px;">superhero since:2011-05-09</td>
        <td>containing "superhero" and sent since date "2011-05-09" (year-month-day).</td>
      </tr>
      <tr>
        <td>twitterapi until:2011-05-09</td>
        <td>containing "twitterapi" and sent before the date "2011-05-09".</td>
      </tr>
      <tr>
        <td>movie -scary :)</td>
        <td>containing "movie", but not "scary", and with a positive attitude.</td>
      </tr>
      <tr>
        <td>flight :(</td>
        <td>containing "flight" and with a negative attitude.</td>
      </tr>
      <tr>
        <td>traffic ?</td>
        <td>containing "traffic" and asking a question.</td>
      </tr>
      <tr>
        <td>hilarious filter:links</td>
        <td>containing "hilarious" and with a URL.</td>
      </tr>
      <tr>
        <td style="white-space:nowrap">news source:tweet_button</td>
        <td>containing "news" and entered via the Tweet Button</td>
      </tr>
    </tbody>
  </table>';