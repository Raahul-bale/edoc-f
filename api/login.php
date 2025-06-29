<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $email = $conn->real_escape_string($data['email']);
    $password = $data['password'];
    $userType = $conn->real_escape_string($data['userType']);
    
    // Select appropriate table based on user type
    $table = '';
    switch ($userType) {
        case 'patient':
            $table = 'patient';
            break;
        case 'doctor':
            $table = 'doctor';
            break;
        case 'admin':
            $table = 'admin';
            break;
        default:
            die(json_encode([
                "status" => "error",
                "message" => "Invalid user type"
            ]));
    }
    
    $query = "SELECT * FROM $table WHERE email = '$email'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Remove password from user data
            unset($user['password']);
            
            // Generate a simple token (in production, use a proper JWT implementation)
            $token = bin2hex(random_bytes(32));
            
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "user" => $user,
                "token" => $token
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Invalid password"
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            "status" => "error",
            "message" => "User not found"
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Method not allowed"
    ]);
}

$conn->close();
?> 