<?php
/**
 * Шаблон элементов в списке спецификаций
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN'))
{
    $path = __FILE__;
    while(! file_exists($path.'/includes/404.php'))
    {
        $parent = dirname($path);
        if($parent == $path) exit;
        $path = $parent;
    }
    include $path.'/includes/404.php';
}

if(empty($result['rows'])) return false;

//вывод списка новостей
foreach ($result["rows"] as $row)
{
    echo '<div class="specifications block">';

    echo '<div class="block-text">';

    //вывод названия и ссылки на новость
    echo '<h4>';
    echo '<a href="'.BASE_PATH_HREF.$row["link"].'" class="specification_list_block__name">'.$row["name"].'</a>';
    echo '</h4>';

    //вывод даты создания спецификации
    if (! empty($row['date']))
    {
        echo '<div class="specification_date date">'.$row["date"]."</div>";
    }

    //вывод описания спецификации
    if(! empty($row["description"]))
    {
        echo '<div class="specification_description">'.$row['description'].'</div>';
    }

    echo '</div>';

    echo '</div>';
}

//Кнопка "Показать ещё"
if(! empty($result["show_more"]))
{
    echo $result["show_more"];
}