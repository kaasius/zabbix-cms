<?php
/* 
 * Kernel file
 */

/*
 * defines
 */

defined(KERNEL_PAGE_TABLE_NAME) or define('KERNEL_PAGE_TABLE_NAME','pages');
defined(KERNEL_ALIASES_TABLE_NAME) or define('KERNEL_ALIASES_TABLE_NAME','aliases');

/*
 * includes
 */
require_once 'db.inc.php';
require_once 'cache.inc.php';
require_once 'errors.inc.php';

/*
 * API
 */

function kernel_start()
{
    ob_start();
    if(GLOBAL_MULTI_DOMAIN===true) {
        $domain=_kernel_get_domain($_SERVER['HTTP_HOST']);
        define('GLOBAL_DOMAIN',$domain);
        _kernel_load_domain_config($domain);
    }
    $page=_kernel_get_page($_SERVER['REQUEST_URI']);
    _kernel_print_page($page);
}

function kernel_do_type($type,$data,$args)
{
    switch ($type){
        case 'text':
            $ret=$data;
            break;
        case 'free_script':
            $ret=kernel_run_script($data,$args);
            break;
        case 'template':
            $ret=kernel_parse_subtemplate($data['name'],$data['placeholders'],$args);
            break;
        default:
            $ret=kernel_run_type($type,$data,$args);
            break;
    }
    return($ret);
}

function kernel_run_script($data,$args)
{

}

function kernel_parse_subtemplate($name,$data,$args)
{

}

function kernel_run_type($type,$data,$args)
{
    
}

/*
 * Internal functions
 */
function _kernel_get_domain($domain)
{
    $domain=str_replace('www.','',$domain);
    return($domain);
}

function _kernel_load_domain_config($domain)
{

}

/**
 * Комментарий
 */
function _kernel_get_page($uri)
{
    if(!$uri) $uri='/';
    if($uri!='/' && $uri[strlen($uri-1)]=='/'){ //Проверка на то, что uri оканчивается на /
        $uri=ltrim($uri,'/');
        if(true===KERNEL_STRICT_URI){
            $page['headers'][]=array('header'=> 'Location: '.$uri,'replace'=>true,'code'=>'301');
            $ret['page']=$page;
        }
    }
    elseif(strstr($uri, '//')){
        $uri=str_replace('//','/',$uri);
        $page['headers'][]=array('header'=> 'Location: '.$uri,'replace'=>true,'code'=>'301');
            $ret['page']=$page;
    }
    else{
        $main=_kernel_alias_list($uri);
        if($main){  //Проверка на то, что этот uri - псевдоним (требуется переадресация)
            $page['headers'][]=array('header'=> 'Location: '.$main,'replace'=>true,'code'=>'301');
            $ret['page']=$page;
        }
        else{  //uri не псевдоним - вытаскиваем параметры страницы
                $ret=_kernel_parse_page($uri);
        }
    }
    return($ret);
}

function _kernel_parse_page($uri)
{
    $pages=_kernel_get_page_list();
    if($pages[$uri]){ // Если нет никаких параметров - сразу отдаем данные страницы
        $ret=_kernel_get_page_data($pages[$uri]['id']);
    }
    else{ // Выделяем аргуметы
        $parts=explode('/',$uri);
        $args=array();
        $ret=false;
        while ($count=count($parts)){
            array_unshift($args, $parts[$count-1]);
            unset($parts[$count-1]);
            $new=implode('/',$parts);
            if($pages[$new]){
                $ret=_kernel_get_page_data($pages[$new]['id'],$args);
            }
        }
    }
    return($ret);
}

function _kernel_get_page_data($id,$args=0)
{
    $page_data=db_select_row('select * from ?_?# where ?#=?',KERNEL_PAGE_TABLE_NAME,'id',$id);
    if($args===0){
        $ret['page']=$page_data;
    }
    elseif($page_data['max_args']===NULL){ //Опция для старых данных - там не задано количество аргументов
        
        if(true===KERNEL_STRICT_ARGS){
            error_handler(WRONG_ARGS_NUMBER,$uri.': '.__FILE__.':'.__LINE__);
            $ret=false;
        }
        else{
            $ret['page']=$page_data;
            $ret['args']=$args;
        }
    }
    elseif(count($args)>=$page_data['min_args'] && count($args)<=$page_data['max_args']){
        // Если количество аргументов корректное - они присоединяются к данным страницы
        $ret['page']=$page_data;
        $ret['args']=_kernel_make_args($args,$page_data['arg_names']);
    }
    else{ // Ошибка - неверное количество аргументов
        error_handler(WRONG_ARGS_NUMBER,$uri.': '.__FILE__.':'.__LINE__);
        $ret=false;
    }
    return($ret);
}

function _kernel_print_page($page)
{
    if(FALSE===$page){
        $headers[]=array('header'=>'Not found','replace'=>true,'code'=>'404');
        die();
    }
    $page_data['page']['template']=$page['template'];
    $page_data['args']=$page['args'];
    $page_data['placeholders']=unserialize($page['page']['placeholders']);
    $page_text=_kernel_make_page($page_data);
    $debug=ob_get_clean();
    _kernel_headers($page['page']['headers']);
    _kernel_debug_print($page_text,$debug);
    echo $page_text;
    die();
}

function _kernel_make_page($page)
{
    if(is_array($page['placeholders'])){
        foreach($page['placeholders'] as $placeholder=>$data){
            $params[$placeholder]=kernel_do_type($data['type'],$data['data'],$page['args']);
        }
    }
    $ret=template_parse_template($page['template'],$params);
    return($ret);
}

function _kernel_headers($headers)
{
    if(is_array($headers)){
        foreach($headers as $header){
            header($header['header'],$header['replace'],$header['code']);
            if($header['code']=='301' or $header['code']=='302' or $header['code']=='404'){
                die();
            }
        }
    }
    else header('Ok',1,'200');
}

function _kernel_alias_list($uri)
{
    if(true===GLOBAL_MULTI_DOMAIN){
        $aliases=db_select_hash('uri','select * from ?_?# where ?#=?',KERNEL_ALIASES_TABLE_NAME,'domain',GLOBAL_DOMAIN);
    }
    else{
        $aliases=db_select_hash('uri','select * from ?_?#',KERNEL_ALIASES_TABLE_NAME);
    }
    $ret=$aliases[$uri]['main']?$aliases[$uri]['main']:false;
    return($ret);
}

//TODO Переписать запросы с использованием нового плэйсхолдера (вместо списка страниц)
function _kernel_get_page_list()
{
    if(true===GLOBAL_MULTI_DOMAIN){
        $pages=db_select_hash('uri','select uri,id from ?_?# where ?#=?',KERNEL_PAGE_TABLE_NAME,'domain',GLOBAL_DOMAIN);
    }
    else{
        $pages=db_select_hash('uri','select uri,id from ?_?#',KERNEL_PAGE_TABLE_NAME);
    }
    return($pages);
}

function _kernel_make_args($args,$names)
{
    if(!is_array($args)){
        return(false);
    }
    $names=explode(',',$names);
    if(count($names)<count($args)){
        return(false);
    }
    $count=count($args);
    for($i=0;$i<$count;$i++){
        $ret[$names[$i]]=$args[$i];
    }
    return($ret);
}
?>