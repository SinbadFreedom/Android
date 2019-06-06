<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set('PRC');

/** 日期相关的key与mongodb表对应*/
$time_stamp = time();
$today = date('Y-m-d', $time_stamp);
$yesterday = date('Y-m-d', strtotime("-1 day"));
$month = date('Y_m', $time_stamp);
$month_last = date('Y_m', strtotime("-1 month"));
/** 从1970年1月1日以来的总周数，方便计算上一周排名, 1970年1月1日是周四，减去4天的时间差，从19700105日，周一的时间差开始算总周数*/
$week = intval(($time_stamp - 4 * 24 * 60 * 60) / (7 * 24 * 60 * 60));
/** 日,周，月，经验数据表名*/
$col_today = "col_day_" . $today;
$col_yesterday = "col_day_" . $yesterday;
$col_week = "col_week_" . $week;
$col_week_last = "col_week_" . ($week - 1);
$col_month = "col_month_" . $month;
$col_month_last = "col_month_" . $month_last;