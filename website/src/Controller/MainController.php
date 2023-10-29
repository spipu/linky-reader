<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class MainController extends AbstractController
{
    private string $logsDir;

    public function __construct(string $logsDir)
    {
        $this->logsDir = $logsDir;
    }

    /**
     * @Route("/", name="app_home", methods="GET")
     * @return Response
     */
    public function home(): Response
    {
        $filename = $this->logsDir . DIRECTORY_SEPARATOR . 'cron.log';
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
