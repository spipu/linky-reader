<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\EnergyDataRepository;
use App\Ui\EnergyDataGrid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Spipu\UiBundle\Service\Ui\GridFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/energy-data')]
#[IsGranted('ROLE_ADMIN')]
class EnergyDataController extends AbstractController
{
    #[Route(path: '/', name: 'energy_data_list', methods: 'GET')]
    public function list(GridFactory $gridFactory, EnergyDataGrid $energyDataGrid): Response
    {
        $manager = $gridFactory->create($energyDataGrid);
        $manager->setRoute('energy_data_list');
        if ($manager->validate()) {
            return $this->redirectToRoute('energy_data_list');
        }

        return $this->render('energy_data/list.html.twig', ['manager' => $manager]);
    }

    #[Route(path: '/show/{id}', name: 'energy_data_show', methods: 'GET')]
    public function show(
        EnergyDataRepository $energyDataRepository,
        int $id
    ): Response {
        $resource = $energyDataRepository->findOneBy(['id' => $id]);
        if (!$resource) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'energy_data/show.html.twig',
            [
                'resource' => $resource,
            ]
        );
    }
}
