<?php
/**
 * Карта ссылок для модуля «Карта сайта»
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

/**
 * Shop_sitemap
 */
class Shop_sitemap extends Diafan
{
	/**
	 * Возвращает настройки для генерирования карты модуля
	 * 
	 * @param integer $site_id номер страницы сайта
	 * @return array
	 */
	public function config($site_id)
	{
		$result = array(
			'type' => array('element', 'brand'),
			'where' =>
			array(
				'element' => ($site_id ? "AND site_id=".$site_id : '')." AND map_no_show='0' AND access='0'",
				'brand' => ($site_id ? "AND site_id=".$site_id : '')." AND map_no_show='0'"
			)
		);
		if ($this->diafan->configmodules("cat", 'shop', $site_id))
		{
			$result["type"][] = 'cat';
			$result["where"]['cat'] = ($site_id ? "AND site_id=".$site_id : '')." AND map_no_show='0' AND access='0'";
		}
		return $result;
	}
}