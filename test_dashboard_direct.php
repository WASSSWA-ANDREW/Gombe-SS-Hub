<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a request for the dashboard
$request = Illuminate\Http\Request::create('/admin/dashboard', 'GET');

try {
    echo "Testing dashboard controller directly...\n";
    
    // Create controller instance
    $controller = new App\Http\Controllers\Admin\DashboardController();
    
    // Call the index method
    $response = $controller->index();
    
    echo "✅ Dashboard controller executed successfully!\n";
    echo "Response type: " . get_class($response) . "\n";
    
    if ($response instanceof Illuminate\View\View) {
        echo "✅ View returned successfully\n";
        $data = $response->getData();
        echo "Data keys: " . implode(', ', array_keys($data)) . "\n";
        
        if (isset($data['ageGroups'])) {
            echo "Age groups data: " . json_encode($data['ageGroups']) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}