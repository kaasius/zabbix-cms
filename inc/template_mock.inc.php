<?php

/*
 * Заглушка для драйвера шаблонов
 */



/*
 * Загружаем шаблон
 */


function load_template($name){

    $preset=array();
    $preset['44006c0f5d1eaa95c6db0789266db2b0']=array( //существует шаблон -> загружаем    
        'vars'=>serialize(array(
            'GROUPID'=>serialize(array(
                'value'=>'номер_группы',
                'type'=>'text'
                    )),
            'NAME'=>serialize(array(
                'value'=>'имя',
                'type'=>'text' 
                    )),
            'TR_NEWS'=>serialize(array(
                'value'=>'тест',
                'type'=>'text' 
                    ))
            )),
        'html'=>'<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
        {TR_NEWS}
        <br>
        <div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>'
        );
    
    $preset['5b448a7bdbeea0be7d7f758f5f8ee90b']=false; //шаблон не существует    


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


function save_template($name, $html, $vars, $create){

    $preset=array();
    $preset['efa9826e1d2705a0def5b7e21941f2e0']=array( //шаблона не существует -> создаём
        'template_name'=>'not_exist',
        'html'=>'<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
        {TR_NEWS}
        <br>
        <div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>',
        'vars'=>serialize(array(
            'var_name'=>'GROUPID',
            'var_value'=>'номер_группы',
            'type'=>'text'
            )),
        'create'=>''      
        );
    
    $preset=array();
    $preset['0d41994c811fb3f9f0155e05ffa74917']=false; //шаблон существует и перезапись запрещена

    $preset=array();
    $preset['9ad9791363c4088fe607f4de3919bb3c']=array( //шаблон сущесвует и перезапись разрешена
        'template_name'=>'exist',
        'html'=>'<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
        {TR_NEWS}
        <br>
        <div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>',
        'vars'=>serialize(array(
            'var_name'=>'GROUPID',
            'var_value'=>'номер_группы',
            'type'=>'text'
            )),        
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
