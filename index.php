<?php
/**
 * Created by PhpStorm.
 * User: tazbingo
 * Date: 2017/10/5
 * Time: 21:54
 *
 * 入口
 */


//文件操作
require_once 'dir.func.php';
require_once 'file.func.php';
$path = "file";
$info = readDirectory($path);

//    print_r($info);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>文件管理器</title>
    <link rel="stylesheet" href="cikonss.css"/>
    <link rel="stylesheet" href="jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" type="text/css"/>
    <style type="text/css">
        body, p, div, ul, ol, table, dl, dd, dt {
            margin: 0;
            padding: 0;
        }

        a {
            text-decoration: none;
        }

        ul, li {
            list-style: none;
            float: left;
        }

        #top {
            width: 100%;
            height: 48px;
            margin: 0 auto;
            background: #E2E2E2;
        }

        #navi a {
            display: block;
            width: 48px;
            height: 48px;
        }

        #main {
            margin: 0 auto;
            border: 2px solid #ABCDEF;
        }

        .small {
            width: 25px;
            height: 25px;
            border: 0;
        }
    </style>
    <script type="text/javascript">

        //todo
    </script>
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

<table width="100%" border="1" cellpadding="5" cellspacing="0" bgcolor="#ABCDEF" align="center">
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

            </tr>


            <?php
            $i++;
        }
    }
    ?>
</table>


<script src="jquery-ui/js/jquery-1.10.2.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
<script src="jquery-ui/js/jquery-ui-1.10.4.custom.min.js"></script>
</body>
</html>