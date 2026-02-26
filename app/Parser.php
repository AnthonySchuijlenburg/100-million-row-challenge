<?php

namespace App;

final class Parser
{
    private array $map = [];
    public function parse(string $inputPath, string $outputPath): void
    {
        $inputFile = fopen($inputPath, 'r');
        while (($line = fgets($inputFile)) !== false) {
            preg_match('/https:\/\/stitcher\.io(?<path>[^,]+),(?<date>202[^T]+)/' ,$line, $match);
            $curr = $this->map[$match['path']][$match['date']] ?? 0;
            $this->map[$match['path']][$match['date']] = $curr + 1;
        }
        fclose($inputFile);

        foreach ($this->map as &$line) {
            ksort($line);
        }

        file_put_contents($outputPath, json_encode($this->map, JSON_PRETTY_PRINT));
    }
}