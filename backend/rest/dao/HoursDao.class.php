<?php

require_once __DIR__ . "/BaseDao.class.php";

class HoursDao extends BaseDao {

    public function __construct() {
        parent::__construct("hours_tracking");
    }

    public function addHours($hours) {
        return $this->insert('hours_tracking', $hours);
    }

    public function count_hours_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM hours_tracking
                  WHERE LOWER(description) LIKE CONCAT('%', :search, '%') OR 
                        LOWER(dateInput) LIKE CONCAT('%', :search, '%')";
        return $this->query_unique($query, ['search' => strtolower($search)]);
    }
    
    public function get_hours_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $valid_columns = ['dateInput', 'description', 'hoursWorked', 'category'];
        $valid_directions = ['ASC', 'DESC'];
    
        if (!in_array($order_column, $valid_columns)) {
            $order_column = 'dateInput';
        }
        if (!in_array($order_direction, $valid_directions)) {
            $order_direction = 'ASC';
        }
    
        $query = "SELECT * FROM hours_tracking
                  WHERE LOWER(description) LIKE CONCAT('%', :search, '%')
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT :limit OFFSET :offset";
        return $this->query($query, [
            'search' => strtolower($search),
            'limit' => (int)$limit,
            'offset' => (int)$offset
        ]);
    }

    public function delete_hours($id) {
        $query = "DELETE FROM hours_tracking WHERE id = :id";
        $this->execute($query, ['id' => $id]);
    }

    public function get_hours_by_id($id) {
        return $this->query_unique("SELECT * FROM hours_tracking WHERE id = :id", ["id" => $id]);
    }

    public function edit_hours($id, $hours) {
        $query = "UPDATE hours_tracking
                  SET dateInput = :dateInput, 
                      description = :description, 
                      hoursWorked = :hoursWorked, 
                      category = :category
                  WHERE id = :id";
        $this->execute($query, [
            'id' => $id,
            'dateInput' => $hours['dateInput'],
            'description' => $hours['description'],
            'hoursWorked' => $hours['hoursWorked'],
            'category' => $hours['category']
        ]);
    }

    public function get_categories() {
        $query = "SELECT category_id, name FROM categories";
        return $this->query($query, []);
    }

    public function get_all_hours_with_categories() {
        $query = "SELECT ht.id, ht.user_id, ht.dateInput, ht.description, ht.hoursWorked, c.name AS category
                  FROM hours_tracking ht
                  LEFT JOIN categories c ON ht.category = c.category_id";
        return $this->query($query, []);
    }
    
}
