<?php
/**
 * Created by PhpStorm.
 * User: tazbingo
 * Date: 2017/10/5
 * Time: 21:54
 *
 * 入口
 * 变更开发环境
 */

error_reporting(0);
//文件操作
require_once 'dir.func.php';
require_once 'file.func.php';
//require_once 'common.func.php';

$path = "file";
$act = @$_REQUEST['act'];
$filename = @$_REQUEST['filename'];
$info = readDirectory($path);
$redirect = "index.php?path={$path}";  //跳转路径

if ($act == "createFile") {
    //创建文件
    $mes = createFile($path . "/" . $filename);
    alertMes($mes, $redirect);

} elseif ($act == "showContent") {
    //    //查看文件内容
    $content = file_get_contents($filename);
    if (strlen($content)) {
        //    echo "<textarea readonly='readonly' cols='100' rows = '10'>{$content}</textarea>  ";
        //
        //    //字段高亮模块
        //    highlight_string($content);
        //    highlight_file($filename);
        $newContent = highlight_string($content, true);
        $str = <<<EOF
    <table width ='80%' bgcolor='#f0f0f0' cellpadding='5' cellspacing='0'>
        <tr>
            <td> {$newContent}</td>
        </tr>
    </table>
EOF;
        echo $str;
    } else {
        alertMes("文件内容为空，请编辑文件");
    }
} else if ($act == "editContent") {
//    echo "编辑文件";
    $content = file_get_contents($filename);
    //放入编辑器修改文件
    $str = <<<EOF
    <form action="index.php?act=doEdit" method="post">
    <textarea name="content" id="" cols="190" rows="10">{$content}</textarea><br/>
    <input type="hidden" name="filename" value="{$filename}">
    <input type="submit" value="修改文件内容">
</form>

EOF;
    echo $str;

} else if ($act == "doEdit") {//修改文件操作
    $content = $_REQUEST('content');
//    echo $content;
    if (file_put_contents($filename, $content)) {
        $mes = "文件修改成功";
    } else {
        $mes = "文件修改失败";
    }
    alertMes($mes, $redirect);

} else if ($act == "renameFile") {  //重命名文件
    $str = <<<EOF
    <form action="index.php?act=doRename" method="post">
    请填写新文件名：<input type="text" name="newname" placeholder="重命名">
    <input type="hidden" name="filename" value="{$filename}">
    <input type="submit" value="重命名">
</form>
EOF;
    echo $str;
} else if ($act == "doRename") {    //实现重命名
    $newname = $_REQUEST['newname'];
    $mes = renameFile($filename, $newname);
    alertMes($mes, $redirect);

} else if ($act == "delFile") { //删除文件
    $mes = delFile($filename);
    alertMes($mes, $redirect);

} else if ($act == "downFile") { //下载文件

    $mes = downloadFile($filename);
//    alertMes($mes, $redirect);
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
                    <td><?php echo transByte(dirSize($p)); ?></td>
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