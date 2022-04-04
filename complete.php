<?php

	//セッションスタート
	session_start();

	//セッションハイジャック対策としてセッションを再作成し、古いセッションを捨てる
	session_regenerate_id(true);

	//$_SESSION['userId']がない
	if(!isset($_SESSION['userId'])){
		header('Location: ./index.php');
		exit();
	}

	//一覧画面に戻るボタンを押した
	if(isset($_POST['back'])){
		$_SESSION['actionName'] = "complete_back";
		header('Location: ./input.php');
		exit();

	//押していない
	} else {
		//$_SESSION['actionName']にcomplete_displayを格納
		$_SESSION['actionName'] = "complete_display";
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
		<p>投稿が完了しました。</p>
		<form action="./complete.php" method="post">
			<p><input class="button" type="submit" name="back" value="一覧画面に戻る"></p>
		</form>
</body>
</html>