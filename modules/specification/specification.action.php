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

                            $this->diafan->_cart->set($value, $row["id"], array());
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
}