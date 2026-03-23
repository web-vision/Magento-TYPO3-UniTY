<?php

declare(strict_types=1);

namespace WebVision\WvT3unity\Event;

use Psr\Http\Message\ServerRequestInterface;
use WebVision\WvT3unity\UserFunc\ContentJson;

/**
 * Allows external extensions manipulating data to the head JSON section before the array is JSON encoded.
 *
 * @see ContentJson::getHeadData()
 */
final class ManipulateHeadDataEvent
{
    /**
     * @param array<string, string> $headData
     * @param array<string, mixed> $configuration TypoScript configuration array
     */
    public function __construct(
        public array $headData,
        public readonly ServerRequestInterface $request,
        public readonly array $configuration,
    ) {
    }
}
