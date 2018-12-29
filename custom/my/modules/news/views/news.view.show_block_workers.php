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

if ( !defined('DIAFAN')) {
    $path = __FILE__;
    $i = 0;
    while ( !file_exists($path . '/includes/404.php')) {
        if ($i == 10) {
            exit;
        }
        $i++;
        $path = dirname($path);
    }
    include $path . '/includes/404.php';
}

if (empty($result["rows"])) {
    return false;
}

$i = 0;
echo '<div class="workers-block">';
//заголовок блока
if ( !empty($result["attributes"]["head"])) {
    echo '<div class="title_h2">' . $result["attributes"]["head"] . '</div>';
} elseif ( !empty($result["name"])) {
    echo '<div class="title_h2">' . $result["name"] . '</div>';
}

echo '<div class="news_elem block' . (!empty($result["attributes"]["class"]) ? ' responsive': '') . '">';


/* //заголовок блока
if (! empty($result["name"]))
{
	echo '<div class="block_header">'.$result["name"].'</div>';
} */

//новости
foreach ($result["rows"] as $row) {
    if ($i == 0 && !empty($result["attributes"]["one"])) {
        $i++;
        continue;

    }
    $i++;
    echo '<div class="col_4 block-row worker-block-item' . (empty($row["img"]) ? 'block-no-img': '') . '">';
//echo '<a href="'.BASE_PATH.$row["link"].'" class="news_block__url">';

    /*if ( !empty($row["img"])) {
        echo '
<div class="worker__img" style="background: url(' . BASE_PATH_HREF . $row["img"][0]["src"] . ') 50% 0%; background-size: cover; background-repeat: no-repeat;"></div>';
    }*/

//дата новости
    /*if ( !empty($row["date"])) {
        echo '<div class="news_date news_elem__date">' . $row["date"] . '</div>';
    }*/

    //изображения новости
    if ( !empty($row["img"])) {
        foreach ($row["img"] as $img) {
            echo '<div class="worker__img">
                    <a href="'.$img["vs"]["large"].'" rel="prettyPhoto[gallery_workers]">';
            echo '<img src="'.$img["vs"]["large"].'" alt="'.$img["alt"].'" title="'.$img["title"].'">'
                .'</a></div>';
        }
    }

    echo '<div class="worker-info">';
    //название и ссылка новости
    echo '<div class="worker__name">' . $row['name'] . '</div>';
    //анонс новости
    if ( !empty($row['anons'])) {
        echo '<div class="worker__contacts">' . $row['anons'] . '</div>';
    }
    //echo '<a href="' . BASE_PATH_HREF . $row["link"] . '" class="news_elem__more">Читать далее</a>';


    echo '</div>';

    echo '</div>';
}

echo '</div>';


//ссылка на все новости
/*if ( !empty($result["link_all"])) {
    echo '<div class="show_all center"><a class="btn" href="' . BASE_PATH_HREF . $result["link_all"] . '">';
    if ($result["category"]) {
        echo $this->diafan->_('Посмотреть все новости в категории «%s»', true, $result["name"]);
    } else {
        echo $this->diafan->_('Все новости');
    }
    echo '</a></div>';
}*/

echo '</div>';
