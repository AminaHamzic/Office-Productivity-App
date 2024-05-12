<?php

require_once __DIR__ . '/../services/ExpenseService.class.php';

Flight::set('expense_service', new ExpenseService());

Flight::route('GET /expenses', function() {
    try {
        $expense_service = Flight::get('expense_service')->get_all_expenses_with_categories();
        Flight::json($expense);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});


Flight::route('POST /expenses/add', function() {
    $data = Flight::request()->data->getData();
    error_log("Received data: " . print_r($data, true)); 

    try {
        $expense = Flight::get('expense_service')->addExpense($data);
        Flight::json(['message' => "Expense added successfully", 'data' => $expense]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});


Flight::route('PUT /expenses/update/@expense_id', function($expense_id) {
    $data = json_decode(Flight::request()->getBody(), true);
    if (!$data) {
        Flight::halt(400, json_encode(['error' => 'Invalid data']));
        return;
    }

    try {
        $updatedExpense = Flight::get('expense_service')->edit_expense($expense_id, $data);
        Flight::json(['success' => 'Expense updated successfully', 'data' => $updatedExpense]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});




Flight::route('DELETE /expenses/delete/@expense_id', function($expense_id) {
    try {
        Flight::get('expense_service')->delete_expense($expense_id);
        Flight::json(['message' => 'Expense deleted successfully']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

Flight::route('GET /expenses/@expense_id', function($expense_id) {
    try {
        $expense = Flight::get('expense_service')->get_expense_by_id($expense_id);
        Flight::json($expense);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});


Flight::route('GET /categories', function() {
    $categories = Flight::get('expense_service')->get_categories();
    Flight::json($categories);
});

