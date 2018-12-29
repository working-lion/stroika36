<?php
/**
 * Шаблон блока товаров, которые обычно покупают с текущим товаром
 *
 * Шаблонный тег <insert name="show_block" module="shop" [count="количество"]
 * [images="количество_изображений"] [images_variation="тег_размера_изображений"]
 * [template="шаблон"]>:
 * блок товаров, которые обычно покупают с текущим товаром
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

if (!empty($result["rows"]))
{
	echo '<div class="shop-buyers-buy">';
	echo '<div class="h2">'.$this->diafan->_('C этим товаром покупают').'</div>
	<div class="shop-pane column4 responsive">';
        echo $this->get('rows','shop',$result);
	echo '</div>';
/*
		echo '<div class="shop_order_rel_list">';
			foreach ($result["rows"] as $row)
			{
				echo '<div class="js_shop shop-item-small shop">';
					echo '<a href="'.BASE_PATH_HREF.$row["link"].'">';

					//изображения товара
					if (!empty($row["img"]))
					{
						$img = $row["img"][0];
						echo '<span class="shop-item-small-image" style="background-image:url('.$img["src"].')">&nbsp;</span>';
						echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">';
					}

					//вывод названия и ссылки на товар
			        echo '<span class="title">'.$row["name"].'</span>';

					//кнопка "Купить"
			        echo $this->get('buy_form_order_rel', 'shop', array("row" => $row, "result" => $result));
			        echo '</a>';
		        echo '</div>';

			}
		echo '</div>';*/
	echo '</div>';

}
