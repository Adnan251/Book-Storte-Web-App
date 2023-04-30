<?php

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require __DIR__.'./vendor/autoload.php';
    require_once __DIR__.'/rest/services/UserService.class.php';
    require_once __DIR__.'/rest/services/OrderService.class.php';
    require_once __DIR__.'/rest/services/InventoryService.class.php';
    require_once __DIR__.'/rest/services/BooksService.class.php';
    require_once __DIR__.'/rest/services/AuthorService.class.php';

    require_once __DIR__.'/rest/dao/AuthorDao.class.php';
    require_once __DIR__.'/rest/dao/BaseDao.class.php';
    require_once __DIR__.'/rest/dao/BooksDao.class.php';
    require_once __DIR__.'/rest/dao/InventoryDao.class.php';
    require_once __DIR__.'/rest/dao/OrderBookDao.class.php';
    require_once __DIR__.'/rest/dao/OrderDao.class.php';
    require_once __DIR__.'/rest/dao/UserDao.class.php';


    Flight::set('flight.log_errors', true);
    Flight::register('userDao', 'UserDao');
    Flight::register('authorDao', 'AuthorDao');
    Flight::register('booksDao', 'BooksDao');
    Flight::register('inventoryDao', 'InventoryDao');
    Flight::register('orderBookDao', 'OrderBookDao');
    Flight::register('orderDao', 'OrderDao');
    
    Flight::register('userService', 'UserService');
    Flight::register('orderService', 'OrderService');
    Flight::register('orderBookService', 'OrderBookService');
    Flight::register('inventoryService', 'InventoryService');
    Flight::register('booksService', 'BooksService');
    Flight::register('authorService', 'AuthorService');

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
        return urldecode($query_param);
    });

    /* REST API documentation endpoint */
    Flight::route('GET /docs.json', function()
    {
        $openapi = \OpenApi\scan('routes');
        header('Content-Type: application/json');
        echo $openapi->toJson();
    });

    Flight::start();
?>