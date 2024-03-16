<?php

namespace App\Model;

use JsonSerializable;

class User implements JsonSerializable
{
    /**
     * User class in DTO pattern.
     * used to transfer data between layers of an application.
     */

    private  int $id;
    private  string $firstName;
    private  string $lastName;
    private  string $email;
    private  string $job;

    /**
     * Initialize instance of User object
     * 
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $job
     *
     */
    private function __construct(int $id, string $firstName, string $lastName, string $email, string $job)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->job = $job;
    }

    // define getters for User properties

    public function getId(): int
    {
        return $this->id;
    }


    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    public function getJob(): string
    {
        return $this->job;
    }

    /**
     * serialize User object data
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'job' => $this->job,
        ];
    }
}
