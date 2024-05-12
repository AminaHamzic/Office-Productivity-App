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
        return $this->insert('items_list', [
            'item' => $item['item'],
            'category_id' => $item['category_id']
    ]);
}


    public function count_items_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM items_list
                  WHERE LOWER(item) LIKE CONCAT('%', :search, '%')";
        return $this->query_unique($query, [
            'search' => strtolower($search)
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
        $query = "UPDATE items_list
           SET item = :item, category_id = :category_id
           WHERE id = :id";
        $this->execute($query, [
            'id' => $id,
            'item' => $item['item'],
            'category_id' => $item['category_id']
        ]);

    }

    public function get_categories() {
        $query = "SELECT category_id, category_name FROM categories";
        return $this->query($query, []);
    }

    public function get_all_items_with_categories() {
        $query = "SELECT i.id, i.item, c.category_name AS category
                  FROM items_list i
                  LEFT JOIN categories c ON i.category_id = c.category_id";
        return $this->query($query, []);
    }
    
  
    
    
    
    

    
}