<?php
/**
 * Шаблон информации о товарах в корзине
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

echo '<a href="'.$result["link"].'" class="cart_bl">
<img src="/custom/my/img/cart.svg" alt="Корзина">';

echo '<div class="cart__sum_bl">
<span class="cart__text">'
    //.$this->diafan->_('') .$result["count"].
    .$this->diafan->_cart->get_count().
'</span>
</div>
';
echo '</a>';
