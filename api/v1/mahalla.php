<?php
// api/v1/mahalla.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../core/Auth.php';

if (!Auth::isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '';

switch ($path) {
    case '/stats':
        if ($method === 'GET') {
            $mahallaId = $_GET['id'] ?? null;
            if ($mahallaId) {
                $mahallaModel = new Mahalla();
                $stats = $mahallaModel->getStatistics($mahallaId);
                echo json_encode($stats);
            }
        }
        break;
        
    case '/crimes':
        if ($method === 'GET') {
            $crimeModel = new Crime();
            $filters = $_GET;
            $crimes = $crimeModel->getByFilters($filters);
            echo json_encode($crimes);
        }
        break;
        
    // More API endpoints...
}