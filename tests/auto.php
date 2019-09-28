<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/28 0028
 * Time: 11:22
 */
include "../vendor/autoload.php";

$txt = file_get_contents('./CommandTXT/key.txt');
$arr = explode("
",$txt);

$str='';
foreach ($arr as $k=> $va){
    if ($k%2==0){
        $data = explode(' ',$va);
        $str .= "const {$data[0]}='{$data[0]}';";
    }else{
        $str .="//{$va}\n";
    }
}

echo $str;