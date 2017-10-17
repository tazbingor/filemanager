<?php
/**
 * Created by PhpStorm.
 * User: tazbingo
 * Date: 2017/10/5
 * Time: 21:57
 *
 * 所有目录相关操作
 */

/**
 *
 * 读取最外层目录和内容
 * @param string $path
 * @return array
 */
function readDirectory($path)
{
    $arr = NULL;

    //打开目录
    $handle = opendir($path);
//    print_r($handle);
//    print_r(readdir($handle));
    //读取目录
    while (($item = readdir($handle)) !== false) {

        //除去.或者..文件名的目录
        if ($item != '.' && $item != '..') {

            //若是文件
            if (is_file($path . "/" . $item)) {
                $arr['file'][] = $item;
//                global $arr;
            }

            //若是目录
            if (is_dir($path . "/" . $item)) {
                $arr['dir'][] = $item;
//                global $arr;
            }
        }
    }

    //关闭
    closedir($handle);
    return $arr;

}


/**
 * 得到文件夹大小
 * @param string $path
 * @return int
 */
function dirSize($path)
{
    $sum = 0;
    global $sum;
    $handle = opendir($path);
    while (($item = readdir($handle)) !== false) {
        if ($item != "." && $item != "..") {
            if (is_file($path . "/" . $item)) {
                $sum += filesize($path . "/" . $item);
            }
            if (is_dir($path . "/" . $item)) {
                $func = __FUNCTION__;
                $func($path . "/" . $item);
            }
        }

    }
    closedir($handle);
    return $sum;
}


/**
 *
 * 复制文件夹
 * @param string $src
 * @param string $dst
 * @return string
 */
function copyFolder($src, $dst)
{
    if (!filesize($dst)) {
        mkdir($dst, 0777, true);
    }

    $handle = opendir($src);
    while (($item = readdir($handle)) !== false) {
        if ($item != "." && $item != "..") {
            if (is_file($src . "/" . $item)) {
                copy($src."/".$item,$dst."/".$item);
            }
            if (is_dir($src . "/" . $item)) {
                $func = __FUNCTION__;
                $func($src."/".$item,$dst."/".$item);
            }

        }
    }

    closedir($handle);
    return "复制成功！";
}