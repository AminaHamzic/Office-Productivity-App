<?php

require_once __DIR__ . '/../services/HoursService.class.php';

Flight::set('hours_service', new HoursService());

/**
 * @OA\Get(
 *      path="/hours",
 *      tags={"hours"},
 *      summary="Get paginated list of hours",
 *      description="Retrieve a paginated list of hours.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of hours list",
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

 Flight::route('GET /hours', function() {
    try {
        $hours = Flight::get('hours_service')->get_all_hours_with_categories();
        Flight::json($hours);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Post(
 *      path="/hours/add",
 *      tags={"hours"},
 *      summary="Add a new entry for hours",
 *      description="Adds a new entry for hours to the database.",
 *      @OA\RequestBody(
 *          description="Hours data to add",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"date", "hours_worked", "category"},
 *              @OA\Property(property="date", type="string", format="date", example="2024-05-13"),
 *              @OA\Property(property="hours_worked", type="number", example=8),
 *              @OA\Property(property="category", type="string", example="Project Management")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Hours added successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Hours added successfully"),
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

Flight::route('POST /hours/add', function() {
    $data = Flight::request()->data->getData();
    
    try {
        $hours = Flight::get('hours_service')->addHours($data);
        Flight::json(['message' => "Hours added successfully", 'data' => $hours]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Put(
 *      path="/hours/update/{hours_id}",
 *      tags={"hours"},
 *      summary="Update an existing entry for hours",
 *      description="Updates an existing entry for hours specified by the ID.",
 *      @OA\Parameter(
 *          name="hours_id",
 *          in="path",
 *          required=true,
 *          description="ID of the hours entry to update"
 *      ),
 *      @OA\RequestBody(
 *          description="Hours data to update",
 *          required=true,
 *          @OA\JsonContent(
 *              required={"date", "hours_worked", "category"},
 *              @OA\Property(property="date", type="string", format="date", example="2024-05-13"),
 *              @OA\Property(property="hours_worked", type="number", example=8),
 *              @OA\Property(property="category", type="string", example="Project Management")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Hours entry updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="success", type="string", example="Hours entry updated successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('PUT /hours/update/@hours_id', function($hours_id) {
    $data = json_decode(Flight::request()->getBody(), true);
    if (!$data) {
        Flight::halt(400, json_encode(['error' => 'Invalid data']));
        return;
    }

    try {
        $updatedHours = Flight::get('hours_service')->edit_hours($hours_id, $data);
        Flight::json(['success' => 'Hours updated successfully', 'data' => $updatedHours]);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Delete(
 *      path="/hours/delete/{hours_id}",
 *      tags={"hours"},
 *      summary="Delete an entry for hours by ID",
 *      description="Deletes the entry for hours specified by the ID.",
 *      @OA\Parameter(
 *          name="hours_id",
 *          in="path",
 *          required=true,
 *          description="ID of the hours entry to delete",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Hours entry deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Hours entry deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('DELETE /hours/delete/@hours_id', function($hours_id) {
    try {
        Flight::get('hours_service')->delete_hours($hours_id);
        Flight::json(['message' => 'Hours entry deleted successfully']);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Get(
 *      path="/hours/{hours_id}",
 *      tags={"hours"},
 *      summary="Retrieve an entry for hours by ID",
 *      description="Fetches details of the entry for hours specified by the ID.",
 *      @OA\Parameter(
 *          name="hours_id",
 *          in="path",
 *          required=true,
 *          description="ID of the hours entry to fetch"
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of hours entry",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('GET /hours/@hours_id', function($hours_id) {
    try {
        $hours = Flight::get('hours_service')->get_hours_by_id($hours_id);
        Flight::json($hours);
    } catch (Exception $e) {
        Flight::halt(500, json_encode(['error' => $e->getMessage()]));
    }
});

/**
 * @OA\Get(
 *      path="/categories",
 *      tags={"categories"},
 *      summary="Get list of hours categories",
 *      description="Retrieve a list of hours categories.",
 *      @OA\Response(
 *          response=200,
 *          description="Successful retrieval of categories",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(type="string", example="Project Management")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Error occurred"
 *      )
 * )
 */

Flight::route('GET /categories', function() {
    $categories = Flight::get('hours_service')->get_categories();
    Flight::json($categories);
});
