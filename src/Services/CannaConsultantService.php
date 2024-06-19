<?php

namespace App\Services;

use App\Entity\BudBash;
use App\Entity\CannaConsultantThreads;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CannaConsultantService
{
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        private WeedWizardKernel $weedWizardKernel,
        private string $apiKey,
        private string $assistentId,
    ) {}

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function addMessageToThread(string $message, bool $runThread = true, string $instructions = ''): array
    {
        $thread = $this->getThread();

        if (!$thread) {
            return [
                'error' => 'No thread found or created',
                'message' => 'Thread creation failed or thread retrieval failed.',
            ];
        }

        $url = "https://api.openai.com/v1/threads/{$thread['id']}/messages";
        $response = $this->client->request('POST', $url, [
            'headers' => $this->getHeaders(),
            'json' => [
                'role' => 'user',
                'content' => $message,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            return [
                'error' => 'Message request failed',
                'status_code' => $response->getStatusCode(),
                'message' => $response->getContent(false),
            ];
        }

        $responseData = $response->toArray();

        if ($runThread) {
            $runResponse = $this->createRun($instructions);
            $responseData['run'] = $runResponse;
        }

        return $responseData;
    }

    public function createRun(string $instructions = ''): array
    {
        $thread = $this->getThread();

        if (!$thread) {
            return [
                'error' => 'No thread found or created',
                'message' => 'Thread creation failed or thread retrieval failed.',
            ];
        }

        $url = "https://api.openai.com/v1/threads/{$thread['id']}/runs";
        $response = $this->client->request('POST', $url, [
            'headers' => $this->getHeaders(),
            'json' => [
                'assistant_id' => $this->assistentId,
                'instructions' => $instructions,
            ],
        ]);

        if ($response->getStatusCode() !== 200) {
            return [
                'error' => 'Run creation failed',
                'status_code' => $response->getStatusCode(),
                'message' => $response->getContent(false),
            ];
        }

        $runData = $response->toArray();

        $runId = $runData['id'];
        $this->waitForRunCompletion($thread['id'], $runId);

        $url = "https://api.openai.com/v1/threads/{$thread['id']}/messages";
        $response = $this->client->request('GET', $url, [
            'headers' => $this->getHeaders(),
        ]);

        if ($response->getStatusCode() !== 200) {
            return [
                'error' => 'Fetching messages failed',
                'status_code' => $response->getStatusCode(),
                'message' => $response->getContent(false),
            ];
        }

        return $response->toArray();
    }

    public function getRecentMessages()
    {
        $thread = $this->getThread();

        if (!$thread) {
            return [
                'error' => 'No thread found or created',
                'message' => 'Thread creation failed or thread retrieval failed.',
            ];
        }

        $url = "https://api.openai.com/v1/threads/{$thread['id']}/messages";
        $response = $this->client->request('GET', $url, [
            'headers' => $this->getHeaders(),
        ]);

        if ($response->getStatusCode() !== 200) {
            return [
                'error' => 'Fetching messages failed',
                'status_code' => $response->getStatusCode(),
                'message' => $response->getContent(false),
            ];
        }

        return $response->toArray();
    }

    public function handleRequiredAction(string $threadId, string $runId, $input)
    {
        $toolOutputs = [];
        $functionArray = $input['required_action']['submit_tool_outputs']['tool_calls'];
        foreach ($functionArray as $function) {
            $functionName = $function['function']['name'];
            $return = $this->{$functionName}();

            $toolOutputs[] = [
                'tool_call_id' => $function['id'],
                'output' => $return,
            ];
            dump($return);
        }
        dd($threadId, $runId, $input);
    }

    private function waitForRunCompletion(string $threadId, string $runId): void
    {
        $url = "https://api.openai.com/v1/threads/{$threadId}/runs/{$runId}";
        $maxRetries = 30; // Maximale Anzahl von Versuchen
        $retryDelay = 2; // Wartezeit zwischen den Versuchen in Sekunden

        for ($i = 0; $i < $maxRetries; ++$i) {
            $response = $this->client->request('GET', $url, [
                'headers' => $this->getHeaders(),
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Error fetching run status: ' . $response->getContent(false));
            }

            $runStatus = $response->toArray();

            if ($runStatus['status'] === 'completed') {
                return;
            }

            if ($runStatus['status'] === 'requires_action') {
                $this->handleRequiredAction($threadId, $runId, $runStatus);
            }

            sleep($retryDelay);
        }

        throw new \Exception('Run did not complete in the expected time');
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
            'OpenAI-Beta' => 'assistants=v2',
        ];
    }

    private function getThread(): ?array
    {
        $threadEntity = $this->entityManager->getRepository(CannaConsultantThreads::class)->findOneBy([
            'user' => $this->weedWizardKernel->getUser(),
        ]);

        if ($threadEntity) {
            return $threadEntity->getThread();
        }

        // If no thread found, create a new one
        $response = $this->client->request('POST', 'https://api.openai.com/v1/threads', [
            'headers' => $this->getHeaders(),
            'json' => [],
        ]);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $threadData = $response->toArray();

        $cannaConsultantThread = new CannaConsultantThreads();
        $cannaConsultantThread->setUser($this->weedWizardKernel->getUser());
        $cannaConsultantThread->setThread($threadData);
        $this->entityManager->persist($cannaConsultantThread);
        $this->entityManager->flush();

        return $threadData;
    }

    private function get_bud_bash_partys(): array
    {
        $budBashes = $this->entityManager->getRepository(BudBash::class)->findAll();

        $budBashes = array_filter($budBashes, function ($budBash) {
            return $budBash->getCreatedBy() !== $this->weedWizardKernel->getUser() && $budBash->getStart() > new \DateTime() && !$budBash->getParticipants()->contains($this->weedWizardKernel->getUser());
        });

        return array_map(function (BudBash $budBash) {
            return [
                'id' => $budBash->getId(),
                'name' => $budBash->getName(),
                'start' => $budBash->getStart()->format('d.m.Y H:i:s'),
                'coordinates' => $budBash->getCoordinates(),
                'entrance_fee' => $budBash->getEntranceFee(),
                'extraInfo' => $budBash->getExtraInfo() ?? 'Der ersteller der Party hat keine Informationen angegeben.',
                'address' => $budBash->getAddress(),
            ];
        }, $budBashes);
    }
}
