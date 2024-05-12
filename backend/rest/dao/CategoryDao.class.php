<?php

require_once __DIR__ . "/BaseDao.class.php";

class ExpenseDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct("categories");
    }
    
    public function get_category_name_by_id($category_id) {
        $query = "SELECT category_name FROM categories WHERE category_id = :category_id";
        return $this->query_unique($query, ["category_id" => $category_id]);
    }
}