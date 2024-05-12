<?php

require_once __DIR__ . '/../services/ItemService.class.php';

Flight::set('item_service', new ItemService());

Flight::route('GET /items', function() {
    try {
        $items = Flight::get('item_service')->get_all_items_with_categories();
        Flight::json($items);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});


Flight::route('POST /items/add', function() {
    $data = Flight::request()->data->getData();
    error_log("Received data: " . print_r($data, true)); 

    try {
        $item = Flight::get('item_service')->addItem($data);
        Flight::json(['message' => "Item added successfully", 'data' => $item]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});


Flight::route('PUT /items/update/@item_id', function($item_id) {
    $data = json_decode(Flight::request()->getBody(), true);
    if (!$data) {
        Flight::halt(400, json_encode(['error' => 'Invalid data']));
        return;
    }

    try {
        $updatedItem = Flight::get('item_service')->edit_item($item_id, $data);
        Flight::json(['success' => 'Item updated successfully', 'data' => $updatedItem]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});




Flight::route('DELETE /items/delete/@item_id', function($item_id) {
    try {
        Flight::get('item_service')->delete_item($item_id);
        Flight::json(['message' => 'Item deleted successfully']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

Flight::route('GET /items/@item_id', function($item_id) {
    try {
        $item = Flight::get('item_service')->get_item_by_id($item_id);
        Flight::json($item);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});


Flight::route('GET /categories', function() {
    $categories = Flight::get('item_service')->get_categories();
    Flight::json($categories);
});

