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

echo '<div class="specification_id">';

if($result["date"]){
    echo '<div class="specification_date">' . $result["date"] . '</div>';
}

if($result["text"]){
    echo '<p><b>' . $this->diafan->_('Описание спецификации:') . '</b></p>';
    echo '<div class="specification_description">' . $result["text"] . '</div>';
}

echo $this->diafan->_tpl->get('table', 'specification', $result);

if($result["all_specifications"]){
    echo '<div class="specification_all_link">'
        . '<a href="' . $result["all_specifications"] . '">Вернуться к списку спецификаций</a>'
        . '</div>';
}

echo '</div>';