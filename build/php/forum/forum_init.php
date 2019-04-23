<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 初始化论坛数据，建立系统文档对应的标签 */
$time_stamp = time();

/** id 为 0采用默认图片*/
$author_id = 0;
$author_name = "系统";

/** python3.7.2*/
$tag = 'python3.7.2';
$titles = [
    '1. 激发你的胃口',
    '2. 使用Python解释器',
    '3. Python的非正式简介',
    '4. 更多控制流工具',
    '5. 数据结构',
    '6. 模块',
    '7. 输入和输出',
    '8. 错误和例外',
    '9. 课程',
    '10. 标准图书馆简介',
    '11. 标准图书馆简介 - 第二部分',
    '12. 虚拟环境和包',
    '13. 现在怎么办?',
    '15. 浮点算术:问题和限制',
    '16. 附录'
];

/** 初始化标题集合*/
$manager = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$db_collection_name = 'db_tag.' . $tag;
/** 初始化官方文档自增ID*/
$content_id = 1;

foreach ($titles as $title) {
    /** 初始化标题集合*/
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['title' => $title],
        ['$set' => ['contentid' => $content_id, 'title' => $title, 'authorid' => $author_id, 'authorname' => $author_name, 'createtime' => $time_stamp]],
        ['multi' => false, 'upsert' => true]
    );
    $result = $manager->executeBulkWrite($db_collection_name, $bulk);
    /**
     * 文章ID自增, 官方文档统一采用这个id自增，
     */
    $content_id++;
}

/**
 * 自定义文章id从100000开始
 * 官方文档初始化完成后检测当前文档id，如果小于100000，设置为100000，大于等于则保持不变
 * 保持对已经加入的自定义文章id兼容
 */
$bulkWrite = new MongoDB\Driver\BulkWrite;
$bulkWrite->update(
    ['content_id_now' => ['$lt' => 100000]],
    ['$set' => ['content_id_now' => 100000]],
    ['multi' => false, 'upsert' => true]
);
$result = $manager->executeBulkWrite('db_account.col_increase', $bulkWrite);

print_r($result);