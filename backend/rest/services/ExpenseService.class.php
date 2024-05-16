<?php

require_once __DIR__ . "/../dao/ExpenseDao.class.php";

class ExpenseService {
    private $expense_dao;

    public function __construct() {
        $this->expense_dao = new ExpenseDao();
    }

    public function addExpense($data) {
        return $this->expense_dao->addExpenses($data);
    }

    public function edit_expense($id, $data) {
        return this->expense_dao->edit_expense($id, $data);
    }

    public function delete_expense($id) {
        return this->expense_dao->delete_expense($id);
    }

    public function get_all_expenses_with_categories() {
        return this->expense_dao->get_all_expenses_with_categories();
    }

    public function get_categories() {
        return this->expense_dao->get_categories();
    }
}
