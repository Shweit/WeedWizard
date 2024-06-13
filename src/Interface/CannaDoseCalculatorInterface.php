<?php

namespace App\Interface;

use App\Entity\CannaDoseCalculator;
use Symfony\UX\Chartjs\Model\Chart;

interface CannaDoseCalculatorInterface
{
    public function calculateDose(array $data): float|int;

    public function calculateRangeIntensityChart(CannaDoseCalculator $data): Chart;
}
