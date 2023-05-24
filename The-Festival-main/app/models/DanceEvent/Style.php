<?php

class Style implements JsonSerializable
{
    private int $styleId;
    private string $styleName;

    /**
     * @param int $styleId
     * @param string $styleName
     */
    public function __construct(int $styleId, string $styleName)
    {
        $this->styleId = $styleId;
        $this->styleName = $styleName;
    }

    /**
     * @return int
     */
    public function getStyleId(): int
    {
        return $this->styleId;
    }

    /**
     * @param int $styleId
     */
    public function setStyleId(int $styleId): void
    {
        $this->styleId = $styleId;
    }

    /**
     * @return string
     */
    public function getStyleName(): string
    {
        return $this->styleName;
    }

    /**
     * @param string $styleName
     */
    public function setStyleName(string $styleName): void
    {
        $this->styleName = $styleName;
    }

    public function jsonSerialize() :mixed
    {
       return get_object_vars($this);
    }
}