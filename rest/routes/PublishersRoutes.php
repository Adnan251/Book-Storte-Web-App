<?php
/**
 * @OA\Get(path="/publishers/", tags={"publishers"}, summary="Returns all publishers from the api. ", security={{"ApiKeyAuth": {}}},
 *      @OA\Response(response=200,description="List of publishers")
 * )
 */
Flight::route('GET /publishers',function()
{
    Flight::json(Flight::publisherService()->get_all());
});

/**
 * @OA\Get(path="/publishers/{id}", tags={"publishers"}, summary="Returns a single publisher by id", security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of publisher"),
 *     @OA\Response(response="200", description="Fetch individual publisher")
 * )
 */
Flight::route('GET /publishers/@id',function($id)
{
    Flight::json(Flight::publisherService()->get_by_id($id));
});

/**
* @OA\Post(
*     path="/publishers",
*     description="Add a new publisher to db",
*     tags={"publishers"},
*     summary="Adds a new publisher to the db ",
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic publisher info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="name", type="string", example="Some publisher",	description="Name of the publisher"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Publisher added"
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
Flight::route('POST /publishers', function()
{
    $request=Flight::request();
    Flight::publisherService()->add($request->data->getData());
    Flight::json(['message' => 'updated']);
});

/**
* @OA\Put(
*     path="/publishers/{id}",
*     description="Add a new publisher to db",
*     tags={"publishers"},
*     summary="Updates one publisher",
*     @OA\Parameter(in="path", name="id", example=1, description="ID of the publisher we want to update"),
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic book info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="name", type="string", example="Some publisher",	description="Name of the publisher"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Publisher added"
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
Flight::route('PUT /publishers/@id',function($id)
{
    $request=Flight::request();
    Flight::publisherService()->update($request->data->getData(),$id);
    Flight::json(['message' => 'updated']);
});
?>