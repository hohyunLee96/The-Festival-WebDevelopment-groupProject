<?php

class BodyHead
{
    private int $bodyHeadId;
    private string $h1;
    private string $h2;
    private string $image;

    /**
     * @return int
     */
    public function getBodyHeadId(): int
    {
        return $this->bodyHeadId;
    }

    /**
     * @param int $bodyHeadId
     */
    public function setBodyHeadId(int $bodyHeadId): void
    {
        $this->bodyHeadId = $bodyHeadId;
    }

    /**
     * @return string
     */
    public function getH1(): string
    {
        return $this->h1;
    }

    /**
     * @param string $h1
     */
    public function setH1(string $h1): void
    {
        $this->h1 = $h1;
    }

    /**
     * @return string
     */
    public function getH2(): string
    {
        return $this->h2;
    }

    /**
     * @param string $h2
     */
    public function setH2(string $h2): void
    {
        $this->h2 = $h2;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }
}