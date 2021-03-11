<?php

declare(strict_types=1);

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace teamiken\ContaoFotowareApiBundle\Tests;

use teamiken\ContaoFotowareApiBundle\ContaoFotowareApiBundle;
use PHPUnit\Framework\TestCase;

class ContaoFotowareApiBundleTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $bundle = new ContaoFotowareApiBundle();

        $this->assertInstanceOf('teamiken\ContaoFotowareApiBundle\ContaoFotowareApiBundle', $bundle);
    }
}
