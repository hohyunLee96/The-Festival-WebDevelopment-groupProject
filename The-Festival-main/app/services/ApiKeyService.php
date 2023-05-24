<?php
require_once __DIR__ . '/../repositories/ApiKeyRepository.php';
require_once __DIR__ . '/../models/Exceptions/DatabaseQueryException.php';

class ApiKeyService
{
    private $apiKeyRepository;

    public function __construct()
    {
        $this->apiKeyRepository = new ApiKeyRepository();
    }

    private function generateKey(): string
    {
        $unformulatedKey = bin2hex(openssl_random_pseudo_bytes(16));
        $unformulatedKey[6] = chr(ord($unformulatedKey[6]) & 0x0f | 0x40); // set version to 0100
        $unformulatedKey[8] = chr(ord($unformulatedKey[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($unformulatedKey), 4));
    }

    public function getUniqueKey(): string
    {
        $key = $this->generateKey();
        while ($this->apiKeyRepository->checkApiKeyExistenceInDb($key)) {
            $key = $this->generateKey();
        }
        return $key;
    }

    /**
     * @throws DatabaseQueryException
     */
    public function createApiKey($data)
    {
        if ($this->apiKeyRepository->checkExistenceOfAPiKeyWithPurpose($data['usedBy'], $data['purpose'])) {
            throw new DatabaseQueryException("Api key already exists for this Party and purpose");
        }
        $data['key'] = $this->getUniqueKey();
        return $this->apiKeyRepository->createApiKey($data);
    }

    public function getAllApiKeys(): ?array
    {
        return $this->apiKeyRepository->getAllApiKeys();
    }
    public function isApiKeyValid($key): bool
    {
        return $this->apiKeyRepository->checkApiKeyExistenceInDb($key);
    }
}