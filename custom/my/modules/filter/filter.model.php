<?php
/**
 * Модель модуля «Фильтр товаров»
 * 
 * @package    DIAFAN.CMS
 * @author     Sarvar Khasanov
 * @version    5.4
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2014 OOO «Диафан» (http://diafan.ru)
 */
if ( ! defined('DIAFAN'))
{
	include dirname(dirname(dirname(__FILE__))).'/includes/404.php';
}

/**
 * Filter_model
 */
class Filter_model extends Model
{
	
	/**
	 * @var array значения параметров, влияющих на цену
	 */
	private $depends;

	/**
	 * @var array идентификаторы персональных скидок текущего пользователя 
	 */
	private $person_discount_ids = false;

	
	/**
	 * Конструктор класса
	 * 
	 * @return void
	 */
	public function __construct(&$diafan)
	{
		$this->diafan = &$diafan;
	}

	/**
	 * Генерирует контент для шаблонной функции: форма поиска по товарам
	 *
	  @param array $attributes атрибуты шаблонного тега
	 * site_id - страницы, к которым прикреплен модуль. Идентификаторы страниц перечисляются через запятую. По умолчанию выбираются все страницы. Если выбрано несколько страниц сайта, то в форме поиска появляется выпадающих список по выбранным страницам. Можно указать отрицательное значение, тогда указанные страницы будут исключены из списка
	 * cat_id - категории товаров, если в настройках модуля отмечено «Использовать категории». Идентификаторы категорий перечисляются через запятую. Можно указать значение **current**, тогда поиск будет осуществляться по текущей (открытой) категории магазина или по всем категориям, если ни одна категория не открыта. Если выбрано несколько категорий, то в форме поиска появится выпадающий список категорий магазина, который будет подгружать прикрепленные к категориям характеристики. Можно указать отрицательное значение, тогда указанные категории будут исключены из списка. Можно указать значение **all**, тогда поиск будет осуществлятся по всем категориям товаров и в форме будут участвовать только общие характеристики
	 * ajax - подгружать результаты поиска без перезагрузки страницы: **true** – результаты поиска подгружаются, по умолчанию будет перезагружена вся страница. Результаты подгружаются только если открыта страница со списком товаром, иначе поиск работает обычным образом
	 * only_module - выводить форму поиска только на странице модуля «Магазин»: **true** – выводить форму только на странице модуля, по умолчанию форма будет выводиться на всех страницах
	 * template - шаблон тега (файл modules/filter/views/filter.view.show_filter_**template**.php; по умолчанию шаблон modules/filter/views/filter.view.show_filter.php)
	 * 
	 * @return array
	 */
	public function show_filter($attributes)
	{
		$this->diafan->_site->js_view[] = 'modules/filter/js/ion.rangeSlider.min.js';
		$site_ids  = explode(",", $attributes["site_id"]);
		$cat_ids   = $attributes["cat_id"] === 'current' || $attributes["cat_id"] === 'all' ? $attributes["cat_id"] : explode(",", $attributes["cat_id"]);
		$ajax      = $attributes["ajax"] == "true" ? true : false;
		$cat_ids_current=false;
		$cat_ids_all=false;
		
		if ($cat_ids === 'current')
		{
			$cat_ids_current=true;
			if($this->diafan->_route->cat && $this->diafan->_site->module == "shop" && (count($site_ids) == 1 && $site_ids[0] == 0 || in_array($this->diafan->_site->id, $site_ids)))
			{
				$cat_ids  = array($this->diafan->_route->cat);
				$site_ids = array($this->diafan->_site->id);
			}
			else
			{
				$cat_ids = array();
			}
		}

		// устаревший атрибут
		if($attributes["only_shop"])
		{
			$attributes["only_module"] = true;
		}

		if ($attributes["only_module"] && ($this->diafan->_site->module != "shop" || $site_ids && ! in_array($this->diafan->_site->id, $site_ids)))
			return;
		
		
		//кеширование
		$cache_meta = array(
			"name" => "show_search",
			"lang_id" => _LANG,
			"cat_ids" => $cat_ids,
			"site_ids" => $site_ids,
			"role_id" => $this->diafan->_users->role_id ? $this->diafan->_users->role_id : 0
		);
		if (!empty($attributes["tmp_cat_ids"]))
		{
			$cat_ids = explode(",",$attributes["tmp_cat_ids"]);
			$site_ids = explode(",",$attributes["tmp_site_ids"]);
		}
		if (true /*! $result = $this->diafan->_cache->get($cache_meta, "shop")*/)
		{
			if($attributes["cat_id"] === 'all')
			{
				$cat_ids_all = true;
			}
			if (empty($attributes["tmp_cat_ids"]))
			{
				if($attributes["cat_id"] === 'all')
				{
					$cat_ids = array();
				}
				
				$one_cat_id = count($cat_ids) == 1 ? $cat_ids[0] : 0;
				$minus = array();
				if(! $this->validate_attribute_site_cat('shop', $site_ids, $cat_ids, $minus))
				{
					return false;
				}
				$result["cat_ids"] = array();
			
				if((count($cat_ids) > 1 || ! empty($cat_ids_all) || !empty($cat_ids_current)))
				{
					if(empty($cat_ids_all)&&empty($cat_ids_current))
					{
						$cats = DB::query_fetch_all("SELECT id, [name], site_id, parent_id FROM {shop_category} WHERE id IN (%s) ORDER BY sort ASC", implode(',', $cat_ids));
					}
					else
					{
						$where = "";
						if($site_ids)
						{
							$where .= " AND c.site_id IN (".implode(',', $site_ids).")";
						}
						elseif(! empty($minus["site_ids"]))
						{
							$where .= " AND c.site_id NOT IN (".implode(',', $minus["site_ids"]).")";
						}
						if(! empty($minus["cat_ids"]))
						{
							$where .= " AND c.id NOT IN (".implode(",", $minus["cat_ids"]).")";
						}
						$cats = DB::query_fetch_all("SELECT c.id, c.[name], c.site_id, c.parent_id FROM {shop_category} AS c"
						.($this->diafan->_users->role_id ? " LEFT JOIN {access} AS a ON a.element_id=c.id AND a.module_name='shop' AND a.element_type='cat'" : "")
						." WHERE c.[act]='1' AND c.trash='0'"
						.$where
						." AND (c.access='0'"
						.($this->diafan->_users->role_id ? " OR c.access='1' AND a.role_id=".$this->diafan->_users->role_id : '')
						.")"
						." GROUP BY c.id ORDER BY c.sort ASC");
						
					}
					
					foreach ($cats as &$cat)
					{
						$cat["level"] = 0;
						if(!empty($cat_ids_all))
						{
							$cat_ids[] = $cat["id"];
						}
						$parents[$cat["id"]] = $cat["parent_id"];
						
					}
					
					$cat_ids1=array();
					foreach ($cats as &$cat)
					{
						$parent = $cat["id"];
						$level = 0;
						$use_cat=false;
						while($parent)
						{
							if(! empty($parents[$parent]))
							{
								$parent = $parents[$parent];
								$level++;
								if (in_array($parent,$cat_ids))
								{
									$cat_ids1[]=$cat["id"];
									$use_cat=true;
								}
							}
							else
							{
								$parent = 0;
							}
						}
						$cat["level"] = $level;
						if ((!empty($cat_ids_all))||$use_cat)
						{
							$cats_h[$level ? $cat["parent_id"] : 0][] = $cat;
						}
					}
					
					if (!empty($cat_ids_current))
					{
						foreach ($cat_ids1 as $c)
						{
							if (!in_array($c,$cat_ids))
							{
								$cat_ids[]=$c;
							}
							
						}
					}
					if($cats && !empty($cats_h))
					{
						
						$this->list_cats_hierarhy($result["cat_ids"], $cats_h, ($one_cat_id ? $parents[$one_cat_id] : 0));
						
					}
				}
				elseif(count($cat_ids) == 1)
				{
					$result["cat_ids"][] = array("id" => $cat_ids[0]);
				}
			}
			else
			{
				if (!empty($cat_ids_all))
				{
					$cats=DB::query_fetch_all("SELECT id, [name], site_id, parent_id FROM {shop_category} WHERE id IN (%s) ORDER BY sort ASC", implode(',', $cat_ids));
					foreach ($cats as &$cat)
					{
						$cat["level"] = 0;
						if(!empty($cat_ids_all))
						{
							$cat_ids[] = $cat["id"];
						}
						$parents[$cat["id"]] = $cat["parent_id"];
						
					}
					
					$cat_ids1=array();
					foreach ($cats as &$cat)
					{
						$parent = $cat["id"];
						$level = 0;
						$use_cat=false;
						while($parent)
						{
							if(! empty($parents[$parent]))
							{
								$parent = $parents[$parent];
								$level++;
								if (in_array($parent,$cat_ids))
								{
									$cat_ids1[]=$cat["id"];
									$use_cat=true;
								}
							}
							else
							{
								$parent = 0;
							}
						}
						$cat["level"] = $level;
						if ((!empty($cat_ids_all))||$use_cat)
						{
							$cats_h[$level ? $cat["parent_id"] : 0][] = $cat;
						}
					}
					
					$result["cat_ids"]=$cats;
				}
				else
				{
					$result["cat_ids"][] = array("id" => $cat_ids[0]);
				}
			}
			
			if(count($site_ids) > 1)
			{
				$result["site_ids"] = DB::query_fetch_all("SELECT id, [name] FROM {site} WHERE id IN (%s) ORDER BY sort ASC", implode(',', $site_ids));
				foreach ($result["site_ids"] as &$site)
				{
					$site["path"] = $this->diafan->_route->link($site["id"]);
				}
			}
			else
			{
				$result["site_ids"][] = array(
						"id" => $site_ids[0],
						"path" => $this->diafan->_route->link($site_ids[0])
					);
			}
			
			$count_goods = DB::query_result(
					"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					.($cat_ids?" INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id AND c.cat_id IN (".implode(",", $cat_ids).")":'')
					." WHERE s.[act]='1' AND s.trash='0' "
					.($site_ids?" AND s.site_id IN (".implode(",", $site_ids).")":'')
					);
			if ($count_goods==0)
			{
				return;
			}
			
			
			
			if ($this->diafan->configmodules("search_name", "filter", $site_ids[0]))
			{
				$result["shop_name"] = array(
					"shop_name" => 1,
					"value" => ''
				);
			}
			
			if ($this->diafan->configmodules("search_article", "filter", $site_ids[0]))
			{
				$result["article"] = array(
					"article" => 1,
					"value" => ''
				);
			}
	
			if ($this->diafan->configmodules("search_price", "filter", $site_ids[0]))
			{
				$result["price"] = array(
					"name" => 1,
					"value1" => 0,
					"value2" => 0
				);
			}
	
			if ($this->diafan->configmodules("search_brand", "filter", $site_ids[0]))
			{
				$result["brands"] = DB::query_fetch_all("SELECT b.id, b.[name], b.site_id FROM {shop_brand} AS b 
				INNER JOIN {shop} AS s ON s.brand_id=b.id  "
				.($cat_ids?" INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id AND c.cat_id IN (".implode(",", $cat_ids).")":'')
				."WHERE b.[act]='1' AND b.trash='0' AND b.site_id IN (%s) GROUP BY b.id, b.[name], b.site_id ORDER BY b.sort ASC", implode(',', $site_ids));
				
				$result["brand"] = array();
			}
	
			if ($this->diafan->configmodules("search_action", "filter", $site_ids[0]))
			{
				$result["action"] = array(
					"name" => 1,
					"value" => false
				);
			}
	
			if ($this->diafan->configmodules("search_hit", "filter", $site_ids[0]))
			{
				$result["hit"] = array(
					"name" => 1,
					"value" => false
				);
			}
	
			if ($this->diafan->configmodules("search_new", "filter", $site_ids[0]))
			{
				$result["new"] = array(
					"name" => 1,
					"value" => false
				);
			}
			$result["rows"] = DB::query_fetch_all("SELECT p.id, p.type, p.[name], p.[measure_unit],p.slider, GROUP_CONCAT(c.cat_id SEPARATOR ',') as cat_ids FROM {shop_param} as p "
				." INNER JOIN {shop_param_category_rel} AS c ON p.id=c.element_id AND "
				.($cat_ids ? "(c.cat_id IN (".(!empty($cat_ids_all)?0:implode(',', $cat_ids)).") OR c.cat_id=0)" : "c.cat_id=0")
				." WHERE p.search='1' AND p.trash='0' GROUP BY p.id ORDER BY p.sort ASC");
	
			foreach ($result["rows"] as &$row)
			{
				if(! isset($row["cat_ids"]))
				{
					$row["cat_ids"] = '';
				}
				if ($row["type"] == 'select' || $row["type"] == 'multiple')
				{
					$row["select_array"] = DB::query_fetch_key_value(
						"SELECT p.[name], p.id FROM {shop_param_select} AS p"
						// выводим значения, только есть товары, чтобы поиск не давал пустых результатов
						." INNER JOIN {shop_param_element} AS e ON p.param_id=e.param_id AND e.value".$this->diafan->_languages->site."=p.id"
						." INNER JOIN {shop} AS s ON e.element_id=s.id AND s.[act]='1' AND s.trash='0'"
						.($cat_ids ? " INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id AND c.cat_id IN (".implode(",", $cat_ids).")" : '')
						." WHERE p.param_id=%d GROUP BY p.id ORDER BY p.sort ASC", $row["id"], "id", "name");
					
					if(empty($row["select_array"]))
					{
						unset($row);
					}
				}
			}
		}

		if (! empty($result["shop_name"]))
		{
			$result["shop_name"]["value"] = ! empty($_REQUEST["sn"]) ? trim(htmlspecialchars(stripslashes($_REQUEST["sn"]))) : '';
		}
		
		if (! empty($result["article"]))
		{
			$result["article"]["value"] = ! empty($_REQUEST["a"]) ? trim(htmlspecialchars(stripslashes($_REQUEST["a"]))) : '';
		}

		if (! empty($result["price"]))
		{
			$result["price"]["value1"] = $this->diafan->filter($_REQUEST, "int", "pr1");
			$result["price"]["value2"] = $this->diafan->filter($_REQUEST, "int", "pr2");
		}

		if (! empty($result["brands"]) && ! empty($_REQUEST["brand"]))
		{
			if(is_array($_REQUEST["brand"]))
			{
				foreach($_REQUEST["brand"] as $b)
				{
					$b = $this->diafan->filter($b, "integer");
					if($b)
					{
						$result["brand"][] = $b;
					}
				}
			}
			else
			{
				$b = $this->diafan->filter($_REQUEST, "integer", "brand");
				if($b)
				{
					$result["brand"][] = $b;
				}
			}
		}

		if (! empty($result["action"]) && ! empty($_REQUEST["ac"]))
		{
			$result["action"]["value"] = true;
		}

		if (! empty($result["hit"]) && ! empty($_REQUEST["hi"]))
		{
			$result["hit"]["value"] = true;
		}

		if (! empty($result["new"]) && ! empty($_REQUEST["ne"]))
		{
			$result["new"]["value"] = true;
		}
		
		foreach ($result["rows"] as &$row)
		{
			switch($row["type"])
			{
				case 'date':
				case 'datetime':
					$row["value1"] = $this->diafan->filter($_REQUEST, "string", "p".$row["id"]."_1");
					$row["value2"] = $this->diafan->filter($_REQUEST, "string", "p".$row["id"]."_2");
					break;

				case 'numtext':
					$p2_max = DB::query_fetch_all("SELECT MAX(ee.value".$this->diafan->_languages->site."+0) as maxv FROM {shop_param_element} as ee INNER JOIN {shop} as s ON s.id=ee.element_id"
							.($cat_ids ? " INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id AND c.cat_id IN (".implode(",", $cat_ids).")" : '')
							." WHERE ee.param_id=%d",$row['id']);
					if (!empty($p2_max[0]))
					{
						$p2_max = $p2_max[0]['maxv'];
					}
					else
						$p2_max=0;
					$p1_min = DB::query_fetch_all("SELECT MIN(ee.value".$this->diafan->_languages->site."+0) as minv FROM {shop_param_element} as ee INNER JOIN {shop} as s ON s.id=ee.element_id"
							.($cat_ids ? " INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id AND c.cat_id IN (".implode(",", $cat_ids).")" : '')	
							." WHERE ee.param_id=%d",$row['id']);
					if (!empty($p1_min[0]))
					{
						$p1_min = $p1_min[0]['minv'];
					}
					else
						$p1_min=0;
					
					
					$row["value1"] = $this->diafan->filter($_REQUEST, "float", "p".$row["id"]."_1");
					$row["value2"] = $this->diafan->filter($_REQUEST, "float", "p".$row["id"]."_2");
					if ($row["value2"] == $p2_max)
					{
						$_REQUEST["p".$row["id"]."_2"]=0;
					}
					if ($row["value1"] == $p1_min)
					{
						$_REQUEST["p".$row["id"]."_1"]=0;
					}
					if (empty($row["value2"]) && !empty($p2_max))
						$row["value2"] = $p2_max;
					if (empty($row["value1"]) && !empty($p1_min))
						$row["value1"] = $p1_min;
					
					if ($row["value2"]>$p2_max)
					{
						$p2_max = $row["value2"];
					}
					
					if ($row["value1"]<$p1_min)
					{
						$p1_min = $row["value1"];
					}
					
					$row['valueMaxG'] = $p2_max;
					$row['valueMinG'] = $p1_min;
					
					if ($row["value1"]!=$p1_min)
						$row["notdefault1"] = true;
					if ($row["value2"]!=$p2_max)
						$row["notdefault2"] = true;
					break;

				case 'checkbox':
					$row["value"] = $this->diafan->filter($_REQUEST, "int", "p".$row["id"]);
					break;

				case 'select':
				case 'multiple':
					$row["value"] = array();
					if ( ! empty($_REQUEST["p".$row["id"]]) && ! is_array($_REQUEST["p".$row["id"]]))
					{
						$row["value"][] = $this->diafan->filter($_REQUEST, "int", "p".$row["id"]);
					}
					elseif ( ! empty($_REQUEST["p".$row["id"]]) && is_array($_REQUEST["p".$row["id"]]))
					{
						foreach ($_REQUEST["p".$row["id"]] as $val)
						{
							$row["value"][] = intval($val);
						}
					}
					break;

				default:
					$row["value"] = array();
			}
		}

		if($this->diafan->_site->module == 'shop' && in_array($this->diafan->_site->id, $site_ids))
		{
			$result["site_id"] = $this->diafan->_site->id;
			foreach ($result["site_ids"] as &$row)
			{
				if($row["id"] == $this->diafan->_site->id)
				{
					$result["path"] = $row["path"];
				}
			}
		}
		else
		{
			$result["site_id"] = $result["site_ids"][0]["id"];
			$result["path"]    = $result["site_ids"][0]["path"];
		}
		if($this->diafan->_site->module == 'shop' && in_array($this->diafan->_route->cat, $cat_ids))
		{
			$result["cat_id"] = $this->diafan->_route->cat;
		}
		elseif(! empty($result["cat_ids"][0]["id"]) && count($result["cat_ids"]) == 1)
		{
			$result["cat_id"] = $result["cat_ids"][0]["id"];
		}
		else
		{
			if (!empty($_REQUEST["cat_id"]))
			{
				$result["cat_id"] = $this->diafan->filter($_REQUEST, "int", "cat_id");
			}
			else
			{
				$result["cat_id"] = 0;
			}
		}
		
		
		
		
		if (!empty($_REQUEST["cat_id"])&&!empty($cat_ids_all))
		{
			$result["cat_id"] = $this->diafan->filter($_REQUEST, "int", "cat_id");
			$result["cat_ids_wd"]=array();
			
			
				$this->list_cats_hierarhy($result["cat_ids_wd"], $cats_h, $result["cat_id"]);
				if (empty($result["cat_ids_wd"]))
				{
					$result["cat_ids_wd"][]=$result["cat_id"];
				}
			
		}
		
		
		$result["send_ajax"] = $ajax;
		$result["attributes"]=array();
		
		$result["attributes"]["site_id"]=$attributes["site_id"];
		$result["attributes"]["cat_id"]=$attributes["cat_id"];
		$result["attributes"]["ajax"]=$attributes["ajax"];
		$result["attributes"]["only_module"]=$attributes["only_module"];
		$result["attributes"]["only_shop"]=$attributes["only_shop"];
		$result["attributes"]["template"]=$attributes["template"]; 
		$result["attributes"]["tmp_cat_ids"] = implode(',', $cat_ids);
		$result["attributes"]["tmp_site_ids"] = implode(',', $site_ids);
		$result=$this->update_filter($result,$cat_ids);
		
		return $result;
	}

	/**
	 * Формирует дерево категорий для поиска
	 * 
	 * @return void
	 */
	private function list_cats_hierarhy(&$result, $cats, $parent = 0)
	{
		if(empty($cats[$parent]))
			return;

		foreach ($cats[$parent] as $cat)
		{
			$result[] = $cat;
			$this->list_cats_hierarhy($result, $cats, $cat["id"]);
		}
	}
	
	/**
	 * Формирует данные о недействительных параметрах поиска
	 *
	 * $result - контент для шаблонной функции.
	 * $cat_ids - категории товаров. 
	 * 
	 * @return array
	 */
	public function update_filter($result,$cat_ids)
	{
		$time = mktime(23, 59, 0, date("m"), date("d"), date("Y"));

		$where_param = '';
		$where = '';
		$values = array();
		$getnav = '';
		$group = '';

		
		$this->where($where, $where_param, $values, $getnav, $group);
		
		if($this->diafan->configmodules('where_period_element'))
		{
			$where .= " AND s.date_start<=".$time." AND (s.date_finish=0 OR s.date_finish>=".$time.")";
		}
		
		$result["count_goods"] = $this->list_search_query_count($where_param, $where, $group, $values);
		
		
		
		
		
		
		
		
		$shop_name='';
		if (!empty($_REQUEST['sn']))
		{
			$shop_name=' AND LOWER(s.[name]) like LOWER("%%'.$this->diafan->filter($_REQUEST, "sql", "sn").'%%") ';
		}
		
		$article='';
		if (!empty($_REQUEST['a']))
		{
			$article=' AND s.article='.$this->diafan->filter($_REQUEST, "int", "a").' ';
		}
		
		$brand='';
		if (! empty($result["brands"]) && ! empty($_REQUEST["brand"]))
		{
			if(is_array($_REQUEST["brand"]))
			{
				$brand .= ' AND( ';
				foreach($_REQUEST["brand"] as $b)
				{
					$b = $this->diafan->filter($b, "integer");
					if($b)
					{
						if ($_REQUEST['brand'][0]!=$b)
						{
							$brand .= ' OR ';
						}
						$brand .= ' s.brand_id='.$b.' ';
					}
				}
				$brand .= ' ) ';
			}
			else
			{
				$b = $this->diafan->filter($_REQUEST, "integer", "brand");
				if($b)
				{
					$brand=' AND s.brand_id='.$b.' ';
				}
			}
		}
		
		$action='';
		if (!empty($_REQUEST['ac'][0]))
		{
			$action=" AND s.action='1' ";
		}

		$hit='';
		if (!empty($_REQUEST['hi'][0]))
		{
			$hit=" AND s.hit='1' ";
		}

		$new='';
		if (!empty($_REQUEST['ne'][0]))
		{
			$new=" AND s.new='1' ";
		}
		
		$where=array();
		$where_param_price=array();
		foreach ($result["rows"] as &$row)
		{
			switch($row["type"])
			{
				case 'date':
				case 'datetime':
					if (!empty($_REQUEST['p'.$row['id']."_1"]) || !empty($_REQUEST['p'.$row['id']."_2"]))
					{
						$p1 = $this->diafan->filter($_REQUEST, "sql", "p".$row["id"]."_1");
						$p2 = $this->diafan->filter($_REQUEST, "sql", "p".$row["id"]."_2");
						$where[$row['id']] = '((SELECT COUNT(*) FROM {shop_param_element} AS ee WHERE ee.element_id = s.id AND (ee.param_id='.$row['id'].' AND ee.[value]>="'.$p1.'" AND ee.[value]<="'.$p2.'"))>0)';
					}
					break;

				case 'numtext':
					if (!empty($_REQUEST['p'.$row['id']."_1"]) || !empty($_REQUEST['p'.$row['id']."_2"]))
					{
						$p1 = $this->diafan->filter($_REQUEST, "float", "p".$row["id"]."_1");
						$p2 = $this->diafan->filter($_REQUEST, "float", "p".$row["id"]."_2");
						$p1 = (!empty($p1)?$p1:0);
						$p2 = (!empty($p2)?$p2:999999999999);
						$where[$row['id']] = '((SELECT COUNT(*) FROM {shop_param_element} AS ee WHERE ee.element_id = s.id AND (ee.param_id='.$row['id'].' AND ee.value'.$this->diafan->_languages->site.'>='.$p1.' AND ee.value'.$this->diafan->_languages->site.'<='.$p2.'))>0)';
					}
					break;

				case 'checkbox':
					if (!empty($_REQUEST['p'.$row['id']]))
					{
						$p = $this->diafan->filter($_REQUEST, "int", "p".$row["id"]);
						$where[$row['id']] = '((SELECT COUNT(*) FROM {shop_param_element} AS ee WHERE ee.element_id = s.id AND (ee.param_id='.$row['id'].' AND ee.[value]="'.$p.'"))>0)';
					}
					break;
					
				case 'select':
				case 'multiple':
					if (!empty($_REQUEST['p'.$row['id']]))
					{
						$w=array();
						$wp=array();
						foreach ($_REQUEST['p'.$row['id']] as $r)
						{
							$p=$r;
							$p = $this->diafan->filter($p, "int");
							$w[] = 'ee.[value]='.$p;
							$wp[] = "pp1.param_value=".$p;
							
						}
						$where[$row['id']] = '((SELECT COUNT(*) FROM {shop_param_element} AS ee WHERE ee.element_id = s.id AND (ee.param_id='.$row['id'].' AND ('.implode(' OR ',$w).')))>0)';
						$where_param_price[$row['id']] = "(((SELECT COUNT(*) FROM {shop_price_param} as pp2 INNER JOIN {shop_price} AS p2 ON pp2.price_id=p2.id WHERE p2.good_id=s.id AND pp2.param_id=".$row['id']." AND p2.trash='0')=0)"
												." OR ((SELECT COUNT(*) FROM {shop_price_param} as pp1 WHERE pp1.price_id=p1.id AND trash='0' AND pp1.param_id=".$row['id']." AND (".implode(' OR ',$wp)." OR pp1.param_value=0))>0))";
						
					}
					break;

				default:
					$row["value"] = array();
			}
	
		}
		
		$param='';
		
		$where_cat_ids='';
		if ($cat_ids)
		{
			$where_cat_ids=" INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id".(!empty($result["cat_ids_wd"])?" AND c.cat_id IN (".implode(",", $result["cat_ids_wd"]).")":"")." AND c.cat_id IN (".implode(",", $cat_ids).")";
		}
		if (! empty($result["price"]))
		{
			$max = DB::query_fetch_all(
						"SELECT MAX(pr.price) as mp FROM {shop} AS s "
						.($where_cat_ids?$where_cat_ids:'')
						." INNER JOIN {shop_price} AS pr ON pr.good_id=s.id "
						." WHERE s.[act]='1' AND s.trash='0' "
						.($where? " AND (".implode(' AND ',$where).")":'')
						.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($action?$action:'')
						.($hit?$hit:'')
						);
			$min = DB::query_fetch_all(
						"SELECT MIN(pr.price) as mp FROM {shop} AS s "
						.($where_cat_ids?$where_cat_ids:'')
						." INNER JOIN {shop_price} AS pr ON pr.good_id=s.id "
						." WHERE s.[act]='1' AND s.trash='0'"
						.($where? " AND (".implode(' AND ',$where).")":'')
						.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($action?$action:'')
						.($hit?$hit:'')
						);
			$maxG = DB::query_fetch_all(
						"SELECT MAX(pr.price) as mp FROM {shop} AS s "
						.($where_cat_ids?$where_cat_ids:'')
						." INNER JOIN {shop_price} AS pr ON pr.good_id=s.id "
						." WHERE s.[act]='1' AND s.trash='0' "
						);
			$minG = DB::query_fetch_all(
						"SELECT MIN(pr.price) as mp FROM {shop} AS s "
						.($where_cat_ids?$where_cat_ids:'')
						." INNER JOIN {shop_price} AS pr ON pr.good_id=s.id "
						." WHERE s.[act]='1' AND s.trash='0'"
						);
						
			if (!empty($max[0]))
				$max=$max[0]['mp'];
			else
				$max=0;
			if (!empty($min[0]))
				$min=$min[0]['mp'];
			else
				$min=0;
			if (!empty($maxG[0]))
				$maxG=$maxG[0]['mp'];
			else
				$maxG=0;
			if (!empty($minG[0]))
				$minG=$minG[0]['mp'];
			else
				$minG=0;
						
			$result["price"]["min"]=(empty($min)?0:$min);			
			$result["price"]["max"]=(empty($max)?0:$max);
			$result["price"]["ming"]=(empty($min)?0:$minG);			
			$result["price"]["maxg"]=(empty($max)?0:$maxG);			
			$result["price"]["value1"]=(empty($min)?0:$min);
			$result["price"]["value2"]=(empty($max)?0:$max);
			$price='';
			if (!empty($_REQUEST['pr1'])||!empty($_REQUEST['pr2']))
			{
				$price=' INNER JOIN {shop_price} AS pr ON pr.good_id=s.id 
					AND pr.price>='.(!empty($_REQUEST['pr1'])?$this->diafan->filter($_REQUEST, "int", "pr1"):0).' 
					AND pr.price<='.(!empty($_REQUEST['pr2'])?$this->diafan->filter($_REQUEST, "int", "pr2"):999999999999).' ';			
			}
			
			if (!empty($_REQUEST['pr1']))
			{
				$result["price"]["value1"]=$this->diafan->filter($_REQUEST, "int", "pr1");
				$result["price"]["notdefault1"]=true;
			}
			if (!empty($_REQUEST['pr2']))
			{
				$result["price"]["value2"]=$this->diafan->filter($_REQUEST, "int", "pr2");
				$result["price"]["notdefault2"]=true;
			}
		}
		
		/*$result["count_goods"] = DB::query_result(
					"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					.($where_cat_ids?$where_cat_ids:'')
					.($price?$price:'')
					." WHERE s.[act]='1' AND s.trash='0'"
					.($where? " AND (".implode(' AND ',$where).")":'')
					.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
					.($article?$article:'')
					.($shop_name?$shop_name:'')
					.($brand?$brand:'')
					.($action?$action:'')
					.($hit?$hit:'')
					.($new?$new:'')
					);*/
		/*File::save_file("SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					.($where_cat_ids?$where_cat_ids:'')
					.($price?$price:'')
					." WHERE s.[act]='1' AND s.trash='0'"
					.($where? " AND (".implode(' AND ',$where).")":'')
					.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
					.($article?$article:'')
					.($shop_name?$shop_name:'')
					.($brand?$brand:'')
					.($action?$action:'')
					.($hit?$hit:'')
					.($new?$new:''), "custom/my/modules/filter/file.txt");//Дебагинг*/
		
		foreach ($result["rows"] as &$row)
		{
			$where1=$where;
			$where_param_price1=$where_param_price;
			if (!empty($where1[$row['id']]))
			{
				unset($where1[$row['id']]);
				unset($where_param_price1[$row['id']]);
			}
			if(! isset($row["cat_ids"]))
			{
				$row["cat_ids"] = '';
			}
			if (!empty($_REQUEST["cat_id"]))
			{
				$cat_ids = array($this->diafan->filter($_REQUEST, "int", "cat_id"));
			}
			switch($row["type"])
			{
				case 'date':
				case 'datetime':
					$available = DB::query_result(
						"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
						." INNER JOIN {shop_param_element} AS ee ON ee.element_id=s.id"
						." INNER JOIN {shop_param_element} AS e ON e.element_id=s.id "
						.($where_cat_ids?$where_cat_ids:'')
						.($price?$price:'')
						." WHERE s.[act]='1' AND s.trash='0' AND e.param_id=".$row["id"]." AND e.value".$this->diafan->_languages->site."=1 "
						.($where1? " AND (".implode(' AND ',$where1).")":'')
						.($where_param_price1?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price1)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($hit?$hit:'')
						.($new?$new:'')
						);
					break;

				case 'numtext':
					$available = DB::query_result(
						"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
						." INNER JOIN {shop_param_element} AS ee ON ee.element_id=s.id"
						." INNER JOIN {shop_param_element} AS e ON e.element_id=s.id "
						.($where_cat_ids?$where_cat_ids:'')
						.($price?$price:'')
						." WHERE s.[act]='1' AND s.trash='0' AND e.param_id=".$row["id"]." " //" AND e.value".$this->diafan->_languages->site.">=".$row["value1"]." AND e.value".$this->diafan->_languages->site."<=".$row["value2"]." "
						.($where1? " AND (".implode(' AND ',$where1).")":'')
						.($where_param_price1?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price1)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($hit?$hit:'')
						.($new?$new:'')
						);
						
					$row["valueMax"] = DB::query_result(
						"SELECT MAX(ee.value".$this->diafan->_languages->site."+0) FROM {shop} AS s "
						." INNER JOIN {shop_param_element} AS ee ON ee.element_id=s.id"
						." INNER JOIN {shop_param_element} AS e ON e.element_id=s.id "
						.($where_cat_ids?$where_cat_ids:'')
						.($price?$price:'')
						." WHERE s.[act]='1' AND s.trash='0' AND e.param_id=".$row["id"]." " //" AND e.value".$this->diafan->_languages->site.">=".$row["value1"]." AND e.value".$this->diafan->_languages->site."<=".$row["value2"]." "
						.($where1? " AND (".implode(' AND ',$where1).")":'')
						.($where_param_price1?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price1)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($hit?$hit:'')
						.($new?$new:'')
						);
						
					$row["valueMin"] = DB::query_result(
						"SELECT MIN(ee.value".$this->diafan->_languages->site."+0) FROM {shop} AS s "
						." INNER JOIN {shop_param_element} AS ee ON ee.element_id=s.id"
						." INNER JOIN {shop_param_element} AS e ON e.element_id=s.id "
						.($where_cat_ids?$where_cat_ids:'')
						.($price?$price:'')
						." WHERE s.[act]='1' AND s.trash='0' AND e.param_id=".$row["id"]." " //" AND e.value".$this->diafan->_languages->site.">=".$row["value1"]." AND e.value".$this->diafan->_languages->site."<=".$row["value2"]." "
						.($where1? " AND (".implode(' AND ',$where1).")":'')
						.($where_param_price1?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price1)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($hit?$hit:'')
						.($new?$new:'')
						);
						
					/*$p2_max = DB::query_result("SELECT MAX(ee.value".$this->diafan->_languages->site."+0) FROM {shop_param_element} as ee INNER JOIN {shop} as s ON s.id=ee.element_id"
						.($cat_ids ? " INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id AND c.cat_id IN (".implode(",", $cat_ids).")" : '')
						." WHERE ee.param_id=%d",$row['id']);
					
					$p1_min = DB::query_result("SELECT MIN(ee.value".$this->diafan->_languages->site."+0) as minv FROM {shop_param_element} as ee INNER JOIN {shop} as s ON s.id=ee.element_id"
							.($cat_ids ? " INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id AND c.cat_id IN (".implode(",", $cat_ids).")" : '')	
							." WHERE ee.param_id=%d",$row['id']);
					*/
					
					break;

				case 'checkbox':
					$row["available"] = DB::query_result(
						"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
						." INNER JOIN {shop_param_element} AS ee ON ee.element_id=s.id"
						." INNER JOIN {shop_param_element} AS e ON e.element_id=s.id "
						.($where_cat_ids?$where_cat_ids:'')
						.($price?$price:'')
						." WHERE s.[act]='1' AND s.trash='0' AND e.param_id=".$row["id"]." AND e.value".$this->diafan->_languages->site."=1 "
						.($where1? " AND (".implode(' AND ',$where1).")":'')
						.($where_param_price1?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price1)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($hit?$hit:'')
						.($new?$new:'')
						);
						
					break;
				case 'select':
				case 'multiple':
					$row["select_array_available"] = DB::query_fetch_key_value(
						"SELECT p.[name], p.id FROM {shop_param_select} AS p"
						." INNER JOIN {shop_param_element} AS e ON p.param_id=e.param_id AND e.value".$this->diafan->_languages->site."=p.id"
						." INNER JOIN {shop} AS s ON e.element_id=s.id AND s.[act]='1' AND s.trash='0'"
						." INNER JOIN {shop_param_element} AS ee ON ee.element_id=s.id"
						.($where_cat_ids?$where_cat_ids:'')
						.($price?$price:'')
						." WHERE p.param_id=".$row["id"]." "
						.($where1? " AND (".implode(' AND ',$where1).")":'')
						.($where_param_price1?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id"
							." AND (((SELECT COUNT(*) FROM {shop_price_param} as pp2 INNER JOIN {shop_price} AS p2 ON pp2.price_id=p2.id WHERE p2.good_id=s.id AND pp2.param_id=".$row['id']." AND p2.trash='0')=0)"
											." OR ((SELECT COUNT(*) FROM {shop_price_param} as pp1 WHERE pp1.price_id=p1.id AND trash='0' AND pp1.param_id=".$row['id']." AND (pp1.param_value=p.id OR pp1.param_value=0))>0))"
							." AND (".implode(' AND ',$where_param_price1)."))>0)":"")
						.($article?$article:'')
						.($shop_name?$shop_name:'')
						.($brand?$brand:'')
						.($action?$action:'')
						.($hit?$hit:'')
						.($new?$new:'')
						." GROUP BY p.id ORDER BY p.sort ASC", "id", "name");
						/*foreach ($row["select_array_available"] as $key => &$value)
						{
							$value = DB::query_result(
								"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
								." INNER JOIN {shop_param_element} AS ee ON ee.element_id=s.id"
								." INNER JOIN {shop_param_element} AS e ON e.element_id=s.id "
								.($where_cat_ids?$where_cat_ids:'')
								.($price?$price:'')
								." WHERE s.[act]='1' AND s.trash='0'"// AND e.param_id=".$row["id"]." AND e.value".$this->diafan->_languages->site."=1 "
								.' AND ((SELECT COUNT(*) FROM {shop_param_element} AS ee WHERE ee.element_id = s.id AND (ee.param_id='.$row['id'].' AND ('.'ee.[value]='.$key.')))>0)'
								." AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (    (((SELECT COUNT(*) FROM {shop_price_param} as pp2 INNER JOIN {shop_price} AS p2 ON pp2.price_id=p2.id WHERE p2.good_id=s.id AND pp2.param_id=".$row['id']." AND p2.trash='0')=0)"
										." OR ((SELECT COUNT(*) FROM {shop_price_param} as pp1 WHERE pp1.price_id=p1.id AND trash='0' AND pp1.param_id=".$row['id']." AND ("."pp1.param_value=".$key." OR pp1.param_value=0))>0))     ))>0)"
								.($where1? " AND (".implode(' AND ',$where1).")":'')
								.($where_param_price1?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price1)."))>0)":"")
								.($article?$article:'')
								.($shop_name?$shop_name:'')
								.($brand?$brand:'')
								.($hit?$hit:'')
								.($new?$new:'')
								); //Подсчет количества (сильно тормозит фильтр )
						}*/
					if(empty($row["select_array"]))
					{
						unset($row);
					}
					break;
			}
		}
		
		foreach ($result["cat_ids"] as &$row)
		{
			$row["available"] = DB::query_result(
					"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					." INNER JOIN {shop_category_rel} AS c ON c.element_id=s.id" 
					.($price?$price:'')
					." WHERE s.[act]='1' AND s.trash='0' AND c.cat_id=".$row["id"]." "
					.($where? " AND (".implode(' AND ',$where).")":'')
					.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
					.($article?$article:'')
					.($shop_name?$shop_name:'')
					.($brand?$brand:'')
					.($hit?$hit:'')
					.($new?$new:'')
					);
		}
		
		
		if (!empty($result["brands"]))
		{
			
			foreach($result["brands"] as &$b)
			{
				$b["available"] = DB::query_result(
					"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					.($where_cat_ids?$where_cat_ids:'')
					.($price?$price:'')
					." WHERE s.[act]='1' AND s.trash='0' "
					.($where? " AND (".implode(' AND ',$where).")":'')
					.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
					." AND s.brand_id=".$b["id"]." "
					.($article?$article:'')
					.($shop_name?$shop_name:'')
					.($action?$action:'')
					.($hit?$hit:'')
					.($new?$new:'')
					);
			}
		}
		
		if (! empty($result["action"]))
		{
			$result["action"]["available"] = DB::query_result(
					"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					.($where_cat_ids?$where_cat_ids:'')
					.($price?$price:'')
					." WHERE s.[act]='1' AND s.trash='0' AND s.action='1' "
					.($where? " AND (".implode(' AND ',$where).")":'')
					.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
					.($article?$article:'')
					.($shop_name?$shop_name:'')
					.($brand?$brand:'')
					.($hit?$hit:'')
					.($new?$new:'')
					);
		}
		
		if (! empty($result["hit"]))
		{
			$result["hit"]["available"] = DB::query_result(
					"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					.($where_cat_ids?$where_cat_ids:'')
					.($price?$price:'')
					." WHERE s.[act]='1' AND s.trash='0' AND s.hit='1' "
					.($where? " AND (".implode(' AND ',$where).")":'')
					.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
					.($article?$article:'')
					.($shop_name?$shop_name:'')
					.($brand?$brand:'')
					.($hit?$hit:'')
					.($new?$new:'')
					);
		}
		if (! empty($result["new"]))
		{
			$result["new"]["available"] = DB::query_result(
					"SELECT COUNT(DISTINCT s.id) FROM {shop} AS s "
					.($where_cat_ids?$where_cat_ids:'')
					.($price?$price:'')
					." WHERE s.[act]='1' AND s.trash='0' AND s.new='1' "
					.($where? " AND (".implode(' AND ',$where).")":'')
					.($where_param_price?" AND ((SELECT COUNT(*) FROM {shop_price} AS p1 WHERE p1.trash='0' AND p1.good_id=s.id AND (".implode(' AND ',$where_param_price)."))>0)":"")
					.($article?$article:'')
					.($shop_name?$shop_name:'')
					.($brand?$brand:'')
					.($action?$action:'')
					.($hit?$hit:'')
					);
			
		}
		
		
		
		return $result;
	} 
	
	
	private function list_search_query_count($where_param, $where, $group, $values)
	{
		$result = DB::query("SELECT ".($group ? "DISTINCT s.id" : "COUNT(DISTINCT s.id)")." FROM {shop} AS s"
		.($this->diafan->configmodules('where_access_element') ? " LEFT JOIN {access} AS a ON a.element_id=s.id AND a.module_name='shop' AND a.element_type='element'" : "")
		.$where_param
		." WHERE s.[act]='1' AND s.trash='0'"
		.($this->diafan->configmodules('where_access_element') ? " AND (s.access='0' OR s.access='1' AND a.role_id=".$this->diafan->_users->role_id.")" : '')
		.$where
		.($group ? " GROUP BY s.id".$group : ''),
		$values);
		if($group)
		{
			$count = DB::num_rows($result);
			DB::free_result($result);
		}
		else
		{
			$count = DB::result($result);
		}
		return $count;
	}
	
	private function where(&$where, &$where_param, &$values, &$getnav, &$group)
	{
		$where = ' AND s.site_id=%d';
		
		$values[] = $this->diafan->_site->id;
		$values_param = array();

		$children=array();
		$getnav = '?action=search';
		if (!empty($_REQUEST["cat_id"]))
		{
			$this->diafan->_route->cat = (int) preg_replace("/\D/", '', $_REQUEST['cat_id']);
			$catarr = array(0);
			$getnav .='&cat_id='.$this->diafan->_route->cat;
			if ($this->diafan->_route->cat)
			{
				$children = $this->diafan->get_children($this->diafan->_route->cat, "shop_category");
				$children[] = $this->diafan->_route->cat;
				$where_param .= " INNER JOIN {shop_category_rel} AS c ON s.id=c.element_id AND c.cat_id IN (".implode(',', $children).")";
			}
		}

		if (!empty($_REQUEST["a"]) && $this->diafan->configmodules("search_article"))
		{
			$where .= " AND LOWER(REPLACE(REPLACE(s.article, ' ', ''), '-', ''))='%h'";
			$_REQUEST["a"] = $this->diafan->filter($_REQUEST, "string", "a");
			$values[] = str_replace(array(' ', '-'), '', $_REQUEST["a"]);
			$getnav .= '&a='.$_REQUEST["a"];
		}
	
		if (!empty($_REQUEST["brand"]) && $this->diafan->configmodules("search_brand"))
		{
			$brand = array();
			if(is_array($_REQUEST["brand"]))
			{
				foreach($_REQUEST["brand"] as $b)
				{
					$b = $this->diafan->filter($b, "integer");
					if($b)
					{
						$brand[] = $b;
					}
				}
			}
			else
			{
				$b = $this->diafan->filter($_REQUEST, "integer", "brand");
				if($b)
				{
					$brand[] = $b;
				}
			}
			if($brand)
			{
				$where .= " AND s.brand_id".(count($brand) == 1 ? '='.$brand[0] : ' IN ('.implode(',', $brand).')');
				$getnav .= '&brand[]='.implode('&brand[]=', $brand);
			}
		}
	
		if (!empty($_REQUEST["ac"]) && $this->diafan->configmodules("search_action"))
		{
			$where .= " AND s.action='1'";
			$getnav .= '&ac=1';
		}
	
		if (!empty($_REQUEST["hi"]) && $this->diafan->configmodules("search_hit"))
		{
			$where .= " AND s.hit='1'";
			$getnav .= '&hi=1';
		}
	
		if (!empty($_REQUEST["ne"]) && $this->diafan->configmodules("search_new"))
		{
			$where .= " AND s.new='1'";
			$getnav .= '&ne=1';
		}
	
		if (!empty($_REQUEST["pr1"]) || !empty($_REQUEST["pr2"]))
		{
			if (!empty($_REQUEST["pr1"]))
			{
				$pr1 = $this->diafan->filter($_REQUEST, "int", "pr1");
				$getnav .= '&pr1='.$pr1;
			}
			if (!empty($_REQUEST["pr2"]))
			{
				$pr2 = $this->diafan->filter($_REQUEST, "int", "pr2");
				$getnav .= '&pr2='.$pr2;
			}
			$where_param .= " INNER JOIN {shop_price} AS pr ON pr.good_id=s.id AND pr.trash='0'"
				." AND pr.date_start<=".time()." AND (pr.date_start=0 OR pr.date_finish>=".time().")"
				." AND pr.currency_id=0"
				." AND pr.role_id".($this->diafan->_users->role_id ? " IN (0,".$this->diafan->_users->role_id.")" : "=0")
				." AND (pr.person='0'".($this->person_discount_ids ? " OR pr.discount_id IN(".implode(",", $this->person_discount_ids).")" : "").")";
			$group = ", pr.price_id HAVING"
				.(!empty($_REQUEST["pr1"]) ? " MIN(ROUND(pr.price))>=".$pr1 : '')
				.(!empty($_REQUEST["pr2"]) ? (!empty($_REQUEST["pr1"]) ? " AND" : "")." MIN(pr.price)<=".$pr2 : '');
		}
		else
		{
			$where_param .= " LEFT JOIN {shop_price} AS pr ON pr.good_id=s.id AND pr.trash='0'"
				." AND pr.date_start<=".time()." AND (pr.date_start=0 OR pr.date_finish>=".time().")"
				." AND pr.currency_id=0"
				." AND pr.role_id".($this->diafan->_users->role_id ? " IN (0,".$this->diafan->_users->role_id.")" : "=0")
				." AND (pr.person='0'".($this->person_discount_ids ? " OR pr.discount_id IN(".implode(",", $this->person_discount_ids).")" : "").")";
		}
		$rows = DB::query_fetch_all("SELECT DISTINCT(p.id), p.type, p.required FROM {shop_param} as p "
				." INNER JOIN {shop_param_category_rel} AS c ON p.id=c.element_id "
				.($this->diafan->configmodules("cat",'shop') ? " AND (c.cat_id in (%s) OR c.cat_id=0)" : "")
				." WHERE p.search='1' AND p.trash='0' ORDER BY p.sort ASC", (!empty($children)?implode(',',$children):(empty($_REQUEST["cat_id"])?"0":$_REQUEST["cat_id"])));
		foreach ($rows as $row)
		{
			if ($row["type"] == 'date' && (!empty($_REQUEST["p".$row["id"]."_1"]) || !empty($_REQUEST["p".$row["id"]."_2"])))
			{
				$where_param .= " INNER JOIN {shop_param_element} AS pe".$row["id"]." ON pe".$row["id"].".element_id=s.id AND pe".$row["id"].".param_id='%d'"
					." AND pe".$row["id"].".trash='0'";
				$values_param[] = $row["id"];
				if (!empty($_REQUEST["p".$row["id"]."_1"]))
				{
					$where_param .= " AND pe".$row["id"].".value".$this->diafan->_languages->site.">='%s'";
					$values_param[] = $this->diafan->formate_in_date($_REQUEST["p".$row["id"]."_1"]);
					$getnav .= '&p'.$row["id"].'_1='.$this->diafan->filter($_REQUEST, "url", "p".$row["id"]."_1");
				}
				if (!empty($_REQUEST["p".$row["id"]."_2"]))
				{
					$where_param .= " AND pe".$row["id"].".value".$this->diafan->_languages->site."<='%s'";
					$values_param[] = $this->diafan->formate_in_date($_REQUEST["p".$row["id"]."_2"]);
					$getnav .= '&p'.$row["id"].'_2='.$this->diafan->filter($_REQUEST, "url", "p".$row["id"]."_2");
				}
			}
			elseif ($row["type"] == 'datetime' && (!empty($_REQUEST["p".$row["id"]."_1"]) || !empty($_REQUEST["p".$row["id"]."_2"])))
			{
				$where_param .= " INNER JOIN {shop_param_element} AS pe".$row["id"]." ON pe".$row["id"].".element_id=s.id AND pe".$row["id"].".param_id='%d'"
					." AND pe".$row["id"].".trash='0'";
				$values_param[] = $row["id"];
				if (!empty($_REQUEST["p".$row["id"]."_1"]))
				{
					$where_param .= " AND pe".$row["id"].".value".$this->diafan->_languages->site.">='%s'";
					$values_param[] = $this->diafan->formate_in_datetime($_REQUEST["p".$row["id"]."_1"]);
					$getnav .= '&p'.$row["id"].'_1='.$this->diafan->filter($_REQUEST, "url", "p".$row["id"]."_1");
				}
				if (!empty($_REQUEST["p".$row["id"]."_2"]))
				{
					$where_param .= " AND pe".$row["id"].".value".$this->diafan->_languages->site."<='%s'";
					$values_param[] = $this->diafan->formate_in_datetime($_REQUEST["p".$row["id"]."_2"]);
					$getnav .= '&p'.$row["id"].'_2='.$this->diafan->filter($_REQUEST, "url", "p".$row["id"]."_2");
				}
			}
			elseif ($row["type"] == 'numtext' && (!empty($_REQUEST["p".$row["id"]."_2"]) || !empty($_REQUEST["p".$row["id"]."_1"])))
			{
				$val1 = $this->diafan->filter($_REQUEST, "float", "p".$row["id"]."_1");
				$val2 = $this->diafan->filter($_REQUEST, "float", "p".$row["id"]."_2");
				$where_param .= " INNER JOIN {shop_param_element} AS pe".$row["id"]." ON pe".$row["id"].".element_id=s.id AND pe".$row["id"].".param_id='%d'"
					." AND pe".$row["id"].".trash='0'"
					.($val1 ? " AND pe".$row["id"].".value".$this->diafan->_languages->site.">=%f" : '')
					.($val2 ? " AND pe".$row["id"].".value".$this->diafan->_languages->site."<=%f" : '')
				;
				$values_param[] = $row["id"];
				if ($val1)
				{
					$values_param[] = $val1;
					$getnav .= '&p'.$row["id"].'_1='.$val1;
				}
				if ($val2)
				{
					$values_param[] = $val2;
					$getnav .= '&p'.$row["id"].'_2='.$val2;
				}
			}
			elseif ($row["type"] == 'checkbox' && !empty($_REQUEST["p".$row["id"]]))
			{
				$where_param .= " INNER JOIN {shop_param_element} AS pe".$row["id"]." ON pe".$row["id"].".element_id=s.id AND pe".$row["id"].".param_id='%d'"
					." AND pe".$row["id"].".trash='0' AND pe".$row["id"].".value".$this->diafan->_languages->site."='1'";
				$values_param[] = $row["id"];
				$getnav .= '&p'.$row["id"].'=1';
			}
			elseif (($row["type"] == 'select' || $row["type"] == 'multiple') && !empty($_REQUEST["p".$row["id"]]))
			{
				if (!is_array($_REQUEST["p".$row["id"]]))
				{
					$val = $this->diafan->filter($_REQUEST, "int", "p".$row["id"]);
					$getnav .= '&p'.$row["id"].'='.$val;
					$vals = array($val);
				}
				else
				{
					$vals = array();
					foreach ($_REQUEST["p".$row["id"]] as $val)
					{
						if ($val)
						{
							$val = intval($val);
							$vals[] = $val;
							$getnav .= '&p'.$row["id"].'[]='.$val;
						}
					}
				}
				if (!empty($vals))
				{
					if ($row["required"])
					{
						$where_param .= " INNER JOIN {shop_price_param} AS prp".$row["id"]." ON prp".$row["id"].".price_id=pr.price_id";
						$where .= " AND prp".$row["id"].".param_id=".$row["id"]." AND prp".$row["id"].".param_value IN (".implode(", ", $vals).",0) AND pe".$row["id"].".param_id=".$row["id"];
						if(empty($first_required_param))
						{
							$first_required_param = " AND prp".$row["id"].".price_id=";
						}
						else
						{
							$where .= $first_required_param."prp".$row["id"].".price_id";
						}
					}
					$where_param .= " ".($row["required"] ? "LEFT" : "INNER")." JOIN {shop_param_element} AS pe".$row["id"]." ON pe".$row["id"].".element_id=s.id AND pe".$row["id"].".param_id='%d'"
					." AND pe".$row["id"].".trash='0' AND pe".$row["id"].".value".$this->diafan->_languages->site." IN (".implode(", ", $vals).")";
					$values_param[] = $row["id"];
				}
			}
		}
	
		$values = array_merge($values_param, $values);
	}
}