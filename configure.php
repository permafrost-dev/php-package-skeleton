<?php


function process_files($dir, $packageName, $namespace)
{
    if (!is_dir($dir)) {
        echo "* error: $dir is not a directory.\n";
        return;
    }
    
    $files = scandir($dir);
        
    foreach($files as $file) {
        if (in_array($file, ['.', '..', '.git', '.github', '.gitignore', 'configure.php'])) {
            continue;
        }
    
        $file = "$dir/$file";
        $relativeFn = str_replace(__DIR__ . DIRECTORY_SEPARATOR, '', $file);
        
        if (!is_file($file)) {
            process_files($file, $packageName, $namespace);
            continue;
        }
        if (basename($file) === basename(__FILE__)) {
            continue;
        }
        
        $content = file_get_contents($file);
        $changed = false;
        
        if (strpos($content, 'permafrost-dev/package-skeleton') !== false) {
            $content = str_replace('permafrost-dev/package-skeleton', "permafrost-dev/{$packageName}", $content);
            $changed = true;
        }
        
        if (preg_match('~package-skeleton~', $content) === 1) {
            $content = str_replace('package-skeleton', "{$packageName}", $content);
            $changed = true;
        }
        
        if (preg_match('~Permafrost\\\\?Example(;|\\\\?)~', $content) === 1) {
            $content = preg_replace('~(Permafrost\\\\?)Example~', "$1{$namespace}", $content);
            $changed = true;
        }
        
        if (strpos($content, 'Permafrost\\\\Example') !== false) {
            $content = str_replace('Permafrost\\\\Example', "Permafrost\\\\{$namespace}", $content);
            $changed = true;
        }
        
        if ($changed) {
           file_put_contents($file, $content);
           echo "* writing file $relativeFn\n";
           $changed = false;
        }    
    }
}

function validate_input($packageName, $namespace)
{
    if (empty($packageName) || empty($namespace)) {
        echo "* error: package name and namespace must not be empty.\n";
        exit(1);
    }
}

$packageName = trim(readline('project name: '));
$namespace = trim(readline('namespace: '));

validate_input($packageName, $namespace);
process_files(__DIR__, $packageName, $namespace);

echo "* finished configuring new package.\n";

