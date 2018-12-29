<?php

class Shop extends Controller
{
    after public function init()
    {

        if ( !empty($_GET['ajax_paginator'])) {
            $this->model->result['ajax'] = true;
            echo $this->diafan->_tpl->get($this->model->result['view'], 'shop', $this->model->result);
            exit;
        }

    }
    after public function action()
    {
        if ( !empty($_POST["action"])) {
            switch ($_POST["action"]) {
                case 'compare_count':
                    return $this->action->compare_count();
                case 'compare_del':
                    return $this->action->compare_del();

            }
        }
    }
}


?>