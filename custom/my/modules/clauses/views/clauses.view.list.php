<?php
/**
 * Шаблон списка статей
 *
 * Шаблон вывода списка статей в том случае, если в настройках модуля отключен параметр «Использовать категории»
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

echo '<div class="clauses_list">';

//описание текущей категории
if (! empty($result["text"]))
{
	echo '<div class="clauses_cat_text">'.$result['text'].'</div>';
}

//рейтинг категории
if (! empty($result["rating"]))
{
	echo $result["rating"];
}

//изображения текущей категории
if (! empty($result["img"]))
{
	echo '<div class="clauses_cat_all_img">';
	foreach ($result["img"] as $img)
	{
		switch($img["type"])
		{
			case 'animation':
				echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$result["id"].'clauses]">';
				break;
			case 'large_image':
				echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'">';
				break;
			default:
				echo '<a href="'.BASE_PATH_HREF.$img["link"].'">';
				break;
		}
		echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">'
		.'</a> ';
	}
	echo '</div>';
}

//подкатегории
if (! empty($result["children"]))
{
	foreach ($result["children"] as $child)
	{
		echo '<div class="clauses_cat_link">';

		//изображение подкатегории
		if (! empty($child["img"]))
		{
			echo '<div class="clauses_cat_img">';
			foreach ($child["img"] as $img)
			{
				switch($img["type"])
				{
					case 'animation':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$child["id"].'clauses]">';
						break;
					case 'large_image':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'">';
						break;
					default:
						echo '<a href="'.BASE_PATH_HREF.$img["link"].'">';
						break;
				}
				echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">'
				.'</a> ';
			}
			echo '</div>';
		}

		//название и ссылка подкатегории
		echo '<a href="'.BASE_PATH_HREF.$child["link"].'">'.$child["name"].'</a>';

		//рейтинг подкатегории
		if (! empty($child["rating"]))
		{
			echo $child["rating"];
		}

		//краткое описание подкатегории
		if ($child["anons"])
		{
			echo '<div class="clauses_cat_anons">'.$child['anons'].'</div>';
		}
		//статьи подкатегории
		if (! empty($child["rows"]))
		{
			foreach ($child["rows"] as $row)
			{
				echo '<div class="block">';

				//изображения статьи
				if (! empty($row["img"]))
				{
					foreach ($row["img"] as $img)
					{
						switch($img["type"])
						{
							case 'animation':
								echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$row["id"].'clauses]" class="block-row-img">';
								break;
							case 'large_image':
								echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'" class="block-row-img">';
								break;
							default:
								echo '<a href="'.BASE_PATH_HREF.$img["link"].'" class="block-row-img">';
								break;
						}
						echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">'
						.'</a> ';
					}
				}

				echo '<div class="block-text">';

					//название и ссылка статьи
					echo '<h4><a href="'.BASE_PATH_HREF.$row["link"].'" class="black">'.$row["name"].'</a></h4>';
					//рейтинг статьи
					if (! empty($row["rating"]))
					{
						echo $row["rating"];
					}

					//анонс статьи
					if (! empty($row["anons"]))
					{
						echo '<div class="anons">'.$row['anons'].'</div>';
					}

					//дата статьи
					if (! empty($row['date']))
					{
						echo '<div class="date">'.$row["date"]."</div>";
					}

					//теги статьи
					if (! empty($row["tags"]))
					{
						echo $row["tags"];
					}

				echo '</div>';

				echo '</div>';
			}
		}
		echo '</div>';
	}
}

//статьи
if (! empty($result["rows"]))
{
	foreach ($result["rows"] as $row)
	{
		echo '<div class="block">';

/* 		//изображения статьи
		if (! empty($row["img"]))
		{
			foreach ($row["img"] as $img)
			{
				switch($img["type"])
				{
					case 'animation':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$row["id"].'clauses]" class="block-row-img">';
						break;
					case 'large_image':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="large_image" width="'.$img["link_width"].'" height="'.$img["link_height"].'" class="block-row-img">';
						break;
					default:
						echo '<a href="'.BASE_PATH_HREF.$img["link"].'" class="block-row-img">';
						break;
				}
				echo '<img src="'.$img["src"].'" width="'.$img["width"].'" height="'.$img["height"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">'
				.'</a> ';
			}
		} */


if (!empty($row["img"]))
{
echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="claus_list_block__url">';
echo '
<div class="claus_list_block__img" style="background: url('.BASE_PATH_HREF.$row["img"][0]["vs"]['large'].') 50% 50%; height: 100%; width: 100%; background-size: contain; background-repeat: no-repeat;"></div>';

echo '</a>'	;
}



		echo '<div class="block-text'.(empty($row["img"]) ? ' no_img' : '').'">';
			echo '<h4>';
				echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="claus_list_block__name">'.$row["name"].'</a>';
			echo '</h4>';
			//вывод даты новости
			if (! empty($row['date']))
			{
				echo '<div class="claus_date date">'.$row["date"]."</div>";
			}
			//вывод рейтинга новости за названием, если рейтинг подключен
			if (! empty($row["rating"]))
			{
				echo '<div class="news_rating rate"> ' .$row["rating"] . '</div>';
			}



	    //анонс новости
		if(!empty($row['anons']))
		{
			echo '<div class="claus_anons anons">'.$this->diafan->short_text($row['anons'], 350).'</div>';
		}
		echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="claus_elem__more">Подробнее</a>';

			//теги статьи
			if (! empty($row["tags"]))
			{
				echo $row["tags"];
			}

		echo '</div>';

		echo '</div>';
	}
}

//постраничная навигация
if (! empty($result["paginator"]))
{
	echo $result["paginator"];
}

//ссылки на предыдущую и последующую категории
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

//комментарии к категории
if (! empty($result["comments"]))
{
	echo $result["comments"];
}
echo '</div>';
