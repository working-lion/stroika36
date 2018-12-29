<?php
class Specification_admin_action extends Action_admin
{
    public function init()
    {
        if (! empty($_POST['user_id']))
        {
            $this->result["name"] = DB::query_result("SELECT fio FROM {users} WHERE id=%d", $_POST['user_id']);
        }
        else
        {
            $this->result["name"] = 'ошибка';
        }
    }
}