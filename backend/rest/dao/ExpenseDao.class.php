<?php

require_once __DIR__ . "/BaseDao.class.php";

class ExpenseDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("expense_tracking");
    }

    public function addExpenses($expense)
    {
        return $this->insert('expense_tracking', $expense);
    }

    public function count_expenses_paginated($search) {
        $query = "SELECT COUNT(*) AS count
                  FROM expense_tracking
                  WHERE LOWER(description) LIKE CONCAT('%', :search, '%'); OR 
                  LOWER(date) LIKE CONCAT('%', :search, '%');";
        return $this->query_unique($query, [
            'search' => strtolower($search)
        ]);
    }
    
    public function get_expenses_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $valid_columns = ['name_surname', 'position', 'office', 'working_hours'];
        $valid_directions = ['ASC', 'DESC'];
    
        $order_column = in_array($order_column, $valid_columns) ? $order_column : 'name_surname';
        $order_direction = in_array($order_direction, $valid_directions) ? $order_direction : 'ASC';
    
        $query = "SELECT *
                  FROM expense_tracking
                  WHERE LOWER(name_surname) LIKE CONCAT('%', :search, '%') OR
                  LOWER(position) LIKE CONCAT('%', :search, '%');
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT :offset, :limit";
        return $this->query($query, [
            'search' => strtolower($search),
            'offset' => (int)$offset,
            'limit' => (int)$limit
        ]);
    }


    public function delete_expense($user_id) {
        $query = "DELETE FROM expense_tracking WHERE user_id = :user_id";
        $this->execute($query, [
            'user_id' => $user_id
        ]);
    }


    public function get_expense_by_id($user_id) {
        return $this->query_unique(
            "SELECT * FROM expense_tracking WHERE user_id = :user_id", 
            ["user_id" => $user_id]);
    }

    public function edit_expense($user_id, $expense) {
        $query  = "UPDATE expense_tracking
                   SET name_surname = :name_surname,
                       position = :position,
                       office = :office,
                       working_hours = :working_hours
                   WHERE user_id = :user_id";
        $this->execute($query, [
            'user_id' => $user_id, // Change from $id to $user_id
            'name_surname' => $expense['name_surname'],
            'position' => $expense['position'],
            'office' => $expense['office'],
            'working_hours' => $expense['working_hours']
        ]);
    }

    public function get_categories() {
        $query = "SELECT category_id, name FROM categories";
        return $this->query($query, []);
    }
    
    
    

    
}