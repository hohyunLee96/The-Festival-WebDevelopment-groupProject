<?php

class SectionText
{
    private array $paragraphs;

    /**
     * @return array
     */
    public function getParagraphs(): array
    {
        return $this->paragraphs;
    }

    /**
     * @param array $paragraphs
     */
    public function setParagraphs(array $paragraphs): void
    {
        $this->paragraphs = $paragraphs;
    }
    public function addParagraph(Paragraph $paragraph): void
    {
        $this->paragraphs[] = $paragraph;
    }



}