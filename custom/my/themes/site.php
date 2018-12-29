<?php
/**
 * Основной шаблон сайта
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
$uriParts = explode('?', $_SERVER['REQUEST_URI'], 2);
$url = $uriParts[0];
$isProjectsPage = $url == '/projects/';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ru">
<head>
    <insert name="show_include" file="header"/>
</head>

<body<?php echo($isProjectsPage ? ' class="projects-page"': ''); ?>>
<insert name="show_include" file="header_bl"/>
<div class="content">
    <div class="container">
        <div class="bread">
            <insert name="show_breadcrumb" current="true" separator=" "/>
        </div>
        <insert name="show_body"/>
    </div>
</div>
<insert name="show_include" file="footer"/>
</body>
</html>
