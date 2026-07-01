<?php

/**
 * Helper script to find all translation strings in Blade files 
 * and append missing ones to lang/id.json
 * 
 * Usage: php update_translations.php
 */

$viewsPath = __DIR__ . '/resources/views';
$langFile = __DIR__ . '/lang/id.json';

echo "Scanning for translation strings in {$viewsPath}...\n";

// Read existing translations
$existing = file_exists($langFile) ? json_decode(file_get_contents($langFile), true) : [];
if (!is_array($existing)) $existing = [];

// Recursively find all blade files
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsPath));
$bladeFiles = [];
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $bladeFiles[] = $file->getPathname();
    }
}

// Find all translation keys
$keys = [];
foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    
    // Match __('Text')
    preg_match_all("/__\('([^']+)'\)/", $content, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $match) {
            $keys[$match] = true;
        }
    }
    
    // Match __("Text")
    preg_match_all('/__\("([^"]+)"\)/', $content, $matches2);
    if (!empty($matches2[1])) {
        foreach ($matches2[1] as $match) {
            $keys[$match] = true;
        }
    }
}

$added = 0;
foreach (array_keys($keys) as $key) {
    if (!array_key_exists($key, $existing)) {
        // Add missing key with the English string as default value
        $existing[$key] = $key;
        $added++;
        echo "Missing translation found: \"$key\"\n";
    }
}

// Sort alphabetically for neatness
ksort($existing);

// Save back to id.json
file_put_contents($langFile, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

if ($added > 0) {
    echo "\nSuccess! Added $added missing translation keys to id.json.\n";
    echo "You can now open lang/id.json and translate the newly added keys.\n";
} else {
    echo "\nNo missing translations found. Your dictionary is up to date!\n";
}
