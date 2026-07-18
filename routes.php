<?php

use App\Controllers\AuthController;
use App\Controllers\CashierController;
use App\Controllers\CustomerController;
use App\Controllers\DashboardController;
use App\Controllers\EmployeeController;
use App\Controllers\ExpenseController;
use App\Controllers\QueueController;
use App\Controllers\ReportController;
use App\Controllers\ServiceController;
use App\Controllers\SettingsController;

$router->get('/', [DashboardController::class, 'index']);
$router->post('/dashboard/mark-paid', [DashboardController::class, 'markAsPaid']);
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/change-password', [AuthController::class, 'changePassword']);
$router->post('/change-password', [AuthController::class, 'updatePassword']);

$router->get('/customers', [CustomerController::class, 'index']);
$router->post('/customers/save', [CustomerController::class, 'save']);
$router->post('/customers/delete', [CustomerController::class, 'delete']);
$router->get('/api/customers/history', [CustomerController::class, 'history']);

$router->get('/cashier', [CashierController::class, 'index']);
$router->post('/cashier/checkout', [CashierController::class, 'checkout']);
$router->get('/receipt', [CashierController::class, 'receipt']);

$router->get('/queue', [QueueController::class, 'index']);
$router->get('/api/queue', [QueueController::class, 'api']);
$router->post('/queue/status', [QueueController::class, 'status']);

$router->get('/services', [ServiceController::class, 'index']);
$router->post('/services/save', [ServiceController::class, 'save']);
$router->post('/services/delete', [ServiceController::class, 'delete']);
$router->post('/services/category/save', [ServiceController::class, 'saveCategory']);
$router->post('/services/category/delete', [ServiceController::class, 'deleteCategory']);


$router->get('/employees', [EmployeeController::class, 'index']);
$router->post('/employees/save', [EmployeeController::class, 'save']);
$router->post('/employees/delete', [EmployeeController::class, 'delete']);

$router->get('/expenses', [ExpenseController::class, 'index']);
$router->post('/expenses/save', [ExpenseController::class, 'save']);
$router->post('/expenses/delete', [ExpenseController::class, 'delete']);

$router->get('/reports', [ReportController::class, 'index']);
$router->get('/settings', [SettingsController::class, 'index']);
$router->post('/settings/save', [SettingsController::class, 'save']);

