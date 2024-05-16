<?php

require_once __DIR__ . "/../dao/CategoryDao.class.php";

class CategoryService {
    protected $dao;

    public function __construct(){
        $this->dao = new CategoryDao();
    }

    public function get_all_categories() {
        return $this->dao->get_all();
    }
}
