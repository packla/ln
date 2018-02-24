<?php

$accesses = explode(":", file_get_contents(__DIR__ . '/db_accesses.txt'));
$dbname   = '';
$username = '';
$password = '';
if (3 === count($accesses)) {
    $dbname   = $accesses[0];
    $username = $accesses[1];
    $password = $accesses[2];
}

return [
    'class'    => 'yii\db\Connection',
    'dsn'      => 'mysql:host=localhost;dbname=' . $dbname,
    'username' => $username,
    'password' => $password,
    'charset'  => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
];
