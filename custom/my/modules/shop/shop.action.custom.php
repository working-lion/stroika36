<?php

class Shop_action extends Action
{
    new public function compare_del()
    {
        if (empty($_POST['id']) || empty($_POST["site_id"])) {
            return;
        }
        $id = $this->diafan->filter($_POST, "int", "id");
        $site_id = $this->diafan->filter($_POST, "int", "site_id");

        if (isset($_SESSION['shop_compare'][$site_id][$id])) {
            unset($_SESSION['shop_compare'][$site_id][$id]);
        }

        $this->result['result'] = 'ok';
        $this->result['count'] = count($_SESSION['shop_compare'][$site_id]);
    }

    new public function compare_count()
    {
        if (empty($_POST["site_id"])) {
            return;
        }
        $site_id = $this->diafan->filter($_POST, "int", "site_id");
        $this->result['result'] = $_SESSION['shop_compare'][$site_id];
        $this->result['count'] = count($_SESSION['shop_compare'][$site_id]);
    }

}

?>