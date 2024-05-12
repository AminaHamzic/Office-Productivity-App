<?php

require_once __DIR__ . '/../services/EmployeeService.class.php';

Flight:: set('employee_service', new EmployeeService());


Flight::route('GET /employees', function() {

    $payload = Flight::request()->query;

    $params = [
        "start" => (int)$payload['start'],
        "search" => $payload['search']['value'],
        "draw" => $payload['draw'],
        "limit" => (int)$payload['length'],
        "order_column" => $payload['order'][0]['name'],
        "order_direction" => $payload['order'][0]['dir'],
    ];

    

    $data = Flight::get('employee_service')->get_employees_paginated($params['start'], $params['limit'], $params['search'], $params['order_column'], $params['order_direction']);


    foreach($data['data'] as $id => $employee){
        $data['data'][$id]['action'] = 
        '<button class="btn btn-primary" onclick="EmployeeService.open_edit_employee_modal('.$employee['user_id'].')">Edit</button> 
        <button class="btn btn-danger" onclick="EmployeeService.delete_employee('.$employee['user_id'].')">Delete</button>';
    }

    Flight::json([
        'draw' => $params['draw'],
        'data' => $data['data'],
        'recordsFiltered' => $data['count'],
        'recordsTotal' => $data['count'],
        'end' => $data['count']
    ], 200);
    

});

Flight::route ('POST /employees/add', function() {
    $payload = Flight::request()->data->getData();

    $id = isset($payload['id']) ? $payload['id'] : null;

    if (!empty($id)) {
        $employee = Flight::get('employee_service')->edit_employee($payload);
    } else {
        if (isset($payload['id'])) {
            unset($payload['id']);
        }
        $employee = Flight::get('employee_service')->addEmployees($payload);
    }

    Flight::json(['message' => "Employee operation successful", 'data' => $employee]);

});




Flight::route ('DELETE /employees/delete/@employee_id', function($employee_id) { //route & query parameter, 

    if($employee_id == NULL || $employee_id == '') {
        Flight::halt(500, 'Employee ID is required');
    }

    Flight::get('employee_service')->delete_employee($employee_id);
    Flight::json(['message' => 'Employee deleted successfully']);
    
});

Flight::route ('GET /employees/@employee_id', function($employee_id) {
    
    if($employee_id == NULL || $employee_id == '') {
        Flight::halt(500, 'Employee ID is required');
    }
    $employee = Flight::get('employee_service')->get_employee_by_id($employee_id);
    Flight::json($employee);

    
});


Flight::route('PUT /employees/update/@employee_id', function($employee_id) {
    $request = Flight::request();
    $data = json_decode($request->getBody(), true);

    if (empty($data)) {
        Flight::halt(400, json_encode(['error' => 'Missing employee data']));
        return;
    }

    try {
        $employee = Flight::get('employee_service')->edit_employee($employee_id, $data);
        Flight::json(['success' => 'Employee updated successfully', 'data' => $employee]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});