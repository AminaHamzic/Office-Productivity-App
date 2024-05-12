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
                  LOWER(dateInput) LIKE CONCAT('%', :search, '%');";
        return $this->query_unique($query, [
            'search' => strtolower($search)
        ]);
    }
    
    public function get_expenses_paginated($offset, $limit, $search, $order_column, $order_direction) {
        $valid_columns = ['dateInput', 'description', 'expenseAmount', 'category'];
        $valid_directions = ['ASC', 'DESC'];
    
        $order_column = in_array($order_column, $valid_columns) ? $order_column : 'description';
        $order_direction = in_array($order_direction, $valid_directions) ? $order_direction : 'ASC';
    
        $query = "SELECT *
                  FROM expense_tracking
                  WHERE LOWER(description) LIKE CONCAT('%', :search, '%'); OR 
                  ORDER BY {$order_column} {$order_direction}
                  LIMIT :offset, :limit";
        return $this->query($query, [
            'search' => strtolower($search),
            'offset' => (int)$offset,
            'limit' => (int)$limit
        ]);
    }


    public function delete_expense($id) {
        $query = "DELETE FROM expense_tracking WHERE id = :id";
        $this->execute($query, [
            'id' => $id
        ]);
    }


    public function get_expense_by_id($id) {
        return $this->query_unique(
            "SELECT * FROM expense_tracking WHERE id = :id", 
            ["id" => $id]);
    }

    public function edit_expense($id, $expense) {
        $query  = "UPDATE expense_tracking
                   SET dateInput = :dateInput, 
                   description = :description, 
                   expenseAmount = :expenseAmount, 
                   category = :category
                   WHERE id = :id";
        $this->execute($query, [
            'id' => $id,
            'dateInput' => $expense['dateInput'],
            'description' => $expense['description'],
            'expenseAmount' => $expense['expenseAmount'],
            'category' => $expense['category']
        ]);
    }

    public function get_categories() {
        $query = "SELECT category_id, name FROM categories";
        return $this->query($query, []);
    }
    
    
    

    
}