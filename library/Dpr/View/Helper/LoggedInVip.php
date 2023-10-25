<?php
class Dpr_View_Helper_LoggedInVip {

	function loggedInVip() {
		$auth = Zend_Auth::getInstance();
		
		if ( $auth->hasIdentity() ) {   
			$logoutUrl ='/admin/login/logoutvip';
			$user = $auth->getIdentity();
			
			$username = $user->nama;
			$string = "Masup sebagai <b>". $username ." &middot; <a href='" . $logoutUrl . "'>logout</a></b>";
		}
		else {
			$loginUrl = '/admin/login';
			$string = "<a href='". $loginUrl . "'>Log in </a>"; 
		}
		
		return $string;
	
	}

}