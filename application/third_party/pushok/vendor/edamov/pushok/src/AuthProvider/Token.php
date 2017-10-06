<?php

/*
 * This file is part of the Pushok package.
 *
 * (c) Arthur Edamov <edamov@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pushok\AuthProvider;

use Jose\Factory\JWKFactory;
use Jose\Factory\JWSFactory;
use Jose\Object\JWKInterface;
use Pushok\AuthProviderInterface;
use Pushok\Request;

/**
 * Class Token
 * @package Pushok\AuthProvider
 *
 * @see http://bit.ly/communicating-with-apns
 */
class Token implements AuthProviderInterface
{
    /**
     * Hash alghorithm for generating auth token.
     */
    const HASH_ALGORITHM = 'ES256';

    /**
     * Generated auth token.
     *
     * @var string
     */
    private $token;

    /**
     * Path to p8 private key.
     *
     * @var string
     */
    private $privateKeyPath;

    /**
     * Private key secret.
     *
     * @var string|null
     */
    private $privateKeySecret;

    /**
     * The Key ID obtained from Apple developer account.
     *
     * @var string
     */
    private $keyId;

    /**
     * The Team ID obtained from Apple developer account.
     *
     * @var string
     */
    private $teamId;

    /**
     * The bundle ID for app obtained from Apple developer account.
     *
     * @var string
     */
    private $appBundleId;

    /**
     * This provider accepts the following options:
     *
     * - key_id
     * - team_id
     * - app_bundle_id
     * - private_key_path
     * - private_key_secret
     *
     * @param array $options
     */
    private function __construct(array $options)
    {
        $this->keyId = $options['key_id'];
        $this->teamId = $options['team_id'];
        $this->appBundleId = $options['app_bundle_id'];
        $this->privateKeyPath = $options['private_key_path'];
        $this->privateKeySecret = $options['private_key_secret'] ?: null;
    }

    /**
     * Create Token Auth Provider.
     *
     * @param array $options
     * @return Token
     */
    public static function create(array $options): Token
    {
        $token = new self($options);
        $token->token = $token->generate();

        return $token;
    }

    /**
     * Use previously generated token.
     *
     * @param string $tokenString
     * @param array $options
     * @return Token
     */
    public static function useExisting(string $tokenString, array $options): Token
    {
        $token = new self($options);
        $token->token = $tokenString;

        return $token;
    }

    /**
     * Authenticate client.
     *
     * @param Request $request
     */
    public function authenticateClient(Request $request)
    {
        $request->addHeaders([
            "apns-topic" => $this->appBundleId,
            'Authorization' => 'bearer ' . $this->token
        ]);
    }

    /**
     * Get last generated token.
     *
     * @return string
     */
    public function get(): string
    {
        return $this->token;
    }

    /**
     * Generate private EC key.
     *
     * @return JWKInterface
     */
    private function generatePrivateECKey(): JWKInterface
    {
        return JWKFactory::createFromKeyFile($this->privateKeyPath, $this->privateKeySecret, [
            'kid' => $this->keyId,
            'alg' => self::HASH_ALGORITHM,
            'use' => 'sig'
        ]);
    }

    /**
     * Get claims payload.
     *
     * @return array
     */
    private function getClaimsPayload(): array
    {
        return [
            'iss' => $this->teamId,
            'iat' => time(),
        ];
    }

    /**
     * Get protected header.
     *
     * @param JWKInterface $privateECKey
     * @return array
     */
    private function getProtectedHeader(JWKInterface $privateECKey): array
    {
        return [
            'alg' => self::HASH_ALGORITHM,
            'kid' => $privateECKey->get('kid'),
        ];
    }

    /**
     * Generate new token.
     *
     * @return string
     */
    private function generate(): string
    {
        $privateECKey = $this->generatePrivateECKey();

        $this->token = JWSFactory::createJWSToCompactJSON(
            $this->getClaimsPayload(),
            $privateECKey,
            $this->getProtectedHeader($privateECKey)
        );

        return $this->token;
    }
}
