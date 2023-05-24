<?php

class ApiKey
{
    private int $apiKeyId;
    private string $Key;
    private string $usedBy;
    private string $purpose;
    private DateTime $createdOn;
    /**
     * @param int $apiKeyId
     * @param string $Key
     * @param string $usedBy
     * @param string $purpose
     * @param DateTime $createdOn
     */
    public function __construct(int $apiKeyId, string $Key, string $usedBy, string $purpose, DateTime $createdOn)
    {
        $this->apiKeyId = $apiKeyId;
        $this->Key = $Key;
        $this->usedBy = $usedBy;
        $this->purpose = $purpose;
        $this->createdOn= $createdOn;
    }
    /**
     * @return int
     */
    public function getApiKeyId(): int
    {
        return $this->apiKeyId;
    }

    /**
     * @param int $apiKeyId
     */
    public function setApiKeyId(int $apiKeyId): void
    {
        $this->apiKeyId = $apiKeyId;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->Key;
    }

    /**
     * @param string $Key
     */
    public function setKey(string $Key): void
    {
        $this->Key = $Key;
    }

    /**
     * @return string
     */
    public function getUsedBy(): string
    {
        return $this->usedBy;
    }

    /**
     * @param string $usedBy
     */
    public function setUsedBy(string $usedBy): void
    {
        $this->usedBy = $usedBy;
    }

    /**
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose(string $purpose): void
    {
        $this->purpose = $purpose;
    }

    /**
     * @return DateTime
     */
    public function getCreatedOn(): DateTime
    {
        return $this->createdOn;
    }

    /**
     * @param DateTime $createdOn
     */
    public function setCreatedOn(DateTime $createdOn): void
    {
        $this->createdOn = $createdOn;
    }


}