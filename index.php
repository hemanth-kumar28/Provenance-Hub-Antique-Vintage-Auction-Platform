<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/autoload.php';

use App\Core\Router;
use App\Controllers\PageController;
use App\Controllers\AuthController;

$router = new Router();

// Page Routes
$router->get('/', [PageController::class, 'home']);
$router->get('/marketplace', [PageController::class, 'marketplace']);
$router->get('/auction', [PageController::class, 'auction']);
$router->get('/dashboard', [PageController::class, 'dashboard']);
$router->get('/dashboard/bids', [PageController::class, 'myBids']);
$router->get('/page', [PageController::class, 'staticPage']);
$router->get('/timeline', [PageController::class, 'timeline']);

// Auth Routes
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);
$router->get('/forgot-password', [AuthController::class, 'showForgot']);
$router->post('/forgot-password', [AuthController::class, 'forgot']);
$router->get('/verify-code', [AuthController::class, 'showVerify']);
$router->post('/verify-code', [AuthController::class, 'verify']);
$router->get('/reset-password', [AuthController::class, 'showReset']);
$router->post('/reset-password', [AuthController::class, 'reset']);

// Profile & Admin Routes
$router->get('/profile', [\App\Controllers\ProfileController::class, 'index']);
$router->post('/profile', [\App\Controllers\ProfileController::class, 'update']);
$router->get('/admin', [\App\Controllers\AdminController::class, 'dashboard']);
$router->get('/admin/auction/create', [\App\Controllers\AdminController::class, 'create']);
$router->post('/admin/auction/create', [\App\Controllers\AdminController::class, 'store']);
$router->get('/admin/auction/edit', [\App\Controllers\AdminController::class, 'edit']);
$router->post('/admin/auction/edit', [\App\Controllers\AdminController::class, 'update']);
$router->post('/admin/auction/delete', [\App\Controllers\AdminController::class, 'delete']);

// API Routes
$router->get('/api/search-suggestions', [\App\Controllers\ApiController::class, 'searchSuggestions']);
$router->post('/api/bids', [\App\Controllers\ApiController::class, 'placeBid']);
$router->get('/api/auction-bids', [\App\Controllers\ApiController::class, 'getBids']);
$router->get('/api/filter', [\App\Controllers\ApiController::class, 'filterAuctions']);

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
