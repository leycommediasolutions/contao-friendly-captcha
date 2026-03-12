<?php

declare(strict_types=1);

namespace Leycommediasolutions\ContaoFriendlyCaptcha\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FriendlyCaptchaVerifier
{
    private const VERIFY_URL = 'https://api.friendlycaptcha.com/api/v1/siteverify';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function verify(string $solution): bool
    {
        $secret = $this->getSecret();

        if ('' === $secret) {
            return false;
        }

        try {
            $response = $this->httpClient->request('POST', self::VERIFY_URL, [
                'json' => [
                    'solution' => $solution,
                    'response' => $solution,
                    'secret' => $secret,
                ],
                'timeout' => 8,
            ]);

            $statusCode = $response->getStatusCode();

            if (200 !== $statusCode) {
                $this->logger->warning('Friendly Captcha verify failed with non-200 status code.', [
                    'status_code' => $statusCode,
                ]);

                return false;
            }

            $payload = $response->toArray(false);
            $success = isset($payload['success']) && true === $payload['success'];

            if (!$success) {
                $this->logger->warning('Friendly Captcha verify response indicates failure.', [
                    'errors' => $payload['errors'] ?? null,
                    'error' => $payload['error'] ?? null,
                ]);
            }

            return $success;
        } catch (ExceptionInterface) {
            $this->logger->warning('Friendly Captcha verify request failed due to HTTP client exception.');

            return false;
        }
    }

    private function getSecret(): string
    {
        $secret = (string) ($_ENV['FRIENDLY_CAPTCHA_SECRET'] ?? $_SERVER['FRIENDLY_CAPTCHA_SECRET'] ?? getenv('FRIENDLY_CAPTCHA_SECRET') ?: '');

        return trim($secret);
    }
}
