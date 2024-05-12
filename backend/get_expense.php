<?php

require_once __DIR__ . "./rest/services/ExpenseService.class.php"; 


$payload= $_REQUEST;

$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => $payload['order'][0]['name'],
    "order_direction" => $payload['order'][0]['dir'],
];

$expense_service = new ExpenseService();

$data = $expense_service->get_expenses_paginated($params['start'], $params['limit'], $params['search'], $params['order_column'], $params['order_direction']);

foreach($data['data'] as $id => $expense){
    $data['data'][$id]['action'] = 
    '<button class="btn btn-primary" onclick="ExpenseService.open_edit_expense_modal('.$expense['id'].')">Edit</button> 
    <button class="btn btn-danger" onclick="ExpenseService.delete_expense('.$expense['id'].')">Delete</button>';
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