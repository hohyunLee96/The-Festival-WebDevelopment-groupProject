<?php
require_once __DIR__ . '/NavBarItem.php';
class InfoPage
{
    private int $infoPageId;
    private NavBarItem $navBarItem;
    private string $content;

    /**
     * @param int $infoPageId
     * @param NavBarItem $navBarItem
     * @param string $content
     */
    public function __construct(int $infoPageId, NavBarItem $navBarItem, string $content)
    {
        $this->infoPageId = $infoPageId;
        $this->navBarItem = $navBarItem;
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getInfoPageId(): int
    {
        return $this->infoPageId;
    }

    /**
     * @param int $infoPageId
     */
    public function setInfoPageId(int $infoPageId): void
    {
        $this->infoPageId = $infoPageId;
    }

    /**
     * @return NavBarItem
     */
    public function getNavBarItem(): NavBarItem
    {
        return $this->navBarItem;
    }

    /**
     * @param NavBarItem $navBarItem
     */
    public function setNavBarItem(NavBarItem $navBarItem): void
    {
        $this->navBarItem = $navBarItem;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }


}