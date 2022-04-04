<?php

	//セッションスタート
	session_start();

	//DB接続class読込、共通関数読込
	include 'dbconnect.php';
	include 'function.php';

	//$SESSION['userId']がある
	if(isset($SESSION['userId'])){
		header('Location: ./input.php');
		exit();
	}
	//ログインボタンを押した
	if(isset($_POST['login'])){

		//エラー変数を空にする
		$err = "";

		//空文字チェック
		if(isBlank($_POST['userID'])){
			$err .= "「ID」";
		}
		//空文字チェック
		if(isBlank($_POST['password'])){
			$err .= "「パスワード」";
		}

		//$errが空である
		if(isBlank($err)){

			//ユーザ情報を取得
			$db = new DbconnectClass();
			$stmt = $db->getDbconnect()->prepare("
					select
						USER_ID,
						USER_NAME,
						EMAIL
					from
						ACCOUNT
					where
						USER_ID=:userID
					and
						USER_PASS=:userPS;");
			$stmt->bindParam(":userID", $_POST['userID'], PDO::PARAM_STR);
			$stmt->bindParam(":userPS", $_POST['password'], PDO::PARAM_STR);
			$stmt->execute();

			//ユーザ情報を取得できた場合
			if($row = $stmt->fetch()){

				//$_SESSIONにACCOUNTデータを格納
				$_SESSION['userId']		= $row['USER_ID'];
				$_SESSION['userName']	= $row['USER_NAME'];
				$_SESSION['email']		= $row['EMAIL'];

				//$_SESSION['actionName']に"index_login"を格納
				$_SESSION['actonName'] = "index_login";

				//一覧画面(input.php)に遷移
				header('Location: ./input.php');
				exit();

			//ユーザ情報を取得できなかった場合
			}else{
				$err .= "「ID」「パスワード」が存在しません";
			}
		//$errが空でない
		}else{
			$err .= "が入力されていません";
		}
	//ログインボタン押していない
	}else{
		//$_SESSION['actionName']に"index_display"を格納
		$_SESSION['actionName'] = "index_display";
	}


?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="./css/master.css" type="text/css">
	<title>掲示板</title>
</head>
<body>
	<header>
		掲示板
	</header>
		<p>あなたのIDとパスワードを入力してログインしてください。</p>

		<!-- $err(エラー変数)を赤字で出力 -->
		<p><FONT COLOR="RED"><?php echo $err; ?></FONT></p>
		<form action="./index.php" method="post">
			<p>
				<label class="itemName">ID:</label>
				<input type="text" name="userID" value="">
			</p>
			<p>
				<label class="itemName">パスワード:</label>
				<input type="password" name="password">
			</p>
			<input class="button" type="submit" name="login" value="ログイン">
		</form>
</body>
</html>