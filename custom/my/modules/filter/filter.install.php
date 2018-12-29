<?php
/**
 * Установка модуля
 *
 * @package    DIAFAN.CMS
 * @author     Sarvar Khasanov
 * @version    5.4
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2014 OOO «Диафан» (http://diafan.ru)
 */

if (! defined('DIAFAN'))
{
	include dirname(dirname(dirname(__FILE__))).'/includes/404.php';
}

class Filter_install extends Install
{
	/**
	 * @var string название
	 */
	public $title = "Фильтр товаров";

	
	/**
	 * @var array записи в таблице {modules}
	 */
	public $modules = array(
		array(
			"name" => "filter",
			"admin" => true,
			"site" => true,
			"site_page" => false,
		),
	);
	/**
	 * @var array настройки
	 */
	public $config = array(
		array(
			"name" => "search_price",
			"value" => "1",
		),
		array(
			"name" => "search_article",
			"value" => "1",
		),
	);
	
	/**
	 * @var array меню административной части
	 */
	public $admin = array(
		array(
			"name" => "Фильтр товаров",
			"rewrite" => "filter",
			"group_id" => "4",
			"sort" => 11,
			"act" => true,
			"docs" => "",
			"children" => array(
				array(
					"name" => "Настройки",
					"rewrite" => "filter/config",
				),
			)
		),
	);
	
	protected function action()
	{
		$this->diafan->configmodules("search_price", "shop", "", _LANG, 1);
		$this->diafan->configmodules("search_article", "shop", "", _LANG, 1);
		$this->diafan->configmodules("search_brand", "shop", "", _LANG, 1);
		$this->diafan->configmodules("search_action", "shop", "", _LANG, 1);
		$this->diafan->configmodules("search_new", "shop", "", _LANG, 1);
		$this->diafan->configmodules("search_hit", "shop", "", _LANG, 1);
	}
	
	public function action_post()
	{
		DB::query("ALTER TABLE {shop_param} ADD `slider` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `id`");
	}
	
	protected function uninstall_action()
	{
		DB::query("ALTER TABLE {shop_param} DROP `slider`");
	}
	
}