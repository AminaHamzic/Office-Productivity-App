<?php

require_once __DIR__ . "./rest/services/ExpenseService.class.php"; 



$payload = $_REQUEST;



$expense_service = new ExpenseService();

unset($payload['id']);
$expense = $expense_service->addExpenses($payload);


echo json_encode(['message' => "You have successfully added the expense", 'data' => $expense]);