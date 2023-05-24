<?php

class language implements JsonSerializable
{
    public string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function jsonSerialize() :mixed
    {
        return [
            'name' => $this->name
        ];
    }
}