<?php
/**
 * Установка модуля
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

if ( !defined('DIAFAN')) {
    $path = __FILE__;
    while ( !file_exists($path . '/includes/404.php')) {
        $parent = dirname($path);
        if ($parent == $path) {
            exit;
        }
        $path = $parent;
    }
    include $path . '/includes/404.php';
}

class Specification_install extends Install
{
    /**
     * @var string название
     */
    public $title = "Спецификации";

    /**
     * @var array записи в таблице {modules}
     */
    public $modules = array(
        array(
            "name" => "specification",
            "admin" => true,
            "site" => true,
            "site_page" => true,
        ),
    );

    /**
     * @var array меню административной части
     */
    public $admin = array(
        array(
            "name" => "Спецификации",
            "rewrite" => "specifications",
            "group_id" => "1",
            "sort" => 5,
            "act" => true
        ),
    );

    /**
     * @var array страницы сайта
     */
    public $site = array(
        array(
            "name" => array('Спецификации', 'Specification'),
            "act" => true,
            "module_name" => "specification",
            "rewrite" => "specifications",
            "map_no_show" => true,
            "noindex" => true,
            "search_no_show" => true,
        ),
    );

    /**
     * @var array настройки
     */
    public $config = array(
        array(
            "name" => "count_list",
            "value" => "2",
        ),
        array(
            "name" => "format_date",
            "value" => "2",
        ),
        array(
            "name" => "nastr",
            "value" => "10",
        ),
        array(
            "name" => "show_more",
            "value" => '1',
        ),
    );

    /**
     * @var array таблицы в базе данных
     */
    public $tables = array(
        /*
         * Спецификации
         * */
        array(
            "name" => "specifications",
            "comment" => "Спецификации",
            "fields" => array(
                array(
                    "name" => "id",
                    "type" => "INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
                    "comment" => "идентификатор",
                ),
                array(
                    "name" => "name",
                    "type" => "VARCHAR(250) NOT NULL DEFAULT ''",
                    "comment" => "название",
                    "multilang" => true,
                ),
                array(
                    "name" => "text",
                    "type" => "TEXT",
                    "multilang" => true,
                    "comment" => "Описание спецификации",
                ),
                array(
                    "name" => "user_id",
                    "type" => "INT(11) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "идентификатор пользователя из таблицы {users}",
                ),
                array(
                    "name" => "created",
                    "type" => "INT(10) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "дата создания",
                ),
                /*
                 * TODO: поля ниже нужны?
                 * */
                array(
                    "name" => "act",
                    "type" => "ENUM('0', '1') NOT NULL DEFAULT '0'",
                    "multilang" => true,
                    "comment" => "показывать на сайте: 0 - нет, 1 - да",
                ),
                array(
                    "name" => "map_no_show",
                    "type" => "ENUM( '0', '1' ) NOT NULL DEFAULT '0'",
                    "comment" => "не показывать на карте сайта: 0 - нет, 1 - да",
                ),
                array(
                    "name" => "noindex",
                    "type" => "ENUM('0','1') NOT NULL DEFAULT '0'",
                    "comment" => "не индексировать: 0 - нет, 1 - да",
                ),
                array(
                    "name" => "sort",
                    "type" => "INT(11) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "подрядковый номер для сортировки",
                ),
                array(
                    "name" => "trash",
                    "type" => "ENUM('0', '1') NOT NULL DEFAULT '0'",
                    "comment" => "запись удалена в корзину: 0 - нет, 1 - да",
                ),
                array(
                    "name" => "site_id",
                    "type" => "INT(11) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "идентификатор страницы сайта из таблицы {site}",
                ),
            ),
            "keys" => array(
                "PRIMARY KEY (id)",
                "KEY site_id (site_id)",
                "KEY user_id (user_id)",
            ),
        ),
        // поля как в {shop_cart}
        array(
            "name" => "specifications_goods",
            "comment" => "Товары в спецификации",
            "fields" => array(
                array(
                    "name" => "id",
                    "type" => "INT(11) UNSIGNED NOT NULL AUTO_INCREMENT",
                    "comment" => "идентификатор",
                ),
                array(
                    "name" => "specification_id",
                    "type" => "INT(11) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "идентификатор товара из таблицы {specifications}",
                ),
                array(
                    "name" => "good_id",
                    "type" => "INT(11) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "идентификатор товара из таблицы {shop}",
                ),
                array(
                    "name" => "user_id",
                    "type" => "INT(11) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "идентификатор пользователя из таблицы {users}",
                ),
                array(
                    "name" => "price_id",
                    "type" => "INT(11) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "идентификатор цены товара - поле price_id из таблицы {shop_price}",
                ),
                array(
                    "name" => "created",
                    "type" => "INT(10) UNSIGNED NOT NULL DEFAULT '0'",
                    "comment" => "дата создания",
                ),
                array(
                    "name" => "count",
                    "type" => "DOUBLE NOT NULL DEFAULT '0'",
                    "comment" => "количество товара",
                ),
                array(
                    "name" => 'param',
                    "type" => "TEXT",
                    "comment" => "серилизованные данные о характеристиках товара (доступных к выбору при заказе)",
                ),
                array(
                    "name" => 'additional_cost',
                    "type" => "TEXT",
                    "comment" => "идентификаторы сопутствующих услугах, разделенные запятой",
                ),
                array(
                    "name" => "is_file",
                    "type" => "ENUM('0', '1') NOT NULL DEFAULT '0'",
                    "comment" => "товар-файл: 0 - нет, 1 - да",
                ),
                array(
                    "name" => "trash",
                    "type" => "ENUM('0', '1') NOT NULL DEFAULT '0'",
                    "comment" => "запись удалена в корзину: 0 - нет, 1 - да",
                ),
            ),
            "keys" => array(
                "PRIMARY KEY (id)",
                "KEY user_id (`user_id`)",
            ),
        ),
        /*
         * TODO:
         * 1) Добавить связь с таблицей пользователей
         * 2) ДОбавить связь между спецификацией и товарами
         * */
    );

}
