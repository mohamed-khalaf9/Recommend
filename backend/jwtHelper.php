<?php
require_once __DIR__ . '/vendor/autoload.php'; // Adjust the path based on your file structure

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class JwtHelper {

    // Secret key used to encode and decode the JWT
    private static $secretKey = "your_secret_key"; // Replace with a more secure key

    // Expiry time in seconds (e.g., 3600 seconds = 1 hour)
    private static $expirationTime = 3600;

    /**
     * Create a JWT token.
     *
     * @param int|string $userId The user ID to include in the token.
     * @return string The generated JWT token.
     */
    public static function createToken($userId): string {
        // Define the payload (data inside the token)
        $issuedAt = time();
        $expirationTime = $issuedAt + self::$expirationTime; // Token valid for 1 hour
        $payload = array(
            "iss" => "your_issuer_name",  // Issuer of the token
            "iat" => $issuedAt,           // Issued at time
            "exp" => $expirationTime,     // Expiration time
            "userId" => $userId           // User identifier
        );

        // Encode the token using the secret key
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
