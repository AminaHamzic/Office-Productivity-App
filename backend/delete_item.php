<?php

require_once __DIR__ . "./rest/services/ItemService.class.php";

$item_id = $_REQUEST['id'];

if($item_id == NULL || $item_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "You have to provide valid item id!"]));
}

$item_service = new ItemService();
$item_service->delete_item($item_id);
echo json_encode(['message' => 'You have successfully deleted the item!']);

