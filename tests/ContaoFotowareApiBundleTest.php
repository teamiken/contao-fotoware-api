<?php

declare(strict_types=1);

use teamiken\Fotoware\ContaoFotowareBundle;
use PHPUnit\Framework\TestCase;

class ContaoFotowareApiBundleTest extends TestCase
{

    public function testCanBeInstantiated(): void
    {
        $bundle = new ContaoFotowareBundle();

        $this->assertInstanceOf('teamiken\Fotoware\ContaoFotowareBundle', $bundle);
    }
}
