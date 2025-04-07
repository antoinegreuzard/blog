<?php

$directory = __DIR__.'/src/Entity';

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($directory)
);

foreach ($files as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    $lines = file($file->getRealPath());
    $newLines = [];
    $inOpenApiContext = false;
    $bracketLevel = 0;
    $buffer = [];

    foreach ($lines as $line) {
        if (!$inOpenApiContext) {
            if (str_contains($line, 'openapiContext: [')) {
                $inOpenApiContext = true;
                $bracketLevel = substr_count($line, '[') - substr_count(
                        $line,
                        ']'
                    );
                $buffer[] = $line;
            } else {
                $newLines[] = $line;
            }
        } else {
            $bracketLevel += substr_count($line, '[');
            $bracketLevel -= substr_count($line, ']');
            $buffer[] = $line;

            if ($bracketLevel === 0) {
                // bloc complet capturé
                $converted = convertOpenapiContextBlock($buffer);
                $newLines[] = $converted;
                $inOpenApiContext = false;
                $buffer = [];
            }
        }
    }

    file_put_contents($file, implode('', $newLines));
    echo "✅ Fichier traité : {$file->getRealPath()}\n";
}

function convertOpenapiContextBlock(array $lines): string
{
    $firstLine = array_shift($lines);
    $indent = str_repeat(' ', strpos($firstLine, 'openapiContext'));

    // Nettoyage première ligne
    $firstLine = preg_replace('/openapiContext:\s*\[/', '', $firstLine);

    // Traiter la dernière ligne séparément AVANT de la modifier
    $rawLastLine = array_pop($lines);
    $afterBracket = strstr($rawLastLine, '],');
    $lastLine = preg_replace('/\],?\s*$/', '', $rawLastLine);

    array_unshift($lines, $firstLine);
    $lines[] = $lastLine;

    // Transforme 'clé' => en clé:
    $transformed = array_map(function ($line) {
        return preg_replace("/'([a-zA-Z0-9_]+)'\s*=>/", "$1:", $line);
    }, $lines);

    // Reformate le bloc complet
    $body = implode('', array_map(fn($l) => $indent.'    '.$l, $transformed));

    // Reconstruit le bloc Operation et ajoute le contenu après si besoin
    $operation = $indent."openapi: new Operation(\n".$body.$indent.")";

    if ($afterBracket !== false && trim($afterBracket) !== '],') {
        $after = trim(str_replace('],', '', $afterBracket));
        $operation .= ", ".$after."\n";
    } else {
        $operation .= ",\n";
    }

    return $operation;
}
