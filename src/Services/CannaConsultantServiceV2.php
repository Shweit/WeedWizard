<?php

namespace App\Services;

use App\Entity\CannaConsultantThreads;
use Doctrine\ORM\EntityManagerInterface;
use OpenAI;

class CannaConsultantServiceV2 extends CannaConsultantFunctions
{
    private OpenAI\Client $client;

    public function __construct(
        private readonly string $apiKey,
        private readonly string $assistantId,
        private string $seedFinderApiKey,
        private readonly WeedWizardKernel $weedWizardKernel,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager, $weedWizardKernel, $seedFinderApiKey);
        $this->client = OpenAI::client($this->apiKey);
    }

    public function getRecentMessages(): ?array
    {
        $thread = $this->getThread();

        return $this->client->threads()->messages()->list($thread['id'])->toArray();
    }

    public function addMessageToThread(string $message, bool $runThread = true, string $instructions = ''): array
    {
        try {
            $thread = $this->getThread();

            // $this->client->threads()->runs()->cancel($thread['id'], 'run_F9NPDOdlRVwSuYKS3wVmpaG1');
            $response = $this->client->threads()->messages()->create($thread['id'], [
                'role' => 'user',
                'content' => $message,
            ])->toArray();

            if ($runThread) {
                $runResponse = $this->createRun($instructions);
                $response['run'] = $runResponse;
            }

            return $this->client->threads()->messages()->list($thread['id'])->toArray();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function createRun(string $instructions = '')
    {
        try {
            $response = $this->client->threads()->runs()->create(
                threadId: $this->getThread()['id'],
                parameters: [
                    'instructions' => $instructions,
                    'assistant_id' => $this->assistantId,
                ],
            );

            $maxRetries = 30;
            $retryDelay = 2;

            for ($i = 0; $i < $maxRetries; ++$i) {
                $response = $this->client->threads()->runs()->retrieve(
                    $this->getThread()['id'],
                    $response->toArray()['id']
                );

                switch ($response->status) {
                    case 'failed':
                    case 'completed':
                        return $response->toArray();
                    case 'requires_action':
                        $toolOutputs = [];
                        foreach ($response->requiredAction->submitToolOutputs->toolCalls as $tool) {
                            if ($tool->type === 'function') {
                                $functionName = $tool->function->name;
                                $arguments = json_decode($tool->function->arguments, true) ?? [];

                                try {
                                    if (method_exists($this, $functionName)) {
                                        $output = call_user_func_array([$this, $functionName], $arguments);
                                        $toolOutputs[] = [
                                            'tool_call_id' => $tool->id,
                                            'output' => is_string($output) ? $output : json_encode($output),
                                        ];
                                    } else {
                                        $toolOutputs[] = [
                                            'tool_call_id' => $tool->id,
                                            'output' => 'Method not found',
                                        ];
                                    }
                                } catch (\Exception $e) {
                                    // Hier können Sie den Fehler behandeln, z.B. den Lauf löschen und den Fehler zurückgeben
                                    $this->client->threads()->runs()->cancel($this->getThread()['id'], $response->toArray()['id']);

                                    return ['error' => $e->getMessage()];
                                }
                            }
                        }

                        $response = $this->client->threads()->runs()->submitToolOutputs(
                            threadId: $this->getThread()['id'],
                            runId: $response->id,
                            parameters: [
                                'tool_outputs' => $toolOutputs,
                            ]
                        );
                        // no break
                    default:
                        sleep($retryDelay);
                }
            }

            throw new \Exception('Run did not complete in the expected time');
        } catch (\Exception $e) {
            $this->client->threads()->runs()->cancel($this->getThread()['id'], $response->toArray()['id']);

            return ['error' => $e->getMessage()];
        }
    }

    public function getSeedFinderApiKey(): string
    {
        return $this->seedFinderApiKey;
    }

    private function getThread(): array
    {
        $threadEntity = $this->entityManager->getRepository(CannaConsultantThreads::class)->findOneBy([
            'user' => $this->weedWizardKernel->getUser(),
        ]);

        if ($threadEntity) {
            return $threadEntity->getThread();
        }

        // Create a Thread if none exists
        $response = $this->client->threads()->create([]);

        $threadEntity = new CannaConsultantThreads();
        $threadEntity->setThread($response->toArray());
        $threadEntity->setUser($this->weedWizardKernel->getUser());
        $this->entityManager->persist($threadEntity);
        $this->entityManager->flush();

        return $response->toArray();
    }
}
