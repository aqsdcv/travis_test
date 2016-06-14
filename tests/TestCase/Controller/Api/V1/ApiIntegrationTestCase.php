<?php

namespace App\Test\TestCase\Controller\Api\V1;

use Cake\TestSuite\IntegrationTestCase;

abstract class ApiIntegrationTestCase extends IntegrationTestCase {

    private $api_path = "/api/v1";
    private $request_data;

    public function helperExtensionTestCase($extension) {
        $this->configRequest([
          'headers' => ['Accept' => $extension]
        ]);
    }

    public function givenIHaveSomeDataToSend($data) {
        $this->request_data = $data;
    }

    public function iSendAGetRequestTo($uri) {
        $this->get($this->api_path . $uri);
    }

    public function iSendAPostRequestTo($uri) {
        $this->post($this->api_path . $uri, $this->request_data);
    }

    public function iSendAPutRequestTo($uri) {
        $this->put($this->api_path . $uri, $this->request_data);
    }

    public function iSendADeleteRequestTo($uri) {
        $this->delete($this->api_path . $uri, $this->request_data);
    }

    public function theResponseCodeShouldBe($code) {
        $this->assertResponseCode($code);
    }

    public function theResponseBodyShouldBe($body) {
        $this->assertEquals($body, $this->_response->body());
    }

    public function getExpectedValue($data) {
        if (\Cake\Core\Configure::read('debug')) {
            return json_encode($data, JSON_PRETTY_PRINT);
        }
        return json_encode($data);
    }
    
    public function getApiPath() {
        return $this->api_path;
    }

}
