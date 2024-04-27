<?php

require_once __DIR__ . "./rest/services/EmployeeService.class.php"; 



$payload = $_REQUEST;



$employee_service = new EmployeeService();

if($payload['id'] != NULL && $payload['id'] != ''){
    $employee = $employee_service->edit_employee($payload);
} else {
    unset($payload['id']);
    $employee = $employee_service->addEmployees($payload);
}

echo json_encode(['message' => "You have successfully added the patient", 'data' => $employee]);