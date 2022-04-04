<?php
	session_start();
?>

<html>
<body>
	<?php
		if(!isset($_SESSION["test"])){
			echo "初回の訪問です。";
			$_SESSION["test"] = 1;
		}else{
			$_SESSION["test"] =  $_SESSION["test"] + 1;
			echo "<p>訪問回数は".$_SESSION["test"]."回目です</p>";
			if(isset($_SESSION["date"])){
				echo "前回の訪問日時は".$_SESSION["date"]."です。<br>";
			}
		}
		$_SESSION["date"] = date("Ymd");
	?>
</body>
</html>