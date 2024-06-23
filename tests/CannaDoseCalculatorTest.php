<?php

use App\Services\CannaDoseCalculatorService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CannaDoseCalculatorTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $container = static::getContainer();

        $cannaDoseCalculatorService = $container->get(CannaDoseCalculatorService::class);

        $cannaDoseCalculatorData = [
            'basis_dosage' => 300,
            'experience' => 0.8,
            'intensity' => 10,
        ];

        $dose = $cannaDoseCalculatorService->calculateDose($cannaDoseCalculatorData);

        $this->assertSame(354.0, $dose, 'The calculated dose is not correct.');
        restore_exception_handler();
    }
}
