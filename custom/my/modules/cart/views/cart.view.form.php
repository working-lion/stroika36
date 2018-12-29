<?php
/**
 * Шаблон формы редактирования корзины товаров, оформления заказа
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
if (empty($result["rows"]))
{
	echo '<p>'.$this->diafan->_('Корзина пуста.').' <a href="'.BASE_PATH_HREF.$result["shop_link"].'">'.$this->diafan->_('Перейти к покупкам.').'</a></p>';
	return;
}

echo '<a name="top"></a>

<div class="cart_order">';

echo '<form action="" method="POST" class="js_cart_table_form cart_table_form ajax">
<input type="hidden" name="module" value="cart">
<input type="hidden" name="action" value="recalc">
<input type="hidden" name="form_tag" value="'.$result["form_tag"].'">
<div class="errors error"'.($result["error"] ? '>'.$result["error"] : ' style="display:none">').'</div>
<div class="cart_table">';
echo $this->get('table', 'cart', $result); //вывод таблицы с товарами
echo '</div>';
echo '<div class="cart_recalc">';
// кнопка пересчитать
echo '<input type="submit" value="'.$this->diafan->_('Пересчитать', false).'">';
echo '</div>';
echo '</form>';

echo '
<form method="POST" action="" class="cart_form ajax" enctype="multipart/form-data">
<input type="hidden" name="module" value="cart">
<input type="hidden" name="action" value="order">
<input type="hidden" name="tmpcode" value="'.md5(mt_rand(0, 9999)).'">
';

if(! empty($result["yandex_fast_order"]))
{
	echo '<p><a href="'.$result["yandex_fast_order_link"].'"><img src="http://cards2.yandex.net/hlp-get/5814/png/3.png" border="0" /></a></p>';
}

$required = false;
if (! empty($result["rows_param"]))
{
	echo '<h2>Данные для доставки</h2>';
	echo '<div class="spis_pol clearfix">';
echo '<div class="left_block">';
	foreach ($result["rows_param"] as $row)
	{
		if($row["required"])
		{
			$required = true;
		}

		$value = ! empty($result["user"]['p'.$row["id"]]) ? $result["user"]['p'.$row["id"]] : '';
if($row["type"]=='textarea')
	echo '
</div>
<div class="righ_block">';
		echo '<div class="pole order_form_param'.$row["id"].' '.($row["id"]==10 ? 'textar' : 'non').'">';


		switch ($row["type"])
		{
			case 'title':
				echo '<div class="infoform">'.$row["name"].':</div>';
				break;

			case 'text':
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input type="text" name="p'.$row["id"].'" value="'.str_replace('"', '&quot;', $value).'">';
				break;

			case "email":
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input type="email" name="p'.$row["id"].'" value="'.str_replace('"', '&quot;', $value).'">';
				break;

			case "phone":
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input type="tel" name="p'.$row["id"].'" value="'.$value.'">';
				break;

			case 'textarea':
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<textarea name="p'.$row["id"].'">'.str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $value).'</textarea>';
				break;

			case 'date':
			case 'datetime':
				$timecalendar  = true;
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
					<input type="text" name="p'.$row["id"].'" value="'.$value.'" class="timecalendar" showTime="'
					.($row["type"] == 'datetime'? 'true' : 'false').'">';
				break;

			case 'numtext':
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<input type="number" name="p'.$row["id"].'" size="5" value="'.$value.'">';
				break;

			case 'checkbox':
				echo '<input name="p'.$row["id"].'" id="cart_p'.$row["id"].'" value="1" type="checkbox" '.($value ? ' checked' : '').'><label for="cart_p'.$row["id"].'">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').'</label>';
				break;

			case 'select':
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>
				<select name="p'.$row["id"].'" class="inpselect">
					<option value="">-</option>';
				foreach ($row["select_array"] as $select)
				{
					echo '<option value="'.$select["id"].'"'.($value == $select["id"] ? ' selected' : '').'>'.$select["name"].'</option>';
				}
				echo '</select>';
				break;

			case 'multiple':
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>';
				foreach ($row["select_array"] as $select)
				{
					echo '<input name="p'.$row["id"].'[]" id="cart_p'.$select["id"].'[]" value="'.$select["id"].'" type="checkbox" '.(is_array($value) && in_array($select["id"], $value) ? ' checked' : '').'><label for="cart_p'.$select["id"].'[]">'.$select["name"].'</label><br>';
				}
				break;

			case "attachments":
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>';
				echo '<div class="inpattachment"><input type="file" name="attachments'.$row["id"].'[]" class="inpfiles" max="'.$row["max_count_attachments"].'"></div>';
				echo '<div class="inpattachment" style="display:none"><input type="file" name="hide_attachments'.$row["id"].'[]" class="inpfiles" max="'.$row["max_count_attachments"].'"></div>';
				if ($row["attachment_extensions"])
				{
					echo '<div class="attachment_extensions">('.$this->diafan->_('Доступные типы файлов').': '.$row["attachment_extensions"].')</div>';
				}
				break;

			case "images":
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div><div class="images"></div>';
				echo '<input type="file" name="images'.$row["id"].'" param_id="'.$row["id"].'" class="inpimages">';
				break;
		}

		echo '<div class="order_form_param_text">'.$row["text"].'</div>

		<div class="errors error_p'.$row["id"].'"'.($result["error_p".$row["id"]] ? '>'.$result["error_p".$row["id"]] : ' style="display:none">').'</div>';
		echo '</div>';
	}
	if(! empty($result["subscribe_in_order"]))
	{
		echo '<input type="checkbox" checked name="subscribe_in_order" id="subscribe_in_order"><label for="subscribe_in_order">'.$this->diafan->_('Подписаться на новости').'</label>';
	}echo '</div>';
echo '</div>';
}
echo '<div class="clear"></div>';
if(! empty($result["payments"]))
{
	echo '<h2>'.$this->diafan->_('Оплата').'</h2>';
	echo '<div class="opl">'.$this->get('list', 'payment', $result["payments"]).'</div>';
}

echo '<input type="submit" class="btn_resul btn" value="'.$this->diafan->_('Оформить заказ', false).'">';

echo '<div class="errors error"'.($result["error"] ? '>'.$result["error"] : ' style="display:none">').'</div>';

if($required)
{
	echo '<div class="required_field"><span style="color:red;">*</span> — '.$this->diafan->_('Поля, обязательные для заполнения').'</div>';
}

echo '</form>';

if($result["show_auth"])
{
	echo '<div class="cart_autorization">';
	echo $this->diafan->_('Если Вы оформляли заказ на сайте ранее, просто введите логин и пароль:');
	echo '<br>';
	echo $this->get('show_login', 'registration', $result["show_login"]);
	echo '</div>';
}
echo '</div>';
