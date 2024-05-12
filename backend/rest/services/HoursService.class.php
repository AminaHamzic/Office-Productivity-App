<?php

require_once __DIR__ . "/../dao/HoursDao.class.php";

class HoursService
{
    private $hours_dao;

    public function __construct()
    {
        $this->hours_dao = new HoursDao();
    }

    public function addHours($hours)
    {
        return $this->hours_dao->addHours($hours);
    }

    public function get_hours_paginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->hours_dao->count_hours_paginated($search)['count'];
        $rows= $this->hours_dao->get_hours_paginated($offset, $limit, $search, $order_column, $order_direction);

        return ['count' => $count, 'data' => $rows];
    }

    public function delete_hours($id)
    {
        $this->hours_dao->delete_hours($id);
    }

    public function get_hours_by_id($id)
    {
        return $this->hours_dao->get_hours_by_id($id);
    }

    public function edit_hours($id, $hours) {
        $this->hours_dao->edit_hours($id, $hours);
    }

    public function get_categories() {
        return $this->hours_dao->get_categories();
    }

    
    

    
}