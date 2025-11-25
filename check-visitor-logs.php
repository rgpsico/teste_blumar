<?php

/**
 * Script to check if visitor logs are working
 * Run with: php check-visitor-logs.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Visitor Logs Diagnostic ===\n\n";

// Check if table exists
try {
    $tableExists = \Illuminate\Support\Facades\Schema::hasTable('visitor_logs');
    echo "1. visitor_logs table exists: " . ($tableExists ? "✅ YES" : "❌ NO") . "\n";

    if (!$tableExists) {
        echo "   → Run: php artisan migrate\n\n";
        exit(1);
    }
} catch (\Exception $e) {
    echo "❌ Error checking table: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Check record count
try {
    $count = \App\Models\VisitorLog::count();
    echo "2. Total visitor logs: $count\n";

    if ($count === 0) {
        echo "   ⚠️  No visitor logs found in database\n";
        echo "   → This means the TrackVisitor middleware is not recording visits\n";
    }
} catch (\Exception $e) {
    echo "❌ Error counting logs: " . $e->getMessage() . "\n";
}

// Check recent logs
try {
    $recent = \App\Models\VisitorLog::orderBy('visited_at', 'desc')->take(5)->get();
    echo "\n3. Recent visitor logs (last 5):\n";

    if ($recent->isEmpty()) {
        echo "   📝 No logs found\n";
    } else {
        foreach ($recent as $log) {
            echo "   - [{$log->visited_at}] {$log->page_url} | IP: {$log->ip_address}\n";
        }
    }
} catch (\Exception $e) {
    echo "❌ Error fetching logs: " . $e->getMessage() . "\n";
}

// Check middleware registration
echo "\n4. TrackVisitor middleware registration:\n";
$bootstrapFile = file_get_contents(__DIR__.'/bootstrap/app.php');
if (strpos($bootstrapFile, 'TrackVisitor') !== false) {
    echo "   ✅ TrackVisitor is registered in bootstrap/app.php\n";
} else {
    echo "   ❌ TrackVisitor is NOT registered in bootstrap/app.php\n";
}

// Check API endpoint
echo "\n5. API routes check:\n";
$routesFile = file_get_contents(__DIR__.'/routes/api.php');
if (strpos($routesFile, 'visitor-logs') !== false) {
    echo "   ✅ /api/admin/visitor-logs route exists\n";
} else {
    echo "   ❌ /api/admin/visitor-logs route NOT found\n";
}

echo "\n=== Diagnostic Complete ===\n";
echo "\nIf the table exists but has no records, try:\n";
echo "1. Visit the website in a browser\n";
echo "2. Check Laravel logs: storage/logs/laravel.log\n";
echo "3. Verify middleware is executing\n";
