<?php

namespace App\Controller;

use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CarController extends AbstractController
{
    #[Route('/car/show/{carId}', name: 'car_show', options: ['expose' => true])]
    public function show(Car $car): Response
    {
        return $this->render('car/index.html.twig', [
            'car' => $car,
        ]);
    }
}
