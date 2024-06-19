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
            new TwigFunction('getUserBlogPosts', [WeedWizardBudBashLocatorExtensionRuntime::class, 'getUserBlogPosts']),
            new TwigFunction('isUserFollowingUser', [WeedWizardBudBashLocatorExtensionRuntime::class, 'isUserFollowingUser']),
            new TwigFunction('hasUserLikedPost', [WeedWizardBudBashLocatorExtensionRuntime::class, 'hasUserLikedPost']),
            new TwigFunction('getAllBlogLikesFromUser', [WeedWizardBudBashLocatorExtensionRuntime::class, 'getAllBlogLikesFromUser']),
            new TwigFunction('arrayKeyFirst', [WeedWizardBudBashLocatorExtensionRuntime::class, 'arrayKeyFirst']),
        ];
    }
}
