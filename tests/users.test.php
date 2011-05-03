<?php

$user_add[] = array(
    'id' =>array('test@test.ru','test'),
    'etalon'=>true,
    'description'=>'Проверка работоспособности создания пользовательского аккаунта'
);
$user_add[] = array(
    'id' =>array('','test'),
    'etalon'=>false,
    'description'=>'Проверка работоспособности создания пользовательского аккаунта при не заполнении почты'
);
$user_add[] = array(
    'id' =>array('test@test.ru',''),
    'etalon'=>false,
    'description'=>'Проверка работоспособности создания пользовательского аккаунта при не заполнении пароля'
);



?>
