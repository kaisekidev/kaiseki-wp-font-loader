<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

interface FontFaceInterface
{
    public const FRONTEND = 2;
    public const BACKEND = 4;
    public const LOGIN = 16;

    /**
     * Returns the font family name.
     *
     * @return string
     */
    public function family(): string;

    /**
     * Set the font family name.
     *
     * @param string $family
     *
     * @return self
     */
    public function withFamily(string $family): self;

    /**
     * Returns the font sources.
     *
     * @return list<FontSource>
     */
    public function sources(): array;

    /**
     * Returns the font weight.
     *
     * @return string
     */
    public function weight(): string;

    /**
     * Set the font weight.
     *
     * @param string $weight
     *
     * @return self
     */
    public function withWeight(string $weight): self;

    /**
     * Returns the font style.
     *
     * @return string
     */
    public function style(): string;

    /**
     * Set the font style.
     *
     * @param string $style
     *
     * @return self
     */
    public function withStyle(string $style): self;

    /**
     * Returns the font display value.
     *
     * @return string
     */
    public function display(): string;

    /**
     * Set the font display value.
     *
     * @param string $display
     *
     * @return self
     */
    public function withDisplay(string $display): self;

    /**
     * Returns the locations where to load the font.
     *
     * @return list<int>
     */
    public function locations(): array;

    /**
     * Set where to load the font.
     *
     * @param int ...$locations
     *
     * @return self
     */
    public function withLocations(int ...$locations): self;

    /**
     * Returns the preload value.
     *
     * @return bool
     */
    public function preload(): bool;

    /**
     * Set the preload value.
     *
     * @param bool $preload
     *
     * @return self
     */
    public function withPreload(bool $preload = true): self;
}
