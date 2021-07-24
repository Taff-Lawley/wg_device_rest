<?php

namespace App\Command;

use App\Entity\Device;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

#[AsCommand(
name: 'app:import-csv',
    description: 'Import one or more CSV Device files from the dir Resources/deviceCsvFiles',
)]

class ImportCsvCommand extends Command
{
    protected EntityManagerInterface $entityManager;

    private $csvParsingOptions = array(
        'finder_in' => 'src/Resources/deviceCsvFiles',
        'ignoreFirstLine' => true
    );

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];
        $finder = new Finder();

        $finder->files()
            ->in($this->csvParsingOptions['finder_in'])
            ->name('*.csv');

        foreach ($finder as $file) {
            $csv = $file;

            $output->writeln('reading file ' . $csv->getRealPath());

            if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
                $i = 0;
                while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                    $i++;
                    if ($ignoreFirstLine && $i === 1) {
                        continue;
                    }

                    $device = new Device();
                    $device->setDeviceId($data[0])
                        ->setDeviceType($data[1])
                        ->setDamagePossible($data[2]);
                    $this->entityManager->persist($device);
                }
                $noOfDevices = $ignoreFirstLine ? $i-1 : $i;
                $this->entityManager->flush();
                $output->writeLn('Imported ' . $noOfDevices . ' devices from ' . $csv->getFilename());
                fclose($handle);
            }
        }

        return Command::SUCCESS;
    }
}
