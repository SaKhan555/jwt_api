<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-type: Application/json; charset=UTF-8");

require '../config/Database.php';
require '../classes/User.php';

$conn = new Database();
$user = new User($conn->connect());
if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $request = json_decode( file_get_contents('php://input') );
    if( !empty($request->name) && !empty($request->email) && !empty($request->password) )
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = password_hash($request->password, PASSWORD_DEFAULT);
        if( empty( $user->existUser() ) ) {
            if ($user->createUser()) {
                http_response_code(200);
                echo json_encode([
                    'status' => 1,
                    'message' => 'User created.'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'status' => 0,
                    'message' => 'Failed to create user'
                ]);
            }
        }
        else
        {
            http_response_code(400);
            echo json_encode([
               'status' => 0,
                'message' => 'User already exist with this email, try another email id'
            ]);
        }
    }
    else
    {
        http_response_code(404);
        echo json_encode([
            'status' => 0,
            'message' => 'Values are missing'
        ]);
    }
}
else
{
    http_response_code(400);
    echo json_encode([
        'status' => 0,
        'message' => 'Bad request'
    ]);
}