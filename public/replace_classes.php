<?php
function replaceInDir($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            replaceInDir($path);
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($path);
            $newContent = str_replace('class="wrapper"', 'class="ls-dashboard-container"', $content);
            $newContent = str_replace('class="main-content"', 'class="ls-main-content"', $newContent);
            if ($newContent !== $content) {
                file_put_contents($path, $newContent);
                echo "Updated: $path\n";
            }
        }
    }
}

replaceInDir(__DIR__ . '/../views');
echo "Done.\n";
