<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/28 0028
 * Time: 11:22
 */
include "../vendor/autoload.php";

$txt = file_get_contents('./1.txt');
$arr = explode("
",$txt);

$str='';

foreach ($arr as $k=> $va){
    if ($k%2==0){
        $data = explode(' ',$va);
        $command = array_shift($data);
        if (empty($command)){
            continue;
        }
        $keys = "";
        foreach ($data as $v){
            if (!empty($keys)){
                $keys .= ',';
            }
            if (strpos($v,'[')===false){
                $v = str_replace(['[',']'],['',''],$v);
                $keys .= '$'."{$v}";
            }else{
                $v = str_replace(['[',']'],['',''],$v);
                $keys ='...$'."{$v}";
            }
        }
        if ($keys){
            $str .= <<<PHP
            
        \$data = \$redis->$command($keys);
        \$this->assertEquals(1,\$data);
        
PHP;

        }else{
            $str .= <<<PHP
        
        \$data = \$redis->$command();
        \$this->assertEquals(1,\$data);
    
PHP;
        }

    }
}

echo $str;