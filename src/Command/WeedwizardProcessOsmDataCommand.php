<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'weedwizard:process-osm-data',
    description: 'This Command will download the newest map of germany and process the OSM Tags',
)]
class WeedwizardProcessOsmDataCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Download and process OSM data for Germany')
            ->setHelp('This command downloads the latest OSM data for Germany and filters it using Osmium tags-filter.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Step 0: Ensure Osmium is installed
        if (!$this->isOsmiumInstalled()) {
            $io->section('Osmium is not installed. Installing Osmium...');
            if (!$this->installOsmium()) {
                $io->error('Failed to install Osmium.');
                return Command::FAILURE;
            }
            $io->success('Osmium installed successfully.');
        } else {
            $io->success('Osmium is already installed.');
        }

        // Step 1: Download the latest OSM data for Germany
        $io->section('Downloading the latest OSM data for Germany...');
        $url = 'https://download.geofabrik.de/europe/germany-latest.osm.pbf';
        $osmFilePath = 'germany-latest.osm.pbf';

        // Initialize the progress bar
        $fileSize = $this->getRemoteFileSize($url);
        if ($fileSize === false) {
            $io->error('Failed to retrieve the file size.');
            return Command::FAILURE;
        }

        $progressBar = $io->createProgressBar($fileSize);
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s%');
        $progressBar->start();

        // Download the file in chunks
        $readHandle = fopen($url, 'rb');
        $writeHandle = fopen($osmFilePath, 'wb');

        if ($readHandle === false || $writeHandle === false) {
            $io->error('Failed to open file handles.');
            return Command::FAILURE;
        }

        $downloadedBytes = 0;
        while (!feof($readHandle)) {
            $chunk = fread($readHandle, 52428800); // Read in 50MB chunks
            fwrite($writeHandle, $chunk);
            $downloadedBytes += strlen($chunk);
            $progressBar->setProgress($downloadedBytes);
            $formattedDownloaded = $this->formatBytes($downloadedBytes);
            $formattedTotal = $this->formatBytes($fileSize);
            $progressBar->setMessage("$formattedDownloaded / $formattedTotal", 'info');
        }

        fclose($readHandle);
        fclose($writeHandle);

        $progressBar->finish();
        $io->newLine();

        $io->success('OSM data downloaded and saved successfully.');

        // Step 2: Run the Osmium tags-filter command
        $io->section('Running Osmium tags-filter...');

        $filteredOsmFilePath = 'germany-latest-with-tags.osm.pbf';
        $tagsFilterCommand = [
            'osmium',
            'tags-filter',
            $osmFilePath,
            'nwr/leisure=playground',
            'nwr/amenity=school',
            'nwr/amenity=kindergarten',
            'nwr/building=kindergarten',
            'nwr/community_centre=youth_centre',
            'nwr/amenity=childcare',
            'nwr/name=*Jugendherberge',
            'nwr/social_facility:for=child',
            'nwr/social_facility:for=juvenile',
            'nwr/healthcare:speciality=child_psychiatry',
            'nwr/community_centre:for=child',
            'nwr/community_centre:for=juvenile',
            'nwr/community_centre:for=girl',
            'nwr/community_centre:for=boy',
            'nwr/leisure=pitch',
            'nwr/leisure=sports_hall',
            'nwr/leisure=sports_centre',
            'nwr/leisure=horse_riding',
            'nwr/sport=swimming',
            'nwr/leisure=stadium',
            'nwr/leisure=water_park',
            'nwr/leisure=golf_course',
            'nwr/leisure=indoor_play',
            '-o', $filteredOsmFilePath
        ];

        $process = new Process($tagsFilterCommand);
        $process->setTimeout(3600); // Set timeout to 1 hour
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $io->success('Tags filter applied successfully.');

        // Step 3: Convert the filtered OSM data to GeoJSON
        $io->section('Converting filtered OSM data to GeoJSON...');

        $geoJsonFilePath = 'germany-latest-with-tags.geojson';
        $convertCommand = [
            'osmtogeojson',
            $filteredOsmFilePath
        ];

        // Create the process and redirect output to file
        $process = new Process($convertCommand);
        $process->setTimeout(3600); // Set timeout to 1 hour
        $process->run(null, ['OUTPUT' => $geoJsonFilePath]);

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $io->success('Filtered OSM data converted to GeoJSON successfully.');
        $io->text('GeoJSON file: ' . $geoJsonFilePath);

        return Command::SUCCESS;
    }

    private function isOsmiumInstalled()
    {
        $process = new Process(['which', 'osmium']);
        $process->run();
        return $process->isSuccessful();
    }

    private function installOsmium()
    {
        $process = new Process(['brew', 'install', 'osmium-tool']);
        $process->run();
        if (!$process->isSuccessful()) {
            return false;
        }

        $process = new Process(['npm', 'install', '-g', 'osmtogeojson']);
        $process->run();
        return $process->isSuccessful();
    }

    private function getRemoteFileSize($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);

        $data = curl_exec($curl);
        curl_close($curl);

        if ($data === false) {
            return false;
        }

        if (preg_match('/Content-Length: (\d+)/', $data, $matches)) {
            return (int) $matches[1];
        }

        return false;
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
