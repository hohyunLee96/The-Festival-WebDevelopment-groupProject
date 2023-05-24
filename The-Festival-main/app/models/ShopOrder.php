<?php

class ShopOrder implements \JsonSerializable
{

    public int $orderId;
    public int $userId;
    public string $orderDate;
    public int $billId;

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }

    /**
     * @return string
     */

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    /**
     * @param int $newId
     */
    public function setOrderId(int $orderId): void
    {
        $this->orderId = $orderId;
    }

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
     * @return string
     */

    public function getOrderDate(): string
    {
        return $this->orderDate;
    }



    /**
     * @param string $orderDate
     */

    public function setOrderDate(string $orderDate): void
    {
        $this->orderDate = $orderDate;
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