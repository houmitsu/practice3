<?php
	class VendingMachine{

		private $maker = "翔栄電気";

		public function buy($money){
			$message = "";
			if($money >= 150){
				$message .= "お買い上げありがとうございます！ <br>";
				$message .= $this -> lucky();
			}else{
				$message .= "購入金額が不足しています。 <br>";
			}
			return $message;
		}

		private function lucky(){
			if (rand(1,10) == 1){
				return "もう一本サービス！ <br>";
			}else{
				return "ハズレ<br>";
			}
		}

		public function getMaker(){
			return $this -> maker;
		}
	}

	class CupnoodleVendor extends VendingMachine{
		private $maker = "翔栄エレクトロニクス";

		public function getMaker(){
			return $this -> maker;
		}
	}
?>