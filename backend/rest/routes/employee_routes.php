<?php

require_once __DIR__ . '/../services/EmployeeService.class.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;



Flight:: set('employee_service', new EmployeeService());

/**
 * @OA\Get(
 *      path="/employees",
 *      tags={"employees"},
 *      summary="Get paginated list of employees",
 *      description="Retrieve a paginated list of employees.",
 *      @OA\Parameter(
 *          name="start",
 *          in="query",
 *          required=true,
 *          @OA\Schema(type="integer"),
 *          description="Pagination start index"
 *      ),
 *      @OA\Parameter(
 *          name="length",
 *          in="query",
 *          required=true,
 *          @OA\Schema(type="integer"),
 *          description="Number of records to fetch"
 *      ),
 *      @OA\Parameter(
 *          name="draw",
 *          in="query",
 *          required=true,
 *          @OA\Schema(type="integer"),
 *          description="Draw counter for DataTables"
 *      ),
 *      @OA\Parameter(
 *          name="search",
 *          in="query",
 *          required=false,
 *          @OA\Schema(type="string"),
 *          description="Search keyword"
 *      ),
 *      @OA\Parameter(
 *          name="order",
 *          in="query",
 *          required=false,
 *          @OA\Schema(type="array", @OA\Items(type="object")),
 *          description="Ordering information"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of employee list",
 *          @OA\JsonContent(
 *              @OA\Property(property="draw", type="integer", example=1),
 *              @OA\Property(property="recordsFiltered", type="integer", example=100),
 *              @OA\Property(property="recordsTotal", type="integer", example=500),
 *              @OA\Property(property="end", type="integer", example=100),
 *              @OA\Property(property="data", type="array", @OA\Items(type="object"))
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

 Flight::route('GET /employees', function() {
    $payload = Flight::request()->query;

    // Add default values and checks for missing parameters
    $start = isset($payload['start']) ? (int)$payload['start'] : 0;
    $search = isset($payload['search']['value']) ? $payload['search']['value'] : '';
    $draw = isset($payload['draw']) ? (int)$payload['draw'] : 1;
    $limit = isset($payload['length']) ? (int)$payload['length'] : 10;
    $order_column = isset($payload['order'][0]['name']) ? $payload['order'][0]['name'] : 'id';
    $order_direction = isset($payload['order'][0]['dir']) ? $payload['order'][0]['dir'] : 'asc';

    $params = [
        "start" => $start,
        "search" => $search,
        "draw" => $draw,
        "limit" => $limit,
        "order_column" => $order_column,
        "order_direction" => $order_direction,
    ];

    // Log the params for debugging
    error_log(print_r($params, true));

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


/**
 * @OA\Post(
 *      path="/employees/add",
 *      tags={"employees"},
 *      summary="Add a new employee",
 *      description="Adds a new employee to the database.",
 *      @OA\RequestBody(
 *          description="Employee data to add",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name_surname", "position", "office", "working_hours"},
 *              @OA\Property(property="name_surname", type="string", example="John Doe"),
 *              @OA\Property(property="position", type="string", example="Manager"),
 *              @OA\Property(property="office", type="string", example="Sarajevo"),
 *              @OA\Property(property="working_hours", type="string", example="8"),
 *              @OA\Property(property="email", type="string", example="amina.hamzic@gmail.com"),
 *              @OA\Property(property="password", type="string", example="orifodn")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Employee added successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Employee added successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Invalid input"
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Server error"
 *      )
 * )
 */


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

/**
 * @OA\Delete(
 *      path="/employees/delete/{employee_id}",
 *      tags={"employees"},
 *      summary="Delete an employee by ID",
 *      description="Deletes the employee specified by the ID.",
 *      @OA\Parameter(
 *          name="employee_id",
 *          in="path",
 *          required=true,
 *          description="ID of the employee to delete",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Employee deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Employee deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */



Flight::route ('DELETE /employees/delete/@employee_id', function($employee_id) { //route & query parameter, 

    if($employee_id == NULL || $employee_id == '') {
        Flight::halt(500, 'Employee ID is required');
    }

    Flight::get('employee_service')->delete_employee($employee_id);
    Flight::json(['message' => 'Employee deleted successfully']);
    
});

/**
 * @OA\Get(
 *      path="/employees/{employee_id}",
 *      tags={"employees"},
 *      summary="Retrieve an employee by ID",
 *      description="Fetches details of the employee specified by the ID.",
 *      @OA\Parameter(
 *          name="employee_id",
 *          in="path",
 *          required=true,
 *          description="ID of the employee to fetch",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of employee",
 *          @OA\JsonContent(
 *              @OA\Property(property="id", type="integer", example=1),
 *              @OA\Property(property="name", type="string", example="John Doe"),
 *              @OA\Property(property="position", type="string", example="Manager"),
 *              @OA\Property(property="salary", type="number", format="float", example=50000)
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Employee ID is required")
 *          )
 *      )
 * )
 */


 Flight::route('GET /employees/@employee_id', function($employee_id) {
    if (is_null($employee_id) || $employee_id === '') {
        Flight::halt(500, json_encode(['message' => 'Employee ID is required']));
    }
    
    $employee = Flight::get('employee_service')->get_employee_by_id($employee_id);

    if ($employee) {
        Flight::json($employee, 200);
    } else {
        Flight::halt(404, json_encode(['message' => 'Employee not found']));
    }
});

/**
 * @OA\Put(
 *      path="/employees/update/{employee_id}",
 *      tags={"employees"},
 *      summary="Update an existing employee",
 *      description="Updates an existing employee specified by the ID.",
 *      @OA\Parameter(
 *          name="employee_id",
 *          in="path",
 *          required=true,
 *          description="ID of the employee to update",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\RequestBody(
 *          description="Employee data to update",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name_surname", "position", "office", "working_hours"},
 *              @OA\Property(property="name_surname", type="string", example="John Doe"),
 *              @OA\Property(property="position", type="string", example="Manager"),
 *              @OA\Property(property="office", type="string", example="Sarajevo"),
 *              @OA\Property(property="working_hours", type="string", example="8"),
 *              @OA\Property(property="email", type="string", example="amina@gmail.com"),
 *              @OA\Property(property="password", type="string", example="orifodn")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Employee updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="string", example="Employee updated successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad request",
 *          @OA\JsonContent(
 *              @OA\Property(property="error", type="string", example="Missing employee data")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred",
 *          @OA\JsonContent(
 *              @OA\Property(property="error", type="string", example="Internal server error")
 *          )
 *      )
 * )
 */

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
