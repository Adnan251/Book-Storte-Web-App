<?php

    Flight::route('GET /getAuthors', function()
    {   
        $authors = Flight::authorService()->get_all();
        Flight::json($authors);
    });

    Flight::route('POST /addAuthor', function()
    {   
        $data = Flight::request()->data->getData();
        Flight::authorService()->add($data);
        Flight::json(["message" => "added"]);
    });

    Flight::route('GET /getOneAuthor/@id', function($id)
    {
        $author = Flight::authorService()->get_by_id($id);
        Flight::json($author);
    });

    Flight::route('DELETE /deleteAuthor/@id', function($id)
    {
        Flight::authorService()->delete($id);
        Flight::json(["message" => "deleted"]);
    });

    Flight::route('PUT /updateAuthor', function()
    {   
        $entity = Flight::request()->data->getData();
        Flight::authorService()->update($entity["ID"], $entity);
        Flight::json(["message" => "updated"]);
    });

    Flight::route('GET /getAuthorByName/@name', function($name)
    {
        $author = Flight::authorService()->get_author_by_name($name);
        Flight::json($author);
    });