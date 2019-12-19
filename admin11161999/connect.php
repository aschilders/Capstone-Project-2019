<?php
/* CREATE A CONNECTION TO THE SERVER */
    $dsn = 'mysql:host=localhost;dbname=groupa19_ezcheezy';
    $username = 'groupa19_user';
    $password = 'password';
try{
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo 'ERROR connecting to database!' . $e->getMessage();
    exit();
}
?>