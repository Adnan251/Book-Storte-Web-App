<?php
/**
 * @OA\Get(path="/purchases/", tags={"purchases"}, summary="Returns all purchases from the api. ", security={{"ApiKeyAuth": {}}},
 *      @OA\Response(response=200,description="List of purchases")
 * )
 */
Flight::route('GET /purchases', function () {
    Flight::json(Flight::purchasesService()->get_purchase_and_book_and_user());
});

/**
 * @OA\Get(path="/purchases/{id}", tags={"purchases"}, summary="Returns a single purchase by id", security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of purchase"),
 *     @OA\Response(response="200", description="Fetch individual purchase")
 * )
 */
Flight::route('GET /purchases/@id', function ($id) {
    Flight::json(Flight::purchasesService()->get_purchase_and_book_and_user_by_id($id));
});

/**
* @OA\Post(
*     path="/purchases",
*     description="Add a new purchase to db",
*     tags={"purchases"},
*     summary="Adds a new purchase ",
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic order info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="BookID", type="int", example="12",	description="Id of the book we want to sell"),
*           @OA\Property(property="User_Name", type="string", example="John",	description="Name of the user who sold the book"),
*           @OA\Property(property="User_Last_Name", type="string", example="doe",	description="Last name of the user who sold the book")
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Purchase added"
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
Flight::route('POST /purchases', function () {
    $request=Flight::request();
    $purchase = Flight::purchasesService()->add_purchase($request->data->getData());
    if($purchase == null) {
        Flight::json(['error' => 'The inventory of chosen book is empty!']);
    } else {
        Flight::json(['message' => 'Sold!']);
    }
});
