<?php

function a_b_tester_uninstall(){
	// Cheackes if the file is called by wordpress
	defined('WP_UNINSTALL_PLUGIN') or die(require_once('404.php'));
	// Just a customary function for standard uninstall.
}
