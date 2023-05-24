<?php
class Restaurant implements \JsonSerializable{
    private int $id;
    private string $name;
    private string $location;
    private string $descriptionHTML;
    private int $numberOfSeats;
    private int $score;
    private string $foodTypes;
    private string $cardImage;

    // add number of seats
    // types of foods
    // price adult, price under 12
    // accessability (wheelchare)
    // number of stars
    // contact information (phone, website, email)

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

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

    /**
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location): void
    {
        $this->location = $location;
    }
    /**
     * @return string
     */
    public function getDescriptionHTML(): string
    {
        return $this->descriptionHTML;
    }

    /**
     * @param string $descriptionHTML
     */
    public function setDescriptionHTML(string $descriptionHTML): void
    {
        $this->descriptionHTML = $descriptionHTML;
    }

    /**
     * @return int
     */
    public function getNumberOfSeats(): int
    {
        return $this->numberOfSeats;
    }

    /**
     * @param int $numberOfSeats
     */
    public function setNumberOfSeats(int $numberOfSeats): void
    {
        $this->numberOfSeats = $numberOfSeats;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     */
    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    /**
     * @return string
     */
    public function getFoodTypes(): string
    {
        return $this->foodTypes;
    }

    /**
     * @param string $foodTypes
     */
    public function setFoodTypes(string $foodTypes): void
    {
        $this->foodTypes = $foodTypes;
    }

    public function getRestaurantCardImageFullPath(): string 
    {
        try {
            $imagePath = '/image/Festival/Yummy/RestaurantCardImage-' . $this->getId() . ".jpg";
            return $imagePath;
            if (is_file($imagePath) && file_exists($imagePath)) {
                return $imagePath;
            } else {
                return "111";
            }
        } catch (Exception $e) {
            return "222";
        }
        return "";
    }

}