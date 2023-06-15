<?php
/**
 * @OA\Get(path="/books/", tags={"books"}, summary="Returns all books from the api. ", security={{"ApiKeyAuth": {}}},
 *      @OA\Response(response=200,description="List of books")
 * )
 */
Flight::route('GET /books', function () {
    Flight::json(Flight::booksService()->get_books_with_writer_names());
});

/**
 * @OA\Get(path="/books/{id}", tags={"books"}, summary="Returns a single book by id", security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of book"),
 *     @OA\Response(response="200", description="Fetch individual book")
 * )
 */
Flight::route('GET /books/@id', function ($id) {
    Flight::json(Flight::booksService()->get_by_id_with_writer_names($id));
});


/**
 * @OA\Get(path="/books/search/{name}", tags={"books"}, summary="Returns a number of books that match the given paramater ", security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="name", description="Searches a book through its name"),
 *     @OA\Response(response="200", description="Success")
 * )
 */
Flight::route('PUT /books/remove/@id', function ($id) {
    $book = Flight::booksService()->update_the_books($id);
    if($book==null) {
        Flight::json(["error"=>"Failed!"]);
    } else {
        Flight::json(['message' => 'Updated!']);
    }
});

/**
 * @OA\Get(path="/books/search/{name}", tags={"books"}, summary="Returns a number of books that match the given paramater ", security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="name", description="Searches a book through its name"),
 *     @OA\Response(response="200", description="Success")
 * )
 */
Flight::route('GET /books/search/@name', function ($name) {
    Flight::json(Flight::booksService()->search_book($name));
});


/**
 * @OA\Get(path="/search_books/writer", tags={"books"}, summary="Return the provided books from the api ",security={{"ApiKeyAuth": {}}},
 *         @OA\Parameter(in="query", name="name", description="Search criteria"),
 *         @OA\Parameter(in="query", name="lastname", description="Search criteria"),
 *         @OA\Response( response=200, description="List of pets.")
 * )
 */
Flight::route('GET /search_books/writer', function () {
    $name = Flight::query('name');
    $lastName = Flight::query('lastname');
    Flight::json(Flight::booksService()->get_book_by_writer($name, $lastName));
});

/**
* @OA\Post(
*     path="/books",
*     description="Add a new book to db",
*     tags={"books"},
*     summary="Adds a new book to the db",
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic book info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="Book_Name", type="string", example="Some book",	description="Name of the book"),
*           @OA\Property(property="name", type="string", example="Some publisher",	description="Name of the publisher"),
*           @OA\Property(property="Year_of_publishing", type="int", example="YYY",	description="The year of the book's publishing"),
*           @OA\Property(property="Book_price", type="float", example="0.00",	description="Price of the book"),
*           @OA\Property(property="In_inventory", type="int", example="0",	description="Number of books in inventory"),
*           @OA\Property(property="Writer_Name", type="string", example="John",	description="Name of the writer"),
*           @OA\Property(property="Writer_Last_Name", type="string", example="Doe",	description="Last name of the writer"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Book added"
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
Flight::route('POST /books', function () {
    $data=Flight::request()->data->getData();
    $book = Flight::booksService()->add_book_and_writer($data);
    if($book==null) {
        Flight::json(["error"=>"Cannot add an existing book!"]);
    } else {
        Flight::json(['message' => 'Book added']);
    }
});

/**
* @OA\Put(
*     path="/books/{id}",
*     description="Add a new book to db",
*     tags={"books"},
*     summary="Updates the selected book by id",
*     @OA\Parameter(in="path", name="id", example=1, description="ID of the book we want to update"),
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic book info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="Book_Name", type="string", example="Some book",	description="Name of the book"),
*           @OA\Property(property="name", type="string", example="Some publisher",	description="Name of the publisher"),
*           @OA\Property(property="Year_of_publishing", type="int", example="YYY",	description="The year of the book's publishing"),
*           @OA\Property(property="Book_price", type="float", example="0.00",	description="Price of the book"),
*           @OA\Property(property="In_inventory", type="int", example="0",	description="Number of books in inventory"),
*           @OA\Property(property="Writer_Name", type="string", example="John",	description="Name of the writer"),
*           @OA\Property(property="Writer_Last_Name", type="string", example="Doe",	description="Last name of the writer"),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Book added"
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
Flight::route('PUT /books/@id', function ($id) {
    $request=Flight::request();
    $book = Flight::booksService()->update_book_and_writer($request->data->getData(), $id);
    if($book==null) {
        Flight::json(["error"=>"Cannot add an existing book!"]);
    } else {
        Flight::json(['message' => 'updated']);
    }
});
