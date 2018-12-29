<?php
/**
 * Настройки модуля
 * 
 * @package    DIAFAN.CMS
 * @author     Sarvar Khasanov
 * @version    5.4
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2014 OOO «Диафан» (http://diafan.ru)
 */
if (!defined('DIAFAN'))
{
	include dirname(dirname(dirname(__FILE__))).'/includes/404.php';
}

/**
 * Filter_admin_config
 */
class Filter_admin_config extends Frame_admin
{
	/**
	 * @var array поля в базе данных для редактирования
	 */
	public $variables = array (
		'base' => array (
			'hr6' => 'hr',
			'search_name' => array(
				'type' => 'checkbox',
				'name' => 'Искать по имени товара',
				'help' => 'Параметр позволяет выводить в блоке поиска по товарам поиск по названию.',
			),
			'search_price' => array(
				'type' => 'checkbox',
				'name' => 'Искать по цене',
				'help' => 'Параметр позволяет выводить в блоке поиска по товарам поиск по цене.',
			),
			'search_article' => array(
				'type' => 'checkbox',
				'name' => 'Искать по артикулу',
				'help' => 'Параметр позволяет выводить в блоке поиска по товарам поиск по артикулу.',
			),
			'search_brand' => array(
				'type' => 'checkbox',
				'name' => 'Искать по производителям',
				'help' => 'Параметр позволяет выводить в блоке поиска по товарам список производителей для выбора.',
			),
			'search_action' => array(
				'type' => 'checkbox',
				'name' => 'Искать товары по акции',
				'help' => 'Параметр позволяет выводить в блоке поиска по товарам поиск товаров, участвующих в акциях (у товара отмечена опция «Акция»).',
			),
			'search_new' => array(
				'type' => 'checkbox',
				'name' => 'Искать по новинкам',
				'help' => 'Параметр позволяет выводить в блоке поиска по товарам поиск новинок (у товара отмечена опция «Новинок»).',
			),
			'search_hit' => array(
				'type' => 'checkbox',
				'name' => 'Искать по хитам',
				'help' => 'Параметр позволяет выводить в блоке поиска по товарам поиск хитов (у товара отмечена опция «Хит»).',
			),
		),
	);

	/**
	 * @var array названия табов
	 */
	public $tabs_name = array(
		'base' => 'Основные настройки',
	);

	/**
	 * @var array настройки модуля
	 */
	public $config = array (
		'tab_card', // использование вкладок
		'element_site', // делит элементы по разделам (страницы сайта, к которым прикреплен модуль)
		'config', // файл настроек модуля
	);

	/**
	 * Сохранение поля "поиск по имени"
	 * 
	 * @return void
	 */
	function save_config_variable_search_name()
	{
		$this->diafan->set_query("search_name='%d'");
		$this->diafan->set_value(! empty($_POST["search_name"]) ? 1 : 0);
	}
	
	/**
	 * Сохранение поля "поиск по цене"
	 * 
	 * @return void
	 */
	function save_config_variable_search_price()
	{
		$this->diafan->configmodules("search_price", "shop", "", _LANG, (! empty($_POST["search_price"]) ? 1 : 0));
		$this->diafan->set_query("search_price='%d'");
		$this->diafan->set_value(! empty($_POST["search_price"]) ? 1 : 0);
	}
	
	/**
	 * Сохранение поля "поиск по артикулу"
	 * 
	 * @return void
	 */
	function save_config_variable_search_article()
	{
		$this->diafan->configmodules("search_article", "shop", "", _LANG, (! empty($_POST["search_article"]) ? 1 : 0));
		$this->diafan->set_query("search_article='%d'");
		$this->diafan->set_value(! empty($_POST["search_article"]) ? 1 : 0);
	}
	
	/**
	 * Сохранение поля "поиск по производителю"
	 * 
	 * @return void
	 */
	function save_config_variable_search_brand()
	{
		$this->diafan->configmodules("search_brand", "shop", "", _LANG, (! empty($_POST["search_brand"]) ? 1 : 0));
		$this->diafan->set_query("search_brand='%d'");
		$this->diafan->set_value(! empty($_POST["search_brand"]) ? 1 : 0);
	}
	
	/**
	 * Сохранение поля "поиск по акции"
	 * 
	 * @return void
	 */
	function save_config_variable_search_action()
	{
		$this->diafan->configmodules("search_action", "shop", "", _LANG, (! empty($_POST["search_action"]) ? 1 : 0));
		$this->diafan->set_query("search_action='%d'");
		$this->diafan->set_value(! empty($_POST["search_action"]) ? 1 : 0);
	}
	
	/**
	 * Сохранение поля "поиск по новинкам"
	 * 
	 * @return void
	 */
	function save_config_variable_search_new()
	{
		$this->diafan->configmodules("search_new", "shop", "", _LANG, (! empty($_POST["search_new"]) ? 1 : 0));
		$this->diafan->set_query("search_new='%d'");
		$this->diafan->set_value(! empty($_POST["search_new"]) ? 1 : 0);
	}
	
	/**
	 * Сохранение поля "поиск по хитам"
	 * 
	 * @return void
	 */
	function save_config_variable_search_hit()
	{
		$this->diafan->configmodules("search_hit", "shop", "", _LANG, (! empty($_POST["search_hit"]) ? 1 : 0));
		$this->diafan->set_query("search_hit='%d'");
		$this->diafan->set_value(! empty($_POST["search_hit"]) ? 1 : 0);
	}
	
}