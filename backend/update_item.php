<?php
require_once __DIR__ . '/rest/services/ItemService.class.php';

$item_service = new ItemService();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['id'])) {
    $item_id = $_REQUEST['id'];
    $payload = $_POST; 

    try {
        $item_service->edit_item($item_id, $payload);
        echo json_encode(['success' => 'item has been updated successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST['id'])) {
    $item_id = $_REQUEST['id'];
    $item = $item_service->get_item_by_id($item_id);
    header('Content-Type: application/json');
    echo json_encode($item);
} else {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Bad request']);
}
