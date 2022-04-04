<?php
	function vending_machine($price,$juiceName){
		if($price >= 120){
			return $juiceName . "のお買い上げありがとうございます！ <br>";
		}else{
			return $juiceName . "の購入金額が不足しています。 <br>";
		}
	}
?>