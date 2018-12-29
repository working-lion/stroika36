<?php
/**
 * Шаблон формы стандартной капчи
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

$codeint = rand(1111, 9999);

echo '<div class="block captcha">';

echo '
<div class="infofield"><span class="input-title captcha-text">'.$this->diafan->_('Введите код с картинки').':</span></div>
<div class="captcha-data">
<img src="'.BASE_PATH.(IS_ADMIN ? ADMIN_FOLDER.'/' : '').'captcha/get/'.$result["modules"].$codeint.'" width="159" height="80" class="code_img captcha-image">
<input type="hidden" name="captchaint" value="'.$codeint.'">
<input type="hidden" name="captcha_update" value="">
<input type="text" name="captcha" value="" autocomplete="off">
<div class="js_captcha_update captcha_update"><a href="javascript:void(0)" class="button-refresh"><i class="fa fa-refresh" aria-hidden="true"></i></a></div>';
echo '</div></div>';

echo '<div class="errors error_captcha"'.($result["error"] ? '>'.$result["error"] : ' style="display:none">').'</div>';