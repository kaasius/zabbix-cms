<?php
/* 
 * Test for all modules
 */

$time=microtime(true);
define('TEST_MEDE',true);
require_once($_SERVER['DOCUMENT_ROOT'].'/config.inc.php');
require_once('utest.inc.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/tests/kernel.test.php');
utest_message("<hr>$_utest_ok tests is Ok, $_utest_errors tests is error");

$delta=microtime(true)-$time;
utest_message("Testing time is $delta c");

?>
