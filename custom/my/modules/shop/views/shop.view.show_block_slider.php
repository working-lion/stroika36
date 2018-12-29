<?php
/**
 * Шаблон блока товаров
 *
 * Шаблонный тег <insert name="show_block" module="shop" [count="количество"]
 * [cat_id="категория"] [site_id="страница_с_прикрепленным_модулем"] [brand_id="производитель"]
 * [images="количество_изображений"] [images_variation="тег_размера_изображений"]
 * [sort="порядок_вывода"] [param="дополнительные_условия"]
 * [hits_only="только_хиты"] [action_only="только акции"] [new_only="только_новинки"]
 * [discount_only="только_со_скидкой"]
 * [only_module="only_on_module_page"] [template="шаблон"]>:
 * блок товаров
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2017 OOO «Диафан» (http://www.diafan.ru/)
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

if (empty($result["rows"]))
{
	return false;
}

//заголовок блока
if (!empty($result["attributes"]["head"]))
{
	echo '<div class="title_h2">'.$result["attributes"]["head"].'</div>';
}
//заголовок блока
elseif (!empty($result["name"]))
{
	echo '<div class="title_h2">'.$result["name"].'</div>';
}

//товары в разделе
if (!empty($result["rows"]))
{
	echo '<div class="shop-pane shop-slider js-shop-slider column4'.(!empty($result["attributes"]["class"]) ? ' responsive' :
            '').'">';
        echo $this->get('rows2','shop',$result);
	echo '</div>';
}
