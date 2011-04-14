<?
require_once("errors.list.php");

if( !defined('DEBUG_MODE') ) define("DEBUG_MODE",false);

global $__LAST_ERROR;

function error_handler($message, $module_name){
	global $__LAST_ERROR;
	
	if( !is_int( $message ) ){
		if( DEBUG_MODE )
			print "Module: $module_name. Error: $message<Br>";
	}
	else{
		$__LAST_ERROR = $message;
		if( DEBUG_MODE )
			print "Module: $module_name. Error: ".$GLOBALS['ERRORS_LIST'][$message]."<Br>";
		
	}
}

function errors_get_last(){
	global $__LAST_ERROR;
	
	return $__LAST_ERROR;
}

?>