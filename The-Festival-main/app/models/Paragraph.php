<?php

class Paragraph
{
    private int $paragraphId;
    private ?string $title;
    private string $text;

    /**
     * @param int $paragraphId
     * @param string|null $title
     * @param string $text
     */

    public function __construct(int $paragraphId, ?string $title, string $text)
    {
        $this->paragraphId = $paragraphId;
        $this->title = $title;
        $this->text = $text;
    }


    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getParagraphId(): int
    {
        return $this->paragraphId;
    }

    /**
     * @param int $paragraphId
     */
    public function setParagraphId(int $paragraphId): void
    {
        $this->paragraphId = $paragraphId;
    }

}