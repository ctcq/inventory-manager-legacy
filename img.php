<?php
/**
 * Created by PhpStorm.
 * User: Chrissi
 * Date: 26.01.2019
 * Time: 03:36
 */

$name = urldecode($_GET['name']);
$name = str_replace("ä", "a", $name);
$name = str_replace("ö", "o", $name);
$name = str_replace("ü", "u", $name);
$name = str_replace("Ä", "A", $name);
$name = str_replace("Ö", "O", $name);
$name = str_replace("Ü", "U", $name);
$name = str_replace("ß", "ss", $name);
$name = preg_replace ( '/[^a-zA-Z0-9 ]/i', '', $name );
$file = "C:/Barlisten/preisliste/images/$name.jpg";
header('Content-type: image/jpg');

if(file_exists($file)){
    readfile($file);
}else{
    echo false;
}