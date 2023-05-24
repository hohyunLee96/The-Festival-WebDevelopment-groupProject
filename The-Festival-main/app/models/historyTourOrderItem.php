<?php
require_once __DIR__ . '/OrderItem.php';


class historyTourOrderItem extends OrderItem
{
    private string $ticketType;
    private string $language;
    /**
     * @return string
     */
    public function getTicketType(): string
    {
        return $this->ticketType;
    }

    /**
     * @param string $ticketType
     */
    public function setTicketType(string $ticketType): void
    {
        $this->ticketType = $ticketType;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

}