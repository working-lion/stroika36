<?php
/**
 * Файл-блок шаблона
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
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

<footer>
	<!--<div class="footer__offer">
        <div class="container">
            <insert name="show_form" module="feedback" site_id="18" template="offer" btn="Получить">
        </div>
    </div>-->
    <div class="footer__slider main_col">
        <div class="title_h2">Наши поставщики</div>
        <insert name="show_block" module="bs" cat_id="4" template="slider" count="10">
    </div>
	<div class="footer__bl">
        <div class="container">
            <div class="footer__top footer__top__flex">
                <div class="footer__text"><insert name="show_block" module="site" id="3"></div>
                <div class="footer__subscription">
                    <div class="footer__search-block">
                        <div class="block_header">Что ищем?</div>
                        <insert name="show_search" module="search" template="top">
                    </div>
                    <div class="footer__subscription-block">
                        <div class="block_header">Рассылка</div>
                        <a href="/subscribtion/" class="button btn white">Подписаться</a>
                    </div>
                    <div class="footer__call-block">
                        <insert name="show_block" module="site" id="13">
                    </div>
                </div>
                <!--<div class="footer__menu"><insert name="show_block" module="menu" id="4" template="fotmenu"></div>-->
                <div class="footer__menu"><insert name="show_block" module="menu" id="3" template="fotmenu"></div>
			    <address class="footer__cont"><insert name="show_block" module="site" id="4"></address>
				<div class="footer__bl">
					<address class="footer__cont footer__cont--phone">
                        <insert name="show_block" module="site" id="10">
                    </address>
				    <div class="footer__soc">
                        <insert name="show_block" module="site" id="5">
                    </div>
				</div>
		    </div>
		    <div class="footer__bot footer__bot__flex">
				<div class="footer__copy"><insert name="show_block" module="site" id="6"></div>
				<div class="footer__des"><insert name="show_block" module="site" id="11"></div>
			</div>
	    </div>
	</div>
</footer>

<div class="modal mod_1">
	<div class="close mod_1__close">✖</div>
	<insert name="show_form" module="feedback" site_id="21" template="modal">
</div>

<div id="call-req-form" class="modal">
    <div class="close">✖</div>
    <insert name="show_form" module="feedback" site_id="38" template="modal">
</div>

<div id="add-specification" class="modal">
    <div class="close">✖</div>
    <!--<insert name="show_form" module="feedback" site_id="57" template="modal">-->
    <div class="feedback_form">
        <form method="POST" action="" class="ajax">
            <input type="hidden" name="module" value="specification">
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="site_id" value="56">
            <input type="hidden" name="tmpcode" value="c4bbac870026694953a91cbd99149a13">
            <h3>Добавить спецификацию</h3>
            <div class="feed_params">
                <div class="feed_params__pole">
                    <div class="pole feedback_form_param111111">
                        <div class="infofield">Название спецификации<span style="color:red;">*</span>:</div>
                        <input placeholder="Название спецификации *" type="text" name="p111111" value="">
                    </div>
                    <div class="errors error_p111111" style="display:none">
                    </div>
                </div>
                <div class="feed_params__pole">
                    <div class="pole feedback_form_param111111">
                        <div class="infofield">Описание спецификации<span style="color:red;">*</span>:</div>
                        <textarea name="p111112" placeholder="Описание спецификации *"></textarea>
                    </div>
                    <div class="errors error_p111112" style="display:none"></div>
                </div>
            </div>
            <input type="submit" value="Сохранить" class="button btn solid">
            <div class="required_field">
                <span style="color:red;">*</span> — <span class="useradmin_contener" href="http://stroika36.com/useradmin/edit/?module_name=languages&amp;name=%25D0%259F%25D0%25BE%25D0%25BB%25D1%258F%252C%2B%25D0%25BE%25D0%25B1%25D1%258F%25D0%25B7%25D0%25B0%25D1%2582%25D0%25B5%25D0%25BB%25D1%258C%25D0%25BD%25D1%258B%25D0%25B5%2B%25D0%25B4%25D0%25BB%25D1%258F%2B%25D0%25B7%25D0%25B0%25D0%25BF%25D0%25BE%25D0%25BB%25D0%25BD%25D0%25B5%25D0%25BD%25D0%25B8%25D1%258F&amp;element_id=0&amp;lang_id=1&amp;type=text&amp;rand=352&amp;is_lang=true&amp;lang_module_name=feedback&amp;iframe=true&amp;width=600&amp;height=120">Поля, обязательные для заполнения</span>
            </div>
        </form>
        <div class="errors error" style="display:none"></div>
    </div>
</div>

<div id="overlay"></div>
<insert name="show_js"/>

<!--<script type="text/javascript" src="//cdn.jsdelivr.net/jquery.slick/1.6.0/slick.min.js"></script>-->

<script type="text/javascript" src="<insert name="path">custom/my/js/slick.min.js" charset="UTF-8"></script>
<?/*?><script type="text/javascript" src="<insert name="path">custom/my/js/jquery.mCustomScrollbar.concat.min.js"
charset="UTF-8"></script><?*/?>
<script src="<insert name="path">custom/my/js/superfish.js"></script>
<script src="<insert name="path">custom/my/js/supersubs.js"></script>
<script type="text/javascript" src="<insert name="path">custom/my/js/theme.js" charset="UTF-8"></script>
<script type="text/javascript" src="<insert name="path">custom/my/js/jquery.easydropdown.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="<insert name="path">custom/my/js/jquery.fancybox.min.js" charset="UTF-8"></script>
<!--<script type="text/javascript" src="<insert name="path">custom/my/js/Hyphenator.js"
charset="UTF-8"></script>-->
