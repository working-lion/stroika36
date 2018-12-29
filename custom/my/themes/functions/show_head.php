<?php
/**
 * Шаблонный тег: формирует часть HTML-шапки сайта. Включает в себя в том числе теги: show_title, show_description, show_keywords.
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

if($this->diafan->configmodules("redhelper_login", "consultant"))
{
	echo "\n".'<meta http-equiv="X-UA-Compatible" content="IE=8">'
	."\n".'<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"> ';
}
if($this->diafan->_site->canonical)
{
	if(substr($this->diafan->_site->canonical, 0, 4) != 'http')
	{
		if(substr($this->diafan->_site->canonical, 0, 1) == '/')
		{
			$this->diafan->_site->canonical = 'http'.(IS_HTTPS ? "s" : '').'://'.BASE_URL.$this->diafan->_site->canonical;
		}
		else
		{
			$this->diafan->_site->canonical = BASE_PATH_HREF.$this->diafan->_site->canonical;
		}
	}
	echo "\n".'<link href="'.$this->diafan->_site->canonical.'" rel="canonical">';
}

if (! IS_MOBILE && ($this->diafan->configmodules('use_animation') || $this->diafan->configmodules('use_animation', 'site') || $this->diafan->_users->useradmin == 1))
{
	echo "\n".'<link rel="stylesheet" href="'.BASE_PATH.File::compress('css/prettyPhoto.css', 'css').'" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8">';
}

echo "\n".'<meta name="robots" content="';
if($this->diafan->_site->noindex)
{
	echo 'noindex';
}
else
{
	echo 'all';
}
echo '">';

if(in_array('news', $this->diafan->installed_modules))
{
	echo "\n".'<link rel="alternate" type="application/rss+xml" title="RSS" href="'.BASE_PATH.'news/rss/">';
}

echo '<title>';
echo $this->functions('show_title', array());
echo '</title>
<meta charset="utf-8">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="description" content="';
echo $this->functions('show_description', array());
echo '">
<meta name="keywords" content="';
$this->functions('show_keywords', array());
echo '">';

if ($this->diafan->_site->edit_meta)
{
	$useradmin_links = $this->diafan->_useradmin->get_meta($this->diafan->_site->edit_meta["id"], $this->diafan->_site->edit_meta["table"]);
}
else
{
	$useradmin_links = $this->diafan->_useradmin->get_meta($this->diafan->_site->id, 'site');
}
if(! empty($useradmin_links))
{
	echo '<meta name="useradmin_title" content="'.$useradmin_links["title_meta"].'">';
	echo '<meta name="useradmin_description" content="'.$useradmin_links["descr"].'">';
	echo '<meta name="useradmin_keywords" content="'.$useradmin_links["keywords"].'">';
}
