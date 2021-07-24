<?php

namespace App\Controller;

use App\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DevicesController
 * @package App\Controller
 */
class DevicesController extends AbstractController
{

    /**
     * @param Device $device
     * @return Response
     */
    #[Route('/device/{id}', name: 'device', methods: ['GET'])]
    public function device(Device $device): Response
    {
        return $this->json([
                'device_id' => $device->getDeviceId(),
                'device_type' => $device->getDeviceType(),
                'damage_possible' => $device->getDamagePossible(),
            ]
        );
    }

    /**
     * @return Response
     */
    #[Route('/devices', name: 'devices', methods: ['GET'])]
    public function index(): Response
    {
        $devices = $this->getDoctrine()->getManager()->getRepository(Device::class)->findAll();
        $response = [];

        foreach ($devices as $device) {
            $response[] = [
                'id' => $device->getId(),
                'device_id' => $device->getDeviceId(),
                'device_type' => $device->getDeviceType(),
                'damage_possible' => $device->getDamagePossible(),
            ];
        }

        return $this->json([$response]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/device/create', name: 'device_create', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        $deviceId = $data['device_id'] ?? 0;
        $deviceType = $data['device_type'] ?? '';
        $damagePossible = $data['damage_possible'] ?? false;

        $device = new Device();
        $device
            ->setDeviceId($deviceId)
            ->setDeviceType($deviceType)
            ->setDamagePossible($damagePossible);

        $entityManager->persist($device);
        $entityManager->flush();

        return $this->json([
            'message' => 'Entity created',
            'id' => $device->getId(),
        ]);
    }

    /**
     * @param Device $device
     * @param Request $request
     * @return Response
     */
    #[Route('/device/edit/{id}', name: 'device_edit', methods: ['POST'])]
    public function edit(Device $device, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        $deviceId = $data['device_id'] ?? '';
        $deviceType = $data['device_type'] ?? '';
        $damagePossible = $data['damage_possible'] ?? false;

        $device
            ->setDeviceId($deviceId)
            ->setDeviceType($deviceType)
            ->setDamagePossible($damagePossible);

        $entityManager->flush();

        return $this->json([
            'message' => 'Entity updated',
            'id' => $device->getId(),
        ]);
    }

    /**
     * @param Device $device
     * @return Response
     */
    #[Route('/device/delete/{id}', name: 'device_delete', methods: ['DELETE'])]
    public function delete(Device $device): Response
    {
        $deletedId = $device->getId();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($device);
        $entityManager->flush();

        return $this->json([
            'message' => 'Entity deleted',
            'id' => $deletedId,
        ]);
    }
}
