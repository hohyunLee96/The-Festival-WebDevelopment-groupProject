<?php

class Page implements JsonSerializable{
    private int $id;
    private string $URI;
    private string $title;
    private string $bodyContentHTML;
    private DateTime $creationTime;
    private int $creatorUserId;
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    /**
     * @return string
     */
    public function getURI(): string
    {
        return $this->URI;
    }
    /**
     * @param string $URI
     */
    public function setURI(string $URI): void
    {
        $this->URI = $URI;
    }
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    /**
     * @return string
     */
    public function getBodyContentHTML(): string
    {
        return $this->bodyContentHTML;
    }
    /**
     * @param string $bodyContentHTML
     */
    public function setBodyContentHTML(string $bodyContentHTML): void
    {
        $this->bodyContentHTML = $bodyContentHTML;
    }
    /**
     * @return DateTime
     */
    public function getCreationTime(): DateTime
    {
        return $this->creationTime;
    }
    /**
     * @param DateTime $creationTime
     */
    public function setCreationTime(DateTime $creationTime): void
    {
        $this->creationTime = $creationTime;
    }
    /**
     * @return int
     */
    public function getCreatorUserId(): int
    {
        return $this->creatorUserId;
    }
    /**
     * @param int $creatorUserId
     */
    public function setCreatorUserId(int $creatorUserId): void
    {
        $this->creatorUserId = $creatorUserId;
    }
}