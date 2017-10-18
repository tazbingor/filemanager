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
            return touch($filename) ? "文件创建成功" : "文件创建失败";

        } else {
            return "文件已存在，请重命名后创建";
        }
    } else
        return "非法文件名";

}


/**
 * 文件重命名
 * @param string $oldname
 * @param string $newname
 * @return string
 */
function renameFile($oldname, $newname)
{
    if (checkFilename($newname)) { //验证文件名
        $path = dirname($oldname);
        if (!file_exists($path . "/" . $newname)) {
            return rename($oldname, $path . "/" . $newname) ? "重命名成功" : "重命名失败";
        } else {
            return "存在同名文件，请重新输入 ";
        }
    } else
        return "非法文件名！";

}

/**
 * 检测文件名
 * @param $filename
 * @return bool
 */
function checkFilename($filename)
{
    $pattern = "/[\/,\*,<>,\?\|]/";//验证文件合法性
    return !preg_match($pattern, $filename) ? true : false;
}


/**
 * 删除文件
 * @param string $filename
 * @return string
 */
function delFile($filename)
{
    return unlink($filename) ? "文件删除成功" : "文件删除失败";
}


/**
 * 下载文件
 * @param string $filename
 */
function downloadFile($filename)
{
    header("content-disposition: attachment; filename=" . basename($filename));
    header("content-length:" . filesize($filename));
    readfile($filename);

}

/**
 * 复制文件
 * @param string $filename
 * @param string $dstname
 * @return string
 */
function copyFile($filename,$dstname){
    if(file_exists($dstname)){
        if(!file_exists($dstname."/".basename($filename))){
            if(copy($filename,$dstname."/".basename($filename))){
                $mes="文件复制成功";
            }else{
                $mes="文件复制失败";
            }
        }else{
            $mes="存在同名文件";
        }
    }else{
        $mes="目标目录不存在";
    }
    return $mes;
}

/**
 * 剪切文件
 * @param $filename
 * @param $dstname
 * @return string
 */
function cutFile($filename,$dstname){
    if(file_exists($dstname)){
        if(!file_exists($dstname."/".basename($filename))){
            if(rename($filename,$dstname."/".basename($filename))){
                $mes="文件剪切成功";
            }else{
                $mes="文件剪切失败";
            }
        }else{
            $mes="存在同名文件";
        }
    }else{
        $mes="目标目录不存在";
    }
    return $mes;
}

/**
 * 上传文件
 * @param array $fileInfo
 * @param string $path
 * @param array $allowExt
 * @param int $maxSize
 * @return string
 */
function uploadFile($fileInfo,$path,$allowExt=array("gif","jpeg","jpg","png","txt"),$maxSize=10485760){
    //判断错误号
    if($fileInfo['error']==UPLOAD_ERR_OK){
        //文件是否是通过HTTP POST方式上传上来的
        if(is_uploaded_file($fileInfo['tmp_name'])){
            //上传文件的文件名，只允许上传jpeg|jpg、png、gif、txt的文件
            //$allowExt=array("gif","jpeg","jpg","png","txt");
            $ext=getExt($fileInfo['name']);
            $uniqid=getUniqidName();
            $destination=$path."/".pathinfo($fileInfo['name'],PATHINFO_FILENAME)."_".$uniqid.".".$ext;
            if(in_array($ext,$allowExt)){
                if($fileInfo['size']<=$maxSize){
                    if(move_uploaded_file($fileInfo['tmp_name'], $destination)){
                        $mes="文件上传成功";
                    }else{
                        $mes="文件移动失败";
                    }
                }else{
                    $mes="文件过大";
                }
            }else{
                $mes="非法文件类型";
            }
        }else{
            $mes="文件不是通过HTTP POST方式上传上来的";
        }
    }else{
        switch($fileInfo['error']){
            case 1:
                $mes="超过了配置文件的大小";
                break;
            case 2:
                $mes="超过了表单允许接收数据的大小";
                break;
            case 3:
                $mes="文件部分被上传";
                break;
            case 4:
                $mes="没有文件被上传";
                break;
        }
    }

    return $mes;

}

