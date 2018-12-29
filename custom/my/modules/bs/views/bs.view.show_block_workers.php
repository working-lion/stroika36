<?php
/**
 * Шаблон блока баннеров
 *
 * Шаблонный тег <insert name="show_block" module="bs" [count="all|количество"]
 * [cat_id="категория"] [id="номер_баннера"] [template="шаблон"]>:
 * блок баннеров
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

if (empty($result)) {
    return false;
}

echo '<div class="workers-block workers-block__flex">';
foreach ($result as $row) {
    echo '<div class="workers-item workers-item__flex" id="workers-item-' . $row['id'] . '">';

    //вывод баннера в виде изображения
    if ( !empty($row['image'])) {

        echo '<div class="workers-item__img">';
        echo '<a href="' . BASE_PATH . USERFILES . '/bs/' . $row['image'] . '" rel="prettyPhoto[gallery_workers]">';
        echo '<img src="' . BASE_PATH . USERFILES . '/bs/' . $row['image'] . '" alt="' . (!empty($row['alt']) ?
        $row['alt']: '') . '" title="' . (!empty($row['title']) ? $row['title']: '') . '">';
        echo '</a>';
        echo '</div>';
    }

    if ( !empty($row['text'])) {
        echo '<div class="workers-item__text__flex"><div class="workers-item__text"><div class="workers-item__text-title">
            ' . $row['name'] . '</div>' . $row['text'] . '</div></div>';
    }

    echo '</div>';

}
echo '</div>';