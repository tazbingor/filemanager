<?php
/**
 * Created by PhpStorm.
 * User: tazbingo
 * Date: 2017/10/7
 * Time: 18:04
 */

/**
 * 判断文件单位
 * @param number $size
 * @return number
 */
function transByte($size)
{
    $i = 0;//默认Byte
//    $size = 200e5;
    $arr = array(
        "Byte",
        "KB",
        "MB",
        "G",
        "T",
        "E"
    );


    while ($size >= 1024) {
        $size /= 1024;
        $i++;
    }
    return round($size, 2) . $arr[$i];
}