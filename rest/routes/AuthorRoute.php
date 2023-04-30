<?php

    Flight::route('POST /getAuthor', function()
    {
        Flight::json($user);
    });