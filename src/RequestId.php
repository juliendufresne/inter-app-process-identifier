<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId;

/**
 * Determines the context of the application's current execution.
 */
final class RequestId implements RequestIdInterface
{
    /** @var string */
    private $root;

    /** @var string|null */
    private $parent;

    /** @var string */
    private $current;

    public function __construct(string $current, ?string $parent = null, ?string $root = null)
    {
        $this->current = $current;
        $this->root = $root ?? $parent ?? $current;
        $this->parent = $parent ?? $root;
    }

    public function getRootAppRequestId(): string
    {
        return $this->root;
    }

    public function getParentAppRequestId(): ?string
    {
        return $this->parent;
    }

    public function getCurrentAppRequestId(): string
    {
        return $this->current;
    }
}
