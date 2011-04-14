<?php
/* 
 * Заглушка для библиотеки DB
 */

function db_select()
{
    $preset=array();

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

function db_select_row()
{
    $preset=array();
    $preset['94bdd5e6df0bbfbd19ee5cd5267f5001']=array( // массив данных страницы с адресом /
        'id'=>1,
        'uri'=>'/',
        'min_args'=>'0',
        'max_args'=>'0',
        'template'=>'index',
        'create_time'=>'0',
        'arg_names'=>'',
        'placeholdes'=>serialize(array(
            'TITLE'=>'Main page',
            'CONTENT'=>'Content'
        ))
    );

    $preset['bc9735ad4261d55733c39eb7a54aefb5']=array( // массив данных страницы с адресом /new и обязательными двумя параметрами
        'id'=>2,
        'uri'=>'/new',
        'min_args'=>'2',
        'max_args'=>'2',
        'template'=>'index',
        'create_time'=>'0',
        'arg_names'=>'arg1,arg2',
        'placeholdes'=>serialize(array(
            'TITLE'=>'New page',
            'CONTENT'=>'Content'
        ))
    );
    $preset['bb5eb0bf454a7f6b0bd68fb6f22e362d']=array( // массив данных страницы с адресом /new и не обязательными двумя параметрами
        'id'=>3,
        'uri'=>'/about',
        'min_args'=>'0',
        'max_args'=>'2',
        'template'=>'index',
        'create_time'=>'0',
        'arg_names'=>'arg1,arg2',
        'placeholdes'=>serialize(array(
            'TITLE'=>'About page',
            'CONTENT'=>'Content'
        ))
    );



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

function db_select_col()
{
    $preset=array();
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

function db_select_hash()
{
    $preset=array();
    $preset['261804611e7ffcc34923914f446d789f']=array(
        '/test'=>array('uri'=>'/test','main'=>'/'),
        '/old'=>array('uri'=>'/old','main'=>'/new'),
        );
    $preset['8997fe98863b387940636d5a68c079d4']=array(
        '/'=>array('uri'=>'/','id'=>1),
        '/new'=>array('uri'=>'/new','id'=>2),
        '/about'=>array('uri'=>'/about','id'=>3),
        );

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
