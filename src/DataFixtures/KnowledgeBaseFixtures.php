<?php

namespace App\DataFixtures;

use App\Entity\KnowledgeBase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class KnowledgeBaseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('How to grow weed');
        $entry->setArticleContent('This is how you grow weed');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('growing_tips');
        $entry->setArticleName('How to grow weed');
        $entry->setArticleContent('This is how you grow weed');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('consuming_tips');
        $entry->setArticleName('How to consume weed');
        $entry->setArticleContent('This is how you consume weed');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('How to use cannabis');
        $entry->setArticleContent('This is how you use cannabis');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('cannabis_tips');
        $entry->setArticleName('How to use cannabis');
        $entry->setArticleContent('This is how you use cannabis');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannaConsultant');
        $entry->setArticleName('CannaConsultant');
        $entry->setArticleContent('This is how you use CannaConsultant');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannabisConsumptionComplianceMap');
        $entry->setArticleName('Cannabis Consumption Compliance Map');
        $entry->setArticleContent('This is how you use Cannabis Consumption Compliance Map');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannabisVereinssuche');
        $entry->setArticleName('Cannabis Vereinssuche');
        $entry->setArticleContent('This is how you use Cannabis Vereinssuche');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('BudBashLocator');
        $entry->setArticleName('Bud Bash Locator');
        $entry->setArticleContent('This is how you use Bud Bash Locator');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannastrainLibrary');
        $entry->setArticleName('Cannastrain Library');
        $entry->setArticleContent('This is how you use Cannastrain Library');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('CannadoseCalculator');
        $entry->setArticleName('Cannadose Calculator');
        $entry->setArticleContent('This is how you use Cannadose Calculator');
        $manager->persist($entry);

        $entry = new KnowledgeBase();
        $entry->setSite('knowledge_base');
        $entry->setCategorie('GrowMate');
        $entry->setArticleName('Grow Mate');
        $entry->setArticleContent('This is how you use Grow Mate');
        $manager->persist($entry);

        $manager->flush();
    }
}
