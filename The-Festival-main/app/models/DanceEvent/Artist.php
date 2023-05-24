<?php

class Artist implements JsonSerializable
{
    private int  $artistId;
    private string $artistName;
    private ?array $artistImages;
    private string $artistLogo;
    private  string $artistDescription;
    private array $artistStyles;

    public function __construct()
    {
        $this->artistImages= array(); // setting up new array whenever the artist object is created
        $this->artistStyles= array();
    }

    /**
     * @return array
     */
    public function getArtistStyles(): array
    {
        return $this->artistStyles;
    }

    /**
     * @param array $artistStyles
     */
    public function setArtistStyles(array $artistStyles): void
    {
        $this->artistStyles = $artistStyles;
    }

    /**
     * @return mixed
     */
    public function getArtistDescription()
    {
        return $this->artistDescription;
    }

    /**
     * @param mixed $artistDescription
     */
    public function setArtistDescription($artistDescription): void
    {
        $this->artistDescription = $artistDescription;
    }


    /**
     * @return mixed
     */
    public function getArtistId()
    {
        return $this->artistId;
    }

    /**
     * @param mixed $artistId
     */
    public function setArtistId( $artistId): void
    {
        $this->artistId = $artistId;
    }

    /**
     * @return mixed
     */
    public function getArtistName(): mixed
    {
        return $this->artistName;
    }

    /**
     * @param mixed $artistName
     */
    public function setArtistName( $artistName): void
    {
        $this->artistName = $artistName;
    }

    /**
     * @return mixed
     */
    public function getArtistImages(): mixed
    {
        return $this->artistImages;
    }

    /**
     * @param mixed $artistImages
     */
    public function setArtistImages( $artistImages): void
    {
        $this->artistImages = $artistImages;
    }

    /**
     * @return mixed
     */
    public function getArtistLogo()
    {
        return $this->artistLogo;
    }

    /**
     * @param mixed $artistLogo
     */
    public function setArtistLogo( $artistLogo): void
    {
        $this->artistLogo = $artistLogo;
    }


    public function jsonSerialize() :mixed
    {
        return [
            'artistName' => $this->artistName,
            'artistStyles' => $this->artistStyles
        ];
    }
}