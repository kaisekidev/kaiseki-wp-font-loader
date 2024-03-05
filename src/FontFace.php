<?php

declare(strict_types=1);

namespace Kaiseki\WordPress\FontLoader;

class FontFace implements FontFaceInterface
{
    /** @var array<int> */
    private array $locations = [self::FRONTEND];
    private bool $preload = false;

    /**
     * @param string           $family
     * @param list<FontSource> $sources
     * @param string           $weight
     * @param string           $style
     * @param string           $display
     */
    public function __construct(
        protected string $family,
        protected array $sources = [],
        protected string $weight = '',
        protected string $style = '',
        protected string $display = '',
    ) {
    }

    public function family(): string
    {
        return $this->family;
    }

    /**
     * @return list<FontSource>
     */
    public function sources(): array
    {
        return $this->sources;
    }

    public function weight(): string
    {
        return $this->weight;
    }

    public function style(): string
    {
        return $this->style;
    }

    public function display(): string
    {
        return $this->display;
    }

    public function locations(): array
    {
        return $this->locations;
    }

    public function preload(): bool
    {
        return $this->preload;
    }

    public function withDisplay(string $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function withFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function withLocations(int ...$locations): self
    {
        $this->locations = $locations;

        return $this;
    }

    public function withPreload(bool $preload = true): self
    {
        $this->preload = $preload;

        return $this;
    }

    public function withStyle(string $style): FontFaceInterface
    {
        $this->style = $style;

        return $this;
    }

    public function withWeight(string $weight): FontFaceInterface
    {
        $this->weight = $weight;

        return $this;
    }
}
