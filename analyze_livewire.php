<?php

$dir = __DIR__ . '/resources/views/livewire';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$results = [];

foreach ($files as $file) {
    if ($file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false) {
        $path = $file->getPathname();
        $relativePath = str_replace(realpath(__DIR__) . '/', '', realpath($path));
        $content = file_get_contents($path);
        
        $isVolt = strpos($content, '<?php') !== false; // rough check for volt logic blocks
        
        $logicLines = 0;
        $functions = 0;
        $validations = substr_count($content, '#[Validate');
        $mounts = substr_count($content, 'mount(');
        $renderings = substr_count($content, 'rendering(');
        $updateds = substr_count($content, 'updated(');
        $dis = substr_count($content, ' function(') + substr_count($content, ' function ') - $mounts - $renderings - $updateds; // rough estimate
        // Real logic count:
        if (preg_match_all('/<\?php(.*?)\?>/s', $content, $matches)) {
            foreach ($matches[1] as $match) {
                $logicLines += substr_count($match, "\n");
                $functions += substr_count($match, 'function ');
                $functions += substr_count($match, '$'); // proxy for logic
            }
        }
        
        $results[] = [
            'path' => $relativePath,
            'logicLines' => $logicLines,
            'functions' => $functions,
            'validations' => $validations,
            'hooks' => $mounts + $renderings + $updateds
        ];
    }
}

echo json_encode($results, JSON_PRETTY_PRINT);
