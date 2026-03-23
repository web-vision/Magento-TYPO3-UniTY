<?php

declare(strict_types=1);

namespace WebVision\WvT3unity\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class DummyTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'web-vision/wv_t3unity',
    ];

    #[Test]
    public function dummy(): void
    {
        static::assertTrue(true);
    }
}
