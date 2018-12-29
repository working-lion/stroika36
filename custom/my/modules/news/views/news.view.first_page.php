<?php
/**
 * Шаблон первой страницы модуля, если в настройках модуля подключен параметр «Использовать категории»
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

if (!empty($result["categories"]))
{
	//вывод категории
    echo '<div class="project-list-slider">';
	foreach ($result["categories"] as $cat_id => $cat)
	{
		echo '<div class="project-list">';

        //вывод названия категории новостей
        echo '<div class="project-list__title"><a href="'.BASE_PATH_HREF.$cat["link_all"].'">'.$cat["name"].'</a></div>';

		//вывод изображений категории
		if (! empty($cat["img"]))
		{
			echo '<div class="news_cat_img">';
			foreach ($cat["img"] as $img)
			{
				switch($img["type"])
				{
					case 'animation':
						echo '<a href="'.BASE_PATH.$img["link"].'" rel="prettyPhoto[gallery'.$cat_id.'news]">';
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

		//вывод краткого описания категории
		if (! empty($cat["anons"]))
		{
			echo '<div class="news_cat_anons">'.$cat['anons'].'</div>';
		}

		//вывод нескольких новостей из категории
        //НОВОСТИ ВЫВОДЯТСЯ КОДОМ НИЖЕ
		if ($cat["rows"])
		{
			foreach ($cat["rows"] as $row)
			{
				echo '<div class="project-item">';

				//вывод изображений новости
				if (! empty($row["img"]))
				{
					foreach ($row["img"] as $img)
					{
                        /*switch($img["type"])
                      {
                            /*case 'animation':
                                echo '<a href="'.BASE_PATH.$img["vs"]["large"].'" rel="prettyPhoto[gallery'
                                    .$row["id"].'news]" class="block-row-img">';
                                break;
                            case 'large_image':
                                echo '<a href="'.BASE_PATH.$img["vs"]["large"].'" rel="large_image" class="block-row-img">';
                                break;
                            default:
                                echo '<a href="'.BASE_PATH_HREF.$img["vs"]["large"].'">';
                                break;
                        }*/

                        echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="project-item-img">';
						echo '<img src="'.$img["vs"]["medium_projects"].'" alt="'.$img["alt"].'" title="'.$img["title"].'" class="block-row-img">'
						.'</a> ';

						break; //выводим только одно изображение
					}
				}

                //вывод названия и ссылки на новость
                echo '<div class="project-item-title">';
                echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="black">'.$row["name"].'</a>';
                echo '</div>';

				echo '<div class="project-item-text">';

					//вывод рейтинга новости за названием, если рейтинг подключен
					if (! empty($row["rating"]))
					{
						echo '<div class="news_rating rate"> ' .$row["rating"] . '</div>';
					}

					//вывод анонса новостей
					if (! empty($row["anons"]))
					{
						echo '<div class="project-item-anons">'.$row['anons'].'</div>';
					}

					//вывод даты новости
					/*if (! empty($row['date']))
					{
						echo '<div class="news_date date">'.$row["date"]."</div>";
					}*/

					//вывод прикрепленных тегов к новости
					/*if (! empty($row["tags"]))
					{
						echo $row["tags"];
					}*/

				echo '</div>';

				echo '</div>';
			}
		}


			//ссылка на все новости в категории
			/*if ($cat["link_all"])
			{
				echo '<div class="show_all"><a href="'.BASE_PATH_HREF.$cat["link_all"].'">'
				.$this->diafan->_('Посмотреть все новости в категории «%s»', true, $cat["name"])
				.'</a></div>';
			}*/

		echo '</div>';
	}
	echo '</div>';
}

//постраничная навигация
/*if (! empty($result["paginator"]))
{
	echo $result["paginator"];
}*/