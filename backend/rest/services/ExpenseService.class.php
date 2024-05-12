<?php

require_once __DIR__ . "/../dao/ExpenseDao.class.php";

class ExpenseService
{
    private $expense_dao;

    public function __construct()
    {
        $this->expense_dao = new ExpenseDao();
    }

    public function addExpenses($expense)
    {
        return $this->expense_dao->addExpenses($expense);
    }

    public function get_expenses_paginated($offset, $limit, $search, $order_column, $order_direction)
    {
        $count = $this->expense_dao->count_expenses_paginated($search)['count'];
        $rows= $this->expense_dao->get_expenses_paginated($offset, $limit, $search, $order_column, $order_direction);

        return ['count' => $count, 'data' => $rows];
    }

    public function delete_expense($id)
    {
        $this->expense_dao->delete_expense($id);
    }

    public function get_expense_by_id($id)
    {
        return $this->expense_dao->get_expense_by_id($id);
    }

    public function edit_expense($id, $expense) {
        $this->expense_dao->edit_expense($id, $expense);
    }

    public function get_categories() {
        return $this->expense_dao->get_categories();
    }

    
    

    
}