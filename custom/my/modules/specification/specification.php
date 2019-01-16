<?php
/**
 * Контроллер
 *
 * @package    DIAFAN.CMS
 * @author     diafan.ru
 * @version    6.0
 * @license    http://www.diafan.ru/license.html
 * @copyright  Copyright (c) 2003-2018 OOO «Диафан» (http://www.diafan.ru/)
 */

// НЕ ПОДДЕРЖИВАЕТСЯ МУЛЬТИЯЗЫЧНОСТЬ

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

/**
 * News
 */
class Specification extends Controller
{
    /**
     * @var array переменные, передаваемые в URL страницы
     */
    public $rewrite_variable_names = array('page', 'show');

    /**
     * Инициализация модуля
     *
     * @return void
     */
    public function init()
    {
        if ($this->diafan->_route->show) {
            if ($this->diafan->_route->page) {
                Custom::inc('includes/404.php');
            }
            $this->model->id();
        } else {
            $this->model->list_();
        }
    }

    /**
     * Обрабатывает полученные данные из формы
     *
     * @return void
     */
    public function action()
    {
        if(! empty($_POST["action"]))
        {
            switch($_POST["action"])
            {
                case 'add':
                    return $this->action->add();

                case 'remove':
                    return $this->action->remove();

                case 'push':
                    return $this->action->push();
            }
        }
    }


}