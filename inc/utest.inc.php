<?php
/* 
 * Testing library
 */
global $_utest_ok, $_utest_errors;
$_utest_ok= $_utest_errors=0;

function utest_message($text)
{
    echo $text ."<br>\n";
}

function utest_message_ok($name,$description)
{
    global $_utest_ok;
    $_utest_ok++;
    utest_message("$_utest_ok Function: $name | Description: $description..........[<font color=green>Ok</font>]");
}

function utest_message_error($name,$data,$res)
{
    global $_utest_errors;
    $_utest_errors++;
    $message="Function: $name | Description: $data[description]..........[<font color=red>Error</font>]";
    utest_message($message);
    utest_message("<pre>Result: ".print_r($res,true)."</pre>");
    utest_message('<pre>'.print_r($data, true).'</pre>');
}

function utest_test_function($name,$data)
{
    if(!is_callable($name)){
        if(!utest_load_module($name)){
            utest_message("Function $name can not be load");
            return(false);
        }
    }
    if(is_array($data)){
        foreach($data as $row){
            $runstr='$res='.$name.'(';
            if(is_array($row['args'])){
                foreach($row['args'] as $key => $arg){
                    $runstr.='$row["args"]['.$key.'],';
                }
                $runstr=rtrim($runstr,',');
            }
            $runstr.=');';
            eval($runstr);
            if(!utest_compare($res,$row['etalon'])){
                utest_message_error($name,$row,$res);
            }
            else{
                utest_message_ok($name,$row['description']);
            }
        }
    }
}

function utest_load_module($function)
{
    $temp=explode('_',$function);
    $name=$temp[0]?$temp[0]:$temp[1];
    $name.='.inc.php';
    require_once "$name";
    if(is_callable($function)){
        return(true);
    }
    return(false);
}

function utest_compare($result,$etalon)
{
    if(is_object($result) and is_object($etalon)){
        if($result==$etalon){
            return(true);
        }
    }
    if($result===$etalon){
        return(true);
    }
    return (false);
}


?>
