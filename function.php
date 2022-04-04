<?php

	/*
	 * Emailフォーマットチェック
	 *
	 * ◆引数
	 * $param1：Emailのチェック対象文字列
	 *
	 * ◆戻り値
	 * TRUE：正常
	 * FALSE：フォーマットエラー
	 */
	function checkEmail($param1){
		//半角英数字を1文字以上使用し、末尾に@test.co.jpを固定
		$pattern = '/^[a-zA-Z0-9]+@test\.co\.jp$/';
		//preg_matchでメールアドレスを判定
		if ($param1 != "" && !preg_match($pattern, $param1)){
			return false;
		}else{
			return true;
		}
	}

	/*
	 * 文字数チェック
	 *
	 * ◆引数
	 * $param1：チェック対象文字列
	 * $length：文字数
	 *
	 * ◆戻り値
	 * TRUE：$param1の文字数 <= $length
	 * FALSE：$param1の文字数 > $length
	 */
	function checkLen($param1,$length){
		//mb_strlenで文字列の長さを取得
		if(mb_strlen($param1) > $length){
			return false;
		}else{
			return true;
		}
	}

	/*
	 * 空文字チェック
	 *
	 * ◆引数
	 * $param1：チェック対象文字列
	 *
	 * ◆戻り値
	 * TRUE：$param1に値がない
	 * FALSE：$param1に値がある
	 */
	function isBlank($param1){
		if ($param1 === ""){
			return true;
		}else{
			return false;
		}
	}
?>