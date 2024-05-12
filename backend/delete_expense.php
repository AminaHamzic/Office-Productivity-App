<?php

require_once __DIR__ . "./rest/services/ExpenseService.class.php";

$expense_id = $_REQUEST['id'];

if($expense_id == NULL || $expense_id == '') {
    header('HTTP/1.1 500 Bad Request');
    die(json_encode(['error' => "You have to provide valid expense id!"]));
}

$expense_service = new ExpenseService();
$expense_service->delete_expense($expense_id);
echo json_encode(['message' => 'You have successfully deleted the expense!']);

