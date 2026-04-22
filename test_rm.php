<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$maxRM = \App\Models\User::whereNotNull('no_rm')->max(\Illuminate\Support\Facades\DB::raw('CAST(no_rm AS UNSIGNED)'));
echo "Max RM with CAST: " . var_export($maxRM, true) . "\n";

$maxRM2 = \App\Models\User::whereNotNull('no_rm')->orderByRaw('CAST(no_rm AS UNSIGNED) DESC')->value('no_rm');
echo "Max RM with orderByRaw: " . var_export($maxRM2, true) . "\n";
