<?php

namespace App\Test\TestCase\Controller\Api\V1;

use App\Test\TestCase\Controller\Api\V1\ApiIntegrationTestCase;

/**
 * App\Controller\Api\V1\UsersController Test Case
 */
class UsersControllerTest extends ApiIntegrationTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
      'app.users',
//    'app.users_memberships'
    ];
    public $autoFixtures = false;

    public function testWhenGettingAllUsers() {
        $this->loadFixtures('Users');

        $this->iSendAGetRequestTo('/users.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'users' => [
            [
              "id" => 1, "name" => "mreyrolle", "mail" => "maxime.reyrolle@example.org", "firstname" => "Maxime", "lastname" => "Reyrolle"
            ],
            [
              "id" => 2, "name" => "aauzolat", "mail" => "arnaud.auzolat@example.org", "firstname" => "Arnaud", "lastname" => "Auzolat"
            ]
          ],
          'pagination' => [
            'page_count' => 1,
            'current_page' => 1,
            'has_next_page' => FALSE,
            'has_prev_page' => FALSE,
            'count' => 2,
            'limit' => null
          ]
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingNoUser() {

        $this->iSendAGetRequestTo('/users.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'users' => [],
          'pagination' => [
            'page_count' => 0,
            'current_page' => 1,
            'has_next_page' => FALSE,
            'has_prev_page' => FALSE,
            'count' => 0,
            'limit' => null
          ]
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingANewUser() {
        $this->loadFixtures('Users');

        $data = [
          'name' => 'username',
          'password' => 'password',
          'mail' => 'mail@example.org',
          'firstname' => 'The firstname of the user',
          'lastname' => 'The lastname of the user'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPostRequestTo('/users.json');
        $this->theResponseCodeShouldBe(201);

        $expected = [
          'name' => 'username',
          'mail' => 'mail@example.org',
          'firstname' => 'The firstname of the user',
          'lastname' => 'The lastname of the user',
          'id' => 3
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingAnExistingUser() {
        $this->loadFixtures('Users');

        $data = [
          'name' => 'mreyrolle',
          'password' => 'password',
          'mail' => 'mail@example.org',
          'firstname' => 'John',
          'lastname' => 'Doe'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPostRequestTo('/users.json');
        $this->theResponseCodeShouldBe(400);

        $expected = [
          'code' => 400,
          'url' => '/api/v1/users.json',
          'message' => 'A validation error occurred',
          'errorCount' => 1,
          'errors' => [
            'name' => [
              'unique' => 'The provided user already exists'
            ]
          ]
        ];

        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenFindingAnExistingUser() {
        $this->loadFixtures('Users');


        $this->iSendAGetRequestTo('/users/2.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'id' => 2,
          'name' => 'aauzolat',
          'mail' => 'arnaud.auzolat@example.org',
          'firstname' => 'Arnaud',
          'lastname' => 'Auzolat'
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenFindingAnUnknownUser() {
        $this->loadFixtures('Users');

        $this->iSendAGetRequestTo('/users/4.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The user with the id 4 does not exist',
          'url' => '/api/v1/users/4.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenDeletingANotExistingUser() {

        $this->iSendADeleteRequestTo('/users/4.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The user with the id 4 does not exist',
          'url' => '/api/v1/users/4.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenDeletingAnExistingUser() {
        $this->loadFixtures('Users');

        $this->iSendADeleteRequestTo('/users/2.json');
        $this->theResponseCodeShouldBe(204);
    }

    public function testWhenUpdatingAnExistingUser() {
        $this->loadFixtures('Users');

        $data = [
          'name' => 'test',
          'password' => 'password',
          'mail' => 'new_mail@example.org',
          'firstname' => 'new firstname',
          'lastname' => 'new lastname'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPutRequestTo('/users/2.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'id' => 2,
          'name' => 'aauzolat',
          'mail' => 'new_mail@example.org',
          'firstname' => 'new firstname',
          'lastname' => 'new lastname'
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenUpdatingANotExistingUser() {
        $this->loadFixtures('Users');

        $data = [
          'password' => 'password',
          'mail' => 'mail@example.org',
          'firstname' => 'new firstname',
          'lastname' => 'new lastname'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPutRequestTo('/users/4.json');
        $this->theResponseCodeShouldBe(404);


        $expected = [
          'message' => 'The user with the id 4 does not exist',
          'url' => '/api/v1/users/4.json',
          'code' => 404
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenUpdatingAUserWithBadData() {
        $this->loadFixtures('Users');
        $data = [
          'mail' => 'itisnotamail',
          'firstname' => '',
          'password' => '',
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPutRequestTo('/users/1.json');
        $this->theResponseCodeShouldBe(400);

        $expected = [
          'code' => 400,
          'url' => '/api/v1/users/1.json',
          'message' => '3 validation errors occurred',
          'errorCount' => 3,
          'errors' => [
            'password' => [
              '_empty' => 'The password cannot be empty'
            ],
            'mail' => [
              'validFormat' => 'E-mail must be valid'
            ],
            'firstname' => [
              '_empty' => 'This field cannot be left empty'
            ]
          ]
        ];

        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

}
