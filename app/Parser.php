<?php

namespace App;

final class Parser
{
    private array $map = [];
    public function parse(string $inputPath, string $outputPath): void
    {
        $inputFile = fopen($inputPath, 'r');
        while (($line = fgets($inputFile)) !== false) {
            $line = explode(',', $line);
            $path = substr($line[0], 19);
            $date = substr($line[1], 0, 10);

            $curr = $this->map[$path][$date] ?? 0;
            $this->map[$path][$date] = $curr + 1;
        }
        fclose($inputFile);

        foreach ($this->map as &$line) {
            ksort($line);
        }

        file_put_contents($outputPath, json_encode($this->map, JSON_PRETTY_PRINT));
    }
}
