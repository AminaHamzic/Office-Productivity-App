<?php

require_once __DIR__ . "/BaseDao.class.php";

class HoursDao extends BaseDao {
    public function __construct()
    {
        parent::__construct("hours_tracking");
    }


} 