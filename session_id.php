<?php
	session_start();
?>

<html>
<body>
	<?php
		if(!isset($_COOKIE["PHPSESSID"])){
			echo "初回の訪問です。セッションを開始します。";
		}else{
			echo "セッションは開始しています。<br> ";
            echo "セッションIDは".$_COOKIE["PHPSESSID"]."です。";
		}
	?>
</body>
</html>