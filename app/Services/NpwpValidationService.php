<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NpwpValidationService
{
    public function isValid(string $npwp): bool
    {
        if (!config('npwp.enabled')) {
            return true;
        }

        $endpoint = trim((string) config('npwp.endpoint'));
        if ($endpoint === '') {
            // No endpoint configured yet; allow to pass until API is ready.
            return true;
        }

        try {
            $response = Http::timeout((int) config('npwp.timeout', 5))
                ->get($endpoint, ['npwp' => $npwp]);

            if (!$response->ok()) {
                return false;
            }

            // Placeholder parsing. Update based on API response schema.
            $data = $response->json();
            return (bool) ($data['valid'] ?? false);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
