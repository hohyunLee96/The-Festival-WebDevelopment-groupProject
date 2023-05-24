<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/ApiKey.php';

class ApiKeyRepository extends Repository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllApiKeys(): ?array
    {
        $query = "SELECT apikey.apiKeyId,apikey.key,apikey.UsedBy,apikey.purpose, apikey.createdOn FROM apikey ";
        $result = $this->executeQuery($query);
        if (!empty($result)) {
            $apiKeys = array();
            foreach ($result as $row) {
                $apiKeys[] = new ApiKey($row['apiKeyId'], $row['key'], $row['UsedBy'], $row['purpose'], new DateTime($row['createdOn']));
            }
            return $apiKeys;
        }
        return null;
    }

    public function checkApiKeyExistenceInDb($key): bool
    {
        $query = "SELECT apikey.apiKeyId FROM apikey WHERE apikey.key = :key";
        $result = $this->executeQuery($query, [':key' => $key]);
        return !empty($result);
    }

    public function createApiKey($data)
    {
        $query = "INSERT INTO apikey (`key`,UsedBy,purpose) VALUES (:key,:usedBy,:purpose)";
        $result = $this->executeQuery($query, [':key' => $data['key'], ':usedBy' => $data['usedBy'], ':purpose' => $data['purpose']]);
        // since it is a insert query, it will return bool
        return $result;
    }

    public function checkExistenceOfAPiKeyWithPurpose($usedBy,$purpose): bool
    {
        $query = "SELECT apikey.apiKeyId FROM apikey WHERE apikey.UsedBy = :usedBy and apikey.purpose = :purpose";
        $result = $this->executeQuery($query, [':usedBy' => $usedBy, ':purpose' => $purpose]);
        if (empty($result)) {
            return false;
        }
        return true;
    }
}