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

if (empty($result["categories"])) {
    return false;
}

/*echo '<pre>';
print_r($result["categories"]);
echo '</pre>';*/

echo '<div class="shop_list_cat shop_list-flex shop_list-first-page">';
//начало большого цикла, вывод категорий и товаров в них
foreach ($result["categories"] as $cat_id => $cat) {



    echo '<a href="' . BASE_PATH_HREF . $cat["link_all"] . '" class="shop_cat_link">';

    //вывод изображений категории
    if ( !empty($cat["img"])) {

        foreach ($cat["img"] as $img) {
            echo '<div class="shop_cat_img" style="background-image: url(' . $img["src"] . ')"></div>';
        }
    }
    //вывод названия категории
    echo '<div class="shop_cat-name">' . $cat["name"] . ' (' . $cat["count"] . ')</div>';

    echo '</a>';

    //краткое описание категории
    /*if ( !empty($cat["anons"])) {
        echo '<div class="shop_cat_anons">' . $cat['anons'] . '</div>';
    }*/

    //подкатегории
    /*if ( !empty($cat["children"])) {

        echo '<div class="shop_list-flex shop_list-children">';
        foreach ($cat["children"] as $child) {

            echo '<a href="' . BASE_PATH_HREF . $child["link"] . '" class="shop_cat_link">';

            //изображения подкатегории
            if ( !empty($child["img"])) {
                foreach ($child["img"] as $img) {
                    echo '<div class="shop_cat_img" style="background-image: url(' . $img["src"] . ')"></div>';
                    break;
                }
            }

            //название и ссылка подкатегории
            echo '<div class="shop_cat-name">' . $child["name"] . ' (' . $child["count"] . ')</div>';

            //краткое описание подкатегории
            if ( !empty($child["anons"])) {
                echo '<div class="shop_cat_anons">' . $child['anons'] . '</div>';
            }

            //вывод списка товаров подкатегории
            if ( !empty($child["rows"])) {
                $res = $result;
                $res["rows"] = $child["rows"];
                echo '<div class="shop-pane">';
                echo $this->get('rows', 'shop', $res);

            }

            echo '</a>';
        }


    }*/

    /*
        //вывод товаров в категории
        if (!empty($cat["rows"]))
        {
            $res = $result;
            $res["rows"] = $cat["rows"];
                    echo '<div class="shop-pane">';
            echo $this->get('rows', 'shop', $res);
                    echo '</div>';
        } */

    /* 	//ссылка на все товары в категории
        if ($cat["link_all"])
        {
            echo '<div class="show_all"><a href="'.BASE_PATH_HREF.$cat["link_all"].'">'
            . $this->diafan->_('Посмотреть все товары в категории «%s»', true, $cat["name"])
            . ' ('.$cat["count"].')</a></div>';
        } */

}
echo '</div>';
//постраничная навигация
if ( !empty($result["paginator"])) {
    echo $result["paginator"];
}