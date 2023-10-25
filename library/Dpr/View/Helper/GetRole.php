<?php
class Dpr_View_Helper_GetRole {

	function getRole() {
		$auth = Zend_Auth::getInstance();
		
		if ( $auth->hasIdentity() ) {   
			$user = $auth->getIdentity();
			return $user->peran;
		}	
	}

}