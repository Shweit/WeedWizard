<?php

namespace App\Command;

use App\Entity\Breeder;
use App\Entity\Strain;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weedwizard:update-strains',
    description: 'This command will update the strains and all new strains if any are newly added to the SeedFinder API.',
)]
class WeedwizardUpdateStrainsCommand extends Command
{
    private SymfonyStyle $io;
    private OutputInterface $output;

    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('breeder-id', 'b', InputArgument::OPTIONAL, 'Enter the breeder id to update the strains for.')
            ->addOption('strain-id', 's', InputArgument::OPTIONAL, 'Enter the strain id to update the strain for.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->io = $io;
        $this->output = $output;

        $breederID = $input->getOption('breeder-id');
        $strainID = $input->getOption('strain-id');

        if ($strainID && !$breederID) {
            $io->error('You need to provide the breeder id and strain id to update the one single strain.');

            return Command::FAILURE;
        }

        if ($breederID && $strainID) {
            $io->info('Updating strain id: ' . $strainID . ' for breeder id: ' . $breederID);
            $this->updateStrain($breederID, $strainID);
        } elseif ($breederID && !$strainID) {
            $io->info('Updating strains for breeder id: ' . $breederID);
            $this->updateBreeder($breederID);
        } elseif (!$breederID && !$strainID) {
            $io->info('Updating all strains for all breeders');
            $this->updateAll();
        }

        $io->success('All strains have been updated.');

        return Command::SUCCESS;
    }

    private function updateStrain($breederID, $strainID): void
    {
        $client = new Client();
        $response = $client->request('GET', 'https://de.seedfinder.eu/api/json/strain.json?br=' . $breederID . '&str=' . $strainID . '&lng=de&parents=1&hybrids=1&medical=1&reviews=1&tasting=1&taste=1&smell=1&effect=1&pics=1&ac=92f2e8788330eed9b2a7519ee7c71737');
        $data = json_decode($response->getBody()->getContents(), true);

        if ($data['error'] !== false) {
            $this->io->error('Fehler beim aktualisieren der Sorte ' . $strainID . ': ' . $data['error']);

            return;
        }

        $strain = $this->entityManager->getRepository(Strain::class)->findOneBy([
            'seedfinder_id' => $strainID,
        ]) ?? new Strain();

        $strain->setSeedfinderId($strainID);
        $strain->setName($data['name']);
        $strain->setBreederInfo(is_array($data['brinfo'] ?? null) ? $data['brinfo'] : []);
        $strain->setParents(is_array($data['parents'] ?? null) ? $data['parents'] : []);
        $strain->setHybrids(is_array($data['hybrids'] ?? null) ? $data['hybrids'] : []);
        $strain->setMedical(is_array($data['medical'] ?? null) ? $data['medical'] : []);
        $strain->setPics(is_array($data['pics'] ?? null) ? $data['pics'] : []);
        $strain->setReviews(is_array($data['reviews'] ?? null) ? $data['reviews'] : []);

        $breeder = $this->entityManager->getRepository(Breeder::class)->findOneBy([
            'seedfinder_id' => $breederID,
        ]) ?? new Breeder();

        if (!$breeder->getName()) {
            $breeder->setSeedfinderId($data['brinfo']['id']);
            $breeder->setName($data['brinfo']['name']);
            $breeder->setLogo($data['brinfo']['pic']);
        }

        $strain->setBreeder($breeder);

        $this->entityManager->persist($strain);
        $this->entityManager->flush();
    }

    private function updateBreeder($breederID): void
    {
        $client = new Client();
        $response = $client->request('GET', 'https://de.seedfinder.eu/api/json/ids.json?br=' . $breederID . '&strains=1&ac=92f2e8788330eed9b2a7519ee7c71737');
        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['error'])) {
            $this->io->error('Fehler beim aktualisieren des Züchters: ' . $data['error']);

            return;
        }

        $breederInfo = $data[$breederID];

        $breeder = $this->entityManager->getRepository(Breeder::class)->findOneBy([
            'seedfinder_id' => $breederID,
        ]) ?? new Breeder();

        $breeder->setSeedfinderId($breederID);
        $breeder->setName($breederInfo['name']);
        $breeder->setLogo($breederInfo['logo']);
        $this->entityManager->persist($breeder);
        $this->entityManager->flush();

        $progressBar = new ProgressBar($this->output, count($breederInfo['strains']));
        $progressBar->setFormat('very_verbose');

        $this->io->section('Updating strains for breeder: ' . $breeder->getName());
        $progressBar->start();

        foreach ($breederInfo['strains'] as $strainID => $strainName) {
            $this->updateStrain($breederID, $strainID);
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->io->newLine();
    }

    private function updateAll(): void
    {
        $client = new Client();
        $response = $client->request('GET', 'https://de.seedfinder.eu/api/json/ids.json?strains=1&ac=92f2e8788330eed9b2a7519ee7c71737');
        $data = json_decode($response->getBody()->getContents(), true);

        if (isset($data['error'])) {
            $this->io->error('Fehler beim aktualisieren aller Züchter: ' . $data['error']);

            return;
        }

        $progressBar = new ProgressBar($this->output, count($data));
        $progressBar->setFormat('very_verbose');

        $this->io->section('Updating all strains');
        $this->io->newLine();

        foreach ($data as $breederID => $breederInfo) {
            $this->updateBreeder($breederID);
            $progressBar->advance();
            $this->io->newLine();
            $this->io->newLine();
        }

        $progressBar->finish();
        $this->io->newLine();
    }
}
