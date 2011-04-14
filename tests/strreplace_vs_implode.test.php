<?php
/* 
 * 
 */

$test_string='/rfg/tyh/gfed/rty/ookjh/lkj/lkjhl/opiu';
$uri_string='/rfg/tyh/gfed';

//test explode
$time=microtime(true);
for($i=0;$i<10000;$i++){
    expl_test($uri_string, $test_string);
}
$delta=microtime(true)-$time;
echo 'Explode time= '.$delta.'<br>';

//test substr
$time=microtime(true);
for($i=0;$i<10000;$i++){
    strr_test($uri_string, $test_string);
}
$delta=microtime(true)-$time;
echo 'Substring time= '.$delta.'<br>';


function expl_test ($uri,$test)
{
    $parts=explode('/',$test);
    while($count=count($parts)){
        $arg=$parts[$count-1];
        unset($parts[$count-1]);
        $new=implode('/',$parts);
        if($new==$uri){
            return($new);
        }
    }
    return(false);
}

function strr_test($uri,$test)
{
    while($test){
        $last=strrpos('/', $test);
        $test=substr($test,0,$last-1);
        $arg=substr($test,$last+1);
        if($test==$uri) return($test);
    }
    return(false);
}
?>
