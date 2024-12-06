<?php

class HttpResponse {
    // Static function to send HTTP responses with custom headers and JSON body
    public static function send(int $statusCode, ?array $headers = null, ?array $body = null): void {
        // Set the status code
        http_response_code($statusCode);
        
        // Check if headers are provided and iterate only if it's an array
        if (is_array($headers)) {
            foreach ($headers as $key => $value) {
                header("$key: $value");
            }
        }
    
        // Set the content-type header
        header("Content-Type: application/json");
    
        // Output the JSON-encoded body if provided
        if ($body !== null) {
            echo json_encode($body, JSON_PRETTY_PRINT);
        }
    }
    
}

?>