<?php
include "conf.php";
include realpath(dirname(__FILE__)."/../../")."/twitter/twitter.class.php";

require_once realpath(dirname(__FILE__).'/../').'/lib/twitteroauth.php';

$Twitter = new KtTwitter( );
$Twitter->setConsumerKey(CONSUMER_KEY);
$Twitter->setConsumerSecret(CONSUMER_SECRET);
$Twitter->setRequestTokenURL(REQUEST_TOKEN_URL);
$Twitter->setAccessTokenURL(ACCESS_TOKEN_URL);
$Twitter->setAuthorizeURL(AUTHORIZE_URL);
session_start();

if(!$Twitter->isOAuthResponded( )){
	$link = $Twitter->getOAuthLink( );
	echo $link;
}else{
	$at = $Twitter->getAccessToken();
	$as = $Twitter->getAccessSecret();

	$Twitter->apiShowTweet($at, $as );

}
?>
