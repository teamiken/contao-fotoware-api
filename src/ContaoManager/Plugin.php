<?php

declare(strict_types=1);

namespace teamiken\Fotoware\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use teamiken\Fotoware\ContaoFotowareBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoFotowareBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
