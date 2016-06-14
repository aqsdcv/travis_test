<?php

namespace App\Test\TestCase\Controller\Api\V1;

/**
 * App\Controller\Api\V1\ServicesController Test Case
 */
class ServicesControllerTest extends ApiIntegrationTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
      'app.connectors',
      'app.services',
    ];
    public $autoFixtures = false;

    public function testWhenGettingAllTheServicesOfAnExistingConnector() {
        $this->loadFixtures('Connectors', 'Services');
        $this->iSendAGetRequestTo('/connectors/1/services.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'services' => [
            [
              'id' => 3, 'name' => 'mailsec', 'connector_id' => 1
            ],
            [
              'id' => 2, 'name' => 'helios', 'connector_id' => 1
            ],
            [
              'id' => 1, 'name' => 'actes', 'connector_id' => 1
            ]
          ],
          'pagination' => [
            'page_count' => 1,
            'current_page' => 1,
            'has_next_page' => FALSE,
            'has_prev_page' => FALSE,
            'count' => 3,
            'limit' => null
          ]
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingAllTheServicesOfAConnectorThatDoesNotExist() {
        $this->loadFixtures('Services');
        $this->iSendAGetRequestTo('/connectors/1/services.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The connector with the id 1 does not exist',
          'url' => '/api/v1/connectors/1/services.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingNoServicesFromAConnectorThatExists() {
        $this->loadFixtures('Connectors');

        $this->iSendAGetRequestTo('/connectors/1/services.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'services' => [],
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

    public function testWhenGettingAServiceThatExistsFromItsConnectorParent() {
        $this->loadFixtures('Connectors', 'Services');
        $this->iSendAGetRequestTo('/connectors/1/services/1.json');
        $this->theResponseCodeShouldBe(200);

        $expected = [
          'id' => 1,
          'name' => 'actes',
          'connector_id' => 1
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingAServiceThatExistsFromAConnectorThatDoesNotExist() {
        $this->loadFixtures('Connectors', 'Services');
        $this->iSendAGetRequestTo('/connectors/100/services/1.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The connector with the id 100 does not exist',
          'url' => '/api/v1/connectors/100/services/1.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingAServiceThatExistsFromAConnectorThatIsNotItsParent() {
        $this->loadFixtures('Connectors', 'Services');
        $this->iSendAGetRequestTo('/connectors/2/services/1.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The service with the id 1 does not exist',
          'url' => '/api/v1/connectors/2/services/1.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingAServiceThatDoesNotExistFromAConnectorThatExists() {
        $this->loadFixtures('Connectors');
        $this->iSendAGetRequestTo('/connectors/2/services/1.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The service with the id 1 does not exist',
          'url' => '/api/v1/connectors/2/services/1.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenGettingAServiceThatDoesNotExistFromAConnectorThatDoesNotExist() {
        $this->iSendAGetRequestTo('/connectors/1/services/1.json');
        $this->theResponseCodeShouldBe(404);

        $expected = [
          'message' => 'The connector with the id 1 does not exist',
          'url' => '/api/v1/connectors/1/services/1.json',
          'code' => 404
        ];
        $expectedBody = $this->getExpectedValue($expected);

        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingANewServiceToAConnectorThatExists() {
        $this->loadFixtures('Connectors');

        $data = [
          'name' => 'Test service'
        ];

        $this->givenIHaveSomeDataToSend($data);

        $this->iSendAPostRequestTo('/connectors/1/services.json');
        $this->theResponseCodeShouldBe(201);
        $expected = [
          'name' => 'Test service',
          'connector_id' => 1,
          'id' => 1
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingANewServiceToAConnectorThatDoesNotExist() {
        $data = [
          'name' => 'Test service'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPostRequestTo('/connectors/1/services.json');
        $this->theResponseCodeShouldBe(404);
        $expected = [
          'message' => 'The connector with the id 1 does not exist',
          'url' => '/api/v1/connectors/1/services.json',
          'code' => 404
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingAServiceThatExistsToAnotherConnector() {
        $this->loadFixtures('Connectors', 'Services');

        $data = [
          'name' => 'actes'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPostRequestTo('/connectors/2/services.json');
        $this->theResponseCodeShouldBe(400);
        $expected = [
          'code' => 400,
          'url' => '/api/v1/connectors/2/services.json',
          'message' => 'A validation error occurred',
          'errorCount' => 1,
          'errors' => [
            'name' => [
              'unique' => 'The provided value is invalid'
            ]
          ]
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    public function testWhenAddingAServiceThatExistsToTheSameConnector() {
        $this->loadFixtures('Connectors', 'Services');

        $data = [
          'name' => 'actes'
        ];

        $this->givenIHaveSomeDataToSend($data);
        $this->iSendAPostRequestTo('/connectors/1/services.json');
        $this->theResponseCodeShouldBe(400);
        $expected = [
          'code' => 400,
          'url' => '/api/v1/connectors/1/services.json',
          'message' => 'A validation error occurred',
          'errorCount' => 1,
          'errors' => [
            'name' => [
              'unique' => 'The provided value is invalid'
            ]
          ]
        ];

        $expectedBody = $this->getExpectedValue($expected);
        $this->theResponseBodyShouldBe($expectedBody);
    }

    //TODO: We need other related tables to manage deletion
//    public function testWhenDeletingAServiceThatExistsFromItsConnector() {
//        $this->loadFixtures('Connectors', 'Services');
//        $this->iSendADeleteRequestTo('/connectors/1/services/2.json');
//        $this->theResponseCodeShouldBe(204);
//    }

}
