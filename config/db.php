<?php
if(file_exists(__DIR__ . '/local.php')){
  $local = require(__DIR__ . '/local.php');
  $dsn=$local['mysqlDsn'];
  $username = $local['mysqlUsername'];
  $password=$local['mysqlPassword'];
  $charset=$local['mysqlCharset']; 

}
else{
    $dsn="mysql:host=localhost;dbname=yii2basic";
    $username = 'root';
    $password = '';
    $charset = 'utf8';
}
return [
    'class' => 'yii\db\Connection',
    'dsn' => $dsn,
    'username' => $username,
    'password' => $password,
    'charset' => $charset,
];
//mongoDB connection see in web.php