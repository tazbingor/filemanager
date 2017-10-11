<?php
/**
 * Created by PhpStorm.
 * User: tazbingo
 * Date: 2017/10/5
 * Time: 21:54
 *
 * 入口
 */

error_reporting(0);
//文件操作
require_once 'dir.func.php';
require_once 'file.func.php';
$path = "file";
$act = @$_REQUEST['act'];
$filename = @$_REQUEST['filename'];
$info = readDirectory($path);


//创建文件
if ($act == "createFile") {
//    echo $path, "--";
//    echo $filename;

    //创建文件
    $mes = createFile($path . "/" . $filename);


} else if ($act == "createFolder") {


}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>文件管理器</title>
    <link rel="stylesheet" href="css/cikonss.css"/>
    <link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" type="text/css"/>
    <link rel="stylesheet" href="css/main.css">

    <link href="images/show.png" rel="icon"/>
    <script src="js/main.js"></script>
</head>
<body>

<div id="showDetail" style="display:none"><img src="" id="showImg" alt=""/></div>
<h1>TD在线文件管理器</h1>
<div id="top">
    <ul id="navi">
        <li><a href="index.php" title="主目录"><span style="margin-left: 8px; margin-top: 0px; top: 4px;"
                                                  class="icon icon-small icon-square"><span
                            class="icon-home"></span></span></a></li>
        <li><a href="#" onclick="show('createFile')" title="新建文件"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-file"></span></span></a></li>
        <li><a href="#" onclick="show('createFolder')" title="新建文件夹"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-folder"></span></span></a></li>
        <li><a href="#" onclick="show('uploadFile')" title="上传文件"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-upload"></span></span></a></li>
        <?php
        $back = ($path == "file") ? "file" : dirname($path);
        ?>
        <li><a href="#" title="返回上级目录" onclick="goBack('<?php echo $back; ?>')"><span
                        style="margin-left: 8px; margin-top: 0px; top: 4px;" class="icon icon-small icon-square"><span
                            class="icon-arrowLeft"></span></span></a></li>
    </ul>
</div>

<form action="index.php" method="post" enctype="multipart/form-data">
    <table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#ABCDEF" align="center">

        <tr id="createFolder" style="display:none;">
            <td>请输入文件夹名称</td>
            <td>
                <input type="text" name="dirname"/>
                <input type="hidden" name="path" value="<?php echo $path; ?>"/>
                <input type="hidden" name="act" value="createFolder">
                <input type="submit" value="创建文件夹"/>
            </td>
        </tr>

        <tr id="createFile" style="display:none;">
            <td>请输入文件名称</td>
            <td>
                <input type="text" name="filename"/>
                <input type="hidden" name="path" value="<?php echo $path; ?>"/>
                <input type="hidden" name="act" value="createFile">
                <input type="submit" value="创建文件"/>
            </td>
        </tr>

        <tr>
            <td>编号</td>
            <td>名称</td>
            <td>类型</td>
            <td>大小</td>
            <td>可读</td>
            <td>可写</td>
            <td>可执行</td>
            <td>创建时间</td>
            <td>修改时间</td>
            <td>访问时间</td>
            <td>操作</td>
        </tr>
        <?php
        if ($info['file']) {
            $i = 1;
            foreach ($info['file'] as $value) {
                $p = $path . "/" . $value;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $value; ?></td>
                    <td><?php $src = filetype($p) == "file" ? "file_ico.png" : "folder_ico.png"; ?><img
                                src="images/<?php echo $src; ?>" alt="" title="文件"/>
                    </td>
                    <td><?php echo transByte(filesize($p)); ?></td>
                    <td><?php $src = is_readable($p) ? "correct.png" : "error.png" ?><img class="small"
                                                                                          src="images/<?php echo $src; ?>"
                                                                                          alt=""></td>
                    <td><?php $src = is_writable($p) ? "correct.png" : "error.png" ?><img class="small"
                                                                                          src="images/<?php echo $src; ?>"
                                                                                          alt="">
                    </td>
                    <td><?php $src = is_executable($p) ? "correct.png" : "error.png" ?><img class="small"
                                                                                            src="images/<?php echo $src; ?>"
                                                                                            alt="">
                    </td>
                    <td><?php echo date("Y-m-d H:i:s", filectime($p)) ?></td>
                    <td><?php echo date("Y-m-d H:i:s", filemtime($p)) ?></td>
                    <td><?php echo date("Y-m-d H:i:s", fileatime($p)) ?></td>

                    <td>
                        <?php

                        //得到文件扩展名
                        $s = explode(".", $value);
                        $endS = end($s);
                        $ext = strtolower($endS);
                        $imageExt = array("gif", "jpg", "jpeg", "png");
                        if (in_array($ext, $imageExt)) {
                            ?>
                            <a href="#" onclick="showDetail('<?php echo $value; ?>','<?php echo $p; ?>')"><img
                                        class="small"
                                        src="images/show.png"
                                        alt=""
                                        title="查看"/></a>|
                            <?php
                        } else {
                            ?>
                            <a href="index.php?act=showContent&path=<?php echo $path; ?>&filename=<?php echo $p; ?>"><img
                                        class="small" src="images/show.png" alt="" title="查看"/></a>|
                        <?php } ?>
                        <a href="index.php?act=editContent&path=<?php echo $path; ?>&filename=<?php echo $p; ?>"><img
                                    class="small" src="images/edit.png" alt="" title="修改"/></a>|
                        <a href="index.php?act=renameFile&path=<?php echo $path; ?>&filename=<?php echo $p; ?>"><img
                                    class="small" src="images/rename.png" alt="" title="重命名"/></a>|
                        <a href="index.php?act=copyFile&path=<?php echo $path; ?>&filename=<?php echo $p; ?>"><img
                                    class="small"
                                    src="images/copy.png"
                                    alt=""
                                    title="复制"/></a>|
                        <a href="index.php?act=cutFile&path=<?php echo $path; ?>&filename=<?php echo $p; ?>"><img
                                    class="small"
                                    src="images/cut.png"
                                    alt=""
                                    title="剪切"/></a>|
                        <a href="#" onclick="delFile('<?php echo $p; ?>','<?php echo $path; ?>')"><img class="small"
                                                                                                       src="images/delete.png"
                                                                                                       alt=""
                                                                                                       title="删除"/></a>|
                        <a href="index.php?act=downFile&path=<?php echo $path; ?>&filename=<?php echo $p; ?>"><img
                                    class="small"
                                    src="images/download.png"
                                    alt=""
                                    title="下载"/></a>
                    </td>


                </tr>


                <?php
                $i++;
            }
        }
        ?>

        <!-- 读取目录的操作-->
        <?php
        if ($info['dir']) {
            $i = $i == null ? 1 : $i;
            foreach ($info['dir'] as $val) {
                $p = $path . "/" . $val;
                ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $val; ?></td>
                    <td><?php $src = filetype($p) == "file" ? "file_ico.png" : "folder_ico.png"; ?><img
                                src="images/<?php echo $src; ?>" alt="" title="文件"/></td>
                    <td><?php $sum = 0;
                        echo transByte(dirSize($p)); ?></td>
                    <td><?php $src = is_readable($p) ? "correct.png" : "error.png"; ?><img class="small"
                                                                                           src="images/<?php echo $src; ?>"
                                                                                           alt=""/></td>
                    <td><?php $src = is_writable($p) ? "correct.png" : "error.png"; ?><img class="small"
                                                                                           src="images/<?php echo $src; ?>"
                                                                                           alt=""/></td>
                    <td><?php $src = is_executable($p) ? "correct.png" : "error.png"; ?><img class="small"
                                                                                             src="images/<?php echo $src; ?>"
                                                                                             alt=""/></td>
                    <td><?php echo date("Y-m-d H:i:s", filectime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:s", filemtime($p)); ?></td>
                    <td><?php echo date("Y-m-d H:i:s", fileatime($p)); ?></td>
                    <td>
                        <a href="index.php?path=<?php echo $p; ?>"><img class="small" src="images/show.png" alt=""
                                                                        title="查看"/></a>|
                        <a href="index.php?act=renameFolder&path=<?php echo $path; ?>&dirname=<?php echo $p; ?>"><img
                                    class="small" src="images/rename.png" alt="" title="重命名"/></a>|
                        <a href="index.php?act=copyFolder&path=<?php echo $path; ?>&dirname=<?php echo $p; ?>"><img
                                    class="small" src="images/copy.png" alt="" title="复制"/></a>|
                        <a href="index.php?act=cutFolder&path=<?php echo $path; ?>&dirname=<?php echo $p; ?>"><img
                                    class="small" src="images/cut.png" alt="" title="剪切"/></a>|
                        <a href="#" onclick="delFolder('<?php echo $p; ?>','<?php echo $path; ?>')"><img class="small"
                                                                                                         src="images/delete.png"
                                                                                                         alt=""
                                                                                                         title="删除"/></a>|
                    </td>
                </tr>
                <?php
                $i++;
            }
        }

        ?>
    </table>
</form>

<script src="jquery-ui/js/jquery-1.10.2.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
</body>
</html>