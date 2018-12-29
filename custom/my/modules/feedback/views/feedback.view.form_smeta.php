<?php
/**
 * Шаблон формы добавления сообщения в обратной связи
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

if (! empty($result["text"]))
{
	echo $result["text"];
	return;
}

echo '
<div class="feedback_form">
<form method="POST" enctype="multipart/form-data" action="" class="ajax">
<input type="hidden" name="module" value="feedback">
<input type="hidden" name="action" value="add">
<input type="hidden" name="form_tag" value="'.$result["form_tag"].'">
<input type="hidden" name="site_id" value="'.$result["site_id"].'">
<input type="hidden" name="tmpcode" value="'.md5(mt_rand(0, 9999)).'">';

//заголовок блока
if (! empty($result["attributes"]["head"]))
{
	echo '<h3>'.$result["attributes"]["head"].'</h3>';
}
//заголовок блока
elseif (! empty($result["name"]))
{
	echo '<h3>'.$result["name"].'</h3>';
}

echo '<p class="subtitle">Оставьте заявку на расчёт стоимости, и мы перезвоним Вам</p>';

//заголовок блока
if (! empty($result["attributes"]["texta"]))
{
	echo '<div class="texta">'.$result["attributes"]["texta"].'</div>';
}

$required = false;
if (! empty($result["rows"]))
{
	//echo '<div class="feed_params">';
	foreach ($result["rows"] as $row) //вывод полей из конструктора форм
	{
		if($row["required"])
		{
			$required = true;
		}
		if($row["type"]=="checkbox") echo '<br />';
		echo '<div class="feed_params__pole'. ($row["type"]=="checkbox" ? ' feed_params__pole--checkbox' : '') .'">';
		echo '<div class="pole feedback_form_param'.$row["id"].'">';

		switch ($row["type"])
		{
			case 'title':
				echo '<div class="infoform">'.$row["name"].':</div>';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case 'text':
				echo '
				<input placeholder="'.$row["name"].'" type="text" name="p'.$row["id"].'" value="">';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case "email":
				echo '
				<input placeholder="'.$row["name"].'" type="email" name="p'.$row["id"].'" value="">';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case "phone":
				echo '
				<input placeholder="'.$row["name"].'" type="tel" name="p'.$row["id"].'" value="">';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case 'textarea':
				echo '
				<textarea placeholder="'.$row["name"].'" name="p'.$row["id"].'" cols="66" rows="10"></textarea>';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case 'date':
			case 'datetime':
				$timecalendar  = true;
				echo '
					<input placeholder="'.$row["name"].'" type="text" name="p'.$row["id"].'" value="" class="timecalendar" showTime="'
					.($row["type"] == 'datetime'? 'true' : 'false').'">';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case 'numtext':
				echo '
				<input placeholder="'.$row["name"].'" type="number" name="p'.$row["id"].'" value="">';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case 'checkbox':
				echo '
				<input name="p'.$row["id"].'" id="feedback_p'.$row["id"].'" value="1" type="checkbox" checked>
				<label for="feedback_p'.$row["id"].'"><span class="text_label">'.$this->htmleditor($row["name"]);
				if($row["text"])
				{
					echo $this->htmleditor($row["text"]);
				}
				break;

			case 'radio':
				echo '';
				foreach ($row["select_array"] as $select)
				{
					echo '<input name="p'.$row["id"].'" type="radio" value="'.$select["id"].'" id="feedback_form_p'.$row["id"].'_'.$select["id"].'"> <label for="feedback_form_p'.$row["id"].'_'.$select["id"].'">'.$select["name"].'</label>';
				}
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case 'select':
				echo '
				<select name="p'.$row["id"].'" class="inpselect">
					<option value="">-</option>';
				foreach ($row["select_array"] as $select)
				{
					echo '<option value="'.$select["id"].'">'.$select["name"].'</option>';
				}
				echo '</select>';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case 'multiple':
				echo '';
				foreach ($row["select_array"] as $select)
				{
					echo '<input name="p'.$row["id"].'[]" id="feedback_p'.$select["id"].'[]" value="'.$select["id"].'" type="checkbox"><label for="feedback_p'.$select["id"].'[]">'.$select["name"].'</label><br>';
				}
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case "attachments":
				echo '';
				echo '<div class="inpattachment"><input type="file" name="attachments'.$row["id"].'[]" class="inpfiles" max="'.$row["max_count_attachments"].'"></div>';
				echo '<div class="inpattachment" style="display:none"><input type="file" name="hide_attachments'.$row["id"].'[]" class="inpfiles" max="'.$row["max_count_attachments"].'"></div>';
				if ($row["attachment_extensions"])
				{
					echo '<div class="attachment_extensions">('.$this->diafan->_('Доступные типы файлов').': '.$row["attachment_extensions"].')</div>';
				}
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;

			case "images":
				echo '<div class="images"></div>';
				echo '<input type="file" name="images'.$row["id"].'" param_id="'.$row["id"].'" class="inpimages">';
				echo '<div class="infofield">'.$row["name"].($row["required"] ? '<span style="color:red;">*</span>' : '').':</div>'; break;
		}

		if($row["text"])
		{
		//	echo '<div class="feedback_form_param_text">'.$row["text"].'</div>';
		}

		echo '</div>';

		if($row["type"] != 'title')
		{
			echo '<div class="errors error_p'.$row["id"].'"'.($result["error_p".$row["id"]] ? '>'.$result["error_p".$row["id"]] : ' style="display:none">').'</div>';
		}
		echo '</div>';
	}
//echo '</div>';
}

//Защитный код
echo $result["captcha"];

//Кнопка Отправить
echo '<input type="submit" value="'.(!empty($result["attributes"]["btn"]) ? $result["attributes"]["btn"]: $this->diafan->_('Отправить', false)).'" class="button btn solid">';

if($required)
{
	echo '<div class="required_field"><span style="color:red;">*</span> — '.$this->diafan->_('Поля, обязательные для заполнения').'</div>';
}

echo '</form>';
echo '<div class="errors error"'.($result["error"] ? '>'.$result["error"] : ' style="display:none">').'</div>
</div>';
