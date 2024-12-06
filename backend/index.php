<?php
// Enable error reporting for troubleshooting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle preflight requests (for OPTIONS method)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    http_response_code(200);  // 200 OK
    exit;  // Stop further processing for OPTIONS request
}

// Add CORS headers for all other requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Include the Database and Router classes
include_once 'db.php'; 
include_once 'router.php';

// Get the request URI
$requestUri = $_SERVER['REQUEST_URI'];

// Ensure the URL starts with `/api/backend/`
if (strpos($requestUri, '/Recommend/backend/') !== 0) {
    http_response_code(404); 
    echo json_encode(['error' => 'Invalid API URL format']);
    exit;
}

// Process the URL to extract `resource` and `id`
$requestUri = str_replace('/Recommend/backend', '', $requestUri);
// resource/id
$requestUri = rtrim($requestUri, '/');
$uriParts = explode('/', $requestUri);

$resource = isset($uriParts[1]) ? $uriParts[1] : null;
$id = isset($uriParts[2]) ? $uriParts[2] : null;

// Get the request body 
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody,true);

// Get the HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Fetch all headers from the request
$headers = getallheaders(); // Use apache_request_headers() if needed
$authorizationHeader = isset($headers['Authorization']) ? $headers['Authorization'] : null;

// Check if resource is missing
if (!$resource) {
    http_response_code(400);
    echo json_encode(['error' => 'Resource not specified']);
    exit;
}

// Initialize the router and handle the request
$router = new Router();
$router->route($method, $authorizationHeader, $resource, $id, $data);
?>
