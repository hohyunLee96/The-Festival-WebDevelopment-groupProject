<?php

class ShoppingBasket implements \JsonSerializable
{

    public int $shoppingBasketId;
    public int $userId;
    public int $billId;

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */

    public function getShoppingBasketId(): int
    {
        return $this->shoppingBasketId;
    }

    /**
     * @param int $shoppingBasketId
     */
    public function setShoppingBasketId(int $shoppingBasketId): void
    {
        $this->shoppingBasketId = $shoppingBasketId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getBillId(): int
    {
        return $this->billId;
    }

    /**
     * @param int $billId
     */
    public function setBillId(int $billId): void
    {
        $this->billId = $billId;
    }

}

?>