<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\WeedWizardExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WeedWizardBudBashLocatorExtensionExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('iconForAttendance', [WeedWizardExtensionRuntime::class, 'iconForAttendance']),
            new TwigFunction('secretStringForAttendance', [WeedWizardExtensionRuntime::class, 'secretStringForAttendance']),
            new TwigFunction('getUserBlogPosts', [WeedWizardExtensionRuntime::class, 'getUserBlogPosts']),
            new TwigFunction('isUserFollowingUser', [WeedWizardExtensionRuntime::class, 'isUserFollowingUser']),
            new TwigFunction('hasUserLikedPost', [WeedWizardExtensionRuntime::class, 'hasUserLikedPost']),
            new TwigFunction('getAllBlogLikesFromUser', [WeedWizardExtensionRuntime::class, 'getAllBlogLikesFromUser']),
            new TwigFunction('arrayKeyFirst', [WeedWizardExtensionRuntime::class, 'arrayKeyFirst']),
            new TwigFunction('isTaskCompleted', [WeedWizardExtensionRuntime::class, 'isTaskCompleted']),
        ];
    }
}
