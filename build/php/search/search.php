<?php
if (!isset($_GET['type'])) {
    echo 'error param type';
    return;
}

if (!isset($_GET['keys'])) {
    echo 'error param keys ';
    return;
}

$max_result = 9;

$type = $_GET['type'];
$keys = $_GET['keys'];

/** search log*/
$time_stamp = time();
$file = '/workplace/log/log_search_' . date('Y-m-d', $time_stamp) . '.log';
$content = $type . ' ' . $keys . ' ' . $time_stamp. "\n";
file_put_contents($file, $content, FILE_APPEND);

$json_file_name = $type . '.json';

$json_string = file_get_contents($json_file_name);

if (!$json_string) {
    echo 'error $type ' . $type;
    return;
}
/** 获取指定文档的目录数据*/
$data = json_decode($json_string);
/** 移除两端空格*/
$keys = trim($keys);
//TODO 空格全角转半角
/** 按空格拆分字符串*/
$key_arr = explode(' ', $keys);
/** 遍历目录匹配字符串 与的关系*/
$data_len = count($data);

$res_data = [];
/** 遍历匹配key*/
$count = 0;
for ($i = 0; $i < $data_len; $i++) {
    $str = $data[$i];
    $contain = true;
    foreach ($key_arr as $value) {
        if (strlen($value) > 0) {
            /** 匹配非空key*/
            $pos = strpos($str, $value);
            if (!$pos) {
                /** 有key没有匹配上*/
                $contain = false;
                break;
            }
        }
    }

    if ($contain) {
        /** 匹配全部key,放入查找结果array*/
        if (count($res_data) < $max_result) {
            array_push($res_data, $str);
        } else {
            /** 超过上限停止搜索*/
            break;
        }
    }
}

/** 返回数据*/
$res = new stdClass();
$res->data = $res_data;
echo json_encode($res);
