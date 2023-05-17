<?php

/**
 * @OA\Info(title="Stattrack API Specs", version="0.2", 
 * @OA\Contact(email="adnan.dzindo@stu.ibu.edu.ba", name="Adnan Džindo"),
 * @OA\Contact(email="dzeneta.dzananovic@gmail.com", name="Dzeneta Dzananovic"),
 * )
 * @OA\OpenApi(
 *    @OA\Server(url="http://localhost/Book-Storte-Web-App/rest", description="Development Environment" ),
 *    @OA\Server(url="https://stattrack.me/rest", description="Production Environment" )
 * ),
 * @OA\SecurityScheme(securityScheme="ApiKeyAuth", type="apiKey", in="header", name="Authorization" )
 */
