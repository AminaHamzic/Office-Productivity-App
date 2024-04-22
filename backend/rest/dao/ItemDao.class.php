<?php

require_once __DIR__ . "/BaseDao.class.php";

class ItemDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("items_list");
    }

    public function addItem($item)
    {
        return $this->insert('items_list', $item);
    }

    public function count_items_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM items_list
                  WHERE LOWER(item) LIKE CONCAT('%', :search, '%')";
        return $this->query_unique($query, [
            'search' => strtolower($search)
        ]);
    }
    
    public function get_items_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $valid_columns = ['item', 'items_list'];
        $valid_directions = ['ASC', 'DESC'];
    
        $order_column = in_array($order_column, $valid_columns) ? $order_column : 'item';
        $order_direction = in_array($order_direction, $valid_directions) ? $order_direction : 'ASC';
    
        $query = "SELECT *
          FROM items_list
          WHERE LOWER(item) LIKE CONCAT('%', :search, '%');
          ORDER BY {$order_column} {$order_direction}
          LIMIT :offset, :limit";
        return $this->query($query, [
            'search' => strtolower($search),
            'offset' => (int)$offset,
            'limit' => (int)$limit
        ]);
    }


    public function delete_item($id) {
        $query = "DELETE FROM items_list WHERE id = :id";
        $this->execute($query, [
            'id' => $id
        ]);
    }


    public function get_item_by_id($id) {
        return $this->query_unique(
            "SELECT * FROM items_list WHERE id = :id", 
            ["id" => $id]);
    }

    public function edit_item($id, $item) {
        $query  = "UPDATE items_list
                   SET item = :item,
                       category_id = :category_id,
                   WHERE id = :id";
        $this->execute($query, [
            'id' => $id, 
            'item' => $item['item'],
            'category_id' => $item['category_id']
            
        ]);
    }
    
    
    

    
}