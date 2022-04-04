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

	//確認ボタン押した
	if(isset($_POST['submit'])){

		//トークンが一致するか確認
		if($_POST['token'] === $_SESSION['token']){

			//$_POSTのデータを$_SESSIONに保存
			$_SESSION['userName'] 	= $_POST['username'];
			$_SESSION['email'] 		= $_POST['email'];
			$_SESSION['title'] 		= $_POST['title'];
			$_SESSION['text'] 		= $_POST['text'];
			$_SESSION['color'] 		= $_POST['color'];

			//エラー変数を空にする
			$err = "";

			//Emailフォーマットチェック
			if(!checkEmail($_POST['email'])){
				$err .= "E-mailは半角英数字@test.co.jpを入力してください。<br>";
			}

			//文字数チェック
			if(!checkLen($_POST['title'], 50)){
				$err .= "タイトルは50文字以内で入力してください。<br>";
			}

			//空文字チェック
			if(isBlank($_POST['text'])){
				$err .= "本文を入力してください。 <br>";
			}

			//$errが空である
			if(isBlank($err)){
				//$_SESSION['actionName']に"input_check"を格納
				$_SESSION['actionName'] = "input_check";
				//確認画面に遷移
				header('Location: ./confirm.php');
				exit();
			}

		//トークンが一致しない
		}else{
			//セッションを削除する
			$_SESSION = array();
			session_destroy();
			//ログイン画面に遷移
			header('Location: ./index.php');
			exit();
		}

	//クリアボタンを押した
	}elseif(isset($_POST['clear'])){
		//セッションを空にする
		$_SESSION['userName']	= "";
		$_SESSION['title']		= "";
		$_SESSION['text']		= "";
		$_SESSION['color']		= "";

		//$_SESSION['actionName']に"input_clear"を格納
		$_SESSION['actionName'] = "input_clear";

	//ボタンを押していない
	}else{

		//$_SESSION['actionName']に"input_display"を格納
		$_SESSION['actionName'] = "input_display";
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
		<!-- エラー変数を赤文字で出力 -->
		<p><FONT COLOR="RED"><?php echo $err; ?></FONT></p>
	</header>
		<form action="./input.php" method="post">
			<table class="inputArticle">
				<tr>
					<td class="itemName">名前</td>
					<td>
						<!-- XSS対策としてエスケープ処理をして出力する -->
						<input type="text" name="username"
							value="<?php echo htmlspecialchars($_SESSION['userName'], ENT_QUOTES, "UTF-8"); ?>">
					</td>
				</tr>
				<tr>
					<td class="itemName">E-mail</td>
					<td>
						<!-- XSS対策としてエスケープ処理をして出力する -->
						<input type="text" name="email"
							value="<?php echo htmlspecialchars($_SESSION['email'], ENT_QUOTES, "UTF-8"); ?>">
					</td>
				</tr>
				<tr>
					<td class="itemName">タイトル</td>
					<td>
						<!-- XSS対策としてエスケープ処理をして出力する -->
						<input type="text" name="title"
							value="<?php echo htmlspecialchars($_SESSION['title'], ENT_QUOTES, "UTF-8"); ?>">
					</td>
				</tr>
				<tr>
					<td class="itemName">本文</td>
					<td>
						<!-- XSS対策としてエスケープ処理をして出力する -->
						<textarea name="text" cols="35" rows="5"><?php
							echo htmlspecialchars($_SESSION['text'], ENT_QUOTES, "UTF-8"); ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="itemName">文字色</td>
					<td>

						<?php
							//DBに接続してCOLOR_MASTERからCOLOR_ID COLOR_CODE COLOR_NAMEを取得
							$db = new DbconnectClass();
							$stmt = $db->getDbconnect()->prepare("
									select
										COLOR_ID,
										COLOR_CODE,
										COLOR_NAME
									from
										COLOR_MASTER;");
							$stmt->execute();

							//while文で繰り返し処理
							while($row = $stmt->fetch()){

						?>
								<!-- ラジオボタンのvalueとidに$row['COLOR_ID']を設定する -->
								<input class="radio" type="radio" name="color"
									value="<?php echo $row['COLOR_ID'];?>" id="color_<?php echo $row['COLOR_ID'];?>"
									<?php
										if(($_SESSION['color'] === "" && $row['COLOR_ID'] === 3)
												|| ($_SESSION['color'] === $row['COLOR_ID'])){
											echo "checked";
										}
									?>
								>

								<!-- labelタグの記述 -->
								<label for="color_<?php echo $row['COLOR_ID']; ?>"
									style="color:#<?php echo $row['COLOR_CODE']; ?>"><?php
									echo $row['COLOR_NAME']; ?></label>
							<?php }?>
					</td>
				</tr>
			</table>
			<input class="button" type="reset"	name="clear"	value="クリア">
			<input class="button" type="submit" name="submit" value="確認">

			<?php
				//csrfの対策としてトークンを生成し保存
				$token = hash('sha256', session_id());
				$_SESSION['token'] = $token;
			?>
			<!-- トークンを埋め込む -->
			<input type="hidden" name="token" value="<?php echo $token ?>">
		</form>
		<hr>
		<?php
			//ARTICLEとCOLOR_MASTERを外部結合させDB接続
			$stmt = $db->getDbconnect()->prepare(
						"select
							A.ARTICLE_ID,
							A.CREATE_DATE,
							A.NAME,
							A.EMAIL,
							A.TITLE,
							A.TEXT,
							C.COLOR_CODE
						from
							ARTICLE A
							left join COLOR_MASTER C
							on A.COLOR_ID = C.COLOR_ID
						order by A.CREATE_DATE desc;");
			$stmt->execute();

			//繰り返し処理
			while($row = $stmt->fetch()){
		?>

		<!-- 文字の色を$row['COLOR_CODE']の色に設定 -->
		<table class="postedArticle" style="color:#<?php echo $row['COLOR_CODE']; ?>">
			<tr>
				<!-- $row['ARTICLE_ID']を出力 -->
				<td class="articleId"><?php echo $row['ARTICLE_ID']; ?></td>
				<td class="articleTitle">
					<?php
						//空文字チェック
						if(isBlank($row['TITLE'])){
							echo "(no title)";
						//空でない
						}else{
							echo htmlspecialchars($row['TITLE'], ENT_QUOTES, "UTF-8");
						}
					?>
				</td>
			</tr>
			<tr>
				<td class="articleText" colspan="2">
					<!-- 改行可の状態でエスケープ処理をして出力 -->
					<?php echo nl2br(htmlspecialchars($row['TEXT'], ENT_QUOTES, "UTF-8")); ?>
				</td>
			</tr>
			<tr>
				<td class="articleDate" colspan="2">
					<!-- strtotimeでタイムスタンプへ変換 -->
					<?php echo date("Y年m月d日 H時i分", strtotime($row['CREATE_DATE'])); ?>
					<?php
						//空文字チェック
						if(isBlank($row['NAME'])){
							$name = "nobody";
						//空でない
						}else{
							$name = $row['NAME'];
						}

						//空文字チェック
						if(!isBlank($row['EMAIL'])){
					?>
							<a href="mailto:<?php echo $row['EMAIL']; ?>">
							<?php echo htmlspecialchars($name, ENT_QUOTES, "UTF-8"); ?></a>
					<?php
						//空でない
						}else{
							echo htmlspecialchars($name, ENT_QUOTES, "UTF-8");

						}
					?>
				</td>
			</tr>
		</table>
			<?php } ?>
</body>
</html>