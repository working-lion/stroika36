<?php
/**
 * Шаблон формы восстановления доступа
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

echo '
<form method="POST" action="" class="reminding_form ajax">
<input type="hidden" name="action" value="mail">
<input type="hidden" name="module" value="reminding">
'.$result["action"].'

<div class="infofield">'.$this->diafan->_('Введите ваш e-mail').'<span style="color:red;">*</span>:</div>
<input type="email" name="mail" value=""><br>
<div class="errors error_mail"'.($result["error_mail"] ? '>'.$result["error_mail"] : ' style="display:none">').'</div>

'.$result["captcha"].'

<input type="submit" value="'.$this->diafan->_('Отправить', false).'">
<div class="privacy_field">'.$this->diafan->_('Отправляя форму, я даю согласие на <a href="%s">обработку персональных данных</a>.', true, BASE_PATH_HREF.'privacy'.ROUTE_END).'</div>

<div class="required_field"><span style="color:red;">*</span> — '.$this->diafan->_('Поля, обязательные для заполнения').'</div>

</form>

<div class="errors error reminding_result"'.($result["error"] ? '>'.$result["error"] : ' style="display:none">').'</div>';