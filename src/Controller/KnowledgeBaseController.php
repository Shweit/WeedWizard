<?php

namespace App\Controller;

use App\Entity\KnowledgeBase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class KnowledgeBaseController extends AbstractController
{
    public const SITE_KNOWLEDGE_BASE = 'knowledge_base';
    public const SITE_FAQ = 'faq';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/knowledge-base', name: 'weedwizard_knowledge_base')]
    public function knowledge_base_index(): Response
    {
        $entries = $this->entityManager->getRepository(KnowledgeBase::class)->findBy([
            'site' => self::SITE_KNOWLEDGE_BASE,
        ]);

        $groupedEntries = [];
        foreach ($entries as $entry) {
            $groupedEntries[$entry->getCategorie()][] = $entry;
        }

        return $this->render('knowledge_base/index.html.twig', [
            'groupedEntries' => $groupedEntries,
        ]);
    }

    #[Route('/knowledge-base/{category}', name: 'weedwizard_knowledge_base_entry')]
    public function knowledge_base_entry(string $category): Response
    {
        $entries = $this->entityManager->getRepository(KnowledgeBase::class)->findBy([
            'site' => self::SITE_KNOWLEDGE_BASE,
            'categorie' => $category,
        ]);

        return $this->render('knowledge_base/entry.html.twig', [
            'entries' => $entries,
            'category' => $category,
        ]);
    }

    #[Route('/faq', name: 'weedwizard_faq')]
    public function faq_index(): Response
    {
        $entries = $this->entityManager->getRepository(KnowledgeBase::class)->findBy([
            'site' => self::SITE_FAQ,
        ]);

        return $this->render('faq/index.html.twig', [
            'entries' => $entries,
        ]);
    }
}
