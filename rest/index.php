<?php

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__.'/../vendor/autoload.php';
    require_once __DIR__.'/services/BooksService.class.php';
    require_once __DIR__.'/services/OrdersService.class.php';
    require_once __DIR__.'/services/PurchasesService.class.php';
    require_once __DIR__.'/services/UsersService.class.php';
    require_once __DIR__.'/services/WritersService.class.php';
    require_once __DIR__.'/services/PublishersService.class.php';
    require_once __DIR__.'/services/BooksAndWritersService.class.php';

    Flight::register('booksService','BooksService');
    Flight::register('booksAndWritersService','BooksAndWritersService');
    Flight::register('purchasesService','PurchasesService');
    Flight::register('ordersService','OrdersService');
    Flight::register('writersService','WritersService');
    Flight::register('usersService','UsersService');
    Flight::register('publishersService','PublishersService');

    // Handle error
    Flight::map('error', function(Exception $ex)
    {
        Flight::json(['message' => $ex->getMessage()], 500);
    });

    /* utility function for reading query parameters from URL */
    Flight::map('query', function($name, $default_value = "")
    {
        $request = Flight::request();
        $query_param = @$request->query->getData()[$name];
        $query_param = $query_param ? $query_param : $default_value;
        return rawurldecode($query_param);
    });

    Flight::route('/*', function()
    {
        //perform JWT decode
        $path = Flight::request()->url;
        if ($path == '/' || $path == '/login' || $path == '/register' || $path == '/docs.json') return TRUE;
        // || // exclude routes from middleware
        // str_starts_with($path, '/summonersMobileAPI/') || str_starts_with($path, '/summoners/')
        $headers = getallheaders();
        if (@!$headers['Authorization']){
            Flight::json(["message" => "Authorization is missing"], 403);
            return FALSE;
        }else{
            try {
                $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
                Flight::set('user', $decoded);
                return TRUE;
            } catch (\Exception $e) {
                Flight::json(["message" => "Authorization token is not valid"], 403);
                return FALSE;
            }
        }
    });

    /* REST API documentation endpoint */
    Flight::route('GET /docs.json', function()
    {
        $openapi = \OpenApi\scan('routes');
        header('Content-Type: application/json');
        echo $openapi->toJson();
    });

    require_once __DIR__.'/Routes/BooksRoutes.php';
    require_once __DIR__.'/Routes/PurchasesRoutes.php';
    require_once __DIR__.'/Routes/OrdersRoutes.php';
    require_once __DIR__.'/Routes/UsersRoutes.php';
    require_once __DIR__.'/Routes/WritersRoutes.php';
    require_once __DIR__.'/Routes/PublishersRoutes.php';
    require_once __DIR__.'/Routes/BooksAndWritersRoutes.php'; 

    Flight::start();
