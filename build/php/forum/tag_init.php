<?php
/** 初始化论坛数据，建立系统文档对应的标签 */
$time_stamp = time();

/** id 为 0采用默认图片*/
$author_id = 0;
$author_name = "系统";

/** Python3.7.2文档*/
$tag = 'Python3.7.2文档';
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

foreach ($titles as $title) {
    /** 初始化标题集合*/
    $bulk = new MongoDB\Driver\BulkWrite;
    $bulk->update(
        ['title' => $title],
        ['$set' => ['title' => $title, 'userid' => $author_id, 'name' => $author_name, 'time' => $time_stamp]],
        ['multi' => false, 'upsert' => true]
    );
    $result = $manager->executeBulkWrite($db_collection_name, $bulk);
}
