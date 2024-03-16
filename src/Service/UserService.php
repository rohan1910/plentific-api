<?php

namespace App\Service;

use App\Model\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UserService implements UserServiceInterface
{
    private Client $client;

    /**
     * UserService for API calls
     * 
     * UserService constructor.
     *
     * @param Client $client The HTTP client to use for requests
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://reqres.in/api',
        ]);
    }

    /**
     * @param string $name
     * @param string $job
     * @return int|null
     */
    public function createUser(string $name, string $job, string $email): ?int
    {
        try {
            $createUser = $this->client->post('/users', [
                'json' => [
                    'name' => $name,
                    'job' => $job,
                    'email' => $email
                ]
            ]);

            $createUserData = json_decode($createUser->getBody()->getContents(), true);

            return $createUserData['id'] ?? null;
        } catch (GuzzleException $exception) {
            return 'Error. Unable to create user. Reason: ' . $exception->getMessage() . ' Status Code: ' . $createUser->getStatusCode();
        }
    }

    /**
     * @param int $userId
     * @return User|null
     * @throws RequestException
     */
    public function getUser(int $userId): ?User
    {
        try {
            $getUser = $this->client->get('/users/' . $userId);

            $getUserData = json_decode($getUser->getBody()->getContents(), true);
            if ($getUserData) {

                return new User(
                    id: $getUserData['id'],
                    firstName: $getUserData['first_name'],
                    lastName: $getUserData['last_name'],
                    email: $getUserData['email'],
                    job: $getUserData['job']
                );
            } else {
                return [
                    'msg' => 'Unable to retrieve user with ID ' . $userId,
                    'statusCode' => $getUser->getStatusCode()
                ];
            }
        } catch (GuzzleException $exception) {
            return 'Error. Unable to retrieve user with ID ' . $userId . ' Reason: ' . $exception->getMessage();
        }
    }

    public function listUsers(int $page, int $per_page): array
    {
        $listUsers = $this->client->get('/users', [
            'query' => [
                'page' => $page,
                'per_page' => $per_page
            ]
        ]);
        $listUsersData = json_decode($listUsers->getBody()->getContents(), true);

        $userDataArray = [
            'page' => $page,
            'per_page' => $per_page
        ];

        if (!$listUsersData) {
            return [];
        }

        foreach ($listUsersData['data'] as $user) {
            $userDataArray['data'] = new User(
                id: $user['id'],
                firstName: $user['first_name'],
                lastName: $user['last_name'],
                email: $user['email'],
                job: $user['job']
            );
        }

        return $userDataArray;
    }
}
