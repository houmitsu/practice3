<?php

	//セッションスタート
	session_start();

	//DB接続class読込、共通関数読込
	include 'dbconnect.php';
	include 'function.php';

	//セッションハイジャック対策としてセッションを再作成し、古いセッションを捨てる
	session_regenerate_id(true);

	//$_SESSION['userId']がない
	if(!isset($_SESSION['userId'])){
		header('Location: ./index.php');
		exit();
	}

	//戻るボタンを押した
	if(isset($_POST['back'])){
		$_SESSION['actionName'] = "confirm_back";
		header('Location: ./input.php');
		exit();

	//投稿ボタンを押した
	}elseif(isset($_POST['submit'])){

		//トークンが一致するか確認
		if($_SESSION['token'] === $_POST['token']){

			//チェック
			if(checkEmail($_SESSION['email'])
					&& checkLen($_SESSION['title'], 50)
					&& !isBlank($_SESSION['text'])){

				//DBに$_SESSIONのデータを保存
				$db = new DbconnectClass();
				$stmt = $db->getDbconnect()->prepare(
						'insert into ARTICLE(
							CREATE_DATE,
							NAME,
							EMAIL,
							TITLE,
							TEXT,
							COLOR_ID,
							DEL_FLG)
						values(
							now(),
							:name,
							:email,
							:title,
							:text,
							:colorID,
							0)');
				$stmt->bindParam(':name',$_SESSION['userName'], PDO::PARAM_STR);
				$stmt->bindParam(':email',$_SESSION['email'], PDO::PARAM_STR);
				$stmt->bindParam(':title',$_SESSION['title'], PDO::PARAM_STR);
				$stmt->bindParam(':text',$_SESSION['text'], PDO::PARAM_STR);
				$stmt->bindParam(':colorID',$_SESSION['color'], PDO::PARAM_STR);
				$stmt->execute();

				//セッションを空にする
				$_SESSION['title'] = "";
				$_SESSION['text'] = "";
				$_SESSION['color'] = "";

				//$_SESSION['actionName']に "confirm_post"を格納
				$_SESSION['actionName'] = "confirm_post";

				header('Location: ./complete.php');
				exit();

			//チェックしてNOだった
			}else{
				header('Location: ./input.php');
				exit();
			}
		//トークンが一致しない
		}else{

			//セッションを削除
			$_SESSION = array();
			session_destroy();
			header('Location: ./index.php');
			exit();
		}

	//何も押していない
	}else{

		//$_SESSION['actionName']がinput_checkが一致しない
		if($_SESSION['actionName'] !== "input_check"){

			header('Location: ./input.php');
			exit();

		//一致する場合
		}else{
			$_SESSION['actionName'] = "confirm_display";
		}
	}

	//DBに接続してCOLOR_MASTERからCOLOR_CODEを取得
	$db = new DbconnectClass();
	$stmt = $db->getDbconnect()->prepare("
					select
						COLOR_CODE
					from
						COLOR_MASTER
					where
						COLOR_ID=:colorID;");
	$stmt->bindParam(":colorID", $_SESSION['color'], PDO::PARAM_STR);
	$stmt->execute();
	$row = $stmt->fetch();

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
	<p>以下の内容で投稿します。</p>
	<form action="./confirm.php" method="post">
		<table class="inputArticle">
			<tr>
				<td class="itemName">名前</td>
				<td style="color:#<?php echo $row['COLOR_CODE']; ?>">
				<?php
					//空文字チェック
					if(isBlank($_SESSION['userName'])){
						echo "nobody";
					//空でない
					}else{
						//エスケープ処理をして出力
						echo htmlspecialchars($_SESSION['userName'], ENT_QUOTES, "UTF-8");
					}
				?>
				</td>
			</tr>
			<tr>
				<td class="itemName">E-mail</td>
				<td style="color:#<?php echo $row['COLOR_CODE']; ?>">
				<?php
					//エスケープ処理をして出力
					echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, "UTF-8");
				?>
				</td>
			</tr>
			<tr>
				<td class="itemName">タイトル</td>
				<td style="color:#<?php echo $row['COLOR_CODE']; ?>">
				<?php
					//空文字チェック
					if(isBlank($_SESSION['title'])){
						echo "(no title)";
					//空でない
					}else{
						//エスケープ処理をして出力
						echo htmlspecialchars($_SESSION['title'], ENT_QUOTES, "UTF-8");
					}
				?>
				</td>
			</tr>
			<tr>
				<td class="itemName">本文</td>
				<td style="color:#<?php echo $row['COLOR_CODE']; ?>">
				<?php
					//改行可の状態でエスケープ処理をして出力
					echo nl2br(htmlspecialchars($_SESSION['text'], ENT_QUOTES, "UTF-8"));
				?>
				</td>
			</tr>
		</table>
		<input class="button" type="submit" name="back"	 value="戻る">
		<input class="button" type="submit" name="submit" value="投稿">

		<?php
			//csrfの対策としてトークンを生成して保存
			$token = hash('sha256', session_id());
			$_SESSION['token'] = $token;
		?>
		<!-- トークンを埋め込む -->
		<input type="hidden" name="token" value="<?php echo $token; ?>">

	</form>
</body>