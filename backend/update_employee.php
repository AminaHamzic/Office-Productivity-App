<?php
require_once __DIR__ . '/rest/services/EmployeeService.class.php';

$employee_service = new EmployeeService();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['id'])) {
    $employee_id = $_REQUEST['id'];
    $payload = $_POST; 

    try {
        $employee_service->edit_employee($employee_id, $payload);
        echo json_encode(['success' => 'Employee has been updated successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST['id'])) {
    $employee_id = $_REQUEST['id'];
    $employee = $employee_service->get_employee_by_id($employee_id);
    header('Content-Type: application/json');
    echo json_encode($employee);
} else {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Bad request']);
}
