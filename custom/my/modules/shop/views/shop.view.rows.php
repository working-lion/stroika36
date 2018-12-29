<?php
/**
 * Шаблон списка товаров
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

if(empty($result['rows'])) return false;

		foreach ($result['rows'] as $row)
		{
			echo '<div class="js_shop col_5 shop-item shop">';

			//вывод изображений товара
			if (!empty($row["img"]))
			{
				echo '<div class="shop_img shop-photo">';
				foreach ($row["img"] as $img)
				{
					switch ($img["type"])
					{
						case 'animation':
							echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$row["id"].'shop]">';
							break;
						case 'large_image':
							echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'">';
							break;
						default:
							echo '<a href="'.BASE_PATH_HREF.$img["link"].'">';
							break;
					}
					echo '<img src="'.$img["src"].'" alt="'.$img["alt"].'" title="'.$img["title"].'" image_id="'.$img["id"].'" class="js_shop_img">';
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
					echo '</a> ';

                                        //if(!empty($result['search'])) break;
				}

                // echo '<span class="js_shop_wishlist shop_wishlist shop-like'.(! empty($row["wish"]) ? ' active' : '').'">&nbsp;</span>';


				echo '</div>';
			}
			else
			{
				echo '<div class="shop_img shop-photo">';

				   echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="no_img">';

					echo '<img src="'.BASE_PATH.'custom/my/img/noimg2.png" class="js_shop_img">';
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
					echo '</a> ';
				echo '</div>';
			}

			echo '<div class="shop_item__text">';

			//вывод названия и ссылки на товар
			echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="shop-item-title">'.$row["name"].'</a>';
			//рейтинг товара
/* 			if (!empty($row["rating"]))
			{
				echo ' '.$row["rating"];
			}	 */

/* 			//вывод краткого описания товара
			if (!empty($row["anons"]))
			{
				echo '<div class="shop_anons">'.$this->htmleditor($row['anons']).'</div>';
			} */

			//вывод производителя
			if (!empty($row["brand"]))
			{
				echo '<div class="shop_brand shop_param__item">';
				echo '<div class="shop_param_tit"> '.$this->diafan->_('Производитель').': </div>';
				echo '<div class="shop_param_val"><a href="'.BASE_PATH_HREF.$row["brand"]["link"].'">'.$row["brand"]["name"].'</a></div>';
				echo '</div>';
			}
/*
			//вывод артикула
			if (!empty($row["article"]))
			{
				echo '<div class="shop_article">';
				echo $this->diafan->_('Артикул').': ';
				echo '<span class="shop_article_value">'.$row["article"].'</span>';
				echo '</div>';
			} */

			//вывод параметров товара
			if (empty($result['search']) && !empty($row["param"]))
			{
				echo $this->get('param', 'shop', array("rows" => $row["param"], "id" => $row["id"]));
			}

/* 			//теги товара
			if (!empty($row["tags"]))
			{
				echo $row["tags"];
			} */


			//вывод кнопки "Купить"
			echo $this->get('buy_form', 'shop', array("row" => $row, "result" => $result));



			echo '</div>
			</div>';
		}
