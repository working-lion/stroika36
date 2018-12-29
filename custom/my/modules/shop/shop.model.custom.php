<?php

/**
 * @package    Diafan.CMS
 *
 * @author     Sarvar Khasanov
 * @license    http://cms.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2013 OOO «Диафан» (http://diafan.ru)
 */
if ( ! defined('DIAFAN'))
{
	include dirname(dirname(dirname(__FILE__))).'/includes/404.php';
}

/**
 * Shop_model
 *
 * Модель модуля "Магазин"
 */
class Shop_model extends Model
{
	
  /**
	 * Генерирует данные для страницы товара
	 * 
	 * @return boolean
	 */
	before public function id()
	{
		$cur_id=$this->diafan->_route->show; 

		if ( (! isset($_SESSION['shop_history']))||(!is_array($_SESSION['shop_history']))) 
		{ 
			$_SESSION['shop_history'] = array(); 
		}
		
		array_unshift($_SESSION['shop_history'], $cur_id); 
		$_SESSION['shop_history']=array_unique($_SESSION['shop_history']); 
		
		$_SESSION['shop_history'] = array_diff($_SESSION['shop_history'], array('',null));
		if (count($_SESSION['shop_history'])>20)
		{
			$_SESSION['shop_history'] = array_chunk($_SESSION['shop_history'],20);
			$_SESSION['shop_history']=$_SESSION['shop_history'][0];
		}
	}
	
	new public function lastview_goods($count)
	{
		$shop_history=array();
		if (is_array($_SESSION['shop_history']))
		{
			foreach ($_SESSION['shop_history'] as $row)
			{
				if (is_int((int)$row))
				{
					$shop_history[] = $row;
				}
			}
		}
		else return null;
		
		if (count($shop_history)>(!empty($count)?$count:4))
		{
			$shop_history=array_chunk($shop_history,(!empty($count)?$count:4));
			$shop_history=$shop_history[0];
		}
		
		$rows = DB::query_range_fetch_all(
			"SELECT s.id, s.[name], s.[anons], s.timeedit, s.site_id, s.brand_id, s.no_buy, s.article, s.hit, s.new, s.action, s.is_file FROM {shop} AS s"
			. " WHERE s.[act]='1' AND s.trash='0'"
			. " AND s.id IN ( ". implode(',',$shop_history) ." )",0,(!empty($count)?$count:4));

		$this->result["rows"]=$rows;
		
		if ($this->result["rows"])
		{
			foreach ($this->result["rows"] as &$row)
			{
				$this->select_price($row);
			}
		}
		$this->elements($this->result["rows"]);
		foreach ($this->result["rows"] as &$row)
		{
			$this->prepare_data_element($row);
		}
		foreach ($this->result["rows"] as &$row)
		{
			$this->format_data_element($row);
		}
		$this->result();
		$res=$this->result;
		return $res;
	}
  
  	/**
	 * Формирует SQL-запрос при поиске по товарам
	 * 
	 * @return boolean true
	 */
	after private function where(&$where, &$where_param, &$values, &$getnav, &$group)
	{
		if (!empty($_REQUEST["sn"]) && $this->diafan->configmodules("search_name","filter"))
		{
		$_REQUEST["sn"] = $this->diafan->filter($_REQUEST, "sql", "sn");
			$where .= ' AND LOWER(s.name'.$this->diafan->_languages->site.') like LOWER("%%'.$_REQUEST["sn"].'%%")';
			
			$values[] = str_replace(array(' ', '-'), '', $_REQUEST["sn"]);
			$getnav .= '&sn='.$this->diafan->filter($_REQUEST, "url", "sn");
		}
	}  

}