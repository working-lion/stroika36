<?php
/**
 * Модель
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
 * Specification_model
 */
class Specification_model extends Model
{
    /*
	 * @var array информация, записанная в спецификацию
	 */
    private $specification = 'no_check';

    /*
     * @var integer общая стоимость товаров, находящихся в спецификации
     */
    private $summ;

    /*
     * @var integer общее количество товаров, находящихся в спецификации
     */
    private $count;


    /**
     * Генерирует данные для страницы статьи
     *
     * @return array
     */
    public function id()
    {
        $time = mktime(23, 59, 0, date("m"), date("d"), date("Y"));

        //кеширование
        $cache_meta = array(
            "name"    => "show",
            "show"    => $this->diafan->_route->show,
            "lang_id" => _LANG,
            "site_id" => $this->diafan->_site->id,
            "access" => ($this->diafan->configmodules('where_access_element')
                || $this->diafan->configmodules('where_access_cat')
                ? $this->diafan->_users->role_id : 0),
            "time"    => $time
        );

        $specification_id = $this->diafan->_route->show;

        if (! $this->result = $this->diafan->_cache->get($cache_meta, $this->diafan->_site->module))
        {
            $row = $this->id_query($time);

            if (empty($row))
            {
                Custom::inc('includes/404.php');
            }

            if (! empty($row['access']) && ! $this->access($row['id']))
            {
                Custom::inc('includes/403.php');
            }

            /*
             * TODO: определить
             * По аналогии с новостями эти данные должны быть в таблице со спецификациями.
             * */

            $row["theme"] = '';
            $row["view"] = '';
            $row["timeedit"] = $row["created"];
            $row["title_meta"] = $row["name"];
            $row["keywords"] = '';
            $row["descr"] = '';

            $row["all_specifications"] = "/specifications/";

            $this->result = $row;

            $this->result["date"] = $this->format_date($row['created']);

            $this->result["breadcrumb"] = $this->get_breadcrumb();
            
            /*
             * TODO: получить данные о товарах по id спицификации (как для корзины):
             * 1) получаем записи из {specifications_goods} по specification_id
             * 2) собираем данные о товарах {shops}
             * */
            
            //$this->result["rows"] = $this->get_goods_by_spec_id($specification_id);

            /*
             * TODO: думаю, можно обойтись без init
             * */
            $this->init();

            $this->form_table();

            /*echo '<pre style="display: none">';
            print_r($this->specification);
            echo '</pre>';*/

            if($row["act"])
            {
                //сохранение кеша
                $this->diafan->_cache->save($this->result, $cache_meta, $this->diafan->_site->module);
            }
        }

        $this->format_data_element($this->result);

        $this->meta($row);

        $this->theme_view_element($row);

        foreach ($this->result["breadcrumb"] as $k => &$b)
        {
            if ($k == 0)
                continue;

            $b["name"] = $this->diafan->_useradmin->get($b["name"], 'name', $b["id"], 'specifications', _LANG);
        }

    }

    /**
     * Инициализация спецификации
     *
     * @return void
     */
    private function init()
    {
        if($this->specification === 'no_check')
        {
            $this->specification = array();

            if($this->diafan->_users->id && $this->diafan->_route->show)
            {
                $rows = DB::query_fetch_all("SELECT * FROM {specifications_goods} WHERE specification_id=%d AND trash='0'",
                    $this->diafan->_route->show);

                foreach ($rows as $row)
                {
                    $this->specification[$row["good_id"]][$row["param"]][$row["additional_cost"]]["price_id"] = $row["price_id"];
                    $this->specification[$row["good_id"]][$row["param"]][$row["additional_cost"]]["count"] = $row["count"];
                    $this->specification[$row["good_id"]][$row["param"]][$row["additional_cost"]]["is_file"] = $row["is_file"];
                }

                if(! isset($_SESSION["specification_summ"]) && ! isset($_SESSION["specification_count"]))
                {
                    $this->recalc();
                }
                else
                {
                    if($this->specification && empty($_SESSION["specification_count"]))
                    {
                        $this->recalc();
                    }
                    if(! empty($_SESSION["specification"]))
                    {
                        foreach ($_SESSION["specification"] as $id => $rows)
                        {
                            foreach ($rows as $param => $rs)
                            {
                                foreach($rs as $additional_cost => $row)
                                {
                                    $this->set($row, $id, $param, $additional_cost);
                                }
                            }
                        }

                        /*
                         * TODO: нужна ли запись?
                         * */

                        //$this->write();

                        //unset($_SESSION["specification"]);
                    }
                    else
                    {
                        $this->summ = $_SESSION["specification_summ"];
                        $this->count = $_SESSION["specification_count"];
                    }
                }
            }
            else
            {
                $this->specification = ! empty($_SESSION["specification"]) ? $_SESSION["specification"] : array();
                $this->summ = ! empty($_SESSION["specification_summ"]) ? $_SESSION["specification_summ"] : 0;
                $this->count = ! empty($_SESSION["specification_count"]) ? $_SESSION["specification_count"] : 0;
            }
        }
    }

    /**
     * Записывает данные в спецификацию
     *
     * @param mixed $value данные
     * @param integer $id номер товра
     * @param mixed $param характеристики товара, учитываемые в заказе
     * @param mixed $additional_cost сопутствующие услуги
     * @param string $name_info тип информации (count - количество, is_file - это товар-файл)
     * @return void
     */
    public function set($value = array(), $id = 0, $param = false, $additional_cost = false, $name_info = '')
    {
        if(! $id)
        {
            $this->specification = $value;
            return;
        }

        if($param === false)
        {
            if($value)
            {
                $this->specification[$id] = $value;
            }
            else
            {
                unset($this->specification[$id]);
            }
            return;
        }

        if(is_array($param))
        {
            $params = $param;
            asort($param);
            $param = serialize($param);
        }
        else
        {
            $params = unserialize($param);
        }

        $price = $this->diafan->_shop->price_get($id, $params);

        if (! $price && ! $this->diafan->configmodules('buy_empty_price', "shop"))
        {
            unset($this->specification[$id][$param][$additional_cost]);

            if(! $this->specification[$id][$param])
            {
                unset($this->specification[$id][$param]);
            }

            if(! $this->specification[$id])
            {
                unset($this->specification[$id]);
            }

            return $this->diafan->_('Товара с заданными параметрами не существует.');
        }

        if(! $name_info)
        {
            if(! $value)
            {
                unset($this->specification[$id][$param][$additional_cost]);
                if(! $this->specification[$id][$param])
                {
                    unset($this->specification[$id][$param]);
                }
                if(! $this->specification[$id])
                {
                    unset($this->specification[$id]);
                }
                return;
            }
            else
            {
                $this->specification[$id][$param][$additional_cost]["is_file"] = $value["is_file"] ? true : false;
                $name_info = "count";
                $value = $value["count"];
            }
        }

        if($name_info == "count")
        {
            $value = preg_replace('/[^0-9\.\-]+/', '', $value);

            if($value == 0)
            {
                unset($this->specification[$id][$param][$additional_cost]);
                if(! $this->specification[$id][$param])
                {
                    unset($this->specification[$id][$param]);
                }
                if(! $this->specification[$id])
                {
                    unset($this->specification[$id]);
                }
                return;
            }
            //товар-файл => можно купить только 1 товар
            if($this->specification[$id][$param][$additional_cost]["is_file"] && $value > 1)
            {
                return $this->diafan->_('Файл уже добавлен в корзину.');
            }
            if($this->diafan->configmodules('use_count_goods', 'shop'))
            {
                $count_price_id = 0;

                foreach ($this->specification as $check_id => $check_array)
                {
                    foreach ($check_array as $check_param => $check_rows)
                    {
                        foreach ($check_rows as $check_additional_cost => $check_row)
                        {
                            if(($param != $check_param || $check_additional_cost != $additional_cost) && $price["price_id"] == $check_row["price_id"])
                            {
                                $count_price_id += $check_row["count"];
                            }
                        }
                    }
                }
                if ($count_price_id + $value > $price["count_goods"])
                {
                    return $this->diafan->_('Извините, Вы запросили больше товара, чем имеется на складе.', false);
                }
            }
        }
        $this->specification[$id][$param][$additional_cost][$name_info] = $value;
    }

    /**
     * Записывает информацию о спецификации в хранилище
     *
     * @return void
     */
    public function write()
    {
        $this->recalc();

        if($this->diafan->_users->id)
        {
            $old_cart = array();
            $rows = DB::query_fetch_all("SELECT * FROM {specifications_goods} WHERE specification_id=%d AND trash='0'",
                $this->diafan->_route->show);
            foreach ($rows as $row)
            {
                $old_cart[$row["good_id"]][$row["param"]][$row["additional_cost"]] = $row;
            }
            foreach ($this->specification as $id => $array)
            {
                foreach ($array as $param => $rows)
                {
                    foreach ($rows as $additional_cost => $row)
                    {
                        if(! empty($old_cart[$id][$param][$additional_cost]))
                        {
                            if($row["count"] != $old_cart[$id][$param][$additional_cost]["count"])
                            {
                                DB::query("UPDATE {specifications_goods} SET created=%d, `count`=%f WHERE id=%d",
                                    time(), $row["count"], $old_cart[$id][$param][$additional_cost]["id"]);
                            }
                            unset($old_cart[$id][$param][$additional_cost]);
                        }
                        else
                        {
                            DB::query(
                                "INSERT INTO {specifications_goods} (good_id, created, count, param, additional_cost, is_file, user_id, price_id, specification_id)"
                                ." VALUES (%d, %d, %f, '%s', '%s', '%d', %d, %d, %d)",
                                $id, time(), $row["count"], $param, $additional_cost, $row["is_file"],
                                $this->diafan->_users->id, $row["price_id"], $this->diafan->_route->show);
                        }
                    }
                }
            }
            foreach ($old_cart as $id => $as)
            {
                foreach ($as as $rows)
                {
                    foreach ($rows as $row)
                    {
                        DB::query("DELETE FROM {specifications_goods} WHERE id=%d", $row["id"]);
                    }
                }
            }
        }
        else
        {
            $_SESSION["specification"] = $this->specification;
        }
    }

    /**
     * Пересчитывает количество товаров в спецификации, общую стоимость и стоимость с учетом скидки
     *
     * @return void
     */
    private function recalc()
    {
        $summ = 0;
        $summ_discount = 0;
        $count = 0;
        foreach ($this->specification as $good_id => $array)
        {
            foreach ($array as $param => $as)
            {
                foreach ($as as $additional_cost => $c)
                {
                    $params = unserialize($param);
                    if($price = $this->diafan->_shop->price_get($good_id, $params))
                    {
                        $price["price"] = $this->diafan->_shop->price_format($price["price"], true);
                    }
                    if(! $price && ! $this->diafan->configmodules('buy_empty_price', "shop"))
                    {
                        unset($this->specification[$good_id][$param][$additional_cost]);

                        if(! $this->specification[$good_id][$param])
                        {
                            unset($this->specification[$good_id][$param]);
                        }
                        if(! $this->specification[$good_id])
                        {
                            unset($this->specification[$good_id]);
                        }
                        continue;
                    }

                    if($c["count"] > 0)
                    {
                        $summ += $price["price"] * $c["count"];

                        if($additional_cost)
                        {
                            $additional_costs = DB::query_fetch_all("SELECT a.id, a.[name], a.percent, a.price, a.amount, r.element_id, r.summ FROM {shop_additional_cost} AS a INNER JOIN {shop_additional_cost_rel} AS r ON r.additional_cost_id=a.id WHERE r.element_id=%d AND a.id IN (%s) AND a.trash='0'", $good_id, $additional_cost);
                            foreach($additional_costs as $a_c)
                            {
                                $a_c_price = 0;
                                if($a_c["amount"] && $a_c["amount"] <= $price["price"])
                                {
                                    $a_c_price = 0;
                                }
                                elseif($a_c["percent"])
                                {
                                    $a_c_price = ($price["price"] * $a_c["percent"]) / 100;
                                }
                                elseif(! $a_c["summ"])
                                {
                                    $a_c_price = $a_c["price"];
                                }
                                else
                                {
                                    $a_c_price = $a_c["summ"];
                                }
                                $summ += $a_c_price * $c["count"];
                            }
                        }
                        $count += $c["count"];
                    }
                    $this->specification[$good_id][$param][$additional_cost]["price_id"] = $price["price_id"];
                }
            }
        }

        $this->count = $count;
        $this->summ = $summ;

        $_SESSION["specification_summ"] = $this->summ;
        $_SESSION["specification_count"] = $this->count;
    }

    /**
     * Генерирует таблицу товаров в спецификации
     *
     * @return array
     */
    public function form_table()
    {
        $this->result["currency"] = $this->diafan->configmodules("currency", "shop");
        $this->result["summ"] = 0;
        $this->result["count"] = 0;
        $this->result["discount"] = false;

        // корзина / спецификация
        //$cart = $this->diafan->_cart->get();

        // массив, как тот, что приходит из _cart->get()
        /*
         * TODO: подумать, как обойтись без init()
         * */
        $cart = $this->specification;

        if (! $cart)
        {
            $this->result["shop_link"] = $this->diafan->_route->module('shop');
            return $this->result;
        }

        $k = 0;
        foreach ($cart as $good_id => $array)
        {
            if (! $row = DB::query_fetch_array("SELECT id, [name], article, cat_id, site_id, [measure_unit] FROM {shop} WHERE [act]='1' AND id = %d AND trash='0' LIMIT 1", $good_id))
            {
                continue;
            }

            $link = $this->diafan->_route->link($row["site_id"], $row["id"], "shop");
            $img = $this->diafan->_images->get('medium', $good_id, 'shop', 'element', $row["site_id"], $row["name"]);

            foreach ($array as $param => &$ar)
            {
                $query = array();
                $params = unserialize($param);

                foreach ($params as $id => $value)
                {
                    $query[] = 'p'.$id.'='.$value;
                    if (empty($param_names[$id]))
                    {
                        $param_names[$id] = DB::query_result("SELECT [name] FROM {shop_param} WHERE id=%d LIMIT 1", $id);
                    }
                }
                $row_price = $this->diafan->_shop->price_get($good_id, $params);
                if(! $row_price && ! $this->diafan->configmodules('buy_empty_price', "shop"))
                {
                    $this->diafan->_cart->write();
                    continue;
                }
                if($this->diafan->configmodules("use_count_goods", "shop"))
                {
                    $count = 0;
                    foreach ($ar as $additional_cost => &$c)
                    {
                        if($c["count"] == -1)
                        {
                            $c["count"] = 0;
                        }
                        $count += $c["count"];
                    }
                    if($row_price["count_goods"] < $count)
                    {
                        $r = $count - $row_price["count_goods"];

                        foreach ($ar as $additional_cost => &$c)
                        {
                            if($c["count"] > 0 && $r)
                            {
                                if($c["count"] > $r)
                                {
                                    $c["count"] = $c["count"] - $r;
                                    $r = 0;
                                }
                                else
                                {
                                    $c["count"] = -1;
                                    $r -= $c["count"];
                                }
                            }
                            $this->diafan->_cart->set($c["count"], $good_id, $param, $additional_cost, "count");
                        }
                        $this->diafan->_cart->write();
                    }
                }
                foreach ($ar as $additional_cost => &$c)
                {
                    $this->result["rows"][$k]["name_only"] = $row["name"];
                    $this->result["rows"][$k]["name"] = $row["name"];
                    $this->result["rows"][$k]["article"] = $row["article"];
                    $this->result["rows"][$k]["measure_unit"] = $row["measure_unit"];
                    if(! empty($this->result["rows"][$k]["measure_unit"]))
                    {
                        $this->result["measure_unit"] = true;
                    }
                    $this->result["rows"][$k]["link"] = $link;

                    if($c["count"] < 0)
                    {
                        $c["count"] = 0;
                    }

                    if($row["cat_id"])
                    {
                        if (empty($select_cats[$row["cat_id"]]))
                        {
                            $select_cats[$row["cat_id"]] = array(
                                "name" => DB::query_result("SELECT [name] FROM {shop_category} WHERE id=%d LIMIT 1", $row["cat_id"]),
                                "link" => $this->diafan->_route->link($row["site_id"], $row["cat_id"], "shop", 'cat')
                            );
                        }
                        $this->result["rows"][$k]["cat"]["name"] = $select_cats[$row["cat_id"]]["name"];
                        $this->result["rows"][$k]["cat"]["link"] = $select_cats[$row["cat_id"]]["link"];
                    }
                    foreach ($params as $id => $value)
                    {
                        if (empty($select_names[$id][$value]))
                        {
                            $select_names[$id][$value] =
                                DB::query_result("SELECT [name] FROM {shop_param_select} WHERE param_id=%d AND id=%d LIMIT 1", $id, $value);
                        }

                        $this->result["rows"][$k]["name"] .= ', '.$param_names[$id].': '.$select_names[$id][$value];
                    }

                    $price = $row_price["price"];
                    //$discount_price = $row_price["discount_summ"];

                    $this->result["rows"][$k]["link"] .= !empty($query) ? '?'.implode('&', $query) : '';
                    $this->result["rows"][$k]["count"] = $c["count"];
                    if ($img)
                    {
                        if($price_image_rel = DB::query_result("SELECT image_id FROM {shop_price_image_rel} WHERE price_id=%d LIMIT 1", $row_price["price_id"]))
                        {
                            foreach ($img as $i)
                            {
                                if($i["id"] == $price_image_rel)
                                {
                                    $this->result["rows"][$k]["img"] = $i;
                                }
                            }
                        }
                        if(empty($this->result["rows"][$k]["img"]))
                        {
                            $this->result["rows"][$k]["img"] = $img[0];
                        }
                    }

                    $this->result["rows"][$k]["additional_cost"] = array();
                    if($additional_cost)
                    {
                        $additional_cost_rels = DB::query_fetch_all("SELECT a.id, a.[name], a.percent, a.price, a.amount, r.element_id, r.summ FROM {shop_additional_cost} AS a INNER JOIN {shop_additional_cost_rel} AS r ON r.additional_cost_id=a.id WHERE r.element_id=%d AND a.id IN (%s) AND a.trash='0'", $good_id, $additional_cost);
                        foreach($additional_cost_rels as $a_c_rel)
                        {
                            if($a_c_rel["amount"] && $a_c_rel["amount"] <= $row_price["price"])
                            {
                                $a_c_rel["summ"] = 0;
                            }
                            elseif($a_c_rel["percent"])
                            {
                                $a_c_rel["summ"] = ($row_price["price"] * $a_c_rel["percent"]) / 100;
                            }
                            elseif(! $a_c_rel["summ"])
                            {
                                $a_c_rel["summ"] = $a_c_rel["price"];
                            }
                            if($a_c_rel["summ"])
                            {
                                $a_c_rel["format_summ"] = $this->diafan->_shop->price_format($a_c_rel["summ"]);
                            }
                            $price += $a_c_rel["summ"];
                            $this->result["rows"][$k]["additional_cost"][] = $a_c_rel;
                        }
                    }

                    $this->result["rows"][$k]["id"] = $row["id"].'_'.str_replace(array('{',':',';','}',' ','"',"'"), '', $param).'_'.$additional_cost;
                    $this->result["rows"][$k]["price"] = $this->diafan->_shop->price_format($price);
                    $this->result["rows"][$k]["summ"] = $this->diafan->_shop->price_format($price * $c["count"]);

                    $this->result["rows"][$k]["old_price"] = $row_price["old_price"] ? $this->diafan->_shop->price_format($row_price["old_price"]) : 0;
                    $this->result["rows"][$k]["discount"] = 0;
                    if($row_price["discount_id"])
                    {
                        if(! isset($cache["discount"][$row_price["discount_id"]]))
                        {
                            $cache["discount"][$row_price["discount_id"]] = DB::query_fetch_array("SELECT discount, deduction FROM {shop_discount} WHERE id=%d LIMIT 1", $row_price["discount_id"]);
                        }
                        $discount = $cache["discount"][$row_price["discount_id"]];
                        $this->result["discount"] = true;
                        if(! empty($discount["deduction"]))
                        {
                            $this->result["rows"][$k]["discount"] = $discount["deduction"].' '.$this->diafan->configmodules("currency", "shop");
                        }
                        else
                        {
                            $this->result["rows"][$k]["discount"] = $discount["discount"].' %';
                        }
                        if(! empty($discount["deduction"]))
                        {
                            $this->result["rows"][$k]["discount_summ"] = $discount["deduction"];
                        }
                        else
                        {
                            $cur_discount = (int) $this->result["rows"][$k]["discount"];

                            $this->result["rows"][$k]["discount_summ"]
                                = $this->diafan->_shop->price_format($row_price["price"]
                                / (100 - $cur_discount)
                                * $cur_discount);
                        }
                    }
                    elseif($row_price["old_price"])
                    {
                        $this->result["discount"] = true;
                        $this->result["rows"][$k]["discount"] = $this->diafan->_shop->price_format($row_price["old_price"] - $row_price["price"]).' '.$this->diafan->configmodules("currency", "shop");
                    }

                    if($c["count"] > 0)
                    {
                        $this->result["summ"] += $price * $c["count"];
                        $this->result["count"] += $c["count"];
                    }
                    $k++;
                }
            }
        }

        $this->result["summ_goods"] = $this->result["summ"];

        if(! $this->result["count"])
        {
            return $this->result;
        }

        /*
         * TODO: вычисление скидки
         * */

        /*$order_summ = 0;
        if($this->diafan->_users->id)
        {
            $order_summ = DB::query_result("SELECT SUM(summ) FROM {shop_order} WHERE user_id=%d AND (status='1' OR status='3')", $this->diafan->_users->id);
        }

        //скидка на общую сумму заказа
        $person_discount_ids = $this->diafan->_shop->price_get_person_discounts();
        $rows = DB::query_fetch_all("SELECT id, discount, amount, deduction, threshold, threshold_cumulative FROM"
            ." {shop_discount} WHERE act='1' AND trash='0' AND (threshold_cumulative>0 OR threshold>0)"
            ." AND role_id".($this->diafan->_users->role_id ? ' IN (0, '.$this->diafan->_users->role_id.')' : '=0')
            ." AND (person='0'".($person_discount_ids ? " OR id IN(".implode(",", $person_discount_ids).")" : "").")"
            ." AND date_start<=%d AND (date_finish=0 OR date_finish>=%d) ORDER BY threshold_cumulative ASC, threshold ASC", time(), time()
        );


        foreach ($rows as $row)
        {
            if($row["threshold"] && $row["threshold"] <= $this->result["summ_goods"]  || $row["threshold_cumulative"] && $row["threshold_cumulative"] <= $order_summ)
            {
                if($row['deduction'])
                {
                    if($row['deduction'] < $this->result["summ_goods"])
                    {
                        $row["discount_summ"] = $row["deduction"];
                    }
                    else
                    {
                        $row["discount_summ"] = 0;
                    }
                }
                else
                {
                    $row["discount_summ"] = $this->result["summ_goods"] * $row["discount"] / 100;
                }
                if(empty($this->result["discount_total"]) || $this->result["discount_total"]["discount_summ"] < $row["discount_summ"])
                {
                    $this->result["discount_total"] = $row;
                }
            }
            elseif($row["threshold"])
            {
                if($row['deduction'])
                {
                    $row["discount_summ"] = $row["threshold"] - $row["deduction"];
                }
                else
                {
                    $row["discount_summ"] = $row["threshold"] * $row["discount"] / 100;
                }
                if(empty($this->result["discount_next"]) || $this->result["discount_next"]["discount_summ"] <= $row["discount_summ"] && $this->result["discount_next"]["threshold"] >= $row["threshold"])
                {
                    if($row["deduction"])
                    {
                        $row["discount"] = $row['deduction'].' '.$this->diafan->configmodules('currency', 'shop');
                    }
                    else
                    {
                        $row["discount"] .= '%';
                    }
                    $row["summ"] = $this->diafan->_shop->price_format($row["threshold"] - $this->result["summ_goods"]).' '.$this->diafan->configmodules('currency',  'shop');
                    $this->result["discount_next"] = $row;
                }
            }
        }
        if(! empty($this->result["discount_total"]))
        {
            $this->result["old_summ_goods"] = $this->diafan->_shop->price_format($this->result["summ_goods"]);
            $this->result["summ_goods"] = $this->result["summ_goods"] - $this->result["discount_total"]["discount_summ"];
            $this->result["summ"] = $this->result["summ"] - $this->result["discount_total"]["discount_summ"];
            if($this->result["discount_total"]["deduction"])
            {
                $this->result["discount_total"]["deduction"] = $this->diafan->_shop->price_format($this->result["discount_total"]["deduction"]);
                $this->result["discount_total"]["discount"] = $this->result["discount_total"]["deduction"].' '.$this->diafan->configmodules('currency',  'shop');
            }
            else
            {
                $this->result["discount_total"]["discount"] .= '%';
            }
            $this->result["discount"] = true;
        }*/

        // дополнительно
        $this->result["cart_additional_cost"] = ! empty($_SESSION["cart_additional_cost"]) ? $_SESSION["cart_additional_cost"] : array();
        $this->result["additional_cost"] = DB::query_fetch_all("SELECT id, [name], price, percent, [text], amount, required FROM {shop_additional_cost} WHERE [act]='1' AND trash='0' AND shop_rel='0' ORDER by sort ASC");
        foreach ($this->result["additional_cost"] as &$row)
        {
            $row["summ"] = $row['price'];
            if($row['percent'])
            {
                $row["summ"] = $this->result["summ_goods"] * $row['percent'] / 100;
            }
            if (! empty($row['amount']))
            {
                if ($row['amount'] < $this->result["summ_goods"])
                {
                    $row["summ"] = 0;
                }
            }
            if (in_array($row["id"], $this->result["cart_additional_cost"]) || $row["required"])
            {
                $this->result["summ"] += $row['summ'];
            }
            $row["summ"] = $this->diafan->_shop->price_format($row["summ"]);
        }

        // способы доставки
        if($this->diafan->configmodules('tax', 'shop'))
        {
            $this->result["tax"] = $this->diafan->_shop->price_format($this->result["summ"] * $this->diafan->configmodules('tax', 'shop') / (100 + $this->diafan->configmodules('tax', 'shop')));
            $this->result["tax_name"] = $this->diafan->configmodules('tax_name', 'shop');
        }
        $this->result["summ"] = $this->diafan->_shop->price_format($this->result["summ"]);
        $this->result["summ_goods"] = ! empty($this->result["summ_goods"]) ? $this->diafan->_shop->price_format($this->result["summ_goods"]) : 0;

        return $this->result;
    }

    /**
     * Получает из базы данных данные о текущем элементе для страницы элемента
     *
     * @param integer $time текущее время, округленное до минут, в формате UNIX
     * @return array
     */
    private function id_query($time)
    {
        $row = DB::query_fetch_array(
            "SELECT id, [name], [text], [act], created, site_id"
            ." FROM {specifications}"
            ." WHERE id=%d AND trash='0' AND site_id=%d AND created<%d"
            ." LIMIT 1",
            $this->diafan->_route->show, $this->diafan->_site->id, $time);
        return $row;
    }


    /**
     * Генерирует данные для списка всех спецификаций
     *
     * @return array
     */
    public function list_()
    {
        if ($this->diafan->_route->cat)
        {
            Custom::inc('includes/404.php');
        }

        $time = mktime(23, 59, 0, date("m"), date("d"), date("Y"));

        $cache_meta = array(
            "name"     => "list",
            "lang_id" => _LANG,
            "page"     => $this->diafan->_route->page > 1 ? $this->diafan->_route->page : 1,
            "site_id"  => $this->diafan->_site->id,
            "time"     => $time,
            "access" => ($this->diafan->configmodules('where_access_element')
                || $this->diafan->configmodules('where_access_cat')
                ? $this->diafan->_users->role_id : 0),
        );

        //кеширование
        if (!$this->result = $this->diafan->_cache->get($cache_meta, $this->diafan->_site->module))
        {
            ////navigation//
            $this->diafan->_paginator->nen = $this->list_query_count($time);

            $this->result["paginator"] = $this->diafan->_paginator->get();
            ////navigation///

            $this->result["rows"] = $this->list_query($time);

            //сохранение кеша
            $this->diafan->_cache->save($this->result, $cache_meta, $this->diafan->_site->module);
        }

        foreach ($this->result["rows"] as &$row)
        {
            $this->format_data_element($row);
        }

        $this->theme_view();

        $this->result["show_more"] = $this->diafan->_tpl->get('show_more', 'paginator', $this->result["paginator"]);
        $this->result["paginator"] = $this->diafan->_tpl->get('get', 'paginator', $this->result["paginator"]);
    }

    /**
     * Получает из базы данных общее количество спецификаций
     *
     * @param integer $time текущее время, округленное до минут, в формате UNIX
     * @return integer
     */
    private function list_query_count($time)
    {
        $count = DB::query_result(
            "SELECT COUNT(DISTINCT e.id) FROM {specifications} AS e WHERE e.user_id=%d",
            $this->diafan->_users->id
        );

        return $count;
    }

    /**
     * Получает из базы данных элементы на одной странице
     *
     * @param integer $time текущее время, округленное до минут, в формате UNIX
     * @return array
     */
    private function list_query($time)
    {
        $rows = DB::query_range_fetch_all(
            "SELECT e.id, e.created, e.[name], e.[text], e.site_id FROM {specifications} AS e"
            . " WHERE created<%d AND trash='0' ORDER BY created DESC",
            $time, $this->diafan->_paginator->polog, $this->diafan->_paginator->nastr
        );

        return $rows;
    }

    /**
     * Форматирование данных о элементе для шаблона вне зоны кэша
     *
     * @return void
     */
    public function format_data_element(&$row)
    {
        if (! empty($row["name"]))
        {
            $row["name"] = $this->diafan->_useradmin->get($row["name"], 'name', $row["id"], 'specifications', _LANG);
        }
        if(! empty($row["description"]))
        {
            $row["description"] = $this->diafan->_useradmin->get($this->diafan->_tpl->htmleditor($row["description"])
                , 'description', $row["id"], 'specifications', _LANG);
        }
        if (! empty($row["created"]))
        {
            $row['date'] = $this->format_date($row['created']);
        }

        $row["link"] = $this->diafan->_route->link($row["site_id"], $row["id"], "specification");
    }
}