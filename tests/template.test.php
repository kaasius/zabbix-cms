<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



$template_delete[] = array(
        'args'=>array('exist'),
        'etalon'=>true,
        'description'=>'Проверка работоспособности при существующем шаблоне'
);

$template_delete[] = array(
        'args'=>array(''),
        'etalon'=>false,
        'description'=>'Проверка работоспособности при передаче пустой строки'
);

$template_delete[] = array(
        'args'=>array('not_exist'),
        'etalon'=>false,
        'description'=>'Проверка работоспособности при не существующем шаблоне'
);



$template_update[] = array(
        'args'=>array('exist'),
        'etalon'=>true,
        'description'=>'Проверка работоспособности изменений при существующем шаблоне'
);

$template_update[] = array(
        'args'=>array(''),
        'etalon'=>false,
        'description'=>'Проверка работоспособности изменений при передаче пустой строки'
);

$template_update[] = array(
        'args'=>array('not_exist'),
        'etalon'=>false,
        'description'=>'Проверка работоспособности изменений при не существующем шаблоне'
);


$template_add[] = array(
        'args'=>array('creating'),
        'etalon'=>true,
        'description'=>'Проверка работоспособности добавления шаблона'
);

$template_add[] = array(
        'args'=>array('not_creating'),
        'etalon'=>false,
        'description'=>'Проверка работоспособности добавления шаблона'
);


utest_test_function('template_delete', $template_delete);
//utest_message('Functions template_delete');
?>