<?php
/**
 * Файл-блок счетчики
 *
 * @package    Diafan.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://diafan.ru)
 */

if (! defined('DIAFAN'))
{
	$path = __FILE__; $i = 0;
	while(! file_exists($path.'/includes/404.php'))
	{
		if($i == 10) exit; $i++;
		$path = dirname($path);
	}
	include $path.'/includes/404.php';
}
?>
<meta name="viewport" content="width=device-width">
<!-- , initial-scale=1.0 -->

<insert name="show_head">

<insert name="show_css" files="default.css">

<link href="<insert name="path">custom/my/css/slick.css" rel="stylesheet" type="text/css">
<link href="<insert name="path">adm/css/fontawesome.css" rel="stylesheet" type="text/css">
<link href="<insert name="path">custom/my/css/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css">
<link href="<insert name="path">custom/my/css/jquery.fancybox.min.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.css"/>
<link href="<insert name="path">custom/my/css/easydropdown.metro.css" rel="stylesheet" type="text/css">

<link href="<insert name="path">custom/my/css/style.css" rel="stylesheet" type="text/css">
<link href="<insert name="path">custom/my/css/ower.css" rel="stylesheet" type="text/css">

<link href="<insert name="path">custom/my/css/responsive.css" rel="stylesheet" type="text/css">
<link href="<insert name="path">custom/my/css/responsive_ower.css" rel="stylesheet" type="text/css">