<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Store;
use Doctrine\DBAL\Connection;

class CalculateCommand extends ContainerAwareCommand
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'calculate:avg-employees';

    protected function configure()
    {
        $this
            ->setDescription('Calculate.')
            ->setHelp('This command allows you to calculate...')
            ->addArgument('latitude', InputArgument::OPTIONAL, 'Latitude.')
            ->addArgument('longitude', InputArgument::OPTIONAL, 'Longitude.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stores = $this->getContainer()->get('doctrine')
            ->getManager()
            ->getRepository(Store::class)
            ->findAll();
        $sum = 0;
        foreach ($stores as $store) {
            // Check if command has latitude and longitude arguments
            if ($input->getArgument('latitude') && $input->getArgument('longitude')) {
                $distance = $this->haversineGreatCircleDistance(
                    $store->getLatitude(),
                    $store->getLongitude(),
                    $input->getArgument('latitude'),
                    $input->getArgument('latitude')
                );

                // Check if store is 10km away from inputed location
                if ($distance < 10000) {
                    $sum = $sum + $store->getEmployee();    
                }
            } else
                $sum = $sum + $store->getEmployee();
        }
        $output->writeln($sum);
    }
    function haversineGreatCircleDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
        
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}