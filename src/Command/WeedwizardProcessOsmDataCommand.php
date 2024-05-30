<?php

namespace App\Command;

use Exception;
use PDO;
use PDOException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Input\InputInterface;
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
            ->setHelp('This command downloads the latest OSM data for Germany and filters it using Osmium tags-filter.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $osmFilePath = 'src/Command/GEOjson/germany-latest.osm.pbf';
        $filteredOsmFilePath = 'src/Command/GEOjson/germany-latest-with-tags.osm.pbf';

        // Step 0: Ensure dependencies are installed
        exec('brew services start postgresql@14');
        $io->section('Installing dependencies...');
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

        if (!$this->isTippecanoeInstalled()) {
            $io->section('Tippecanoe is not installed. Installing Tippecanoe...');
            if (!$this->installTippecanoe()) {
                $io->error('Failed to install Tippecanoe.');

                return Command::FAILURE;
            }
            $io->success('Tippecanoe installed successfully.');
        } else {
            $io->success('Tippecanoe is already installed.');
        }

        if (!$this->isPostGISInstalled()) {
            $io->section('PostGIS is not installed. Installing PostGIS...');
            if (!$this->installPostGIS()) {
                $io->error('Failed to install PostGIS.');

                return Command::FAILURE;
            }
            $io->success('PostGIS installed successfully.');
        } else {
            $io->success('PostGIS is already installed.');
        }

        if (!$this->isosm2pgsqlInstalled()) {
            $io->section('osm2pgsql is not installed. Installing osm2pgsql...');
            if (!$this->installosm2pgsql()) {
                $io->error('Failed to install osm2pgsql.');

                return Command::FAILURE;
            }
            $io->success('osm2pgsql installed successfully.');
        } else {
            $io->success('osm2pgsql is already installed.');
        }

        // Step 1: Download the latest OSM data for Germany
        $io->section('Downloading the latest OSM data for Germany...');
        $url = 'https://download.geofabrik.de/europe/germany-latest.osm.pbf';

        // Initialize the progress bar
        $fileSize = $this->getRemoteFileSize($url);
        if ($fileSize === false) {
            $io->error('Failed to retrieve the file size.');

            return Command::FAILURE;
        }

        $progressBar = $io->createProgressBar($fileSize);
        $progressBar->setFormat('%current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %message%');
        $progressBar->start();

        if (file_exists($osmFilePath)) {
            unlink($osmFilePath);
        }

        // Download the file in chunks
        $readHandle = fopen($url, 'rb');
        $writeHandle = fopen($osmFilePath, 'wb');

        if ($readHandle === false || $writeHandle === false) {
            $io->error('Failed to open file handles.');

            return Command::FAILURE;
        }

        $downloadedBytes = 0;
        while (!feof($readHandle)) {
            $chunk = fread($readHandle, 70000000); // Read in 70MB chunks
            fwrite($writeHandle, $chunk);
            $downloadedBytes += strlen($chunk);
            $progressBar->setProgress($downloadedBytes);

            // Update progress bar message with formatted bytes
            $formattedDownloaded = $this->formatBytes($downloadedBytes);
            $formattedTotal = $this->formatBytes($fileSize);
            $progressBar->setMessage("{$formattedDownloaded} / {$formattedTotal}");
        }

        fclose($readHandle);
        fclose($writeHandle);

        $progressBar->finish();
        $io->newLine();
        $io->newLine();

        $io->success('OSM data downloaded and saved successfully.');

        // Step 2: Run the Osmium tags-filter command
        $io->section('Running Osmium tags-filter...');

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
            '-o', $filteredOsmFilePath,
            '--overwrite',
        ];

        $process = new Process($tagsFilterCommand);
        $process->setTimeout(3600); // Set timeout to 1 hour
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Step 3: Buffer and merge the filtered OSM data
        $io->section('Buffering and merging the filtered OSM data...');

        $progressIndicator = new ProgressIndicator($output, 'verbose');
        $progressIndicator->start('Importing data...');
        putenv('PGPASSWORD=weedwizard');

        $osm2pgsqlCommand = [
            'osm2pgsql',
            '-c',
            '-d', 'weedwizard_geometry',
            '-U', 'weedwizard',
            '-p', 'weedwizard_geometry',
            'src/Command/GEOjson/germany-latest-with-tags.osm.pbf',
        ];

        $process = new Process($osm2pgsqlCommand);
        $process->setTimeout(3600); // Set timeout to 1 hour
        $process->run();

        if (!$process->isSuccessful()) {
            $io->error($process->getErrorOutput());
        }
        $progressIndicator->finish('Done.');

        $pdo = new PDO('pgsql:host=localhost;dbname=weedwizard_geometry', 'weedwizard', 'weedwizard');

        $query = $pdo->query('SELECT COUNT(*) FROM weedwizard_geometry_point');
        $count = $query->fetchColumn();

        if ($count > 0) {
            $io->success("Es gibt {$count} Einträge in der Tabelle weedwizard_geometry_point.");
        } else {
            $io->error('Es gibt keine Einträge in der Tabelle weedwizard_geometry_point.');
        }

        $tables = ['weedwizard_geometry_point', 'weedwizard_geometry_line', 'weedwizard_geometry_roads', 'weedwizard_geometry_polygon'];

        $progressIndicator = new ProgressIndicator($output, 'verbose');
        $progressIndicator->start('Buffering tables...');
        foreach ($tables as $table) {
            $bufferedTable = $table . '_buffered';

            $query = $pdo->query("SELECT to_regclass('{$bufferedTable}')");
            $tableExists = $query->fetchColumn() !== false;

            if ($tableExists) {
                $pdo->exec("DROP TABLE {$bufferedTable}");
            }

            // Erstellen Sie die Tabelle
            $createTableSql = "
                CREATE TABLE {$bufferedTable} AS
                SELECT *, ST_Transform(ST_Buffer(ST_Transform(way, 4326)::geography, 100)::geometry, 3857) AS buffered_way
                FROM {$table};
            ";
            $pdo->exec($createTableSql);

            $indexName = $bufferedTable . '_buffered_way_gist';
            $createIndexSql = "
                CREATE INDEX {$indexName}
                ON {$bufferedTable}
                USING gist (buffered_way);
            ";
            $pdo->exec($createIndexSql);
        }
        $progressIndicator->finish('Done.');

        $progressIndicator = new ProgressIndicator($output, 'verbose');
        $progressIndicator->start('Merging tables...');
        $unifiedTable = 'weedwizard_geometry_unified';
        $query = $pdo->query("SELECT to_regclass('{$unifiedTable}')");
        $tableExists = $query->fetchColumn() !== false;

        if ($tableExists) {
            try {
                $pdo->exec("DROP TABLE {$unifiedTable}");
            } catch (PDOException $e) {
                $io->error("Failed to drop table {$unifiedTable}: " . $e->getMessage());
            }
        }

        $unifiedTable = 'weedwizard_geometry_unified';
        $createTableSql = "
            CREATE TABLE {$unifiedTable} AS
            SELECT ST_Union(a.buffered_way) AS unified_way
            FROM (
                SELECT buffered_way FROM weedwizard_geometry_point_buffered
                UNION ALL
                SELECT buffered_way FROM weedwizard_geometry_line_buffered
                UNION ALL
                SELECT buffered_way FROM weedwizard_geometry_roads_buffered
                UNION ALL
                SELECT buffered_way FROM weedwizard_geometry_polygon_buffered
            ) AS a,
            (
                SELECT buffered_way FROM weedwizard_geometry_point_buffered
                UNION ALL
                SELECT buffered_way FROM weedwizard_geometry_line_buffered
                UNION ALL
                SELECT buffered_way FROM weedwizard_geometry_roads_buffered
                UNION ALL
                SELECT buffered_way FROM weedwizard_geometry_polygon_buffered
            ) AS b
            WHERE ST_Intersects(a.buffered_way, b.buffered_way);
        ";
        $pdo->exec($createTableSql);
        $progressIndicator->finish('Done.');

        $progressIndicator = new ProgressIndicator($output, 'verbose');
        $progressIndicator->start('Exporting data...');

        $mergedFilePath = 'src/Command/GEOjson/merged.geojson';
        if (file_exists($mergedFilePath)) {
            unlink($mergedFilePath);
        }

        $fileHandle = fopen($mergedFilePath, 'w');
        if ($fileHandle === false) {
            throw new Exception("Failed to open or create file: {$mergedFilePath}");
        }

        fclose($fileHandle);

        $geoJsonFilePath = realpath($mergedFilePath);
        $exportSql = "
            COPY (
                SELECT row_to_json(t)
                FROM (
                    SELECT 'Feature' as type,
                           ST_AsGeoJSON(ST_Transform(unified_way, 4326))::json as geometry,
                           json_build_object('name', 'unified_way') as properties
                    FROM weedwizard_geometry_unified
                ) t
            ) TO '{$geoJsonFilePath}';
        ";
        $pdo->exec($exportSql);
        $progressIndicator->finish('Done.');

        exec('brew services stop postgresql@14');

        $io->success('Filtered OSM data buffered and merged successfully.');

        // Step 4: Convert the GEOjson data to MBTiles
        $io->section('Converting the GeoJSON data to MBTiles...');
        $mbtilesFilePath = 'germany-latest-with-tags.mbtiles';

        $tippecanoeCommand = [
            'tippecanoe',
            '-o', $mbtilesFilePath,
            $geoJsonFilePath,
            '--force',
            '--drop-fraction-as-needed',
        ];

        $process = new Process($tippecanoeCommand);
        $process->setTimeout(7200); // Set timeout to 2 hour
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $io->success('MBTiles file created successfully.');

        $io->info('Remember to restart the tile server to see the changes.');

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

        return $process->isSuccessful();
    }

    private function isTippecanoeInstalled()
    {
        $process = new Process(['which', 'tippecanoe']);
        $process->run();

        return $process->isSuccessful();
    }

    private function installTippecanoe()
    {
        $process = new Process(['brew', 'install', 'tippecanoe']);
        $process->run();

        return $process->isSuccessful();
    }

    private function isPostGISInstalled()
    {
        $process = new Process(['which', 'postgis']);
        $process->run();

        return $process->isSuccessful();
    }

    private function installPostGIS()
    {
        $process = new Process(['brew', 'install', 'postgis']);
        $process->run();

        return $process->isSuccessful();
    }

    private function isosm2pgsqlInstalled()
    {
        $process = new Process(['which', 'osm2pgsql']);
        $process->run();

        return $process->isSuccessful();
    }

    private function installosm2pgsql()
    {
        $process = new Process(['brew', 'install', 'osm2pgsql']);
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
            return (int)$matches[1];
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
