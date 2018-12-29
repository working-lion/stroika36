<?php
/**
 * Шапка
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
<header class="header">
	<div class="header__left">
		<div class="header__logo">
            <insert name="show_href" img="img/logo-clear-text-2.png" alt="title">
        </div>
        <div class="header__left-close"></div>
        <div class="header__menu_title">
            <a href="/shop/">Каталог</a>
            <div class="btn-back">Назад</div>
        </div>
		<div class="header__menu_cat">
			<insert name="show_block" module="menu" id="1" template="leftmenu" count_level="6">
		</div>
	</div>
	<div id="header-top" class="header__top">
		<div class="header__top_up clearfix">
			<div class="header__top_left clearfix">
                <div class="header__logo-mobile">
                    <insert name="show_href" img="img/logo-clear-text-2.png" alt="title">
                </div>
                <div class="header__top_left-search ">
                    <insert name="show_search" module="search" template="top">
                </div>
                <div class="header__top_left-contacts">
                    <!--<address class="header__city"><insert name="show_block" module="site" id="8"></address>
				    <address class="header__addr"><insert name="show_block" module="site" id="1"></address>-->
                    <address class="header__addr header__addr--two"><insert name="show_block" module="site" id="9"></address>
                    <address class="header__time_phone"><insert name="show_block" module="site" id="2"></address>
                    <address class="header__email"><insert name="show_block" module="site" id="12"></address>
                    <div class="header__top_left-links"><insert name="show_block" module="site" id="13"></div>
                </div>
			</div>
            <div class="header__catr-user__flex">
                <div class="header__cart">
                    <insert name="show_block" module="cart"/>
                </div>
                <div class="header__user">
                    <a href="#" class="header__user-link">
                        <img src="/custom/my/img/user_ico.svg" alt="Личный кабинет">
                        <!--<div class="header__user-menu">
                            <insert name="show_block" module="registration">
                        </div>-->
                    </a>
                    <div class="header__user-login">
                        <insert name="show_login" module="registration">
                    </div>
                </div>
            </div>
		</div>
		<div class="header__top_menu clearfix">
            <nav class="header__top_menu-nav">
                <button class="c-hamburger c-hamburger--htx c_hamburger_top">
                   <span></span>
                </button>
                <insert name="show_block" module="menu" id="2" template="topmenu"/>
            </nav>
            <div class="header__top_menu-btns">
                <a href="#form-smeta" class="btn btn-calc js-btn-calc btn-icon btn-orange">Калькулятор</a>
            </div>
		</div>
	</div>

</header>