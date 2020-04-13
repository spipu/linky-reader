<?php
declare(strict_types = 1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @Route("/")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     * @param ContainerInterface $container
     * @return Response
     */
    public function home(ContainerInterface $container): Response
    {
        $logDir = $container->getParameter('kernel.logs_dir');

        $filename = $logDir .DIRECTORY_SEPARATOR . 'cron.log';
        $content = sprintf('Log File [%s] is missing', $filename);
        if (is_file($filename)) {
            $content = file_get_contents($filename);
        }

        return $this->render(
            'main/home.html.twig',
            [
                'content' => $content,
            ]
        );
    }
}
