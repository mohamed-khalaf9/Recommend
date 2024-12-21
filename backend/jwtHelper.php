<?php
require_once __DIR__ . '/vendor/autoload.php'; // Adjust the path based on your file structure

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtHelper {

   
    private static $secretKey = "$2b$10!W^n39sxA&Bz@45Xs3r@";

   
    private static $expirationTime = 3600;

    /**
     * Create a JWT token.
     *
     * @param int|string $userId The user ID to include in the token.
     * @return string The generated JWT token.
     */
    public static function createToken($userId): string {
        
        $issuedAt = time();
        $expirationTime = $issuedAt + self::$expirationTime; 
        $payload = array(
            "iss" => "your_issuer_name",  
            "iat" => $issuedAt,           
            "exp" => $expirationTime,     
            "userId" => $userId           
        );

       
        return JWT::encode($payload, self::$secretKey, 'HS256');
    }

    /**
     * Decode a JWT token.
     *
     * @param string $token The JWT token to decode.
     * @return object|null The decoded payload or null if invalid.
     */
    public static function decodeToken($token) {
        try {
            return JWT::decode($token, new Key(self::$secretKey, 'HS256'));
        } catch (Exception $e) {
            return null; // Return null if decoding fails
        }
    }

    /**
     * Verify the JWT token.
     *
     * @param string $token The JWT token to verify.
     * @return bool True if the token is valid, false otherwise.
     */
    public static function verifyToken($token): bool {
        // If decodeToken returns a payload, the token is valid
        return self::decodeToken($token) !== null;
    }
}

?>
