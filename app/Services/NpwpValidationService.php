<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NpwpValidationService
{
    public function isValid(string $npwp): bool
    {
        $result = $this->validate($npwp);
        return $result['valid'];
    }

    public function validate(string $npwp): array
    {
        if (!config('npwp.enabled')) {
            return [
                'valid' => true,
                'message' => null,
                'http' => null,
                'data' => null,
            ];
        }

        $endpoint = trim((string) config('npwp.endpoint'));
        if ($endpoint === '') {
            return [
                'valid' => true,
                'message' => null,
                'http' => null,
                'data' => null,
            ];
        }

        try {
            $response = Http::timeout((int) config('npwp.timeout', 5))
                ->asJson()
                ->post($endpoint, ['npwp' => $npwp]);

            $data = $response->json();
            if (!is_array($data)) {
                Log::warning('NPWP validation failed: invalid response body', [
                    'npwp' => $npwp,
                    'http' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'valid' => false,
                    'message' => 'Gagal memvalidasi NPWP. Respon tidak dikenali.',
                    'http' => $response->status(),
                    'data' => null,
                ];
            }

            $isValid = ($data['ok'] ?? false) === true && ($data['status'] ?? '') === 'valid';
            if (!$isValid) {
                Log::channel('npwp')->warning('NPWP validation invalid', [
                    'npwp' => $npwp,
                    'http' => $data['http'] ?? $response->status(),
                    'status' => $data['status'] ?? null,
                    'message' => $data['message'] ?? null,
                    'data' => $data,
                ]);
            }

            return [
                'valid' => $isValid,
                'message' => $data['message'] ?? 'NPWP tidak valid.',
                'http' => $data['http'] ?? $response->status(),
                'data' => $data,
            ];
        } catch (\Throwable $e) {
            Log::channel('npwp')->error('NPWP validation error', [
                'npwp' => $npwp,
                'error' => $e->getMessage(),
            ]);

            return [
                'valid' => false,
                'message' => 'Gagal memvalidasi NPWP. Silakan coba lagi.',
                'http' => null,
                'data' => null,
            ];
        }
    }
}
