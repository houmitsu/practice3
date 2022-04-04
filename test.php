<?php
	function get_price($price){
		$price = $price * 1.10;
		return $price;
	}

	echo get_price(300);
?>