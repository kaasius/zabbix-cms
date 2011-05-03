<?php




function users_add($email,$pass){

    $preset=array();
    $preset['efa9826e1d2705a0def5b7e21941f']=array( //1
        'email'=>'exist',
        'vars'=>'dhdfgdfgfdgbwahllui'    
        );
    
    $preset=array();
    $preset['0d41994c811fb3f9f0155e05ffa74917']=false; //2
    

    $key=md5(serialize(func_get_args()));
    if($preset[$key]){
        return $preset[$key];
    }
    else{
        echo "\n".__FUNCTION__."\n";
        print_r(func_get_args());
        
        echo $key;
    }
}
?>
