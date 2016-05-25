<?php
/**
 * 监控客户端
 * Created by lixuan-it@360.cn
 * User: lane
 * Date: 16/4/26
 * Time: 下午2:32
 * E-mail: lixuan868686@163.com
 * WebSite: http://www.lanecn.com
 */

$errno = $errmsg = '';
$client = stream_socket_client('127.0.0.1:19910', $errno, $errmsg);
while (1) {
    $data = getData();
    $data = json_encode($data) . "\n";
    fwrite($client, $data);
    while (feof($client) === false && $d = fgetc($client)) {
        if ($d === "\n") {
            break;
        }
        $data .= $d;
    }
    var_dump($data);
    sleep(3);
}

function getData(){
    exec('vmstat', $vmstat);
    $keyList = explode(' ', $vmstat[1]);
    foreach($keyList as $k=>$key){
        if(!$key){
            unset($keyList[$k]);
        }
    }
    $keyList = array_values($keyList);
    $valueList = explode(' ', $vmstat[2]);
    foreach($valueList as $k=>$value){
        if($value === ''){
            unset($valueList[$k]);
        }
    }
    $valueList = array_values($valueList);
    $data = array();
    foreach($keyList as $k=>$key){
        switch($key){
            case 'buff':
            case 'us':
            case 'sy':
            case 'id':
                $data[$key] = $valueList[$k];
                break;
        }
    }
    return $data;
}