<?php
/**
 * @OA\Get(path="/orders/", tags={"orders"}, summary="Returns all orders from the api. ", security={{"ApiKeyAuth": {}}},
 *      @OA\Response(response=200,description="List of orders")
 * )
 */
Flight::route('GET /orders',function()
{
    Flight::json(Flight::ordersService()->get_orders_and_users());
});

/**
 * @OA\Get(path="/orders/{id}", tags={"orders"}, summary="Returns an order by id",security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of order"),
 *     @OA\Response(response="200", description="Fetch individual order")
 * )
 */
Flight::route('GET /orders/@id',function($id)
{
    Flight::json(Flight::ordersService()->get_order_by_id($id));
});

/**
* @OA\Post(
*     path="/orders",
*     description="Add a new order to db",
*     tags={"orders"},
*     summary="Adds a new order to the db ",
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic order info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="Order_Amount", type="int", example="Some number",	description="Amount of books ordered"),
*           @OA\Property(property="Book_Name", type="string", example="Some book",	description="Name of the book"),
*           @OA\Property(property="Date_of_Order", type="date", example="YYYY-MM-DD",	description="The date the order was made"),
*           @OA\Property(property="Date_of_Delivery", type="date", example="YYYY-MM-DD",	description="The date the order was delivered"),
*           @OA\Property(property="User_Name", type="string", example="John",	description="Name of the user who made the order"),
*           @OA\Property(property="User_Last_Name", type="string", example="Doe",	description="Last name of the user who made the order"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Order added"
*     ),
*     @OA\Response(
*         response=404,
*         description="Unexpected error"
*     ),
*     @OA\Response(
*         response=403,
*         description="JWT token not passed"
*     )
* )
*/
Flight::route('POST /orders', function()
{
    $request=Flight::request();
    $order=Flight::ordersService()->add_order_with_user($request->data->getData());
    if($order==null){
        Flight::json(['error' => 'Please add book entry before creating order!']);
    } else {
        Flight::json(['message' => 'updated']);
    }
});

/**
* @OA\Put(
*     path="/orders/{id}",
*     description="Add a new order to db",
*     tags={"orders"},
*     summary="Updates an order in db by id",
*     @OA\Parameter(in="path", name="id", example=1, description="ID of the order we want to update"),
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic order info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="Order_Amount", type="int", example="Some number",	description="Amount of books ordered"),
*           @OA\Property(property="Book_Name", type="string", example="Some book",	description="Name of the book"),
*           @OA\Property(property="Date_of_Order", type="date", example="YYYY-MM-DD",	description="The date the order was made"),
*           @OA\Property(property="Date_of_Delivery", type="date", example="YYYY-MM-DD",	description="The date the order was delivered"),
*           @OA\Property(property="User_Name", type="string", example="John",	description="Name of the user who made the order"),
*           @OA\Property(property="User_Last_Name", type="string", example="Doe",	description="Last name of the user who made the order"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Order added"
*     ),
*     @OA\Response(
*         response=404,
*         description="Unexpected error"
*     ),
*     @OA\Response(
*         response=403,
*         description="JWT token not passed"
*     )
* )
*/
Flight::route('PUT /orders/@id',function($id)
{
    $request=Flight::request();
    $order = Flight::ordersService()->update_order($request->data->getData(),$id);
    if($order==null){
        Flight::json(['error' => 'Please add book entry before creating order!']);
    } else {
        Flight::json(['message' => 'updated']);
    }
});


/** 
*   @OA\Delete(
*     path="/orders/{id}", security={{"ApiKeyAuth": {}}},
*     description="Delete order",
*     summary="Deletes an order from db by its id",
*     tags={"orders"},
*     @OA\Parameter(in="path", name="id", example=1, description="Id of order"),
*     @OA\Response(
*         response=200,
*         description="User deleted"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error, may indicate JWT abuse"
*     )
* )
*/
Flight::route('DELETE /orders/@id',function($id)
{
    Flight::ordersService()->delete($id);
    Flight::json(['message'=>'deleted']);
});
