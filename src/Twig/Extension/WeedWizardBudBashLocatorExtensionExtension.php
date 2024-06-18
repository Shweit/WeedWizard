<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\WeedWizardBudBashLocatorExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WeedWizardBudBashLocatorExtensionExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('iconForAttendance', [WeedWizardBudBashLocatorExtensionRuntime::class, 'iconForAttendance']),
            new TwigFunction('secretStringForAttendance', [WeedWizardBudBashLocatorExtensionRuntime::class, 'secretStringForAttendance']),
            new TwigFunction('arrayKeyFirst', [WeedWizardBudBashLocatorExtensionRuntime::class, 'arrayKeyFirst']),
        ];
    }
}
