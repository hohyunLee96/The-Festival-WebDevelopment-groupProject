

<?php

require_once __DIR__ . "./Paragraph.php";

class FooterItem
{
    private int $footerItemId;
    private string $pageUrl;
    private string $name;
    private Paragraph $paragraph;
    private string $icon;

    /**
     * @return int
     */
    public function getFooterItemId(): int
    {
        return $this->footerItemId;
    }

    /**
     * @param int $footerItemId
     * @return self
     */
    public function setFooterItemId(int $footerItemId): self
    {
        $this->footerItemId = $footerItemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFooterItemrUrl(): string {
        return $this->pageUrl;
    }

    /**
     * @param string $pageUrl
     * @return self
     */
    public function setFooterItemUrl(string $pageUrl): self {
        $this->pageUrl = $pageUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getFooterItemName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setFooterItemName(string $name): self {
        $this->name = $name;
        return $this;
    }



    /**
     * @return Paragraph
     */
    public function getParagraph(): Paragraph {
        return $this->paragraph;
    }

    /**
     * @param Paragraph $paragraph
     * @return self
     */
    public function setParagraphItem(Paragraph $paragraph): self {
        $this->paragraph = $paragraph;
        return $this;
    }

     /**
     * @return string
     */
    public function getIcon(): string {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return self
     */
    public function setIcon(string $icon): self {
        $this->icon = $icon;
        return $this;
    }
}?>