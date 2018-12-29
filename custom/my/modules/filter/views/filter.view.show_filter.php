<?php
/**
 * Шаблон форма поиска по товарам
 *
 * Шаблонный тег <insert name="show_filter" module="filter"
 * [cat_id="категория"] [site_id="страница_с_прикрепленным_модулем"]
 * [ajax="подгружать_результаты"]
 * [only_module="только_на_странице_модуля"] [template="шаблон"]>:
 * форма поиска по товарам
 *
 * @package    DIAFAN.CMS
 * @author     Sarvar Khasanov
 * @version    5.4
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2014 OOO «Диафан» (http://diafan.ru)
 */
if (!defined('DIAFAN'))
{
	include dirname(dirname(dirname(__FILE__))).'/includes/404.php';
}


if (empty($result["update_form"]))
{
	echo '<div class="sty hide">Фильтры</div><div class="block shop-filter">';
}



echo '
<link href="'.BASE_PATH.'custom/my/modules/filter/css/filter.css" rel="stylesheet" type="text/css">
<link href="'.BASE_PATH.'custom/my/modules/filter/css/ion.rangeSlider.css" rel="stylesheet" type="text/css">
<link href="'.BASE_PATH.'custom/my/modules/filter/css/ion.rangeSlider.skinHTML5.css" rel="stylesheet" type="text/css">

<div class="block-content filter_block">
<form method="GET" action="'.BASE_PATH_HREF.$result["path"].'" class="js_shop_search_form'.(! empty($result["send_ajax"]) ? ' ajax' : '').'">';
echo '<input type="hidden" name="module" value="shop">
<input type="hidden" name="action" value="search">';

echo '<input type="hidden" name="attributes[cat_id]" value="'.$result["attributes"]["cat_id"].'">';
echo '<input type="hidden" name="attributes[site_id]" value="'.$result["attributes"]["site_id"].'">';
echo '<input type="hidden" name="attributes[ajax]" value="'.$result["attributes"]["ajax"].'">';
echo '<input type="hidden" name="attributes[only_module]" value="'.$result["attributes"]["only_module"].'">';
echo '<input type="hidden" name="attributes[only_shop]" value="'.$result["attributes"]["only_shop"].'">';
echo '<input type="hidden" name="attributes[template]" value="'.$result["attributes"]["template"].'">';

echo '<input type="hidden" name="attributes[tmp_cat_ids]" value="'.$result["attributes"]["tmp_cat_ids"].'">';
echo '<input type="hidden" name="attributes[tmp_site_ids]" value="'.$result["attributes"]["tmp_site_ids"].'">';

echo '<div class="shop-filter__list">';
if (count($result["site_ids"]) > 1)
{
	echo '<div class="shop_search_site_ids">
	<span class="input-title">'.$this->diafan->_('Раздел').':</span>
	<select class="js_shop_search_site_ids">';
	foreach ($result["site_ids"] as $row)
	{
		echo '<option value="'.$row["id"].'" path="'.BASE_PATH_HREF.$row["path"].'"';
		if($result["site_id"] == $row["id"])
		{
			echo ' selected';
		}
		echo '>'.$row["name"].'</option>';
	}
	echo '</select>';
	echo '</div>';
}

if (count($result["cat_ids"]) > 1)
{
	echo '<div class="js_shop_search_param_div shop_search_cat_ids">
	<span class="input-title">'.$this->diafan->_('Категория').':</span>
	<select name="cat_id" class="js_shop_search_cat_ids js_shop_search_params">';
	echo '<option value="">'.$this->diafan->_('Все').'</option>';
	foreach ($result["cat_ids"] as $row)
	{
		echo '<option value="'.$row["id"].'" site_id="'.$row["site_id"].'"';
		if($result["cat_id"] == $row["id"])
		{
			echo ' selected';
		}
		if(empty($row["available"]))
		{
			echo ' disabled';
		}
		echo '>';
		if($row["level"])
		{
			echo str_repeat('- ', $row["level"]);
		}
		echo $row["name"].(!empty($row["available"])? /*'('.$row["available"].')'*/ '' : '').'</option>';
	}
	echo '</select>';
	echo '</div>';
}
else
{
	echo '<input class="js_shop_search_params" name="cat_id" type="hidden" value="'.$result["cat_id"].'">';
}

if (! empty($result["shop_name"]))
{
	echo '<div class="js_shop_search_param_div shop_search_name">
		<div class="input-title">'.$this->diafan->_('Название').'</div>
		<input class="js_shop_search_params width-full text_form" type="text" name="sn" value="'.$result["shop_name"]["value"].'">
	</div>';
}

/* if (! empty($result["article"]))
{
	echo '<div class="js_shop_search_param_div shop_search_article">
		<div class="input-title">'.$this->diafan->_('Артикул').':</div>
		<input class="js_shop_search_params width-full text_form" type="text" name="a" value="'.$result["article"]["value"].'">
	</div>';
} */


if (! empty($result["price"]))
{

	echo '<div class="js_shop_search_param_div js_shop_search_param shop_search_price">
		<div class="input-title">'.$this->diafan->_('Цена').', руб.</div>
		<div class="search_price '.(!empty($_POST["pr1"]) || !empty($_POST["pr2"]) ? 'is_active' : '').'">
			<input type="text" placeholder="от" class="from js_shop_search_params text_form'.(empty($result["price"]["notdefault1"])?' default_value':'').'" name="pr1" value="'.$result["price"]["value1"].'"  style="">
			<span class="shop_search_price__middle"> - </span>
			<input type="text" placeholder="до"  class="to js_shop_search_params text_form'.(empty($result["price"]["notdefault2"])?' default_value':'').'" name="pr2" value="'.$result["price"]["value2"].'"  style="">
		<div id="slider"></div>

   </div>
	</div>';
}
// <div class="range-slider">
// 	<input type="text" class="js-range-slider" value="" />
// </div>
// <input type="hidden" id="minCost" name="minCost" value="'.$result["price"]["min"].'">
// <input type="hidden" id="maxCost" name="maxCost" value="'.$result["price"]["max"].'">
// <input type="hidden" id="minCostG" name="minCostG" value="'.$result["price"]["ming"].'">
// <input type="hidden" id="maxCostG" name="maxCostG" value="'.$result["price"]["maxg"].'">



if (! empty($result["brands"]))
{
	echo '<div class="js_shop_search_param_div js_shop_search_param shop_search_brand">
	<div class="shop_search_chek">
	<div class="input-title">'.$this->diafan->_('Производитель').'</div>';

  echo '
  <div class="br chek">
  <div class="input-title">'.$this->diafan->_('Производитель').'</div>';
	foreach ($result["brands"] as $row)
	{
		echo '<div class="chek_lab  js_shop_search_brand" site_id="'.$row["site_id"].'">
		<input class="js_shop_search_params" type="checkbox" name="brand[]" value="'.$row["id"].'"';
		if (!$row['available'])
		{
			echo ' disabled';
		}

			if(in_array($row["id"], $result["brand"]))
			{
				echo ' checked';
			}
			echo ' id="shop_search_brand'.$row["id"].'"> ';

		echo '<label for="shop_search_brand'.$row["id"].'" class="'.(in_array($row["id"], $result["brand"])?"cb_checked":"").'">'.$row["name"].'</label></div>';
	}
  echo '</div></div>';
	echo '</div>';
}

if (! empty($result["action"]))
{
	echo '<div class="js_shop_search_param_div shop_search_action">
		<input class="js_shop_search_params" type="checkbox" name="ac" id="shop_search_ac" value="1"'.($result["action"]["value"] ? ' checked' : '').(!$result["action"]["available"] ? ' disabled' : '').'>
		<label for="shop_search_ac" class="'.($result["action"]["value"]?"cb_checked":"").'">'.$this->diafan->_('Товар по акции').(!empty($result["action"]["available"])? '('.$result["action"]["available"].')' : '').'</label>
	</div>';
}

if (!empty($result["new"]))
{
	echo '<div class="js_shop_search_param_div shop_search_new">
		<input class="js_shop_search_params" type="checkbox" name="ne" id="shop_search_ne" value="1"'.($result["new"]["value"] ? ' checked' : '').(!$result["new"]["available"] ? ' disabled' : '').'>
		<label for="shop_search_ne" class="'.($result["new"]["value"]?"cb_checked":"").'">'.$this->diafan->_('Новинка').(!empty($result["new"]["available"])? '('.$result["new"]["available"].')' : '').'</label>
	</div>';
}

if (!empty($result["hit"]))
{
	echo '<div class="js_shop_search_param_div shop_search_hit">
		<input class="js_shop_search_params" type="checkbox" name="hi" id="shop_search_hit" value="1"'.($result["hit"]["value"] ? ' checked' : '').(!$result["hit"]["available"] ? ' disabled' : '').'>
		<label for="shop_search_hit" class="'.($result["hit"]["value"]?"cb_checked":"").'">'.$this->diafan->_('Хит').(!empty($result["hit"]["available"])? '('.$result["hit"]["available"].')' : '').'</label>
	</div>';
}

//echo vd($result);

if (!empty($result["rows"]))
{
	foreach ($result["rows"] as $row)
	{
		if($row["id"]==15)
			continue;

		echo '<div class="js_shop_search_param_div js_shop_search_param shop_search_param shop_search_param'.$row["id"].'" cat_ids="'.$row["cat_ids"].'" site_ids="'.$result["site_id"].'">';
		//echo vd($_POST["p3_1"]);
		switch ($row["type"])
		{
			case 'title':
				echo '<span class="input-title">'.$row["name"].':</span>';
				break;

			case 'date':
				echo '
				<span class="input-title">'.$row["name"].':</span>
				<div class="'.(!empty($_POST["p".$row["id"]."_1"]) || !empty($_POST["p".$row["id"]."_2"]) ? 'is_active' : '').'">
					<input class="js_shop_search_params text_form" type="text" name="p'.$row["id"].'_1" value="'.$row["value1"].'" class="from timecalendar" showTime="false">
					&nbsp;-&nbsp;
					<input class="js_shop_search_params text_form" type="text" name="p'.$row["id"].'_2" value="'.$row["value2"].'" class="to timecalendar" showTime="false">
				</div>';
				break;

			case 'datetime':
				echo '
				<span class="input-title">'.$row["name"].':</span>
				<div class="'.(!empty($_POST["p".$row["id"]."_1"]) || !empty($_POST["p".$row["id"]."_2"]) ? 'is_active' : '').'">
					<input class="js_shop_search_params text_form" type="text" name="p'.$row["id"].'_1" value="'.$row["value1"].'" class="from timecalendar" showTime="true">
					&nbsp;-&nbsp;
					<input type="text" name="p'.$row["id"].'_2" value="'.$row["value2"].'" class="to timecalendar text_form" showTime="true">
				</div>';
				break;

			case 'numtext':
				echo '
				<span class="input-title">'.$row["name"].':</span>';
				/*if ($row['slider'])
				{*/
					echo '
							<div class="slider_search_param'.(!empty($_POST["p".$row["id"]."_1"]) || !empty($_POST["p".$row["id"]."_2"]) ? ' is_active' : '').'" >
								<input type="floattext" class="from js_shop_search_params text_form'.(empty($row["notdefault1"])?' default_value':'').'" name="p'.$row["id"].'_1" value="'.$row["value1"].'" style="display:none;">

								<input type="floattext" class="to js_shop_search_params text_form'.(empty($row["notdefault2"])?' default_value':'').'" name="p'.$row["id"].'_2" value="'.$row["value2"].'" style="display:none;">
								<div class="slider_param"></div>
								<div class="range-slider">
								<input type="text" class="js-range-slider-param" value="" />
								</div>
								<input type="hidden" class="minCost" name="minCostp[]" value="'.($row["valueMinG"]<=$row["valueMin"]?$row["valueMin"]:$row["valueMinG"]).'">
								<input type="hidden" class="maxCost" name="maxCostp[]" value="'.($row["valueMaxG"]>=$row["valueMax"]?$row["valueMax"]:$row["valueMaxG"]).'">
								<input type="hidden" class="minCostG" name="minCostGp[]" value="'.($row["valueMinG"]<=$row["valueMin"]?$row["valueMin"]:$row["valueMinG"]).'">
								<input type="hidden" class="maxCostG" name="maxCostGp[]" value="'.($row["valueMaxG"]>=$row["valueMax"]?$row["valueMax"]:$row["valueMaxG"]).'">
						   </div>
						';
			/*	}
				else*/
				/*	echo '
					<div>
						<input class="js_shop_search_params" type="text" class="from text_form" name="p'.$row["id"].'_1" value="'. $row["value1"].'">
						&nbsp;-&nbsp;
						<input class="js_shop_search_params" type="text" class="to text_form"  name="p'.$row["id"].'_2" value="'.$row["value2"].'">
					</div>';*/
				break;

			case 'checkbox':
				echo '
				<input class="js_shop_search_params" type="checkbox" id="shop_search_p'.$row["id"].'" name="p'.$row["id"].'" value="1"'.($row["value"] ? " checked" : '').(!$row["available"] ? " disabled" : '').'>
				<label for="shop_search_p'.$row["id"].'" class="'.($row["value"]?"cb_checked":"").'">'.$row["name"].'</label>
				<br>';
				break;

			case 'select':
			case 'multiple':
			/* echo vd($_POST);
			echo vd($_POST["p".$row["id"]]); */
				echo '<div class="js_shop_search_param_div shop_search_chek shop_search_chek'.$row["id"].'">
				<div class="input-title">'.$row["name"].'</div>';


        echo '<div class="chek'.(!empty($_POST["p".$row["id"]]) ? ' is_active' : '').'">';
		echo '<div class="input-title">'.$row["name"].'</div>';



				foreach ($row["select_array"] as $key => $value)
				{
					echo '<div class="chek_lab '.(empty($row["select_array_available"][$key])?' chek_lab_disabled ':'').'">
          <input class="js_shop_search_params" type="checkbox" id="shop_search_p'.$row["id"].'_'.$key.'" name="p'.$row["id"].'[]" value="'.$key.'"'.(in_array($key, $row["value"]) ? " checked" : '').(empty($row["select_array_available"][$key])?' disabled ':'').'>
					<label id="shop_search_p'.$row["id"].'_'.$key.'" for="shop_search_p'.$row["id"].'_'.$key.'" class="'.(in_array($key, $row["value"])?"cb_checked":"").'">'.($row["id"]==33 ? '<span class="color"></span>': '').$value.'</label>
          </div>';
				}
         echo '</div>';
        echo '</div>';
		}
		echo '
		</div>';
	}
}

echo '<div style="" class="js_shop_search_param shop_search_param button clear_filter_button" onclick="clear_filter(this);">

	<div class="input-title">Сбросить фильтр<span class="symb">✖</span></div>

</div>


</div>';
echo '
	<div class="btn" style="display: none;">
	<input type="submit" class="button solid btn_filter" value="'.$this->diafan->_('Показать', false).'">
  </div>


	</form>';
	echo '</div>';
if (empty($result["update_form"]))
{
	echo '</div>';
	echo '	<div style="display: none;" id="shop_search_popup_div" class="js_shop_search_popup" style="
													margin-top: 10px;
													position: absolute;
													display: none;
													border: 1px solid;
													margin: 5px;
													padding: 5px;
													left: 290px;
													z-index: 9999;
													height: 17px;
													padding: 15px;
													background:white">
				'.$this->diafan->_('Выбрано').'
				<span class="filter_count_goods">
				</span>.
				<a class="js_shop_search_popup_button" href="#">'.$this->diafan->_('Показать').'</a>
			</div>';
}
