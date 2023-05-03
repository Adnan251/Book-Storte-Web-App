<?php

    Flight::route('GET /getBooks', function()
    {   
        $books = Flight::booksService()->get_all();
        Flight::json($books);
    });

    Flight::route('POST /addBook', function()
    {   
        $data = Flight::request()->data->getData();
        Flight::booksService()->add($data);
        Flight::json(["message" => "added"]);
    });

    Flight::route('GET /getOneBook/@id', function($id)
    {
        $author = Flight::booksService()->get_by_id($id);
        Flight::json($author);
    });

    Flight::route('DELETE /deleteBook/@id', function($id)
    {
        Flight::booksService()->delete($id);
        Flight::json(["message" => "deleted"]);
    });

    Flight::route('PUT /updateBook', function()
    {   
        $entity = Flight::request()->data->getData();
        Flight::booksService()->update($entity["ID"], $entity);
        Flight::json(["message" => "updated"]);
    });

    Flight::route('GET /getBooksByName/@name', function($name)
    {   
        $books = Flight::booksService()->get_book_by_title($name);
        Flight::json($books);
    });

        Flight::route('GET /getBooksByAuthor/@authorID', function($authorID)
    {   
        $books = Flight::booksService()->get_book_by_author_id($authorID);
        Flight::json($books);
    });

        Flight::route('GET /getBooksByGenre/@genre', function($genre)
    {   
        $books = Flight::booksService()->get_book_by_genre($genre);
        Flight::json($books);
    });