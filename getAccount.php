<?php
	include "dbconnect.php";
	$db = new DbconnectClass();

	$stmt = $db ->getdbconnect() -> prepare("SELECT * FROM ACCOUNT");
	$stmt -> execute();

	while($account = $stmt -> fetch()) {
		echo $account['USER_NAME']. "<br>";
	}
?>