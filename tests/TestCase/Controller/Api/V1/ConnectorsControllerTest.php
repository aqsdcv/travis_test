<?php

namespace App\Test\TestCase\Controller\Api\V1;

use App\Test\TestCase\Controller\Api\V1\ApiIntegrationTestCase;

/**
 * App\Controller\ConnectorsController Test Case
 */
class ConnectorsControllerTest extends ApiIntegrationTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
      'app.connectors',
      'app.services',
//    'app.subscriptions',
//    'app.subscriptions_services',
//    'app.users_memberships',
//    'app.users_memberships_services'
    ];
    public $autoFixtures = false;

    public function testWhenGettingAllTheConnectors() {
        $this->loadFixtures('Connectors');
        $this->iSendAGetRequestTo('/connectors.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'connectors' => [
            [
              'id' => 1, 'name' => 'pastell', 'url' => 'https://pastell.example.org'
            ],
            [
              'id' => 2, 'name' => 'parapheur', 'url' => 'https://parapheur.example.org'
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

    public function testWhenGettingNoConnectors() {
        $this->iSendAGetRequestTo('/connectors.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'connectors' => [],
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

    public function testWhenGettingAConnector() {
        $this->loadFixtures('Connectors');
        $this->iSendAGetRequestTo('/connectors/1.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'id' => 1,
          'name' => 'pastell',
          'url' => 'https://pastell.example.org'
        ];
        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingAConnectorThatDoesNotExists() {
        $this->loadFixtures('Connectors');
        $this->iSendAGetRequestTo('/connectors/4.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The connector with the id 4 does not exist',
          'url' => '/api/v1/connectors/4.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingANewConnector() {

        $data = [
          'name' => 'idelibre',
          'url' => 'https://idelibre.example.org',
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPostRequestTo('/connectors.json');
        $this->theResponseCodeShouldBe(201);

        $expected = [
          'name' => 'idelibre',
          'url' => 'https://idelibre.example.org',
          'id' => 1
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingAnExistingConnector() {
        $this->loadFixtures('Connectors');
        $uri = '/connectors.json';

        $data = [
          'name' => 'pastell',
          'url' => 'https://random.example.org'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPostRequestTo($uri);
        $this->theResponseCodeShouldBe(400);

        $expected = [
          'code' => 400,
          'url' => $this->getApiPath() . $uri,
          'message' => 'A validation error occurred',
          'errorCount' => 1,
          'errors' => [
            'name' => [
              'unique' => 'The provided connector already exists'
            ]
          ]
        ];

        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenUpdatingAnExistingConnector() {
        $this->loadFixtures('Connectors');

        $data = [
          'url' => 'https://random.example.org'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPutRequestTo('/connectors/1.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'id' => 1,
          'name' => 'pastell',
          'url' => 'https://random.example.org'
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenUpdatingANotExistingUser() {
        $this->loadFixtures('Connectors');

        $data = [
          'url' => 'https://random.example.org'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPutRequestTo('/connectors/4.json');
        $this->theResponseCodeShouldBe(404);


        $expected = [
          'message' => 'The connector with the id 4 does not exist',
          'url' => '/api/v1/connectors/4.json',
          'code' => 404
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenUpdatingAConnectorWithInvalidUrl() {
        $this->loadFixtures('Connectors');
        $uri = '/connectors/2.json';
        $data = [
          'url' => 'pastell.example.org',
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPutRequestTo($uri);
        $this->theResponseCodeShouldBe(400);

        $expected = [
          'code' => 400,
          'url' => $this->getApiPath() . $uri,
          'message' => 'A validation error occurred',
          'errorCount' => 1,
          'errors' => [
            'url' => [
              'validFormat' => 'URL must be valid'
            ]
          ]
        ];

        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenDeletingAConnectorThatExists() {
        $this->loadFixtures('Connectors');

        $this->iSendADeleteRequestTo('/connectors/2.json');
        $this->theResponseCodeShouldBe(204);
    }

    public function testWhenDeletingAConnectorThatDoesNotExist() {
        $this->loadFixtures('Connectors');

        $this->iSendADeleteRequestTo('/connectors/4.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The connector with the id 4 does not exist',
          'url' => '/api/v1/connectors/4.json',
          'code' => 404
        ];

        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

}
