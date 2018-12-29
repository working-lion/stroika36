<?php
/**
 * Шаблон блока авторизации
 *
 * Шаблонный тег <insert name="show_login" module="registration" [template="шаблон"]>:
 * блок авторизации
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

if (! $result["user"])
{
	echo '<div class="block">';
	echo '<h3>'.$this->diafan->_('Вход на сайт').'</h3>';
	echo '<form method="post" action="'.$result["action"].'" class="login ajax">
	<input type="hidden" name="action" value="auth">
	<input type="hidden" name="module" value="registration">
	<input type="hidden" name="form_tag" value="registration_auth">
	<div class="form-field">
	    <input type="text" name="name" placeholder="'.$this->diafan->_($this->diafan->configmodules("mail_as_login", "users") ? 'E-mail' : 'Имя пользователя').'" autocomplete="off">
	</div>
	<div class="form-field">
	    <input type="password" name="pass" placeholder="'.$this->diafan->_('Пароль').'" autocomplete="off">
	</div>
	<div class="form-field form-field-checkbox">
	    <input type="checkbox" id="not_my_computer" name="not_my_computer" value="1">
	    <label for="not_my_computer">'.$this->diafan->_('Чужой компьютер').'</label>	
    </div>
    <div class="form-field form-field-btn">
	    <input type="submit" value="Войти" class="btn">
    </div>';
	if (! empty($result["reminding"]))
	{
		echo '<p><a href="'.$result["reminding"].'" class="arrow-link black">'.$this->diafan->_('Забыли пароль?').'</a></p>';
	}
	if(! empty($result["registration"]))
	{		
		echo '<p><a href="'.$result["registration"].'" class="arrow-link black">'.$this->diafan->_('Регистрация').'</a></p>';
	}
	echo '<div class="errors error"'.($result["error"] ? '>'.$result["error"] : ' style="display:none">').'</div>';
	echo '</form>';
	
	if(! empty($result["use_loginza"]))
	{
		$this->diafan->_site->js_view[] = 'http://loginza.ru/js/widget.js';
	    echo '<br><a href="https://loginza.ru/api/widget?token_url='.urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'" class="loginza">
		<img src="http://loginza.ru/img/providers/yandex.png" alt="Yandex" title="Yandex">
		<img src="http://loginza.ru/img/providers/google.png" alt="Google" title="Google Accounts">
		<img src="http://loginza.ru/img/providers/vkontakte.png" alt="Вконтакте" title="Вконтакте">
		<img src="http://loginza.ru/img/providers/mailru.png" alt="Mail.ru" title="Mail.ru">
		<img src="http://loginza.ru/img/providers/twitter.png" alt="Twitter" title="Twitter">
		<img src="http://loginza.ru/img/providers/loginza.png" alt="Loginza" title="Loginza">
		<img src="http://loginza.ru/img/providers/myopenid.png" alt="MyOpenID" title="MyOpenID">
		<img src="http://loginza.ru/img/providers/openid.png" alt="OpenID" title="OpenID">
		<img src="http://loginza.ru/img/providers/webmoney.png" alt="WebMoney" title="WebMoney">
	    </a><br><br>';
	}
	echo '</div>';
}
else
{
	echo '<div class="block profile-block">';
	echo '<h3>'.$this->diafan->_('Профиль').'</h3>';
	if (! empty($result["avatar"]))
	{
		echo '<img src="'.BASE_PATH.USERFILES.'/avatar/'.$result["name"].'.png" width="'.$result["avatar_width"].'" height="'.$result["avatar_height"].'" alt="'.$result["fio"].' ('.$result["name"].')" class="avatar profile-hello-avatar">';
	}
	elseif(! empty($result["avatar_none"]))
	{
		echo '<img src="'.$result["avatar_none"].'" width="'.$result["avatar_width"].'" height="'.$result["avatar_height"].'" alt="'.$result["fio"].' ('.$result["name"].')" class="avatar profile-hello-avatar">';
	}
	echo '<div class="profile-hello-text">
			'.$this->diafan->_('Здравствуйте').',<br>';
   
		echo $result["fio"];
	    echo '!
		</div>';

		echo '<ul class="menu">';
		echo '<li><a href="/specifications/">' . $this->diafan->_('Спецификации') . '</a></li>';
		if($result['userpage'])
		{
			echo '<li><a href="'.$result['userpage'].'">'.$this->diafan->_('Личная страница').'</a></li>';
		}	       
        if(! empty($result["usersettings"]))
		{
			echo '<li><a href="'.$result["usersettings"].'">'.$this->diafan->_('Настройки').'</a></li>';
		}
		if (!empty($result['messages']))
		{
			echo '<li><a href="'.$result['messages'].'">'.$result['messages_name'];
			if($result['messages_unread'])
			{
			    echo ' (<b>'.$result['messages_unread'].'</b>)';
			}
			echo '</a></li>';
		}	      
	    echo '</ul>';
		
	
	
	echo '<a href="'.BASE_PATH_HREF.'logout/?'.rand(0, 99999).'" class="button">'.$this->diafan->_('Выйти', false).'</a>';
	echo '</div>';
}