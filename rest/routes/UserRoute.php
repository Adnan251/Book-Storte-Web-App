<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    require 'Password.php';

    Flight::route('POST /register', function()
    {
        $data = Flight::request()->data->getData(); //get data from post ajax
        $data['password'] = md5($data['password']); //hash the password
        $user = Flight::userService()->add($data); //add user to db
        Flight::json($user);
    });

    
    Flight::route('POST /login', function()
    {
        $login = Flight::request()->data->getData(); //get data from post ajax
        $user = Flight::userDao()->getUserByEmail($login['emailLogIn']); 
        if (isset($user['iduser'])){ //check if user exists in db
            if($user['password'] == md5($login['passwordLogIn'])){ //check password validity
                unset($user['password']);
                $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256'); //if all ios good, create jwt token and return it to the ajax call
                Flight::json(['token' => $jwt]);
            }else{
                Flight::json(["message" => "Wrong password"], 404);
            }
        }else{
            Flight::json(["message" => "User doesn't exist"], 404);
        }
    });

    Flight::route('GET /updateToAdmin', function()
    {
        $data = Flight::require()->data->getData();
        $user = Flight::userDao()->get_user_by_username($data['Username']);

        $user['IsAdmin'] = "true";
        Flight::userDao()->update();
    });