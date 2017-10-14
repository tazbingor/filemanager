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
 * 测试用,展示语法高亮
 * @param string $mes
 * @param string $url
 */
function testMes($mes, $url)
{
    echo "<script type='text/javascript'>alert('{$mes}');location.href='{$url}';</script>";
}