<?php

require_once __DIR__ . "./rest/services/EmployeeService.class.php"; 



$payload = $_REQUEST;

/*
if($payload ['name_surname'] == null || $payload ['name_surname'] == '') {
    header('HTTP/1.1 400 Bad Request');
    die(json_encode(array("message" => "Name and surname is required")));
}*/

$employeeService = new EmployeeService();

$employees = $employeeService-> addEmployees($payload);

echo json_encode(array("message" => "Employee added successfully!", "data" => $employees));
