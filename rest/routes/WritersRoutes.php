<?php
/**
 * @OA\Get(path="/writers/", tags={"writers"}, summary="Returns all writers from the api. ", security={{"ApiKeyAuth": {}}},
 *      @OA\Response(response=200,description="List of publishers")
 * )
 */
Flight::route('GET /writers', function () {
    Flight::json(Flight::writersService()->get_all());
});

/**
 * @OA\Get(path="/writers/{id}", tags={"writers"}, summary="Returns a single writer by id", security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of writer"),
 *     @OA\Response(response="200", description="Fetch individual publisher")
 * )
 */
Flight::route('GET /writers/@id', function ($id) {
    Flight::json(Flight::writersService()->get_by_id($id));
});

/**
* @OA\Post(
*     path="/writers",
*     description="Add a new writer to db",
*     tags={"writers"},
*     summary="Adds a new writer to the db ",
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic writer info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="Writer_Name", type="string", example="John",	description="Name of the writer"),
*           @OA\Property(property="Writer_Last_Name", type="string", example="Doe",	description="Last name of the writer")
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Writer added"
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
Flight::route('POST /writers', function () {
    $request=Flight::request();
    Flight::writersService()->add($request->data->getData());
    Flight::json(['message' => 'updated']);
});

/**
* @OA\Put(
*     path="/writers/{id}",
*     description="Update a writer in db",
*     tags={"writers"},
*     summary="Updates a writer in db by id",
*     @OA\Parameter(in="path", name="id", example=1, description="ID of the writer we want to update"),
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic writer info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="Writer_Name", type="string", example="John",	description="Name of the writer"),
*           @OA\Property(property="Writer_Last_Name", type="string", example="Doe",	description="Last name of the writer")
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Writer updated"
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
Flight::route('PUT /writers/@id', function ($id) {
    $request=Flight::request();
    Flight::writersService()->update($request->data->getData(), $id);
    Flight::json(['message' => 'updated']);
});
