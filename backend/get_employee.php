<?php

require_once __DIR__ . "./rest/services/EmployeeService.class.php"; 


$payload= $_REQUEST;

$params = [
    "start" => (int)$payload['start'],
    "search" => $payload['search']['value'],
    "draw" => $payload['draw'],
    "limit" => (int)$payload['length'],
    "order_column" => $payload['order'][0]['name'],
    "order_direction" => $payload['order'][0]['dir'],
];

$employee_service = new EmployeeService();

$data = $employee_service->get_employees_paginated($params['start'], $params['limit'], $params['search'], $params['order_column'], $params['order_direction']);

foreach($data['data'] as $id => $employee){
    $data['data'][$id]['action'] = 
    '<button class="btn btn-primary" onclick="EmployeeService.open_edit_employee_modal('.$employee['user_id'].')">Edit</button> 
    <button class="btn btn-danger" onclick="EmployeeService.delete_employee('.$employee['user_id'].')">Delete</button>';
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