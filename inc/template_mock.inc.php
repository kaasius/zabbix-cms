<?php

/*
 * Заглушка для драйвера шаблонов
 */



/*
 * Загружаем шаблон
 */


function load_template($name, $vars, $html){

    $preset=array();
    $preset['1251ffef7a077b76711eb4bfa838656e']=array( //существует шаблон -> загружаем
        'template_name'=>'exist',        
        'vars'=>serialize(array(
            'name_var'=>'GROUPID',
            'var_value'=>'номер_группы',
            'type'=>'text'
            )),
        'html'=>'<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
        {TR_NEWS}
        <br>
        <div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>'
        );
    
    $preset['0d41994c811fb3f9f0155e05ffa74917']=false; //шаблон не существует    


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


function save_template($name, $html, $create){

    $preset=array();
    $preset['56d1c23025e6b4d9de432a13ee2fa663']=array( //шаблона не существует -> создаём
        'template_name'=>'not_exist',
        'html'=>'<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
        {TR_NEWS}
        <br>
        <div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>',
        'create'=>''      
        );
    
    $preset=array();
    $preset['0d41994c811fb3f9f0155e05ffa74917']=false; //шаблон существует и перезапись запрещена

    $preset=array();
    $preset['b24d8c5b23bd7e21e00d83bab6cbcf03']=array( //шаблон сущесвует и перезапись разрешена
        'template_name'=>'exist',
        'html'=>'<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
        {TR_NEWS}
        <br>
        <div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>',
        'create'=>'true'     
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
