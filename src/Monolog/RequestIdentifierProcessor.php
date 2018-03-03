<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Monolog;

use JulienDufresne\InterAppRequestIdentifier\RequestIdentifierInterface;

final class RequestIdentifierProcessor
{
    /** @var string */
    private $extraEntryName;
    /** @var RequestIdentifierInterface */
    private $requestIdentifier;
    /** @var string */
    private $rootAppEntryName;
    /** @var string */
    private $parentAppEntryName;
    /** @var string */
    private $currentAppEntryName;

    /**
     * @param RequestIdentifierInterface $requestIdentifier
     * @param string                     $extraEntryName
     * @param string                     $currentAppEntryName
     * @param string                     $rootAppEntryName
     * @param string                     $parentAppEntryName
     */
    public function __construct(
        RequestIdentifierInterface $requestIdentifier,
        string $extraEntryName = 'request_id',
        string $currentAppEntryName = 'current',
        string $rootAppEntryName = 'root',
        string $parentAppEntryName = 'parent'
    ) {
        $this->currentAppEntryName = $currentAppEntryName;
        $this->rootAppEntryName = $rootAppEntryName;
        $this->parentAppEntryName = $parentAppEntryName;
        $this->extraEntryName = $extraEntryName;
        $this->requestIdentifier = $requestIdentifier;
    }

    public function __invoke(array $record)
    {
        $extra = array_filter([
            $this->currentAppEntryName => $this->requestIdentifier->getCurrentAppRequestId(),
            $this->rootAppEntryName => $this->requestIdentifier->getRootAppRequestId(),
            $this->parentAppEntryName => $this->requestIdentifier->getParentAppRequestId(),
        ]);

        if ([] !== $extra) {
            $record['extra'][$this->extraEntryName] = $extra;
        }

        return $record;
    }
}
