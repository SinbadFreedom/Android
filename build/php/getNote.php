<?php
/**
 * Created by PhpStorm.
 * User: sinbad
 * Date: 2019/3/15
 * Time: 12:20
 */


$page = $_GET['page'];

switch ($page) {
    case '1':
        $noteStr = '[
{
    "icon":"https://dashidan.com/1","name":"Sinbad1","note":"这是第一个笔记测试1"},
{
    "icon":"https://dashidan.com/2","name":"Sinbad2","note":"这是第二个笔记测试1"},
{
    "icon":"https://dashidan.com/3","name":"Sinbad3","note":"这是第三个笔记测试1"},

]';
        break;
    case '2':
        $noteStr = '[
{
    "icon":"https://dashidan.com/1","name":"Sinbad1","note":"这是第一个笔记测试2"},
{
    "icon":"https://dashidan.com/2","name":"Sinbad2","note":"这是第二个笔记测试2"},
{
    "icon":"https://dashidan.com/3","name":"Sinbad3","note":"这是第三个笔记测试2"},

]';
        break;
    case '3':
        $noteStr = '[
{
    "icon":"https://dashidan.com/1","name":"Sinbad1","note":"这是第一个笔记测试3"},
{
    "icon":"https://dashidan.com/2","name":"Sinbad2","note":"这是第二个笔记测试3"},
{
    "icon":"https://dashidan.com/3","name":"Sinbad3","note":"这是第三个笔记测试3"},

]';
        break;
}

return $noteStr;