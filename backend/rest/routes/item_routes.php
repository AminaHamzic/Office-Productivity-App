<?php

require_once __DIR__ . '/../services/ItemService.class.php';

Flight::set('item_service', new ItemService());

/**
 * @OA\Get(
 *      path="/items",
 *      tags={"items"},
 *      summary="Get paginated list of items",
 *      description="Retrieve a paginated list of items.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of item list",
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

 Flight::route('GET /items', function() {
    try {
        $items = Flight::get('item_service')->get_all_items_with_categories();
        Flight::json($items);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Post(
 *      path="/items/add",
 *      tags={"items"},
 *      summary="Add a new item",
 *      description="Adds a new item to the database.",
 *      @OA\RequestBody(
 *          description="Item data to add",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name", "category_id", "price"},
 *              @OA\Property(property="name", type="string", example="Product Name"),
 *              @OA\Property(property="category_id", type="integer", example=1),
 *              @OA\Property(property="price", type="number", example=25.99)
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Item added successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Item added successfully"),
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

Flight::route('POST /items/add', function() {
    $data = Flight::request()->data->getData();
    
    try {
        $item = Flight::get('item_service')->addItem($data);
        Flight::json(['message' => "Item added successfully", 'data' => $item]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Put(
 *      path="/items/update/{item_id}",
 *      tags={"items"},
 *      summary="Update an existing item",
 *      description="Updates an existing item specified by the ID.",
 *      @OA\Parameter(
 *          name="item_id",
 *          in="path",
 *          required=true,
 *          description="ID of the item to update"
 *      ),
 *      @OA\RequestBody(
 *          description="Item data to update",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"name", "category_id", "price"},
 *              @OA\Property(property="name", type="string", example="Product Name"),
 *              @OA\Property(property="category_id", type="integer", example=1),
 *              @OA\Property(property="price", type="number", example=25.99)
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Item updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="string", example="Item updated successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('PUT /items/update/@item_id', function($item_id) {
    $data = json_decode(Flight::request()->getBody(), true);
    if (!$data) {
        Flight::halt(400, json_encode(['error' => 'Invalid data']));
        return;
    }

    try {
        $updatedItem = Flight::get('item_service')->edit_item($item_id, $data);
        Flight::json(['success' => 'Item updated successfully', 'data' => $updatedItem]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Delete(
 *      path="/items/delete/{item_id}",
 *      tags={"items"},
 *      summary="Delete an item by ID",
 *      description="Deletes the item specified by the ID.",
 *      @OA\Parameter(
 *          name="item_id",
 *          in="path",
 *          required=true,
 *          description="ID of the item to delete",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Item deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Item deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('DELETE /items/delete/@item_id', function($item_id) {
    try {
        Flight::get('item_service')->delete_item($item_id);
        Flight::json(['message' => 'Item deleted successfully']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Get(
 *      path="/items/{item_id}",
 *      tags={"items"},
 *      summary="Retrieve an item by ID",
 *      description="Fetches details of the item specified by the ID.",
 *      @OA\Parameter(
 *          name="item_id",
 *          in="path",
 *          required=true,
 *          description="ID of the item to fetch"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of item",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('GET /items/@item_id', function($item_id) {
    try {
        $item = Flight::get('item_service')->get_item_by_id($item_id);
        Flight::json($item);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Get(
 *      path="/categories",
 *      tags={"items"},
 *      summary="Get list of item categories",
 *      description="Retrieve a list of item categories.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of categories",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(type="string", example="Electronics")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('GET /categories', function() {
    $categories = Flight::get('item_service')->get_categories();
    Flight::json($categories);
});
