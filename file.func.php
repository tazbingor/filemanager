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


/**
 *
 * 创建文件
 * @param string $filename
 * @return string
 */
function createFile($filename)
{
    $pattern = "/[\/,\*,<>,\?\|]/";//验证文件合法性
    if (!preg_match($pattern, basename($filename))) {
        //检测当前目录下是否存在同名文件
        if (!file_exists($filename)) {
            //通过touch($filename)来创建
            if (touch($filename)) {
                return "文件创建成功";
            } else {
                return "文件创建失败";
            }
        } else {
            return "文件已存在，请重命名后创建";
        }
    } else {
        return "非法文件名";
    }
}