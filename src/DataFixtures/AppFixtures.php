<?php

namespace App\DataFixtures;

use App\Entity\Device;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;

class AppFixtures extends Fixture
{
    /**
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $types = ['phone', 'pc', 'tablet', 'smartwatch'];
        $damages = [true, false];
        for ($i = 0; $i < 20; $i++) {
            $device = new Device();
            $device
                ->setDeviceId(random_int(0, 9999))
                ->setDeviceType($types[random_int(0, 2)])
                ->setDamagePossible($damages[random_int(0,1)]);
            $manager->persist($device);
        }

        $manager->flush();
    }
}
