<?php
/**
 * Подключение модуля «Спецификации»
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

class Wishlist_inc extends Diafan
{
    /*
	 * @var array информация, записанная в список спецификаций
	 */
    private $specifications = 'no_check';

    /**
     * Конструктор класса
     *
     * @return void
     */
    public function __construct(&$diafan)
    {
        $this->diafan = &$diafan;
        //$this->init();
    }

    public function get()
    {
        if($this->specifications === 'no_check') {
            return array();
        }

        return $this->specifications;
    }

    /**
     * Записывает информацию в хранилище
     *
     * @return void
     */
    public function write()
    {
        // получить товары из карзины по user_id -> нужно проверить в модуле cart метод get
        // получить id спецификации - тут уже нужно писать inc в модуле спецификаций
        // записать данные в {specifications_goods} - всё, как в корзине + specification_id
    }
}