<?php
/* 
 * Global config file
 */
define('TEST_MODE',true);
//if(true===TEST_MODE) define('DEBUG_MODE',true);
define('GLOBAL_MULTI_DOMAIN',false);
define('GLOBAL_INC_PATH',$_SERVER['DOCUMENT_ROOT'].'/inc');
set_include_path(GLOBAL_INC_PATH.PATH_SEPARATOR.get_include_path());

?>
