<?php

// Load all API route files from the Domains directory
$domainRouteFiles = glob(base_path('app/Domains/*/routes/api.php'));

foreach ($domainRouteFiles as $file) {
    require $file;
}
