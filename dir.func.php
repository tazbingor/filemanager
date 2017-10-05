<?php
/**
 * Created by PhpStorm.
 * User: tazbingo
 * Date: 2017/10/5
 * Time: 21:57
 *
 * 所有目录相关操作
 */

//打开目录
$handle = opendir($path);
//读取目录
while (($item = readdir($handle)) !== false) {

    //除去.或者..文件名的目录
    if ($item = '.' && $item != '..') {

        //若是文件
        if (is_file($path . "/" . $item)) {
            $arr['file'][] = $item;
        }

        //若是目录
        if (is_dir($path . "/" . $item)) {
            $arr['dir'][] = $item;
        }
    }
}

//关闭
closedir($handle);