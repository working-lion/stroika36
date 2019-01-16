<?php
/**
 * Шаблон список спецификаций
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

if ( !defined('DIAFAN')) {
    $path = __FILE__;
    while ( !file_exists($path . '/includes/404.php')) {
        $parent = dirname($path);
        if ($parent == $path) {
            exit;
        }
        $path = $parent;
    }
    include $path . '/includes/404.php';
}

echo '<div class="specification_id">';

echo '<pre>';
    print_r($_SESSION);
echo '</pre>';

if ($this->diafan->_users->id) {

    if ($result["date"]) {
        echo '<div class="specification_date">' . $result["date"] . '</div>';
    }

    if ($result["text"]) {
        echo '<p><b>' . $this->diafan->_('Описание спецификации:') . '</b></p>';
        echo '<div class="specification_description">' . $result["text"] . '</div>';
    }

    echo $this->diafan->_tpl->get('table', 'specification', $result);

    echo '<div class="spec_btn_block">';

    if(!empty($result["rows"])) {
        echo '<a href="" id="push_to_catr_btn" class="btn">' . $this->diafan->_('Отправить в корзину') . '</a>';
    }
    echo '<a href="" id="remove_spec_btn" class="btn">' . $this->diafan->_('Удалить') . '</a>';
    echo '</div>';

    echo '<div class="spec_form_container">';
    echo '<form method="POST" action="" class="ajax" id="js_push_to_cart_form">
            <input type="hidden" name="module" value="specification">
            <input type="hidden" name="action" value="push" id="js_push_to_cart_form_action">
            <input type="hidden" name="site_id" value="56">
            <input type="hidden" name="specification_id" value="' . $result["id"] . '">
            <input type="hidden" name="tmpcode" value="c4bbac870026694953a91cbd99149a13">
        </form>';
    echo '<div class="errors error" style="display:none"></div>';
    echo '</div>';
}
else {
    echo '<p>' . $this->diafan->_('Сохранить в спецификацию') . '</p>';
}

if ($result["all_specifications"]) {
    echo '<div class="specification_all_link">' . '<a href="' . $result["all_specifications"] . '">Вернуться к списку спецификаций</a>' . '</div>';
}

echo '</div>';