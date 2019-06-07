<?php

namespace AppBundle\Entity;

use DateTimeImmutable;

/**
 * Class Order
 *
 * @package AppBundle\Entity
 */
class Order
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTimeImmutable
     */
    private $orderDate;

    /**
     * @var DateTimeImmutable
     */
    private $shippedDate;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $comments;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     */
    private $updatedAt;

    /**
     * @var Customer
     */
    private $customer;

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable
     */
    public function orderDate(): DateTimeImmutable
    {
        return $this->orderDate;
    }

    /**
     * @param DateTimeImmutable $orderDate
     */
    public function changeOrderDate(DateTimeImmutable $orderDate): void
    {
        $this->orderDate = $orderDate;
    }

    /**
     * @return DateTimeImmutable
     */
    public function shippedDate(): DateTimeImmutable
    {
        return $this->shippedDate;
    }

    /**
     * @param DateTimeImmutable $shippedDate
     */
    public function changeShippedDate(DateTimeImmutable $shippedDate): void
    {
        $this->shippedDate = $shippedDate;
    }

    /**
     * @return string
     */
    public function status(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function changeStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function comments(): string
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function changeComments(string $comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return DateTimeImmutable
     */
    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function updatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return Customer
     */
    public function customer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function changeCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }
}

