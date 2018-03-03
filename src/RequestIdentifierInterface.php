<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier;

interface RequestIdentifierInterface
{
    /**
     * Uniquely identifies the root application execution id.
     *
     * @return string
     */
    public function getRootAppRequestId(): string;

    /**
     * Uniquely identifies the execution id of this application's caller.
     *
     * @return string|null
     */
    public function getParentAppRequestId(): ?string;

    /**
     * Uniquely identifies the execution id of this application.
     *
     * @return string
     */
    public function getCurrentAppRequestId(): string;
}
