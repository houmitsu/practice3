<?php
	class Sample{
		public function __construct($name = "Sample"){
			echo "コンストラクタ:" . $name . "<br>";
		}

		public function __destruct(){
			echo "デストラクタ<br>";
		}
	}

	$sample = new Sample();
	unset($sample);
	$sample2 = new Sample("太郎");
?>