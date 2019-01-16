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

echo '<div class="specifications_list">';

if(! $this->diafan->_users->id) {
    echo '<p>Для просмотра спецификаци необходимо авторизоваться.</p>';
}
else{
    
    if(! empty($result["rows"]))
    {
        echo $this->get($result["view_rows"], 'specification', $result);
    }
    else{
        echo '<p>Вы не сохранили ни одной спецификации</p>';
    }
}

echo '</div>';