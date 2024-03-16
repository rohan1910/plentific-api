<?php

namespace App\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use App\Service\UserService;
use App\Model\User;

class UserServiceTest extends TestCase
{

    private UserService $userService;

    /**
     * Mock Guzzle HTTP client. Can be used to simul;ate API calls.
     * Can be used if API is offline.
     * Mocks the UserService
     * 
     * @param string $method
     * @param string $uri
     * @param int $statusCode
     * @param string $body
     * @return UserService
     */
    private function createMockUserService(string $method, string $uri, int $statusCode, string $body): UserService
    {
        $mock = $this->createMock(Client::class);
        $mock->expects($this->once())
             ->method('request')
             ->with($this->equalTo($method), $this->equalTo($uri))
             ->willReturn(new Response($statusCode, [], $body));

        return new UserService($mock);
    }

    public function testCreateUser()
    {
        $userService = $this->createMockUserService(
            'POST',
            'users',
            201,
            json_encode([
                "name" => "Rohan",
                "job" => "Developer",
                "email" => "rpBZ9@example.com",
                "id" => 1
            ])
        );

        $createdUserId = $userService->createUser('Rohan', 'Developer', "rpBZ9@example.com");
        $this->assertEquals(3, $createdUserId);
    }

    public function testGetUserById()
    {
        $userService = $this->createMockUserService(
            'GET',
            'users/1',
            200,
            json_encode([
                "data" => [
                    "id" => 1,
                    "email" => "rpBZ9@example.com",
                    "first_name" => "Rohan",
                    "last_name" => "Patel",
                    "job" => "Developer"
                ]
            ])
        );

        $user = $userService->getUser(1);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->getId());
    }

    public function testGetUsers()
    {
        $userService = $this->createMockUserService(
            'GET',
            'users?page=1&per_page=1',
            200,
            json_encode([
                "page" => 1,
                "per_page" => 1,
                "data" => [[
                    "id" => 1,
                    "email" => "rpBZ9@example.com",
                    "first_name" => "Rohan",
                    "last_name" => "Patel",
                    "job" => "Developer"
                ]]
            ])
        );

        $users = $userService->listUsers(1, 1);
        $this->assertIsArray($users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertEquals(1, $users[0]->getId());
    }
}
