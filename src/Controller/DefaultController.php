<?php

namespace Basilicom\ToolbarExtension\Controller;

use Basilicom\ToolbarExtension\Service\ConfigReaderService;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{
    /**
     * @Route("/admin/basilicom-toolbar-extension/js/pimcore/config")
     */
    public function jsConfig(ConfigReaderService $config): Response
    {
        return new Response(
            sprintf(
                'pimcore.basilicomToolbarExtensionConfig = %s;',
                $config->getConfigAsJson()
            ),
            Response::HTTP_OK,
            ['Content-Type' => 'application/javascript']
        );
    }

    /**
     * @Route("/admin/basilicom-toolbar-extension/demo")
     */
    public function demo(ConfigReaderService $config): Response
    {
        return new Response('It works!');
    }
}
