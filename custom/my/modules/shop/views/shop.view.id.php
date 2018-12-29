<?php
/**
 * Шаблон страницы товара
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

echo '<div class="js_shop_id js_shop shop shop_id shop-item-container">';


echo '<div class="shop-item-left">';

//вывод изображений товара
if (!empty($result["img"]))
{
	echo '<div class="js_shop_all_img shop_all_img shop-item-big-images">';
	//$k = 0;
    echo '<div class="shop_all_img__slider">';
	foreach ($result["img"] as $img)
	{
		switch ($img["type"])
		{
			case 'animation':
				echo '<a class="js_shop_img shop-item-image'.(empty($k) ? ' active' : '').'" href="'.BASE_PATH.$img["link"].'" data-fancybox image_id="'.$img["id"].'">';
				break;
			case 'large_image':
				echo '<a class="js_shop_img shop-item-image'.(empty($k) ? ' active' : '').'" href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'" image_id="'.$img["id"].'">';
				break;
			default:
				echo '<a class="js_shop_img shop-item-image'.(empty($k) ? ' active' : '').'" href="'.BASE_PATH.$img["link"].'" image_id="'.$img["id"].'">';
				break;
		}
		echo '<img src="'.BASE_PATH.$img["link"].'" alt="'.$img["alt"].'" title="'.$img["title"].'" image_id="'.$img["id"].'" class="shop_id_img">';
		echo '</a>';
	//	$k++;
	}
    echo '</div>';
    echo '<div class="shop_all_img__nav">';
	foreach ($result["img"] as $img)
	{
		echo '<div class="shop_all_img__nav_img">
        <img src="'.BASE_PATH.$img["link"].'" alt="'.$img["alt"].'" title="'.$img["title"].'" image_id="'.$img["id"].'" class="shop_id_img">
        </div>';
	}
    echo '</div>';
	echo '<span class="shop-photo-labels">';
		if (!empty($row['hit']))
		    {
				echo '<span class="shop_photo_labels__block shop_photo_labels__hit">Хит</span>';
			}
		if (!empty($row['action']))
			{
				echo '<span class="shop_photo_labels__block shop_photo_labels__act">Акция</span>';
			}
		if (!empty($row['new']))
			{
				echo '<span class="shop_photo_labels__block shop_photo_labels__new">Новинка</span>';
			}

		//вывод скидки на товар
		if (!empty($row["discount"]))
			{
				echo '<div class="shop_photo_labels__block shop_photo_labels__discount shop_discount"> <span class="shop_discount_value">-'.$row["discount"].' '.$row["discount_currency"].($row["discount_finish"] ? ' ('.$this->diafan->_('до').' '.$row["discount_finish"].')' : '').'</span></div>';
			}
	echo '</span>';

	echo '</div>';
}

echo '</div>';

echo '<div class="shop-item-right">';
		//кнопка "Купить"
		echo $this->get('buy_form_id', 'shop', array("row" => $result, "result" => $result));

		//вывод артикула
		if (!empty($result["article"]))
		{
			echo '<div class="shop_param__item shop_param__item-row shop-item-artikul">
			<div class="shop_param_tit">'.$this->diafan->_('Артикул').':</div>
			<div class="shop_param_val">'.$result["article"].'</div>
			</div>';
		}

		//вывод производителя
		if (!empty($result["brand"]))
		{
			echo '<div class="shop_param__item shop_param__item-row shop_brand">';
			echo '<div class="shop_param_tit">'.$this->diafan->_('Производитель').': </div>';
			echo '<div class="shop_param_val"><a href="'.BASE_PATH_HREF.$result["brand"]["link"].'">'.$result["brand"]["name"].'</a></div>';
			echo '</div>';
		}
		//характеристики товара
		if (!empty($result["param"]))
		{
			echo $this->get('param_table', 'shop', array("rows" => $result["param"], "id" => $result["id"]));
		}

echo '</div>';
echo '</div>';
//счетчик просмотров
if(! empty($result["counter"]))
{
	echo '<div class="shop_counter">'.$this->diafan->_('Просмотров').': '.$result["counter"].'</div>';
}

//теги товара
if (!empty($result["tags"]))
{
	echo $result["tags"];
}
//теги товара
if (!empty($result["text"]))
{
	echo '<div class="shop_text">
	<div class="h2">'.$this->diafan->_('Описание товара').'</div>
	'.$this->htmleditor($result['text']).'</div>';
}


echo $this->htmleditor('<insert name="show_block_order_rel" module="shop" count="5" images="1">');
echo $this->htmleditor('<insert name="show_block_rel" module="shop" count="5" images="1">');

//комментарии к товару
if (!empty($result["comments"]))
{
	echo '<div class="comments_all">';
	echo '<div class="h3">'.$this->diafan->_('Отзывы о товаре').'</div>';
	echo $result["comments"];
	echo '</div>';
}
echo '<div class="clear"></div>';

//полное описание товара







//ссылки на предыдущий и последующий товар
if (! empty($result["previous"]) || ! empty($result["next"]))
{
	echo '<div class="previous_next_links">';
	if (! empty($result["previous"]))
	{
		echo '<div class="previous_link"><a href="'.BASE_PATH_HREF.$result["previous"]["link"].'">&larr; '.$result["previous"]["text"].'</a></div>';
	}
	if (! empty($result["next"]))
	{
		echo '<div class="next_link"><a href="'.BASE_PATH_HREF.$result["next"]["link"].'">'.$result["next"]["text"].' &rarr;</a></div>';
	}
	echo '</div>';
}
