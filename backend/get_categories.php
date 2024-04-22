<?php

require_once __DIR__ . "/rest/services/ItemService.class.php"; // Corrected the path.

$item_service = new ItemService();

$categories = $item_service->get_categories();

header('Content-Type: application/json');

echo json_encode($categories);


