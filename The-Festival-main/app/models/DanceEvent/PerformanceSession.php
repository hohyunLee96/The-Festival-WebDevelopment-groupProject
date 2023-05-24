<?php

class PerformanceSession
{
    private int $performanceSessionId;
    private string $sessionName;
    private ?string $sessionDescription =null; // nott every session have description for now so allowing it to be null

    /**
     * @return int
     */
    public function getPerformanceSessionId(): int
    {
        return $this->performanceSessionId;
    }

    /**
     * @param int $performanceSessionId
     */
    public function setPerformanceSessionId(int $performanceSessionId): void
    {
        $this->performanceSessionId = $performanceSessionId;
    }

    /**
     * @return string
     */
    public function getSessionName(): string
    {
        return $this->sessionName;
    }

    /**
     * @param string $sessionName
     */
    public function setSessionName(string $sessionName): void
    {
        $this->sessionName = $sessionName;
    }

    /**
     * @return string|null
     */
    public function getSessionDescription(): ?string
    {
        return $this->sessionDescription;
    }

    /**
     * @param string|null $sessionDescription
     */
    public function setSessionDescription(?string $sessionDescription): void
    {
        $this->sessionDescription = $sessionDescription;
    }



}