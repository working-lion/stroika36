<?php
/**
 * Шаблон вывода информации о последнем совершенном заказе
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN')) {
    $path = __FILE__; $i = 0;
	while(! file_exists($path.'/includes/404.php'))
	{
		if($i == 10) exit; $i++;
		$path = dirname($path);
	}
	include $path.'/includes/404.php';
}


//шапка таблицы
echo '
<div class="cart_relult_block">
<table class="cart resulya">
	<thead><tr>

		<th class="cart_name">'.$this->diafan->_('Детали заказа').'</th>';
		if(! empty($result["measure_unit"]))
		{
			echo '<th class="cart_measure_unit">'.$this->diafan->_('Единица измерения').'</th>';
		}
		echo '
		<th class="cart_count">'.$this->diafan->_('Количество').'</th>';
		if($result["discount"])
		{
			echo '<th class="cart_old_price">'.$this->diafan->_('Цена со скидкой').'</th>';
		}
		echo '
		<th class="cart_price">'.$this->diafan->_('Цена').'</th>';
		if($result["discount"])
		{
			echo '<th class="cart_discount">'.$this->diafan->_('Скидка').'</th>';
		}
		echo '<th class="cart_summ">'.$this->diafan->_('Итоговая стоимость').'</th>
	</tr></thead><tbody>';

//товары
if (! empty($result["rows"]))
{
	foreach ($result["rows"] as $row)
	{
		echo '
		<tr>
			<td class="cart_name">';
/* 			if(! empty($row["cat"]))
			{
				echo '<a href="'.BASE_PATH_HREF.$row["cat"]["link"].'">'.$row["cat"]["name"].'</a> / ';
			} */
			echo '<div class="car_img">';
		if (!empty($row["img"]))
		{
			echo '<a href="'.BASE_PATH_HREF.$row["link"].'"><img src="'.$row["img"]["src"].'" width="'.$row["img"]["width"].'" height="'.$row["img"]["height"].'" alt="'.$row["img"]["alt"].'" title="'.$row["img"]["title"].'"></a> ';
		}
echo '</div>';
			echo '<a href="'.BASE_PATH_HREF.$row["link"].'">'.$row["name"];

			/*
			if(! empty($row["article"]))
			{
				echo '<br/>'.$this->diafan->_('Артикул').': '.$row["article"];
			}*/
			echo '</a>';
			if(! empty($row["additional_cost"]))
			{
				foreach($row["additional_cost"] as $a)
				{
					echo '<br>'.$a["name"];
					if($a["summ"])
					{
						echo ' + '.$a["format_summ"].' '.$result["currency"];
					}
				}
			}
			echo '</td>';
			if(! empty($result["measure_unit"]))
			{
				echo '<td class="cart_measure_unit">'.($row["measure_unit"] ? $row["measure_unit"] : $this->diafan->_('шт.')).'</td>';
			}
			echo '
			<td class="js_cart_count cart_count">'.$row["count"].'</td>';
$count_clm2=4;
			if($result["discount"])
			{

				$count_clm2++;

				echo '<td class="cart_price">'.($row["old_price"] ? $row["price"].'<span class="cur">'.$result["currency"].'</span>' : '').'</td>';
				echo '<td class="cart_old_price">'.($row["old_price"] ? $row["old_price"] : $row["price"]).' <span class="cur">'.$result["currency"].'</span></td>';
				echo '<td class="cart_discount">'.($row["discount"] ? $row["discount"].' <span class="cur">'.$result["currency"].'</span>' : '').'</td>';
			}
			else
			{
				echo '<td class="cart_price">'.$row["price"].'<span class="cur">'.$result["currency"].'</span><span class="eda">/'.($row["measure_unit"] ? $row["measure_unit"] : $this->diafan->_('шт.')).'</span></td>';
			}
			echo '
			<td class="cart_summ">'.$row["summ"].' <span class="cur">'.$result["currency"].'</span></td>
		</tr>';
	}

	$count_clm = 0;
	if(! empty($result["discount"]))
	{
		$count_clm += 2;
	}

/*
	// общая скидка от объема
	if(! empty($result["discount_summ"]))
	{
		echo '
			<tr>
				<td class="cart_discount_total_text" colspan="'.($count_clm + 2).'" ></td>
				<td class="cart_discount_total">';
		if(! empty($result["discount_summ"]))
		{
			echo $this->diafan->_('Скидка').' '.$result["discount_summ"].' <span class="cur">'.$result["currency"].'</span>';
		}
		echo '</td>
		</tr>';
	} */

	//итоговая строка для товаров
	echo '
		<tr class="cart_last_trr resu">
             <td class="empty"></td>
			<td class="cart_totalr" colspan="'.($count_clm2).'">';
			echo '<div class="cart_totalr__bl">';
			if(! empty($result["discount_summ"]))
			{
				echo '<span class="discount_total">'.$this->diafan->_('Скидка').' </span>';
				echo '<span class="cart_summ_old_total_sum">'.$result["discount_summ"].' <span class="cur">'.$result["currency"].'</span></span>';
			}
			echo '</div>';
			echo $this->diafan->_('Стоимость товаров').': <div class="cart_summa">'. $result["summ_goods"].' <span class="cur">'.$result["currency"].'</span></div></td>';
			echo '
			<td class="cart_count">'.$result["count"].'</td><td class="cart_price"></td>';
	/* if($result["discount"])
	{
		echo '<td class="cart_old_price"></td>';
        echo '<td class="cart_discount"></td>';
	} */
	/* echo '
	<td class="cart_summ">';
	if(! empty($result["old_summ_goods"]))
	{
		echo '<div class="cart_summ_old_total">'.$result["old_summ_goods"].' <span class="cur">'.$result["currency"].'</span></div>';
	}
	echo $result["summ_goods"]. ' <span class="cur">'.$result["currency"].'</span>';
	echo '</td>
	</tr>';
 */
	//дополнительно
	if (! empty($result["additional_cost"]))
	{
		echo '<tr><th colspan="'.($count_clm + 5).'" class="cart_additional_title">'.$this->diafan->_('Дополнительно').'</th></tr>';
		foreach ($result["additional_cost"] as $row)
		{
			if ($row['amount'])
			{
				$row['text'] .= '<br>'.$this->diafan->_('Бесплатно от суммы').' '.$row['amount'].' '.$result["currency"];
			}
			echo '
			<tr>
				<td class="cart_additional" colspan="'.($count_clm + 3).'">
					<div class="cart_additional_cost_name">'.$row["name"].'</div>
				</td>
				<td class="cart_price">'.($row['percent'] ? $row['percent'].'%' : $row["price"]).'</td>
				<td class="cart_summ">'.$row["summ"].'</td
			</tr>';
		}
	}

 	//способы доставки
	if (! empty($result["delivery"]))
	{
		echo '<tr><th colspan="'.($count_clm + 3).'" class="cart_delivery_title">'.$this->diafan->_('Способ доставки').'</th></tr>';
		echo '
		<tr>
			<td colspan="'.($count_clm + 2).'" class="cart_delivery">
				<div class="cart_delivery_name">'.$result["delivery"]["name"].'</div>
			</td>
			<td class="cart_summ">'.$result["delivery"]["summ"].' '.$result["currency"].'</td>
		</tr>';
	}
}


//итоговая строка таблицы
if (! empty($result["delivery"]))
	{
echo '
	<tr class="cart_last_trr resu2">
	<td class="empty"></td>
		<td class="cart_totalr" colspan="'.($count_clm2).'">'.$this->diafan->_('Итого к оплате').': <div class="cart_summa">'.$result["summ"].' <span class="cur">'.$result["currency"].'</span></div></td>';
			/* if(! empty($result["measure_unit"]))
			{
				echo '<td></td>';
			} */
			echo '<td class="cart_count"></td><td class="cart_price"></td>';
/* 	if($result["discount"])
	{
		 echo '<td class="cart_old_price"></td>';
		 echo '<td class="cart_discount"></td>';
	} */
	/* echo '<td class="cart_summ" colspan="2">'.$result["summ"].' <span class="cur">'.$result["currency"].'</span>';
	if(! empty($result["tax"]))
	{
		echo '<br>'.$this->diafan->_('в т. ч. %s', true, $result["tax_name"]).'<br>'.$result["tax"];
	} */
	echo '</td>';
	}
	echo '</tr> </tbody>
</table>';

foreach($result["param"] as $param)
{
	echo '<div class="cart_param"><span class="nam_pa"><strong>'.$param["name"].':</strong>';
	if ($param["value"])
	{
		echo ' </span><span class="cart_param_value">';
		if($param["type"] == "attachments")
		{
			foreach ($param["value"] as $a)
			{
				if ($a["is_image"])
				{
					if($param["use_animation"])
					{
						echo ' <a href="'.$a["link"].'" rel="prettyPhoto[gallery'.$result["id"].'ab]"><img src="'.$a["link_preview"].'"></a> <a href="'.$a["link"].'" rel="prettyPhoto[gallery'.$result["id"].'ab_link]">'.$a["name"].'</a>';
					}
					else
					{
						echo ' <a href="'.$a["link"].'"><img src="'.$a["link_preview"].'"></a> <a href="'.$a["link"].'">'.$a["name"].'</a>';
					}
				}
				else
				{
					echo ' <a href="'.$a["link"].'">'.$a["name"].'</a>';
				}
			}
		}
		elseif($param["type"] == "images")
		{
			foreach ($param["value"] as $img)
			{
				echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">';
			}
		}
		elseif (is_array($param["value"]))
		{
			foreach ($param["value"] as $p)
			{
				if ($param["value"][0] != $p)
				{
					echo ', ';
				}
				if (is_array($p))
				{
					if ($p["link"])
					{
						echo '<a href="'.BASE_PATH_HREF.$p["link"].'">'.$p["name"].'</a>';
					}
					else
					{
						echo $p["name"];
					}
				}
				else
				{
					echo $p;
				}
			}
		}
		else
		{
			echo $param["value"];
		}
		echo '</span>';
	}
	echo '</div>';
	if($param["text"])
	{
		echo '<div class="cart_param_text">'.$param["text"].'</div>';
	}
}

echo '</div>';

/* array_unshift($this->diafan->_site->js_view, BASE_PATH_HREF.'custom/my/modules/cart/js/cart.table.js'); */
