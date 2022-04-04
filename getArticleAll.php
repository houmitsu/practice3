<?php
	include "dbconnect.php";
	$db = new DbconnectClass();

	// SQL実行準備(全件取得)
	$stmt = $db -> getdbconnect() ->prepare ( "SELECT * FROM ARTICLE;" );
	// SQL実行
	$stmt->execute ();
	// 取得したデータの出力
	while ( $row = $stmt->fetch () ) {
		echo "ARTICLE_ID:" . $row ['ARTICLE_ID'] . "<br>";
		echo "CREATE_DATE:" . $row ['CREATE_DATE'] . "<br>";
		echo "NAME:" . $row ['NAME'] . "<br>";
		echo "EMAIL:" . $row ['EMAIL'] . "<br>";
		echo "TITLE:" . $row ['TITLE'] . "<br>";
		echo "TEXT:" . $row ['TEXT'] . "<br>";
		echo "COLOR_ID:" . $row ['COLOR_ID'] . "<br>";
		echo "DEL_FLG:" . $row ['DEL_FLG'] . "<br><br>";
	}
?>