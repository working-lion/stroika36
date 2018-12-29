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
    public function init()
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