<?php

declare(strict_types=1);

namespace App\Controller;

use App\Ui\AdminDashboard;
use Spipu\DashboardBundle\Entity\DashboardAcl;
use Spipu\DashboardBundle\Service\DashboardControllerService;
use Spipu\UserBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route(path: '/dashboard/{action}/{id?}', name: 'app_home')]
    public function main(
        DashboardControllerService $dashboardControllerService,
        AdminDashboard $dashboard,
        UserRepository $userRepository,
        string $action = '',
        ?int $id = null
    ): Response {
        $dashboardAcl = new DashboardAcl();
        $dashboardAcl
            ->configure(
                $this->isGranted('ROLE_ADMIN'),
                $this->isGranted('ROLE_ADMIN'),
                $this->isGranted('ROLE_ADMIN'),
                $this->isGranted('ROLE_ADMIN')
            )
            ->setDefaultUser($userRepository->find(1))
        ;

        return $dashboardControllerService->dispatch($dashboard, 'app_home', $action, $id, $dashboardAcl);
    }
}
