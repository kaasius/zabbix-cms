<?php

/*
 * Заглушка для драйвера шаблонов
 */


load_template('not_exist');

/*
 * Загружаем шаблон
 */


function load_template(){

    $preset=array();
    $preset['44006c0f5d1eaa95c6db0789266db2b0']=array( //существует шаблон -> загружаем
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
    
    $preset['cac3a07c1243196f5e369364183afcb2']=array( //шаблон не существует
        'template_name'=>'not_exist',
        'html'=>'',
        'vars'=>''
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


function save_template(){

    $preset=array();
    $preset['4287c37180b5760567c04ae3f654de3a']=array( //шаблона не существует -> создаём
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
    $preset['0c7acb9ab4214df7a55c5223bfb8625c']=array( //шаблон существует и перезапись запрещена
        'template_name'=>'exist',
        'html'=>'',
        'create'=>'false'
        );    

    $preset=array();
    $preset['0422ac27529862c48bca986f48021129']=array( //шаблон сущесвует и перезапись разрешена
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
