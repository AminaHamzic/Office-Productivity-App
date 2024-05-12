<?php
require_once __DIR__ . '/rest/services/ExpenseService.class.php';

$expense_service = new ExpenseService();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_REQUEST['id'])) {
    $expense_id = $_REQUEST['id'];
    $payload = $_POST; 

    try {
        $expense_service->edit_expense($expense_id, $payload);
        echo json_encode(['success' => 'expense has been updated successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_REQUEST['id'])) {
    $expense_id = $_REQUEST['id'];
    $expense = $expense_service->get_expense_by_id($expense_id);
    header('Content-Type: application/json');
    echo json_encode($expense);
} else {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Bad request']);
}
