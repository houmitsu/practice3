<?php
	if(isset ( $_COOKIE["test"])){
		$count = $_COOKIE["test"] + 1 ;
	}else{
		$count = 1 ;
	}
	setcookie("test",$count);
?>

<html>
<body>
	<?php
		echo "<p>訪問回数は".$count."回目です</p>";
	?>
</body>
</html>