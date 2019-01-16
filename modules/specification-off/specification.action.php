<?php
/**
 * Обрабатывает полученные данные из формы
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

class Specification_action extends Action
{
    /**
     * Обрабатывает запрос на добавление спецификации
     *
     * @return void
     */
    public function add()
    {
        // в обработчике форм узазаны имена p#
        $name = $_POST['p111111'];
        $text = $_POST['p111112'];

        if(!$this->diafan->_users->id) {
            $this->result["errors"][0] = 'Для сохранения спецификации необходимо авторизоваться.';
        }
        if ($this->result())
            return;

        $this->check_site_id();
        if ($this->result())
            return;

        $this->check_fields();
        if ($this->result())
            return;

        $specification_id = DB::query("INSERT INTO {specifications} ([name], [text], user_id, created, [act], site_id) VALUES ('%h', '%h', '%d', '%d', '1', %d)",
            $name, $text, $this->diafan->_users->id, time(), $this->site_id);


        if($this->diafan->_cart->get_count() > 0) {
            $cartGoods = DB::query_fetch_all("SELECT * FROM {shop_cart} WHERE user_id=%d",
                $this->diafan->_users->id);

            foreach ($cartGoods as $cartItem) {
                DB::query(
                    "INSERT INTO {specifications_goods} (specification_id, additional_cost, count, created, good_id,"
                    . " is_file, param, price_id, trash, user_id)"
                    . " VALUES ('%d', '%h', '%d', '%d', '%d', '%d', '%h', '%d', '%d', '%d')",
                    $specification_id, $cartItem['additional_cost'], $cartItem['count'], $cartItem['created'],
                    $cartItem['good_id'], $cartItem['is_file'], $cartItem['param'], $cartItem['price_id'],
                    $cartItem['trash'], $cartItem['user_id']);
            }
        }
        $this->result["result"] = "success";
        $this->result["redirect"] = '/specifications/show' . $specification_id . '/';
    }

    /**
     * Обрабатывает запрос на удаление спецификации
     *
     * @return void
     */
    public function remove() {
        if(!$this->diafan->_users->id) {
            $this->result["errors"][0] = 'Для удаления спецификации необходимо авторизоваться.';
        }
        if ($this->result())
            return;

        $this->check_site_id();
        if ($this->result())
            return;

        if(!empty($_POST["specification_id"]) && $_POST["specification_id"] >= 0) {
            $specification_id = $_POST["specification_id"];

            $row = DB::query_fetch_all(
                "SELECT id FROM {specifications}"
                . " WHERE id='%d' LIMIT 1",
                $specification_id);

            if(!$row) {
                $this->result["errors"][0] = 'Спецификация не найдена. Обратитесь к администратору сайта.';
                $this->result["result"] = "success";
                return;
            }
            else {
                // удаляем данные о товарах
                DB::query("DELETE FROM {specifications_goods} WHERE specification_id='%d'", $specification_id);

                // удаляем данные о спецификации
                DB::query("DELETE FROM {specifications} WHERE id='%d'", $specification_id);

                $this->result["result"] = "success";
                $this->result["redirect"] = '/specifications/';
            }
        }
        else {
            $this->result["errors"][0] = 'Спецификация не найдена. Обратитесь к администратору сайта.';
            $this->result["result"] = "success";
        }
    }

    /**
     * Обрабатывает запрос на перенос спецификации в коризину
     *
     * @return void
     */
    public function push() {
        if(!$this->diafan->_users->id) {
            $this->result["errors"][0] = 'Для переноса спецификации в корзину необходимо авторизоваться.';
        }
        if ($this->result())
            return;

        $this->check_site_id();
        if ($this->result())
            return;

        if(!empty($_POST["specification_id"]) && $_POST["specification_id"] >= 0) {
            $specification_id = $_POST["specification_id"];

            $row = DB::query_fetch_all(
                "SELECT id FROM {specifications}"
                . " WHERE id='%d' LIMIT 1",
                $specification_id);

            if(!$row) {
                $this->result["errors"][0] = 'Спецификация не найдена. Обратитесь к администратору сайта.';
                $this->result["result"] = "success";
                return;
            }
            else {
                // получаем данные о товарах в спецификации
                $rows = DB::query_fetch_all(
                    "SELECT * FROM {specifications_goods}"
                    . " WHERE specification_id='%d'",
                    $specification_id);

                if($rows) {

                    // очищаем корзину (https://www.diafan.ru/docs/manual_DIAFAN.CMS_5.4.pdf стр. 151)
                    $this->diafan->_cart->set();
                    // записываем данные, установленные функцией set()
                    $this->diafan->_cart->write();



                    foreach ($rows as $row) {
                        if($row["id"]){
                            //$this->diafan->_cart->set(0, $row["id"], false, false, 'count');
                            $value = array(
                                'count' => 3,
                                'is_file' => false,
                            );

                            $this->diafan->_cart->set($value, $row["id"], array(), "count");
                        }
                    }

                    $this->diafan->_cart->write();

                    $this->result["result"] = "success";
                    $this->result["redirect"] = '/shop/cart/';

                }
            }
        }
        else {
            $this->result["errors"][0] = 'Спецификация не найдена. Обратитесь к администратору сайта.';
            $this->result["result"] = "success";
            return;
        }
    }

    /**
     * Валидация введенных данных
     *
     * @return void
     */
    private function check_fields()
    {
        if(empty($_POST['p111111'])) {
            $this->result["errors"]["p111111"] = "Введите название спецификации.";
        }

        if(empty($_POST['p111112'])) {
            $this->result["errors"]["p111112"] = "Введите описание спецификации.";
        }
    }

    /**
     * Добавляет товар в корзину
     *
     * @return void
     */
    public function add_to_cart($good_id, $count = 1, $additional_costs = Array())
    {
        if (!$good_id
            || $good_id < 0
            || $this->diafan->configmodules('security_user')
            && ! $this->diafan->_users->id)
        {
            return false;
        }
        if(! $cart_link = $this->diafan->_route->module("cart"))
        {
            return false;
        }

        $count = $count > 0 ? $count : 1;
        $this->tag = 'shop'.$good_id;

        $row = DB::query_fetch_array(
            "SELECT id, is_file, [measure_unit] FROM {shop} WHERE id=%d AND trash='0' AND [act]='1' LIMIT 1",
            $good_id);

        if (empty($row['id']))
        {
            $this->result["errors"][0] = 'ERROR! Товар с ID=' . $good_id . ' не найден';
            return false;
        }

        $params = array();

        $rows_param = DB::query_fetch_all(
            "SELECT p.[name], p.id FROM {shop_param} AS p"
            ." INNER JOIN {shop_param_element} AS e ON e.element_id=%d AND e.param_id=p.id"
            ." WHERE p.`type`='multiple' AND p.required='1' AND p.trash='0' GROUP BY p.id",
            $good_id
        );

        foreach ($rows_param as $row_param)
        {
            if (empty($_POST["param".$row_param["id"]]))
            {
                $this->result["errors"][0] = $this->diafan->_('Пожалуйста, выберите %s.', false, $row_param["name"]);
                return;
            }

            $params[$row_param["id"]] = $this->diafan->filter($_POST, "int", "param".$row_param["id"]);
        }

        $additional_cost_arr = array();
        $additional_cost = '';

        if(! empty($additional_costs))
        {
            $a_cs = array();
            foreach($additional_costs as $a_c)
            {
                $a_c = $this->diafan->filter($a_c, "integer");
                if($a_c)
                {
                    $a_cs[] = $a_c;
                }
            }
            if($a_cs)
            {
                $additional_cost_arr = DB::query_fetch_value("SELECT additional_cost_id FROM {shop_additional_cost_rel} WHERE element_id=%d AND trash='0' AND additional_cost_id IN (%s)", $good_id, implode(',', $a_cs), "additional_cost_id");
            }
        }

        $additional_cost_arr_2 = DB::query_fetch_value("SELECT r.additional_cost_id FROM {shop_additional_cost_rel} AS r INNER JOIN {shop_additional_cost} AS a ON a.id=r.additional_cost_id WHERE r.element_id=%d AND r.trash='0' AND a.required='1'", $good_id, "additional_cost_id");
        if($additional_cost_arr_2)
        {
            $additional_cost_arr = array_unique(array_merge($additional_cost_arr, $additional_cost_arr_2));
        }

        if($additional_cost_arr)
        {
            sort($additional_cost_arr);
            $additional_cost = implode(',', $additional_cost_arr);
        }

        $count_good = $this->diafan->_cart->get($good_id, $params, $additional_cost, "count");
        $count_good += $count;

        $cart = array(
            "count" => $count_good,
            "is_file" => $row['is_file'],
        );

        if($err = $this->diafan->_cart->set($cart, $good_id, $params, $additional_cost))
        {
            $this->result["errors"][0] = $err;
            return;
        }
        $this->diafan->_cart->write();

        DB::query("UPDATE {shop} SET counter_buy=counter_buy+1 WHERE id='%d'", $good_id);

        $measure_unit = ! empty($row["measure_unit"]) ? $row["measure_unit"] : $this->diafan->_('шт.');
        $this->result["errors"][0] = $this->diafan->_('В <a href="%s">корзине</a> %s %s', false, BASE_PATH_HREF.$cart_link.'?'.rand(0, 999999), $count_good, $measure_unit);

        Custom::inc('modules/cart/cart.model.php');
        $model = new Cart_model($this->diafan);
        $cart_tpl = $model->show_block();
        $this->result["data"] = array("#show_cart" => $this->diafan->_tpl->get('info', 'cart', $cart_tpl));
        if($this->diafan->_site->module == 'cart')
        {
            $this->result["redirect"] = BASE_PATH_HREF.$this->diafan->_route->current_link().'?'.rand(0, 99999);
        }
    }
}