<?php

require_once __DIR__ . "./rest/services/ItemService.class.php"; 


$payload= $_REQUEST;

$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => $payload['order'][0]['name'],
    "order_direction" => $payload['order'][0]['dir'],
];

$item_service = new ItemService();

$data = $item_service->get_items_paginated($params['start'], $params['limit'], $params['search'], $params['order_column'], $params['order_direction']);

foreach($data['data'] as $id => $item){
    $data['data'][$id]['action'] = 
    '<button class="btn btn-primary" onclick="ItemService.open_edit_item_modal('.$item['id'].')">Edit</button> 
    <button class="btn btn-danger" onclick="ItemService.delete_item('.$item['id'].')">Delete</button>';
}


// Response
echo json_encode([
    'draw' => $params['draw'],
    'data' => $data['data'],
    'recordsFiltered' => $data['count'],
    'recordsTotal' => $data['count'],
    'end' => $data['count']
]);




?>