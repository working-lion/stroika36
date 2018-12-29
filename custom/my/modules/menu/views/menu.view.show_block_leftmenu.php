<?php
/**
 * Шаблон меню template=leftmenu
 *
 * Шаблонный тег: вывод меню
 * Полный аналог функции show_block, но с другим оформлением. 
 * Нужен, если необходимо оформить другое меню на сайте
 * Вызывается с параметром template=leftmenu при вызове тега. 
 * <insert name="show_block" module="menu" id="1" template="leftmenu"> 
 * Параметр должен быть приклеен к имени функции в конце
 * 
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2017 OOO «Диафан» (http://www.diafan.ru/)
 */

if (! defined('DIAFAN'))
{
    $path = __FILE__; $i = 0;
	while(! file_exists($path.'/includes/404.php'))
	{
		if($i == 10) exit; $i++;
		$path = dirname($path);
	}
	include $path.'/includes/404.php';
}

if (empty($result["rows"]))
{
	return false;
}
if (!empty($result["name"]))
{
	echo '<div class="block_header">'.$result["name"].'</div>';
}

echo '<ul class="menu menu-lvl-1 is-active" data-menu-lvl="1">';
echo $this->get('show_level_leftmenu', 'menu', $result);
echo '</ul>';