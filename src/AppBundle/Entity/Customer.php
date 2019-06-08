<?php

namespace AppBundle\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use JsonSerializable;

/**
 * Class Customer
 *
 * @package AppBundle\Entity
 */
class Customer implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $addressLine1;

    /**
     * @var string
     */
    private $addressLine2;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $postalCode;

    /**
     * @var string
     */
    private $country;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    /**
     * @var DateTimeImmutable
     */
    private $updatedAt;

    /**
     * @var Collection
     */
    private $orders;

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function changeFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function changeLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function changeEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function phone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function changePhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function addressLine1(): string
    {
        return $this->addressLine1;
    }

    /**
     * @param string $addressLine1
     */
    public function changeAddressLine1(string $addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * @return string
     */
    public function addressLine2(): string
    {
        return $this->addressLine2;
    }

    /**
     * @param string $addressLine2
     */
    public function changeAddressLine2(string $addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * @return string
     */
    public function city(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function changeCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function state(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function changeState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function postalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function changePostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function country(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function changeCountry(string $country): void
    {
        $this->country = $country;
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
     * @return Collection
     */
    public function orders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Collection $orders
     */
    public function changeOrders(Collection $orders): void
    {
        $this->orders = $orders;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id'           => $this->id,
            'firstName'    => $this->firstName,
            'lastName'     => $this->lastName,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'addressLine1' => $this->addressLine1,
            'addressLine2' => $this->addressLine2,
            'city'         => $this->city,
            'state'        => $this->state,
            'postalCode'   => $this->postalCode,
            'country'      => $this->country,
            'orders'       => $this->orders,
            'created_at'   => $this->createdAt,
            'updated_at'   => $this->updatedAt,
        ];
    }
}

