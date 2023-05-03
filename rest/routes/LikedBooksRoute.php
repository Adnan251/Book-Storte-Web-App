<?php

    Flight::route('GET /getAllLiked/@id', function($id)
    {   
        $books = Flight::likedBooksService()->get_books_by_user($id);
        Flight::json($books);
    });

    Flight::route('POST /likeABook/@bookID/@userID', function($bookID, $userID)
    {   
        Flight::likedBooksService()->add($bookID, $userID);
        Flight::json(["message" => "added"]);
    });

    Flight::route('DELETE /removelikedBook/@id', function($id)
    {
        Flight::likedBooksService()->delete($id);
        Flight::json(["message" => "deleted"]);
    });
