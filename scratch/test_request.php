<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Bypass CSRF middleware
$app->instance(\App\Http\Middleware\VerifyCsrfToken::class, new class {
    public function handle($request, $next) { return $next($request); }
});

// Target user is 14
$target = User::find(14);
echo "Target: " . $target->name . " | Status: " . $target->status . "\n";

// Run request
$request = Request::create('/superadmin/approvals/' . $target->id . '/approve', 'POST');

// Bind request to container
$app->instance('request', $request);

// Act as Super Admin (ID 1)
$superadmin = User::find(1);
auth()->login($superadmin);

// Add session
$request->setLaravelSession($app['session']->driver());

$response = $kernel->handle($request);

echo "Status Code: " . $response->getStatusCode() . "\n";
if ($response->isRedirection()) {
    echo "Redirect location: " . $response->headers->get('Location') . "\n";
    // Check if error or success in session
    $session = $app['session']->driver();
    echo "Session Error: " . $session->get('error') . "\n";
    echo "Session Success: " . $session->get('success') . "\n";
    
    // Check database status
    $target->refresh();
    echo "New Target Status: " . $target->status . "\n";
} else {
    echo "Content: " . substr($response->getContent(), 0, 500) . "\n";
}
