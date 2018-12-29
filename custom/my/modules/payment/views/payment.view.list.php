<?php
/**
 * Шаблон списка платежных система при оплате
 * 
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2016 OOO «Диафан» (http://www.diafan.ru/)
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

if(empty($result))
{
	return;
}

echo '<div class="pay_may"><select name="payment_id" >'; 
foreach ($result as $i => $row) 
{  
	/* echo '<div class="paymenty">
	<input name="payment_id" id="payment'.$row['id'].'" value="'.$row['id'].'" type="radio" '.(! $i ? 'checked' : '').'> */
	echo '<option value="'.$row['id'].'" '.(! $i ? 'selected' : '').'>'.$row['name'].'</option>';
/* 	if(! empty($row['text']))
	{
		echo '<div class="payment_text">'.$row['text'].'</div>';
	}
	echo '</div>';  */     
}
echo '</select></div>';
