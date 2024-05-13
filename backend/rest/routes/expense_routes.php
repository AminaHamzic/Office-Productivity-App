<?php

require_once __DIR__ . '/../services/ExpenseService.class.php';

Flight::set('expense_service', new ExpenseService());

/**
 * @OA\Get(
 *      path="/expenses",
 *      tags={"expenses"},
 *      summary="Get paginated list of expenses",
 *      description="Retrieve a paginated list of expenses.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of expense list",
 *          @OA\JsonContent(
 *              @OA\Property(property="draw", type="integer", example=1),
 *              @OA\Property(property="recordsFiltered", type="integer", example=100),
 *              @OA\Property(property="recordsTotal", type="integer", example=500),
 *              @OA\Property(property="end", type="integer", example=100)
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

 Flight::route('GET /expenses', function() {
    try {
        $expenses = Flight::get('expense_service')->get_all_expenses_with_categories();
        Flight::json($expenses);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Post(
 *      path="/expenses/add",
 *      tags={"expenses"},
 *      summary="Add a new expense",
 *      description="Adds a new expense to the database.",
 *      @OA\RequestBody(
 *          description="Expense data to add",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"description", "dateInput", "expenseAmount", "category"},
 *              @OA\Property(property="description", type="string", example="Office supplies"),
 *              @OA\Property(property="dateInput", type="string", example="2024-05-14"),
 *              @OA\Property(property="expenseAmount", type="number", example=50.75),
 *              @OA\Property(property="category", type="string", example="Office")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Expense added successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Expense added successfully"),
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

Flight::route('POST /expenses/add', function() {
    $data = Flight::request()->data->getData();
    
    try {
        $expense = Flight::get('expense_service')->addExpense($data);
        Flight::json(['message' => "Expense added successfully", 'data' => $expense]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Put(
 *      path="/expenses/update/{expense_id}",
 *      tags={"expenses"},
 *      summary="Update an existing expense",
 *      description="Updates an existing expense specified by the ID.",
 *      @OA\Parameter(
 *          name="expense_id",
 *          in="path",
 *          required=true,
 *          description="ID of the expense to update"
 *      ),
 *      @OA\RequestBody(
 *          description="Expense data to update",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"description", "dateInput", "expenseAmount", "category"},
 *              @OA\Property(property="description", type="string", example="Office supplies"),
 *              @OA\Property(property="dateInput", type="string", example="2024-05-14"),
 *              @OA\Property(property="expenseAmount", type="number", example=50.75),
 *              @OA\Property(property="category", type="string", example="Office")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Expense updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="string", example="Expense updated successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

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

/**
 * @OA\Delete(
 *      path="/expenses/delete/{expense_id}",
 *      tags={"expenses"},
 *      summary="Delete an expense by ID",
 *      description="Deletes the expense specified by the ID.",
 *      @OA\Parameter(
 *          name="expense_id",
 *          in="path",
 *          required=true,
 *          description="ID of the expense to delete",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Expense deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Expense deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('DELETE /expenses/delete/@expense_id', function($expense_id) {
    try {
        Flight::get('expense_service')->delete_expense($expense_id);
        Flight::json(['message' => 'Expense deleted successfully']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Get(
 *      path="/expenses/{expense_id}",
 *      tags={"expenses"},
 *      summary="Retrieve an expense by ID",
 *      description="Fetches details of the expense specified by the ID.",
 *      @OA\Parameter(
 *          name="expense_id",
 *          in="path",
 *          required=true,
 *          description="ID of the expense to fetch"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of expense",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('GET /expenses/@expense_id', function($expense_id) {
    try {
        $expense = Flight::get('expense_service')->get_expense_by_id($expense_id);
        Flight::json($expense);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Get(
 *      path="/categories",
 *      tags={"categories"},
 *      summary="Get list of expense categories",
 *      description="Retrieve a list of expense categories.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of categories",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(type="string", example="Office Supplies")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('GET /categories', function() {
    $categories = Flight::get('expense_service')->get_categories();
    Flight::json($categories);
});
