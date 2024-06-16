<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserProfileFormType;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserProfileController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WeedWizardKernel $weedWizardKernel,
        private SluggerInterface $slugger,
    ) {

    }

    #[Route('/profile/{username}', name: 'weedwizard_user_profile')]
    public function index(string $username): Response
    {
        $userProfile = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        return $this->render('user_profile/index.html.twig', [
            'userProfile' => $userProfile,
        ]);
    }

    #[Route('/profile/{username}/edit', name: 'weedwizard_user_profile_edit')]
    public function edit(string $username, Request $request): Response
    {
        if (!$this->weedWizardKernel->getUser()) {
            $this->addFlash('error', 'Du musst angemeldet sein, um dein Profil zu bearbeiten.');
            return $this->weedWizardKernel->redirectToPreviousPage($request);
        }

        $userProfile = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);

        if ($this->weedWizardKernel->getUser() !== $userProfile) {
            $this->addFlash('error', 'Du kannst nur dein eigenes Profil bearbeiten.');
            new RedirectResponse($this->generateUrl('weedwizard_user_profile', ['username' => $this->weedWizardKernel->getUser()->getUsername()]));
        }

        $userProfileForm = $this->createForm(UserProfileFormType::class, $userProfile);
        $userProfileForm->handleRequest($request);
        if ($userProfileForm->isSubmitted() && $userProfileForm->isValid()) {
            /** @var UploadedFile $profilePicture */
            $profilePicture = $userProfileForm->get('profilePicture')->getData();

            if ($profilePicture) {
                $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePicture->guessExtension();

                if ($newFilename != $userProfile->getProfilePicture()) {
                    unlink('uploads/profile_pictures/' . $userProfile->getProfilePicture());

                    $profilePicture->move(
                        'uploads/profile_pictures/',
                        $newFilename
                    );
                    $userProfile->setProfilePicture($newFilename);
                }
            }

            /** @var UploadedFile $banner */
            $banner = $userProfileForm->get('banner')->getData();

            if ($banner) {
                $originalFilename = pathinfo($banner->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $banner->guessExtension();

                if ($newFilename != $userProfile->getBanner()) {
                    unlink('uploads/banners/' . $userProfile->getBanner());

                    $banner->move(
                        'uploads/banners/',
                        $newFilename
                    );
                    $userProfile->setBanner($newFilename);
                }
            }

            $this->entityManager->flush();
            $this->addFlash('success', 'Dein Profil wurde erfolgreich aktualisiert.');
            return new RedirectResponse($this->generateUrl('weedwizard_user_profile', ['username' => $userProfile->getUsername()]));
        }

        return $this->render('user_profile/edit.html.twig', [
            'userProfile' => $userProfile,
            'userProfileForm' => $userProfileForm
        ]);
    }
}
