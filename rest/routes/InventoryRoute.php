<?php

    Flight::route('POST /addInventory', function()
    {   
        $data = Flight::request()->data->getData();
        Flight::inventoryService()->add($data);
        Flight::json(["message" => "added"]);
    });

    Flight::route('GET /getQuantity/@id', function($id)
    {
        $amount = Flight::inventoryService()->get_amount_by_book_id($id);
        Flight::json($amount);
    });

    Flight::route('DELETE /deleteInventory/@id', function($id)
    {
        Flight::inventoryService()->delete($id);
        Flight::json(["message" => "deleted"]);
    });

    Flight::route('PUT /updateInventory', function()
    {   
        $entity = Flight::request()->data->getData();
        Flight::inventoryService()->update_quantity($entity["ID"], $entity);
        Flight::json(["message" => "updated"]);
    });