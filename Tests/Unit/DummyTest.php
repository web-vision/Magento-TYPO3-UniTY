<?php

declare(strict_types=1);

namespace WebVision\WvT3unity\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

final class DummyTest extends UnitTestCase
{
    #[Test]
    public function dummy(): void
    {
        static::assertTrue(true);
    }
}
