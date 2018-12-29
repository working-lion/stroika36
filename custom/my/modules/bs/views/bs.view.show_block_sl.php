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
 * @version    5.4
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2014 OOO «Диафан» (http://diafan.ru)
 */

if ( !defined('DIAFAN')) {
    include(dirname(dirname(dirname(__FILE__))) . '/includes/404.php');
}

if (empty($result)) {
    return false;
}

echo '<div class="slider">';

foreach ($result as $row) {

    echo '<div class="slide">';

    /*if ( !empty($row['link'])) {
        echo '<a href="' . $row['link'] . '" class="js_bs_counter bs_counter" rel="' . $row['id'] . '" ' . (!empty($row['target_blank']) ? 'target="_blank"': '') . '>';
    }*/
    //вывод баннера в виде изображения
    /* 	if (! empty($row['image']))
        {
        echo '<div class="back_img" style="height:473px; background: url('.BASE_PATH.USERFILES.'/bs/'.$row['image'].') 50% 50%; background-size: cover;">';
        }  */

    //вывод баннера в виде изображения
    if ( !empty($row['image'])) {
        echo '<img src="' . BASE_PATH . USERFILES . '/bs/' . $row['image'] . '" alt="' . (!empty($row['alt']) ? $row['alt']: '') . '" title="' . (!empty($row['title']) ? $row['title']: '') . '">';
    }

    if ( !empty($row['text'])) {
        $bsLink = '';
        if($row['link']) $bsLink = '<p><a href="' . $row['link'] . '">Подробнее</a></p>';
        echo '<div class="abs_bs">
				<div class="container">
					<div class="text_bs">'
                        . '<div class="h2">' . $row['name'] . '</div>'
                        . $bsLink
                        . '</div>
				</div>
			 </div>';
    }

    /*if ( !empty($row['image'])) {
        echo '</div>';
    }*/

    if ( !empty($row['link'])) {
        echo '</a>';
    }

    echo '</div>';

}
echo '</div>';
