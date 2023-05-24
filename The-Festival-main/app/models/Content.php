<?php

require_once __DIR__ . '/BodyHead.php';
require_once __DIR__ . '/SectionText.php';

class Content
{
    private int $contentId;
    private BodyHead $bodyHead;
    private SectionText $sectionText;
    private string $h2;
    private string $h3;
    private string $p;

    public function __construct()
    {
        $this->sectionText = new SectionText();
        $this->bodyHead = new BodyHead();
    }


    /**
     * @return int
     */
    public function getContentId(): int
    {
        return $this->contentId;
    }

    /**
     * @param int $contentId
     */
    public function setContentId(int $contentId): void
    {
        $this->contentId = $contentId;
    }

    /**
     * @return BodyHead
     */
    public function getBodyHead(): BodyHead
    {
        return $this->bodyHead;
    }

    /**
     * @param BodyHead $bodyHead
     */
    public function setBodyHead(BodyHead $bodyHead): void
    {
        $this->bodyHead = $bodyHead;
    }

    /**
     * @return SectionText
     */
    public function getSectionText(): SectionText
    {
        return $this->sectionText;
    }

    /**
     * @param SectionText $sectionText
     */
    public function setSectionText(SectionText $sectionText): void
    {
        $this->sectionText = $sectionText;
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
    public function getH3(): string
    {
        return $this->h3;
    }

    /**
     * @param string $h3
     */
    public function setH3(string $h3): void
    {
        $this->h3 = $h3;
    }

    /**
     * @return string
     */
    public function getP(): string
    {
        return $this->p;
    }

    /**
     * @param string $p
     */
    public function setP(string $p): void
    {
        $this->p = $p;
    }

}