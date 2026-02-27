<?php

namespace App;

final class Parser
{
    private const int PREFIX_LENGTH = 25;
    private const int DATE_LENGTH = 11;

    private array $map = [];
    public function parse(string $inputPath, string $outputPath): void
    {
        $inputFile = fopen($inputPath, 'r');
        while (($line = fgets($inputFile, 128)) !== false) {
            $commaPos = strpos($line, ',');
            [$path, $date] = explode(
                ',',
                substr(
                    $line,
                    self::PREFIX_LENGTH,
                    $commaPos + self::DATE_LENGTH - self::PREFIX_LENGTH
                )
            );

            $path = '/blog/'.$path;
            $date = str_replace('-', '', $date);
            $this->map[$path][$date] = ($this->map[$path][$date] ?? 0) + 1;
        }
        fclose($inputFile);

        foreach ($this->map as &$line) {
            ksort($line);

            $modified = [];
            foreach ($line as $key => $value) {
                $modified[substr($key, 0, 4) . '-' . substr($key, 4, 2) . '-' . substr($key, 6, 2)] = $value;
            }
            $line = $modified;
        }

        file_put_contents($outputPath, json_encode($this->map, JSON_PRETTY_PRINT));
    }
}
