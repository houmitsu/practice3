<?php

//クラス宣言
class DbconnectClass {

	//メソッド宣言
	public function getDbconnect(){

		//DNS情報(接続するDBの情報)
		$dsn = 'mysql:host=localhost;dbname=keijiban;charset=utf8';
		//データベースにログインする際のユーザ名
		$user = 'root';
		//データベースにログインする際のパスワード
		$password = 'root';

		//例外処理のための構文
		try {
			//例外の発生する可能性のある処理
			$db = new PDO($dsn,$user,$password);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			return $db;

		} catch (PDOException $e){
			//例外が発生したときに行う処理
			header('Location: ./error.php');
			exit;
		}

	}
}

?>