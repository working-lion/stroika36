<?php
/**
 * Обработка запроса при обновлении формы поиска товаров
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

class Filter_action extends Action
{
	
	public function update_filter()
	{
		$result = $this->model->show_filter($_REQUEST["attributes"]);
		if (empty($result))
		{
			return;
		}
		$result["update_form"]=true;
		$this->result["data"] = array(".shop-filter" => $this->diafan->_tpl->get('show_filter', 'filter', $result, $_REQUEST["attributes"]["template"])
			,".js_shop_search_popup .filter_count_goods" => $result["count_goods"]);
	}
	
	

}