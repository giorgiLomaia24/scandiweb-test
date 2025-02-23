<?php

declare(strict_types=1);



$allowedOrigin = $_SERVER['HTTP_ORIGIN'] ?? 'http://localhost:5173';
header("Access-Control-Allow-Origin: $allowedOrigin", true);
header("Access-Control-Allow-Methods: GET, POST, OPTIONS", true);
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With", true);
header("Access-Control-Allow-Credentials: true", true);
header("Content-Type: application/json", true);


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

ob_start();

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;
use App\GraphQL\Mutations\MutationType;
use App\GraphQL\Queries\QueryType;
use Dotenv\Dotenv;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;


try {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} catch (Exception $e) {
    error_log("⚠️ Error loading .env file: " . $e->getMessage());
}


try {
    Database::connect();
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal Server Error']);
    exit();
}

try {
    // Define GraphQL Schema
    $schema = new Schema([
        'query' => new QueryType(),
        'mutation' => new MutationType(),
    ]);

    // Read GraphQL Request
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    if (!isset($input['query'])) {
        throw new Exception("No query found in request.");
    }

    $query = $input['query'];
    $variables = $input['variables'] ?? null;

    $result = GraphQL::executeQuery($schema, $query, null, null, $variables);
    $output = $result->toArray(DebugFlag::INCLUDE_DEBUG_MESSAGE);
} catch (Exception $e) {
    error_log("⚠️ GraphQL Execution Error: " . $e->getMessage());
    http_response_code(500);
    $output = ['error' => 'Internal Server Error'];
}

ob_end_flush();

echo json_encode($output);
