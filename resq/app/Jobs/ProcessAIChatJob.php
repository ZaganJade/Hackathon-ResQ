<?php

namespace App\Jobs;

use App\Models\Chatlog;
use App\Services\AIAssistService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAIChatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 5;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 60;

    private int $userId;
    private string $message;
    private string $conversationId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, string $message, string $conversationId)
    {
        $this->userId = $userId;
        $this->message = $message;
        $this->conversationId = $conversationId;
    }

    /**
     * Execute the job.
     */
    public function handle(AIAssistService $aiService): void
    {
        $startTime = microtime(true);

        try {
            // Process the chat message through AI service
            $result = $aiService->chat($this->message, $this->userId, $this->conversationId);

            $endTime = microtime(true);
            $processingTime = round($endTime - $startTime, 3);

            if ($result['success']) {
                Log::info('AI chat processed successfully', [
                    'user_id' => $this->userId,
                    'conversation_id' => $this->conversationId,
                    'processing_time' => $processingTime,
                    'response_time' => $result['response_time'],
                ]);
            } else {
                Log::warning('AI chat processed with errors', [
                    'user_id' => $this->userId,
                    'conversation_id' => $this->conversationId,
                    'error' => $result['error'] ?? 'Unknown error',
                ]);

                // Update the last chatlog with error status
                Chatlog::forUser($this->userId)
                    ->conversation($this->conversationId)
                    ->where('role', 'user')
                    ->latest()
                    ->first()
                    ?->update([
                        'metadata' => array_merge(
                            Chatlog::forUser($this->userId)
                                ->conversation($this->conversationId)
                                ->where('role', 'user')
                                ->latest()
                                ->first()
                                ?->metadata ?? [],
                            ['processing_error' => $result['error'] ?? 'Unknown error']
                        ),
                    ]);
            }
        } catch (RequestException $e) {
            Log::error('Fireworks AI API error in queue job', [
                'message' => $e->getMessage(),
                'user_id' => $this->userId,
                'conversation_id' => $this->conversationId,
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        } catch (\Exception $e) {
            Log::error('Unexpected error in AI chat job', [
                'message' => $e->getMessage(),
                'user_id' => $this->userId,
                'conversation_id' => $this->conversationId,
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('AI chat job failed after all retries', [
            'message' => $exception->getMessage(),
            'user_id' => $this->userId,
            'conversation_id' => $this->conversationId,
            'attempts' => $this->attempts(),
        ]);

        // Create a fallback AI response indicating the failure
        Chatlog::create([
            'user_id' => $this->userId,
            'conversation_id' => $this->conversationId,
            'role' => 'assistant',
            'message' => 'Maaf, sistem AI sedang mengalami gangguan. Tim teknis kami telah diberitahu. Silakan coba lagi nanti.',
            'metadata' => [
                'error' => $exception->getMessage(),
                'failed_at' => now()->toIso8601String(),
                'is_error_response' => true,
            ],
        ]);
    }
}
