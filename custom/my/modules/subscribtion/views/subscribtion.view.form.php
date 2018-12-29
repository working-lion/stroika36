<?php
/**
 * Шаблон формы подписки на рассылки
 *
 * Шаблонный тег <insert name="show_form" module="subscribtion" [template="шаблон"]>:
 * блок вывода формы подписки на рассылки
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
echo '<p>check</p>';
echo '
<form method="POST" enctype="multipart/form-data" action="" class="subscription ajax">
    <input type="hidden" name="module" value="subscribtion">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="form_tag" value="' . $result["form_tag"] . '">
    <input type="email" placeholder="' . $this->diafan->_('Ваш e-mail', false) . '" name="mail">
    <input type="submit" class="button btn white" value="' . $this->diafan->_('Подписаться', false) . '">
    <div class="errors error_mail"' . ($result["error_mail"] ? '>' . $result["error_mail"]: ' style="display:none">') . '</div>
    <div class="errors error"' . ($result["error"] ? '>' . $result["error"]: ' style="display:none">') . '</div>
</form>';