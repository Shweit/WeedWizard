<?php

namespace App\Services;

use App\Entity\CannaDoseCalculator;
use App\Interface\CannaDoseCalculatorInterface;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

readonly class CannaDoseCalculatorService implements CannaDoseCalculatorInterface
{
    public function __construct(
        private ChartBuilderInterface $chartBuilder,
    ) {}

    /**
     * Berechnet die Dosis für Cannabis basierend auf Erfahrung, Intensität und einer Basisdosierung.
     * Diese Funktion nimmt ein Array mit spezifischen Schlüssel-Wert-Paaren auf und berechnet
     * eine angepasste Cannabis-Dosis in Milligramm. Die Dosis wird durch die Erfahrung des Nutzers,
     * die gewünschte Intensität und eine vorgegebene Basisdosierung bestimmt. Die berechnete Dosis
     * wird gerundet, um eine ganze Zahl als Ergebnis zu liefern.
     *
     * @param array $data Ein assoziatives Array, das die Schlüssel 'basis_dosage', 'experience'
     *                    und 'intensity' enthält. Diese Werte sind erforderlich für die Berechnung:
     *                    - 'basis_dosage' (float|int): Die Basisdosierung in mg.
     *                    - 'experience' (float): Der Erfahrungsfaktor des Nutzers (z.B. 0.8 für Anfänger).
     *                    - 'intensity' (int): Der Intensitätsgrad, skaliert von 1 bis 20.
     * @return float|int die gerundete Dosis von Cannabis in mg
     */
    public function calculateDose(array $data): float|int
    {
        $basisDosage = $data['basis_dosage'];
        $experience = $data['experience'];
        $intensity = $data['intensity'];

        $intensityAdjustment = 1 + ($intensity - 1) / 19;
        $calculatedDose = $basisDosage * $experience * $intensityAdjustment;

        return round($calculatedDose);
    }

    public function calculateRangeIntensityChart(CannaDoseCalculator $data): Chart
    {
        $data = $data->toArray();

        $rangeIntensity = [];

        $intensity = $data['intensity'];

        for ($i = 1; $i <= 20; ++$i) {
            $data['intensity'] = $i;
            $rangeIntensity[$i] = $this->calculateDose($data);
        }

        $data['intensity'] = $intensity;

        $chart = $this->chartBuilder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => array_keys($rangeIntensity),
            'datasets' => [
                [
                    'label' => 'Dosierung',
                    'borderColor' => 'rgb(64, 253, 20)',
                    'data' => array_values($rangeIntensity),
                ],
            ],
        ]);

        $chart->setOptions([
            'responsive' => true,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Dosierung (mg)',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Intensität',
                    ],
                ],
            ],
            'plugins' => [
                'annotation' => [
                    'annotations' => [
                        [
                            'type' => 'point',
                            'xValue' => $data['intensity'] -1,
                            'yValue' => $data['recommended_dosage'],
                            'backgroundColor' => 'rgba(255, 99, 132, 0.25)',
                        ],
                    ],
                ],
            ],
        ]);

        return $chart;
    }
}
