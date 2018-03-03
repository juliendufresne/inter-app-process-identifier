<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier;

/**
 * Ensure process identifier is set only once at runtime.
 */
final class RequestIdentifierFacade implements RequestIdentifierInterface
{
    /** @var RequestIdentifierInterface|null */
    private $requestIdentifier;

    public function __construct(?RequestIdentifierInterface $requestIdentifier = null)
    {
        $this->requestIdentifier = $requestIdentifier;
    }

    public function initRequestIdentifier(RequestIdentifierInterface $requestIdentifier): void
    {
        if (null !== $this->requestIdentifier) {
            throw new \LogicException('Can not reset process identifier');
        }
        $this->requestIdentifier = $requestIdentifier;
    }

    /**
     * Uniquely identifies the root application execution id.
     *
     * @return string
     */
    public function getRootAppRequestId(): string
    {
        return null === $this->requestIdentifier ? '' : $this->requestIdentifier->getRootAppRequestId();
    }

    /**
     * Uniquely identifies the execution id of this application's caller.
     *
     * @return string|null
     */
    public function getParentAppRequestId(): ?string
    {
        return null === $this->requestIdentifier ? null : $this->requestIdentifier->getParentAppRequestId();
    }

    /**
     * Uniquely identifies the execution id of this application.
     *
     * @return string
     */
    public function getCurrentAppRequestId(): string
    {
        return null === $this->requestIdentifier ? '' : $this->requestIdentifier->getCurrentAppRequestId();
    }
}
