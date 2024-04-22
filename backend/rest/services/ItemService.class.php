<?php

require_once __DIR__ . "/../dao/ItemDao.class.php";

class ItemService
{
    private $item_dao;

    public function __construct()
    {
        $this->item_dao = new ItemDao();
    }

    public function addItem($item)
    {
        return $this->item_dao->addItem($item);
    }

    public function get_items_paginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->item_dao->count_items_paginated($search)['count'];
        $rows= $this->item_dao->get_items_paginated($offset, $limit, $search, $order_column, $order_direction);

        return ['count' => $count, 'data' => $rows];
    }

    public function delete_item($id)
    {
        $this->item_dao->delete_item($id);
    }

    public function get_item_by_id($id)
    {
        return $this->item_dao->get_item_by_id($id);
    }

    public function edit_item($id, $item) {
        $this->item_dao->edit_item($id, $item);
    }

    
    

    
}