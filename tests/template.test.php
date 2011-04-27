<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



$template_delete[] = array(
        'args'=>array('creating'),
        'etalon'=>true,
        'description'=>'Проверка работоспособности удаления при существующем шаблоне'
);

$template_delete[] = array(
        'args'=>array(''),
        'etalon'=>false,
        'description'=>'Проверка работоспособности удаления при передаче пустой строки'
);

$template_delete[] = array(
        'args'=>array('not_exist'),
        'etalon'=>false,
        'description'=>'Проверка работоспособности удаления при не существующем шаблоне'
);



$template_update[] = array(
        'args'=>array('creating', array()),
        'etalon'=>true,
        'description'=>'Проверка работоспособности изменений при существующем шаблоне'
);

$template_update[] = array(
        'args'=>array('', ''),
        'etalon'=>false,
        'description'=>'Проверка работоспособности изменений при передаче пустой строки'
);

$template_update[] = array(
        'args'=>array('not_exist', array(1)),
        'etalon'=>false,
        'description'=>'Проверка работоспособности изменений при не существующем шаблоне'
);


$template_add[] = array(
        'args'=>array('<div class="title"><a href="/archiv_news/group:{GROUPID}" class="groupnews">
        <img src="/design/title_news.gif" alt="" width="86" height="20" border="0"></a>
        </div>
	{TR_NEWS}
	<br>
	<div align="right"><a href="/archiv_news/group:{GROUPID}" class="link_all_news">Все {NAME} &raquo;</a>', 'creating', '', ''),
        'etalon'=>true,
        'description'=>'Проверка работоспособности добавления шаблона'
);

$template_add[] = array(
        'args'=>array('', '', ''),
        'etalon'=>false,
        'description'=>'Проверка работоспособности добавления шаблона при пустом названии или файле'
);


$template_fetch[] = array(
        'args'=>array('creating', ''),
        'etalon'=>false,
        'description'=>'Проверка обработки переменных в шаблоне'
);

$template_fetch[] = array(
        'args'=>array('', ''),
        'etalon'=>false,
        'description'=>'Проверка обработки переменных в шаблоне, если он не существует'
);



utest_test_function('template_add', $template_add);
utest_test_function('template_update', $template_update);
utest_test_function('template_fetch', $template_fetch);
utest_test_function('template_delete', $template_delete);
//utest_message('Functions template_delete');
?>