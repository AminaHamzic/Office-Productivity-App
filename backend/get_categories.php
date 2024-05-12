<?php

require_once __DIR__ . "./rest/services/CategoryService.class.php"; 

$category_service = new CategoryService();

$categories = $category_service->get_all_categories();

header('Content-Type: application/json');
echo json_encode($categories);
