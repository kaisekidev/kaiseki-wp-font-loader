<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader\FileNameParser;

use function array_keys;
use function array_map;
use function implode;
use function in_array;
use function strtolower;

final class FontFilenameParser implements FontFilenameParserInterface
{
    private ?string $weight = null;
    private ?string $style = null;

    private const WEIGHTS = [
        '100',
        '200',
        '200',
        '300',
        '400',
        '500',
        '600',
        '600',
        '700',
        '800',
        '800',
        '900',
    ];

    private const WEIGHT_NAMES = [
        'thin' => '100',
        'hairline' => '100',
        'extralight' => '200',
        'ultralight' => '200',
        'light' => '300',
        'normal' => '400',
        'regular' => '400',
        'book' => '400',
        'medium' => '500',
        'semibold' => '600',
        'demibold' => '600',
        'bold' => '700',
        'extrabold' => '800',
        'ultrabold' => '800',
        'black' => '900',
        'heavy' => '900',
    ];

    private const STYLES = [
        'italic',
        'oblique',
    ];

    public function getFamily(string $filename, string $directory): string
    {
        $parts = $this->getFileNameParts($filename);
        $keys = [
            ...self::WEIGHTS,
            ...array_keys(self::WEIGHT_NAMES),
            ...self::STYLES,
        ];
        $nameParts = [];
        foreach ($parts as $part) {
            if (in_array(strtolower($part), $keys, true)) {
                break;
            }
            $nameParts[] = $part;
        }
        return implode(' ', $nameParts);
    }

    public function getWeight(string $filename, string $directory): string
    {
        if ($this->weight !== null) {
            return $this->weight;
        }
        $parts = array_map(fn ($part) => strtolower($part), $this->getFileNameParts($filename));
        foreach (self::WEIGHTS as $weight) {
            if (in_array($weight, $parts, true)) {
                return $this->weight = $weight;
            }
        }
        foreach (self::WEIGHT_NAMES as $name => $weight) {
            if (in_array($name, $parts, true)) {
                return $this->weight = $weight;
            }
        }
        return $this->weight = '400';
    }

    public function getStyle(string $filename, string $directory): string
    {
        if ($this->style !== null) {
            return $this->style;
        }
        $parts = array_map(fn ($part) => strtolower($part), $this->getFileNameParts($filename));
        foreach (self::STYLES as $style) {
            if (in_array($style, $parts, true)) {
                return $this->style = $style;
            }
        }
        return $this->style = '';
    }

    /**
     * @param string $filename
     *
     * @return list<string>
     */
    private function getFileNameParts(string $filename): array
    {
        return \Safe\preg_split('/[- ]/', $filename);
    }
}
