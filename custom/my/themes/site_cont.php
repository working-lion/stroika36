<?php
/**
 * Шаблон страницы "Контакты"
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
 */

if ( !defined("DIAFAN")) {
    $path = __FILE__;
    $i = 0;
    while ( !file_exists($path . '/includes/404.php')) {
        if ($i == 10) {
            exit;
        }
        $i++;
        $path = dirname($path);
    }
    include $path . '/includes/404.php';
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ru">
    <head>
        <insert name="show_include" file="header">
    </head>

    <body>
        <insert name="show_include" file="header_bl">
        <div class="content">
            <div class="container">
                <div class="bread">
                    <insert name="show_breadcrumb" current="true" separator=" ">
                </div>
                <div class="title">
                    <h1 class="new_h1">
                        <insert name="show_h1">
                    </h1>
                </div>
                <div class="kont__flex">
                    <div class="kont__left">
                        <insert name="show_text">
                        <div class="kont__form">
                            <insert name="show_form" module="feedback" template="modal" site_id="31">
                        </div>
                    </div>
                    <div class="kont__left maps">
                        <insert name="show_block" module="site" id="7">
                    </div>

                </div>
            </div>
        </div>


        <insert name="show_include" file="footer">
    </body>
</html>
