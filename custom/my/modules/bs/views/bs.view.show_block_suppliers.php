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

if ( !isset($GLOBALS['include_bs_js'])) {
    $GLOBALS['include_bs_js'] = true;
    //скрытая форма для отправки статистики по кликам
    echo '<form method="POST" enctype="multipart/form-data" action="" class="ajax js_bs_form bs_form">
	<input type="hidden" name="module" value="bs">
	<input type="hidden" name="action" value="click">
	<input type="hidden" name="banner_id" value="0">
	</form>';
}
echo '<div class="table-container wrap-finger">';
echo '<table class="suppliers ">';
echo '<span class="finger"></span>';
foreach ($result as $row) {
    echo '<tr>';

    echo '<td class="suppliers_img">';
    if ( !empty($row['link'])) {
        echo '<a href="' . $row['link'] . '" class="js_bs_counter bs_counter" rel="' . $row['id'] . '" ' .
            (!empty
            ($row['target_blank']) ? 'target="_blank"': '') . '>';
    }

    //вывод баннера в виде html разметки
    if ( !empty($row['html'])) {
        echo $row['html'];
    }

    //вывод баннера в виде изображения
    if ( !empty($row['image'])) {
        echo '<div class="img_cont"><img src="' . BASE_PATH . USERFILES . '/bs/' . $row['image'] . '" alt="' . (!empty($row['alt']) ? $row['alt']: '') . '" title="' . (!empty($row['title']) ? $row['title']: '') . '"></div>';
    }

    if ( !empty($row['link'])) {
        echo '</a>';
    }
    echo '</td>';

    echo '<td class="suppliers_link">';
    if ( !empty($row['link'])) {
        echo '<a href="' . $row['link'] . '">' . $row['link'] . '</a>';
    }
    echo '</td>';

    if ( !empty($row['text'])) {
        echo '<td class="suppliers__item-text">' . $row['text'] . '</td>';
    }

    echo '</tr>';//закрываем слайд

}
echo '</table>';
echo '</div>';