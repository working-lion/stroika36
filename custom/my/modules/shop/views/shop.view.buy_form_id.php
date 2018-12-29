<?php
/**
 * Шаблон кнопки «Купить», в котором характеристики, влияющие на цену выводятся в виде выпадающего списка
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

if (! empty($result["result"]["access_buy"]))
	return false;

if($result["row"]["empty_price"])
	return false;

$action = '';
if(! $result["result"]["cart_link"] || $result["row"]["no_buy"] || empty($result["row"]["count"]))
{
	$action = 'buy';
}

echo '
<form method="post" action="" class="js_shop_form shop_form ajax">
<input type="hidden" name="good_id" value="'. $result["row"]["id"].'">
<input type="hidden" name="module" value="shop">
<input type="hidden" name="action" value="'.$action.'">';

if ($result["row"]["no_buy"] || empty($result["row"]["count"]))
{
	//echo '<div class="js_shop_no_buy js_shop_no_buy_good shop_no_buy shop_no_buy_good">'.$this->diafan->_('Товар временно отсутствует').'</div>';
	$hide_submit = true;
	$waitlist = true;
}
if(! $result["result"]["cart_link"])
{
    $hide_submit = true;
}

$price_count_param_id=array();
$price_count_param=array();

// у товара несколько цен
if ($result["row"]["price_arr"])
{
	foreach ($result["row"]["price_arr"] as $price)
	{
		$param_code = '';
		$pcp=0;
		$p32name='';
		$p32id=0;
		foreach ($price["param"] as $p)
		{
			if($p["value"])
			{
				// if ($p['id']==31)
				// 	$pcp=$p['value'];

				if ($p["id"]==32)
				{
					$p32id=$p["value"];
					if ($p["value"]==53)
					{
						$p32name=' за шт.';
					}
					elseif ($p["value"]==54)
					{
						$p32name=' за м<sup>2</sup>';
					}
					elseif ($p["value"]==69)
					{
						$p32name=' за длину';
					}
				}
				else
				{
					$param_code .= ' param'.$p["id"].'="'.$p["value"].'"';
				}
			}
		}
		if(! empty($price["image_rel"]))
		{
			$param_code .= ' image_id="'.$price["image_rel"].'"';
		}
		$price_count_param[$pcp]=$price["price"];
		$price_count_param_id[]=$pcp;
		echo '<div class="js_shop_param_price shop_param_price shop-item-price"'.$param_code.' price_no_format="'.$price["price_no_format"].'" currency="'.$result["result"]["currency"].'" p32id="'.$p32id.'">';
		echo '<div class="title_price"><span class="title">'.$this->diafan->_('Цена ').'</span>'.$p32name.'</div>';
		echo '<div class="price-wrap"></div><span class="price"><span class="js_shop_price" summ="'.$price["price_no_format"].'" 
format_price_1="' .$this->diafan->configmodules("format_price_1", "shop").'" format_price_2="'.$this->diafan->configmodules("format_price_2", "shop").'" format_price_3="'.$this->diafan->configmodules("format_price_3", "shop").'">'.$price["price"].'</span> '.$result["result"]["currency"];
			if (!empty($price["old_price"]))
			{
				echo '<div class="cart_old-wrap"><span class="shop_old_price price-old"><span class="shop_price_value strike">'
                    .$price["old_price"].' 
'.$result["result"]["currency"].$p32name.'</span></span></div>';
			}
			if (! $price["count"] && empty($hide_submit) || empty($price["price_no_format"]) && ! $result['result']["buy_empty_price"])
			{
				echo '<span class="js_shop_no_buy shop_no_buy">'.$this->diafan->_('Товар временно отсутствует').'</span>';
				$waitlist = true;
			}
			echo '</span>';
		echo '</div>';
	}


}
echo '<div class="summa_buy"><div class="title_summ title_price">Итого</div><div class="buy_form_itogo_block"></div></div>';
echo '<div class="clear"></div>';
if(! empty($result["row"]["additional_cost"]))
{
	$rand = rand(0, 9999);
	echo '<div class="js_shop_additional_cost shop_additional_cost">';
	foreach($result["row"]["additional_cost"] as $r)
	{
		echo '<div class="shop_additional_cost_block"><input type="checkbox" name="additional_cost[]" value="'.$r["id"].'" id="shop_additional_cost_'.$result["row"]["id"].'_'.$r["id"].'_'.$rand.'" summ="';
		if(! $r["percent"] && $r["summ"])
		{
			echo $r["summ"];
		}
		echo '"';
		if($r["required"])
		{
			echo ' checked disabled';
		}
		echo '> <label for="shop_additional_cost_'.$result["row"]["id"].'_'.$r["id"].'_'.$rand.'">'.$r["name"];
		if($r["percent"])
		{
			foreach ($result["row"]["price_arr"] as $price)
			{
				$param_code = '';
				foreach ($price["param"] as $p)
				{
					if($p["value"])
					{
						$param_code .= ' param'.$p["id"].'="'.$p["value"].'"';
					}
				}
				echo '<div class="js_shop_additional_cost_price" summ="'.$r["price_summ"][$price["price_id"]].'"'.$param_code.'>';
				echo ' <b>+'.$r["format_price_summ"][$price["price_id"]].' '.$result["result"]["currency"].'</b></div>';
			}
		}
		elseif($r["summ"])
		{
			echo ' <div class="js_shop_additional_cost" summ="'.$r["summ"].'"><b>+'.$r["format_summ"].' '.$result["result"]["currency"].'</b></div>';
		}
		echo '</label></div>';
	}
	echo '</div>';
}

// $vals=DB::query_fetch_key_value("SELECT [name],id FROM {shop_param_select} WHERE id in (%s) AND param_id=%d ORDER BY [name]", implode(',',$price_count_param_id), 31,'id','name');
//
// echo '<div class="price_variation">';
// $first=true;
// foreach ($vals as $k=>$name)
// {
// 	$price=$price_count_param[$k];
// 	if ($first)
// 	{
// 		$first=false;
// 		echo $name.' чехол '.$price.' '.$result["result"]["currency"].', ';
// 	}
// 	else
// 	{
// 		echo $name.' и более - '.$price.' '.$result["result"]["currency"].'/шт';
// 	}
// }
// echo '</div>';

echo '<div class="js_shop_buy shop_buy to-cart buy_block">';
/* 	if (empty($result["row"]['is_file']) && empty($hide_submit))
	{
		echo '<input type="text" value="1" name="count" class="number" pattern="[0-9]+([\.|,][0-9]+)?" step="any">';
		if(! empty($result["row"]["measure_unit"]))
		{
			echo ' '.$result["row"]["measure_unit"].' ';
		}
	} */
	if(empty($hide_submit))
	{
		echo '

		<div class="number_bl">
		<span class="minus">-</span>';
		echo '<input type="text" value="1" name="count" class="number" pattern="[0-9]+([\.|,][0-9]+)?" step="any">';

		echo '    <span class="plus">+</span>';
		echo '</div>';

}

	// у товара несколько цен
if ($result["row"]["price_arr"])
	{
	echo '<div class="addict-field">';
		echo '<div class="js_shop_form_param shop_form_param">';
		foreach ($result["result"]["depends_param"] as $param)
		{
			if(! empty($result["row"]["param_multiple"][$param["id"]]))
			{
				if(count($result["row"]["param_multiple"][$param["id"]]) == 1)
				{
					foreach ($result["row"]["param_multiple"][$param["id"]] as $value => $depend)
					{
						echo '<input type="hidden" name="param'.$param["id"].'" value="'.$value.'"'.($depend == 'depend' ? ' class="depend_param js_shop_depend_param"' : '').'>';
					}
				}
				else
				{
					$select = '';
					foreach ($param["values"] as $value)
					{
						if(! empty($result["row"]["param_multiple"][$param["id"]][$value["id"]]))
						{
							if(! $select)
							{
								if ($param["id"]==9)
								{
									$select = '<select style="display:none;" name="param'.$param["id"].'" class="shop-dropdown inpselect'.($result["row"]["param_multiple"][$param["id"]][$value["id"]] == 'depend' ? ' depend_param js_shop_depend_param' : '').'">';
								}
								else
								{
									$select = '<span class="title_select hide"> '.$param["name"].'</span> <select name="param'.$param["id"].'" class="shop-dropdown inpselect'.($result["row"]["param_multiple"][$param["id"]][$value["id"]] == 'depend' ? ' depend_param js_shop_depend_param' : '').'">';
								}
							}

							$select .= '<option value="'.$value["id"].'"'
							.(! empty($value["selected"]) ? ' class="js_form_option_selected" selected' : '')
							.'>'.$value["name"].'</option>
							';
						}
					}
					if($select)
					{
						echo $select.'</select> ';
					}
				}
			}
		}
		echo '</div>';
	echo '</div>';
}



if(empty($hide_submit))
{
	echo '<input class="btn btn_buy" type="button" class="button solid" value="'.$this->diafan->_('в корзину', false).'" action="buy">';

}
else
{
   echo '<a href="" class="subsc_good btn">'.$this->diafan->_('Сообщить').'</a>';
}

	if(empty($hide_submit) && ! empty($result["result"]["one_click"]))
	{
		echo '<span class="js_shop_one_click shop_one_click"><input type="button" class="btn_one_click btn_trans" value="'.$this->diafan->_('Купить в 1 клик', false).'" action="one_click"></span>';
	}


echo '</div>';


echo '<div class="error"';
if (! empty($result["row"]["count_in_cart"]))
{
	$measure_unit = ! empty($result["row"]["measure_unit"]) ? $result["row"]["measure_unit"] : $this->diafan->_('шт.');
	echo '>'.$this->diafan->_('В <a href="%s">корзине</a> %s %s', true, BASE_PATH_HREF.$result["result"]["cart_link"], $result["row"]["count_in_cart"], $measure_unit);
}
else
{
	echo ' style="display:none;">';
}
echo '</div>';
echo '</form>';
//форма быстрого заказа
if(! empty($result["result"]["one_click"]))
{
	$result["result"]["one_click"]["good_id"] = $result["row"]["id"];
	echo $this->get('one_click', 'cart', $result["result"]["one_click"]);
}


if(! empty($waitlist))
{
	echo '
		<div class="no_buy_good_block moda">
			<div class="close no_buy__close">✖</div>
   <h3 class="js_shop_no_buy js_shop_no_buy_good shop_no_buy shop_no_buy_good">'.$this->diafan->_('Товар временно отсутствует').'</h3>
	<div class="js_shop_waitlist shop_waitlist">
		<div class="infofield">'.$this->diafan->_('Сообщить когда появится на e-mail').'</div>
		<input type="email" name="mail" value="'.$this->diafan->_users->mail.'">
		<input type="button" class="btn" value="'.$this->diafan->_('Ок', false).'" action="wait">
		<div class="errors error_waitlist" style="display:none"></div>
	</div></div>';
}

$this->diafan->_site->js_view[] =BASE_PATH.Custom::path('modules/shop/js/shop.buy_form.js');
