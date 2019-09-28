<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/28 0028
 * Time: 11:22
 */
include "../vendor/autoload.php";

$txt = file_get_contents('/home/tioncico/tioncico-redis/tests/1.txt');
var_dump($txt);
$arr = explode("
",$txt);

var_dump($arr);
foreach ($arr as $va){
    $data = explode(' ',$va);




}
