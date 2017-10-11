<?php
/**
 * Created by PhpStorm.
 * User: tazbingo
 * Date: 2017/10/11
 * Time: 18:53
 *
 * 公共函数
 */

/**
 * 提示操作信息,并跳转
 * @param string $mes
 * @param string $url
 */
function alertMes($mes, $url)
{
    echo "<script type='text/javascript'>alert('{$mes}');location.href ='{$url}';</script>";
}