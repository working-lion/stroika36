<?php
/**
 * Шаблон стартовой страницы сайта
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
 */

if(! defined("DIAFAN"))
{
	$path = __FILE__; $i = 0;
	while(! file_exists($path.'/includes/404.php'))
	{
		if($i == 10) exit; $i++;
		$path = dirname($path);
	}
	include $path.'/includes/404.php';
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ru">
<head>
<insert name="show_include" file="header"/>

</head>

<body>
<insert name="show_include" file="header_bl">
<div class="banners main-slider-wrap">
    <insert name="show_block" module="bs" cat_id="1" template="sl" count="10">
</div>

<div class="hit_items main_col">
    <insert name="show_block" module="shop" hits_only="true" head="Рекомендуем" class="responsive"
            template="slider" count="20" images="1" site_id="11">
</div>

<div id="form-smeta" class="form-section form-smeta footer__offer">
    <insert name="show_form" module="feedback" site_id="37" template="smeta" btn="Отправить">
</div>
<div class="container">
    <main class="main_text">
        <insert name="show_body">
    </main>
</div>
<div class="hit_items main_col">
    <insert name="show_block" module="news" count="2" images="5" cat_id="3" template="projects">
</div>

<insert name="show_include" file="footer">
</body>
</html>
