<?php

$directory = __DIR__ . '/src/Entity';

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($directory)
);
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
        continue;
    }

    $contents = file_get_contents($file);
    if (!str_contains($contents, 'openapiContext: [')) {
        continue;
    }

    echo "Traitement de $file\n";

    // Match tous les blocs openapiContext: [ ... ]
    $contents = preg_replace_callback(
        '#openapiContext:\s*\[(.*?)\](\s*,)?#s',
        function ($matches) {
            $arrayBlock = $matches[1];

            // Convert 'key' => to key:
            $converted = preg_replace_callback(
                "/'([^']+)'\s*=>\s*/",
                function ($m) {
                    return $m[1] . ': ';
                },
                $arrayBlock
            );

            // Enlève les quotes inutiles autour des clés (s'ils sont simples)
            $converted = trim($converted);

            // Corrige indentation pour coller avec `new Operation(`
            $converted = preg_replace('/^/m', '                ', $converted);

            return "openapi: new Operation(\n$converted\n            )";
        },
        $contents
    );

    file_put_contents($file, $contents);
}

echo "✅ Migration complète.\n";
