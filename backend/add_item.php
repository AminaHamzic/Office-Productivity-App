<?php

require_once __DIR__ . "./rest/services/ItemService.class.php"; 



$payload = $_REQUEST;



$item_service = new ItemService();

unset($payload['id']);
$item = $item_service->addItem($payload);


echo json_encode(['message' => "You have successfully added the item", 'data' => $item]);