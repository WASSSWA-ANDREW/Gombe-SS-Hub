<?php
// Set the content type to JavaScript
header('Content-Type: application/javascript');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Path to the manifest.json file
$manifestPath = __DIR__ . '/build/manifest.json';

// Default CSS path in case manifest doesn't exist
$cssPath = '/css/app.css';

// Check if the manifest file exists
if (file_exists($manifestPath)) {
    // Read the manifest file
    $manifest = json_decode(file_get_contents($manifestPath), true);
    
    // Check if the manifest file is valid and contains the CSS file
    if ($manifest && isset($manifest['resources/css/app.css']['file'])) {
        $cssPath = '/build/' . $manifest['resources/css/app.css']['file'];
        
        // Also update the css-check.js file with the new CSS path
        $cssCheckPath = __DIR__ . '/css-check.js';
        if (file_exists($cssCheckPath)) {
            $cssCheckContent = file_get_contents($cssCheckPath);
            $pattern = '/cssLink\.href = \'\/build\/assets\/app-.*\.css\';/';
            $replacement = "cssLink.href = '/build/{$manifest['resources/css/app.css']['file']}';";
            $updatedContent = preg_replace($pattern, $replacement, $cssCheckContent);
            
            if ($updatedContent !== $cssCheckContent) {
                file_put_contents($cssCheckPath, $updatedContent);
            }
        }
    }
}

// Output the JavaScript with the CSS path
echo "// Auto-generated CSS manifest - " . date('Y-m-d H:i:s') . "\n";
echo "window.CSS_PATH = '{$cssPath}';\n";
echo "console.log('CSS Path: {$cssPath}');\n";