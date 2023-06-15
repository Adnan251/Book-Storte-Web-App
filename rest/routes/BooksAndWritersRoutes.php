<?php

/**
 * @OA\Get(path="/baw/", tags={"books and writers"}, summary="Returns all baws from the api. ", security={{"ApiKeyAuth": {}}},
 *      @OA\Response(response=200,description="List of baws")
 * )
 */
Flight::route('GET /baw', function () {
    Flight::json(Flight::booksAndWritersService()->get_all());
});

/**
 * @OA\Get(path="/baw/{id}", tags={"books and writers"}, summary="Returns a single baw by id", security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of baw"),
 *     @OA\Response(response="200", description="Fetch individual baw")
 * )
 */
Flight::route('GET /baw/@id', function ($id) {
    Flight::json(Flight::booksAndWritersService()->get_by_id($id));
});

/**
* @OA\Post(
*     path="/baw",
*     description="Add a new baw to db",
*     tags={"books and writers"},
*     summary="Adds a new baw to the db",
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic book info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="writerid", type="int", example="1",	description="Id of the writer"),
*           @OA\Property(property="bookid", type="int", example="1",	description="Id of the book")
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="baw added"
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
Flight::route('POST /baw', function () {
    $request=Flight::request();
    Flight::booksAndWritersService()->add($request->data->getData());
    Flight::json(['message' => 'updated']);
});

/**
* @OA\Put(
*     path="/baw/{id}",
*     description="Add a new baw to db",
*     tags={"books and writers"},
*     summary="Update a baw from the db by id",
*     @OA\Parameter(in="path", name="id", example=1, description="ID of the baw we want to update"),
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic book info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="writerid", type="int", example="1",	description="Id of the writer"),
*           @OA\Property(property="bookid", type="int", example="1",	description="Id of the book")
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="baw updated"
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
Flight::route('PUT /baw/@id', function ($id) {
    $request=Flight::request();
    Flight::booksAndWritersService()->update($request->data->getData(), $id);
    Flight::json(['message' => 'updated']);
});
