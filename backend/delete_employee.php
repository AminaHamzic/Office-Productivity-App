<?php

require_once __DIR__ . "./rest/services/EmployeeService.class.php";

$employee_id = $_REQUEST['id'];

if($employee_id == NULL || $employee_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "You have to provide valid employee id!"]));
}

$employee_service = new EmployeeService();
$employee_service->delete_employee($employee_id);
echo json_encode(['message' => 'You have successfully deleted the employee!']);

