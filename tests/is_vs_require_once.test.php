<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once $_SERVER['DOCUMENT_ROOT'].'/config.inc.php';
$time= microtime(TRUE);

for ($i=0;$i<10000;$i++){
    require_once('db.inc.php');
}

$delta=  microtime(TRUE)-$time;
echo 'require_once test: '.$delta.'<br>';

$time= microtime(TRUE);

for ($i=0;$i<10000;$i++){
    load_module('db.inc.php');
}

$delta=  microtime(TRUE)-$time;
echo 'if test: '.$delta.'<br>';

function load_module($module)
{
    static $loaded;
    if ($loaded[$module]){
        return true;
    }
    include ($module);
    $loaded[$module]=true;
    return TRUE;
}

?>
