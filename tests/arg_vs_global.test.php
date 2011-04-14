<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$test=';lkjpiHJKLmnjLIj;lkjOUGhlkj ШОР oIJH oiu OiJHLkj uToIJU lKJOUyti UyOIJ OUIytIYt oIuyoUy
    oIUYIOUHolkj OYiuH opKJolIH OijpOjopI HoIJHpOjk: LikOuihlikJ;LKJ OuiHplkjPou Oiyojk; uj';

$time=  microtime(TRUE);
for($i=0;$i<10000;$i++){
    do_any_global();
}
$delta=  microtime(TRUE)-$time;

echo "Result for global: $delta <br>";

$time=  microtime(TRUE);
for($i=0;$i<10000;$i++){
    do_any_arg($test);
}
$delta=  microtime(TRUE)-$time;

echo "Result for arg: $delta <br>";

$time=  microtime(TRUE);
for($i=0;$i<10000;$i++){
    do_any_static();
}
$delta=  microtime(TRUE)-$time;

echo "Result for static: $delta <br>";

function do_any_global()
{
  //  global $test;
  //  return($test.'123');
}
function do_any_arg($arg)
{
    return($arg.'123');
}
function do_any_static()
{
    $test=get_static();
    return($test.'123');
}

function get_static()
{
    static $arg;
    if($arg){
        return($arg);
    }
    global $test;
    $arg=$test;
}

?>
