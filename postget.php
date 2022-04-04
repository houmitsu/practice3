<!DOCTYPE HTML>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>GET POST</title>
</head>
<body style="padding:20px,">
	<div>
	<!--●の部分を修正してください。-->
	<!--ここから-->
		<p> [GET] </p>
		<form method="get" action="postget.php">
			<input type="text" name="text" value="">
			<input type="submit" name="btn" value="GET送信">
		</form>
		<p> [POST] </p>
		<form method="post" action="postget.php">
			<input type="text" name="text" value="">
			<input type="submit" name="btn" value="POST送信">
		</form>

<?PHP
	echo "$_GETの中身<pre>";
	var_dump($_GET);
	echo "</pre>";
	echo "$_POSTの中身<pre>";
	var_dump($_POST);
	echo "</pre>";

	//個別に値をとる場合
	echo "テキストボックスの中身(GET)<pre>". $_GET["text"]. "</pre>";
	echo "テキストボックスの中身(POST)<pre>". $_POST["text"]. "</pre>";
?>

	<!--ここまで-->
</div>
</body></html>