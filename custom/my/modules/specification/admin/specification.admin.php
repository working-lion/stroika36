<?php
/**
 * Редактирование спецификаций
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
 * Specification_admin
 */
class Specification_admin extends Frame_admin
{
    /**
     * @var string таблица в базе данных
     */
    public $table = 'specifications';

    /**
     * @var array поля в базе данных для редактирования
     * на странице добавления и редактирования спецификации
     */
    public $variables = array (
        'main' => array (
            'name' => array(
                'type' => 'text',
                'name' => 'Название спецификации',
                'help' => 'Используется в ссылках на спецификацию, заголовках.',
                'multilang' => true,
            ),
            'act' => array(
                'type' => 'checkbox',
                'name' => 'Опубликовать на сайте',
                'help' => 'Если не отмечена, спецификацию не увидят посетители сайта.',
                'default' => true,
                'multilang' => true,
            ),
            'created' => array(
                'type' => 'datetime',
                'name' => 'Дата и время',
                'help' => 'Вводится в формате дд.мм.гггг чч:мм. Статьи, старше текущей даты начнут отображаться на сайте, начиная с указанной даты.',
            ),
            /*
             * TODO: понять, как работает
             * */
            'user_id' => array(
                'type' => 'select',
                'name' => 'Пользователь',
                'select_db' => array(
                    'table' => 'users',
                    'name' => 'fio',
                    'where' => "trash='0'",
                ),
            ),
            'text' => array(
                'type' => 'editor',
                'name' => 'Описание',
                'multilang' => true,
            ),
        ),
        'other_rows' => array (
            /*'number' => array(
                'type' => 'function',
                'name' => 'Номер',
                'help' => 'Номер элемента в БД (веб-мастеру и программисту).',
                'no_save' => true,
            ),
            'site_id' => array(
                'type' => 'function',
                'name' => 'Раздел сайта',
                'help' => 'Перенос новости на другую страницу сайта, к которой прикреплен модуль новостей. Параметр выводится, если в настройках модуля отключена опция «Использовать категории», если опция подключена, то раздел сайта задается такой же, как у основной категории.',
            ),
            'rewrite' => array(
                'type' => 'function',
                'name' => 'Псевдоссылка (ЧПУ)',
                'help' => 'ЧПУ, т.е. адрес страницы вида: *http://site.ru/psewdossylka/*. Смотрите параметры сайта (SEO-специалисту).',
            ),
            'access' => array(
                'type' => 'function',
                'name' => 'Доступ к новости',
                'help' => 'Если отметить опцию «Доступ только», новость увидят только авторизованные на сайте пользователи, отмеченных типов. Не авторизованные, в том числе поисковые роботы, увидят «404 Страница не найдена» (администратору сайта).',
            ),
            'theme' => array(
                'type' => 'function',
                'name' => 'Шаблон страницы',
                'help' => 'Возможность подключить для страницы новости шаблон сайта отличный от основного (themes/site.php). Все шаблоны для сайта должны храниться в папке *themes* с расширением *.php* (например, themes/dizain_so_slajdom.php). Подробнее в [разделе «Шаблоны сайта»](http://www.diafan.ru/dokument/full-manual/templates/site/). (веб-мастеру и программисту, не меняйте этот параметр, если не уверены в результате!).',
            ),
            'view' => array(
                'type' => 'function',
                'name' => 'Шаблон модуля',
                'help' => 'Шаблон вывода контента модуля на странице отдельной новости (веб-мастеру и программисту, не меняйте этот параметр, если не уверены в результате!).',
            ),
            'search' => array(
                'type' => 'module',
                'name' => 'Индексирование для поиска',
                'help' => 'Новость автоматически индексируется для модуля «Поиск по сайту» при внесении изменений.',
            ),
            'map' => array(
                'type' => 'module',
                'name' => 'Индексирование для карты сайта',
                'help' => 'Новость автоматически индексируется для карты сайта sitemap.xml.',
            ),*/
        ),
            /*
             * TODO: Добавить редактирование списка товаров в спецификации
             * */
    );

    /**
     * @var array поля в списка элементов в админке
     */
    public $variables_list = array (
        'checkbox' => '',
        'sort' => array(
            'name' => 'Сортировка',
            'type' => 'numtext',
            'sql' => true,
            'fast_edit' => true,
            'desc' => true,
        ),
        'name' => array(
            'name' => 'Название и категория',
            'sql' => true,
        ),
        'created' => array(
            'name' => 'Дата и время',
            'type' => 'datetime',
            'sql' => true,
            'no_important' => true,
        ),
        'user_id' => array(
            'name' => 'Пользователь',
            'type' => 'string',
            'sql' => true,
        ),
        'text' => array(
            'name' => 'Описание',
            'sql' => true,
            'type' => 'text',
            'class' => 'text',
            'no_important' => true,
        ),
        'actions' => array(
            'del' => true
        ),
    );

    /**
     * @var array поля для фильтра
     */
    public $variables_filter = array (
        'user_id' => array(
            'type' => 'select',
            'name' => 'Искать по пользователю',
        ),
    );

    // ссылка на добавление новой спецификации
    public function show_add()
    {
        $this->diafan->addnew_init('Добавить спецификацию');
    }

    // функция, которая определяет что выводит модуль при открытии
    public function show()
    {
        // список объявлений
        $this->diafan->list_row();
    }

    /**
     * Выводит имя пользователя по id в списке спецификаций в админке
     * @param $row - поля текущего элемента
     * @return string
     */
    public function list_variable_user_id($row)
    {
        return '<div>'.DB::query_result("SELECT fio FROM {users} WHERE id=%d", $row['user_id']).'</div>';
    }


    /*public function edit_variable_user_id()
    {
        echo '<div class="unit">
            <div class="infofield">Нажми</div>
            <div class="user_id" rel="'.$this->diafan->value.'"><b>ЗДЕСЬ</b></div>
        </div>';
    }

    public function save_variable_user_id()
    {
        $this->diafan->set_query("user_id=%d");
        $this->diafan->set_value(1);
    }*/
}