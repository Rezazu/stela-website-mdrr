<?php
class Dpr_View_Helper_LoggedInUser {

	function loggedInUser() {
		$auth = Zend_Auth::getInstance();
		
		if ( $auth->hasIdentity() ) {   
			$logoutUrl ='/admin/login/logout';
			$user = $auth->getIdentity();
			
			$username = $user->nama;
			$string = "Masuk sebagai <b>". $username ." &middot; <a href='" . $logoutUrl . "'>logout</a></b>";
		}
		/*
		else {
			$loginUrl = '/admin/login';
			$string = "<a href='". $loginUrl . "'>Log in </a>"; 
		}*/
		
		return $string;
	
	}

}