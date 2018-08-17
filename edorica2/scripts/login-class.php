<?php
class login{
	public $status =0;
	function _construnct(){
		$this->status = $this->status();
		
		}
	function status($prestatus=0){
		if($prestatus==$GLOBALS['login_success']){
			return 1;
			}
		else if(!isset($_COOKIE[$GLOBALS['login_email_hash']])&&!isset($COOKIE[$GLOBALS['login_password_hash']])){
			$login_status=$GLOBALS['login_failed'];
			return $login_status;
			}
			else{
				$login_status=$GLOBALS['login_success'];
				return $login_status;
				}		
		}
}
?>