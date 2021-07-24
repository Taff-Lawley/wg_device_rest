<?php

namespace App\Tests;

use App\Controller\DevicesController;
use PHPUnit\Framework\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DeviceTest extends WebTestCase
{
    private static $createdId = null;

    public function testMethodsExist(): void
    {
        $this->assertTrue(method_exists(DevicesController::class, 'index'));
        $this->assertTrue(method_exists(DevicesController::class, 'device'));
        $this->assertTrue(method_exists(DevicesController::class, 'create'));
        $this->assertTrue(method_exists(DevicesController::class, 'edit'));
        $this->assertTrue(method_exists(DevicesController::class, 'delete'));
    }

    public function testCanReadDevice(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/devices');
        $foundDevices = json_decode($client->getResponse()->getContent());
        $this->assertIsArray($foundDevices[0]);
        $this->assertResponseIsSuccessful();

        $firstDevice = $foundDevices[0][0];
        $client->xmlHttpRequest('GET', '/device/' . $firstDevice->id);
        $foundDevice = json_decode($client->getResponse()->getContent());
        $this->assertEquals($firstDevice->device_id, $foundDevice->device_id);
        $this->assertResponseIsSuccessful();
    }

    public function testCanCreateDevice(): void
    {
        $client = static::createClient();

        $client->request(
            'POST', '/device/create', array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            '{"device_id": 1337, "device_type": "Harley Davidson", "damage_possible": false}'
        );
        $this->static = static::$createdId = json_decode($client->getResponse()->getContent())->id;
        $this->assertResponseIsSuccessful();
    }

    public function testCanEditDevice(): void
    {
        $newDeviceType = 'Not a motorcycle';
        $client = static::createClient();
        $client->xmlHttpRequest('GET', '/devices');
        $foundElements = json_decode($client->getResponse()->getContent());
        $lastElement = array_slice($foundElements[0], -1)[0];
        $this->assertEquals($lastElement->id, static::$createdId);
        $client->request(
            'POST', '/device/edit/' . $lastElement->id, array(), array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Requested-With' => 'XMLHttpRequest'
            ),
            '{"device_id": 1337, "device_type": "' . $newDeviceType . '", "damage_possible": false}'
        );

        $client->xmlHttpRequest('GET', '/device/' . $lastElement->id);
        $editedElements = json_decode($client->getResponse()->getContent());
        $this->assertEquals($lastElement->device_id, $editedElements->device_id);
        $this->assertEquals($editedElements->device_type, $newDeviceType);
        $this->assertResponseIsSuccessful();
    }

    public function testCanDeleteDevice(): void
    {
        $client = static::createClient();
        $client->xmlHttpRequest('DELETE', '/device/delete/' . static::$createdId);
        $this->assertEquals(static::$createdId, json_decode($client->getResponse()->getContent())->id);
    }
}
