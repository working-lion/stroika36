<?php
/**
 * Шаблон блока новостей
 *
 * Шаблонный тег <insert name="show_block" module="news" [count="количество"]
 * [cat_id="категория"] [site_id="страница_с_прикрепленным_модулем"]
 * [images="количество_изображений"] [images_variation="тег_размера_изображений"]
 * [only_module="only_on_module_page"] [template="шаблон"]>:
 * блок новостей
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
	return false;
}
$i=0;

//заголовок блока
if (! empty($result["attributes"]["head"]))
{
	echo '<div class="title_h2">'.$result["attributes"]["head"].'</div>';
}
elseif (! empty($result["name"]))
{
	echo '<div class="title_h2">'.$result["name"].'</div>';
}
echo '<div class="news_elem block'.(!empty($result["attributes"]["class"]) ? ' responsive' : '').'">';



/* //заголовок блока
if (! empty($result["name"]))
{
	echo '<div class="block_header">'.$result["name"].'</div>';
} */

//новости
foreach ($result["rows"] as $row)
{
if($i==0 && !empty($result["attributes"]["one"]))
{
	$i++;
	continue;

}
$i++;
	echo '<div class="news col_5 block-row '.(empty($row["img"]) ? 'block-no-img' : '').'">';
//echo '<a href="'.BASE_PATH.$row["link"].'" class="news_block__url">';

if (!empty($row["img"]))
{
echo '
<div class="news_block__img" style="background: url('.BASE_PATH_HREF.$row["img"][0]["src"].') 50% 50%; background-size: cover; background-repeat: no-repeat;"></div>';
}

//дата новости
if (! empty($row["date"]))
{
	echo '<div class="news_date news_elem__date">'.$row["date"].'</div>';
}
		//название и ссылка новости
		echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="news_block__url">';
		echo '<div class="news_elem__name">'.$row['name'].'</div>';
		echo '</a>'	;


/* 	//изображения новости
	if (! empty($row["img"]))
	{
		foreach ($row["img"] as $img)
		{
			switch($img["type"])
			{
				case 'animation':
					echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$row["id"].'news]" class="block-row-img">';
					break;
				case 'large_image':
					echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'" class="block-row-img">';
					break;
				default:
					echo '<a href="'.BASE_PATH_HREF.$img["link"].'" class="block-row-img">';
					break;
			}
			echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'" class="block-row-img">'
			.'</a> ';
		}
	}
 */
	echo '<div class="block-text">';


		//рейтинг новости
		if (! empty($row["rating"]))
		{
			echo '<div class="news_rating rate"> ' .$row["rating"] . '</div>';
		}

	    //анонс новости
		if(!empty($row['anons']))
		{
			echo '<div class="news_anons anons">'.$this->diafan->short_text($row['anons'], 110).'</div>';
		}
		echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="news_elem__more">Читать далее</a>';


	echo '</div>';

	echo '</div>';
}

echo '</div>';

//ссылка на все новости
if (! empty($result["link_all"]))
{
	echo '<div class="show_all center"><a class="btn" href="'.BASE_PATH_HREF.$result["link_all"].'">';
	if ($result["category"])
	{
		echo $this->diafan->_('Посмотреть все новости в категории «%s»', true, $result["name"]);
	}
	else
	{
		echo $this->diafan->_('Все новости');
	}
	echo '</a></div>';
}
