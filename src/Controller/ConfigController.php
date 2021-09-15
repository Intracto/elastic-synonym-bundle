<?php

namespace Intracto\ElasticSynonymBundle\Controller;

use Intracto\ElasticSynonym\Service\ConfigService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class ConfigController
{
    private ConfigService $configService;
    private Environment $twig;
    private Session $session;
    private RouterInterface $router;
    private TranslatorInterface $translator;

    public function __construct(ConfigService $configService, Environment $twig, Session $session, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->configService = $configService;
        $this->twig = $twig;
        $this->session = $session;
        $this->router = $router;
        $this->translator = $translator;
    }

    public function index(): Response
    {
        return new Response($this->twig->render('@IntractoElasticSynonym/config/index.html.twig', [
            'configs' => $this->configService->getConfigs(),
        ]));
    }

    public function refresh(string $id): Response
    {
        if (null === $config = $this->configService->getConfig($id)) {
            throw new NotFoundHttpException('Config not found');
        }

        $this->configService->refresh($config);
        $this->session->getFlashBag()->add('success', $this->translator->trans('config.refresh.flash.success', [], 'IntractoElasticSynonym'));

        return new RedirectResponse($this->router->generate('intracto_elastic_synonym.config.index'));
    }
}