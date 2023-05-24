<?php
require_once __DIR__ . '/Content.php';

class EventPage
{
    private int $eventPageId;
    private string $eventPageName;
    private Content $content;
    private string $url;
    private string $image;
    private string $ticket;

    /**
     * @return int
     */
    public function getEventPageId(): int
    {
        return $this->eventPageId;
    }

    /**
     * @param int $eventPageId
     */
    public function setEventPageId(int $eventPageId): void
    {
        $this->eventPageId = $eventPageId;
    }

    /**
     * @return string
     */
    public function getEventPageName(): string
    {
        return $this->eventPageName;
    }

    /**
     * @param string $eventPageName
     */
    public function setEventPageName(string $eventPageName): void
    {
        $this->eventPageName = $eventPageName;
    }

    /**
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @param Content $content
     */
    public function setContent(Content $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
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

    /**
     * @return string
     */
    public function getTicket(): string
    {
        return $this->ticket;
    }

    /**
     * @param string $ticket
     */
    public function setTicket(string $ticket): void
    {
        $this->ticket = $ticket;
    }

}