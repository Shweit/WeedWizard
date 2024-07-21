<?php

namespace App\Service;

use App\Entity\Plant;
use App\Services\NotificationService;
use App\Services\WeedWizardKernel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\Component\Notifier\Notification\Notification;

class GrowMateService
{
    public const TASK_WEIGHTS = [
        'temperature' => 1,
        'pesticide' => 3,
        'fertilize' => 5,
        'water' => 10,
    ];

    public function __construct(
        private ChartBuilderInterface $chartBuilder,
        private EntityManagerInterface $entityManager
    ) {}

    public function calculateRangeIntensityChart(Plant $plant): Chart
    {
        $rangeIntensity = [];
        $intensity = 10;

        $weeklyTasks = $plant->getWeeklyTasks();
        $weeklyTasks = array_merge($weeklyTasks['water'] ?? [], $weeklyTasks['fertilize'] ?? [], $weeklyTasks['temperature'] ?? [], $weeklyTasks['pesticide'] ?? []);

        // Sort the weekly tasks by date
        usort($weeklyTasks, function ($a, $b) {
            $aDate = strtotime($a['date']);
            $bDate = strtotime($b['date']);

            return $aDate - $bDate;
        });

        $prognose = $this->createPrognose($weeklyTasks, $plant->getDate()->format('Y-m-d H:i:s'), $plant);

        $prognose = array_slice($prognose, 0, 20);

        for ($i = 1; $i <= 20; ++$i) {
            // Date time to string
            $dateTime = new \DateTime("now - {$i} days");
            $dateTimeString = $dateTime->format('d.m.Y');
            $rangeIntensity[$dateTimeString] = $i * 10;
        }

        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => array_keys($prognose),
            'datasets' => [
                [
                    'label' => 'Prognose',
                    'borderColor' => 'rgb(12, 79, 17)',
                    'backgroundColor' => 'rgba(12, 79, 17, 0.1)',
                    'fill' => true,
                    'data' => array_values($prognose),
                    'tension' => 0.4,
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Prognose',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Zeitspanne',
                    ],
                ],
            ],
            'plugins' => [
                'annotation' => [
                    'annotations' => [
                        [
                            'type' => 'point',
                            'xValue' => $intensity - 1,
                            'yValue' => 100, // Example value
                            'backgroundColor' => 'rgba(255, 99, 132, 0.25)',
                            'borderColor' => 'rgb(255, 99, 132)',
                        ],
                    ],
                ],
            ],
        ]);

        return $chart;
    }

    public function createPrognose(array $data, string $creationDate, Plant $plant): array
    {
        $prognose = [];

        // Group the data by their task
        $groupedData = [];
        foreach ($data as $task) {
            $groupedData[$task['task']][] = $task;
        }

        // Calculate the intensity for each task
        foreach ($groupedData as $taskName => $tasks) {
            switch ($taskName) {
                case 'temperature':
                    $lastTask = null;
                    foreach ($tasks as $task) {
                        if ($lastTask === null) {
                            $lastTask = [
                                'date' => $creationDate,
                                'task' => 'temperature',
                            ];
                        }

                        $lastTaskDate = new \DateTime($lastTask['date']);
                        $taskDate = new \DateTime($task['date']);

                        $prognose[$taskDate->format('d.m.Y')] = self::TASK_WEIGHTS['temperature'];

                        $diff = $lastTaskDate->diff($taskDate);

                        if ($diff->days > 2) {
                            // Give penalty for each day the plant was not watered.
                            for ($i = $diff->days; $i != 2; --$i) {
                                $modifyAble = clone $lastTaskDate;
                                $date = $modifyAble->modify("+{$i} day")->format('d.m.Y');

                                // we don't want a penalty for the date we watered the plant
                                if ($date == $taskDate->format('d.m.Y')) {
                                    continue;
                                }

                                if (!isset($prognose[$date])) {
                                    $prognose[$date] = 0;
                                }
                                $prognose[$date] -= self::TASK_WEIGHTS['temperature'] * 0.3;
                            }
                        }

                        $lastTask = $task;
                    }

                    break;
                case 'pesticide':
                    $lastTask = null;
                    foreach ($tasks as $task) {
                        if ($lastTask === null) {
                            $lastTask = [
                                'date' => $creationDate,
                                'task' => 'pesticide',
                            ];
                        }

                        $lastTaskDate = new \DateTime($lastTask['date']);
                        $taskDate = new \DateTime($task['date']);

                        $prognose[$taskDate->format('d.m.Y')] = self::TASK_WEIGHTS['pesticide'];

                        $diff = $lastTaskDate->diff($taskDate);

                        if ($diff->days > 7) {
                            // Give penalty for each day the plant was not watered.
                            for ($i = $diff->days; $i != 7; --$i) {
                                $modifyAble = clone $lastTaskDate;
                                $date = $modifyAble->modify("+{$i} day")->format('d.m.Y');

                                // we don't want a penalty for the date we watered the plant
                                if ($date == $taskDate->format('d.m.Y')) {
                                    continue;
                                }

                                if (!isset($prognose[$date])) {
                                    $prognose[$date] = 0;
                                }
                                $prognose[$date] -= self::TASK_WEIGHTS['pesticide'] * 0.3;
                            }
                        }

                        $lastTask = $task;
                    }

                    break;
                case 'fertilize':
                    $lastTask = null;
                    foreach ($tasks as $task) {
                        if ($lastTask === null) {
                            $lastTask = [
                                'date' => $creationDate,
                                'task' => 'fertilize',
                            ];
                        }

                        $lastTaskDate = new \DateTime($lastTask['date']);
                        $taskDate = new \DateTime($task['date']);

                        $prognose[$taskDate->format('d.m.Y')] = self::TASK_WEIGHTS['fertilize'];

                        $diff = $lastTaskDate->diff($taskDate);

                        if ($diff->days > 14) {
                            // Give penalty for each day the plant was not watered.
                            for ($i = $diff->days; $i != 14; --$i) {
                                $modifyAble = clone $lastTaskDate;
                                $date = $modifyAble->modify("+{$i} day")->format('d.m.Y');

                                // we don't want a penalty for the date we watered the plant
                                if ($date == $taskDate->format('d.m.Y')) {
                                    continue;
                                }

                                if (!isset($prognose[$date])) {
                                    $prognose[$date] = 0;
                                }
                                $prognose[$date] -= self::TASK_WEIGHTS['fertilize'] * 0.3;
                            }
                        }

                        $lastTask = $task;
                    }

                    break;
                case 'water':
                    $lastTask = null;
                    foreach ($tasks as $task) {
                        if ($lastTask === null) {
                            $lastTask = [
                                'date' => $creationDate,
                                'task' => 'water',
                            ];
                        }

                        $lastTaskDate = new \DateTime($lastTask['date']);
                        $taskDate = new \DateTime($task['date']);

                        $prognose[$taskDate->format('d.m.Y')] = self::TASK_WEIGHTS['water'];

                        $diff = $lastTaskDate->diff($taskDate);

                        if ($diff->days > 5) {
                            // Give penalty for each day the plant was not watered.
                            for ($i = $diff->days; $i != 5; --$i) {
                                $modifyAble = clone $lastTaskDate;
                                $date = $modifyAble->modify("+{$i} day")->format('d.m.Y');

                                // we don't want a penalty for the date we watered the plant
                                if ($date == $taskDate->format('d.m.Y')) {
                                    continue;
                                }

                                if (!isset($prognose[$date])) {
                                    $prognose[$date] = 0;
                                }
                                $prognose[$date] -= self::TASK_WEIGHTS['water'] * 0.3;

                            }
                        }

                        $lastTask = $task;
                    }

                    break;
            }
        }

        // Sort the prognose by date ASC
        ksort($prognose);

        // Now we have to sum up the values for each day
        // The day should have all values summed up, which are in the past
        $sum = 0;
        foreach ($prognose as $date => $value) {
            $sum += $value;
            $prognose[$date] = $sum;
        }

        $plant->setCurrentPrognosisValue($sum);
        $this->entityManager->persist($plant);
        $this->entityManager->flush();

        return $prognose;
    }

}
