<?php
	include "vending_machine.php";

	$vendor = new CupnoodleVendor();
	echo $vendor -> buy(180);
	echo "製造元:" . $vendor -> getMaker() . "<br>";
?>