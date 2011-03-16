<?php
/**
 * きっとTwitterクラス
 * -----------------------------------------------------------------
 * Twitter API を操作するクラス
 * -----------------------------------------------------------------
 * @author	Hajime MATSUMOTO<mail@hazime.org>
 * @date		20110317
 * -----------------------------------------------------------------
 */
class KtTwitter {

	private $consumerKey, $secretKey, $requestTokenURL, $accessTokenURL, $authorizeURL;

	function __construct( ){
	}

	/**
	 * 基本情報のセッター
	 */
	function setConsumerKey( $CK ){
		$this->consumerKey = $CK;
	}
	function setConsumerSecret( $S ){
		$this->consumerSecret = $S;
	}
	function setRequestTokenURL( $RTU ){
		$this->requestTokenURL = $RTU;
	}
	function setAccessTokenURL( $ATU ){
		$this->accessTokenURL = $ATU;
	}
	function setAuthorizeURL( $AU ){
		$this->authorizeURL = $AU;
	}

	/**
	 * Oauthをスターとする
	 */
	function startOAuth( ){

	}

	/**
	 * Get Auth Link
	 */
	function getOAuthLink( ){
		$to = new TwitterOAuth($this->consumerKey, $this->consumerSecret);
		$tok = $to->getRequestToken();
		$_SESSION['oauth_request_token'] = $token = $tok['oauth_token'];
		$_SESSION['oauth_request_token_secret'] = $tok['oauth_token_secret'];
		$link = $to->getAuthorizeURL($_SESSION['oauth_request_token']);
		return $link;
	}

	/**
	 * OAuthのスタートとレスポンスの判定
	 */
	function isOAuthResponded( ){
		if($_SESSION['oauth_request_token_secret'] !== "" && $_SESSION['oauth_request_token'] !== "" && isset($_GET['oauth_token'])){
			$to = new TwitterOAuth($this->consumerKey, $this->consumerSecret, $_SESSION['oauth_request_token'], $_SESSION['oauth_request_token_secret']);
			$tok = $to->getAccessToken();

      // Tokenをセッションに格納 
      $_SESSION['oauth_access_token'] = $tok['oauth_token'];
      $_SESSION['oauth_access_token_secret'] = $tok['oauth_token_secret'];
			$_SESSION['username'] = $tok["screen_name"];
			return true;
		}
		return false;
	}

	/**
	 * Oauthレスポンスを受け取る
	 */
	function getAccessToken( ){
		return $_SESSION["oauth_access_token"];
	}
	function getAccessSecret( ){
		return $_SESSION["oauth_access_token_secret"];
	}
	function getRequestToken( ){
		return $_SESSION["oauth_request_token"];
	}
	function getRequestSecret( ){
		return $_SESSION["oauth_request_token_secret"];
	}
	function getScreenName( ){
		return $_SESSION["screen_name"];
	}

	/**
	 * つぶやきを取得する
	 */
	function apiShowTweet( $accessToken, $accessSecret, $option = array("count"=>50 ) ){
		$to = new TwitterOAuth($this->consumerKey,$this->consumerSecret, $accessToken,$accessSecret);
		$req = $to->OAuthRequest("https://twitter.com/statuses/home_timeline.xml","GET", $option );
		// XML文字列をオブジェクトに代入する
		$xml = simplexml_load_string($req);
		// foreachで呟きの分だけループする
		foreach($xml->status as $status){
			$status_id = $status->id; // 呟きのステータスID
			$text = $status->text; // 呟き
			$user_id = $status->user->id; // ユーザーナンバー
			$screen_name = $status->user->screen_name; // ユーザーID（いわゆる普通のTwitterのID）
			$name = $status->user->name; // ユーザーの名前（HNなど）
			echo "<p><b>".$screen_name." / ".$name."</b> <a href=\"http://twitter.com/".$screen_name."/status/".$status_id."\">この呟きのパーマリンク</a><br />\n".$text."</p>\n";
		}
	}

}
?>
