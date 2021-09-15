<?php

namespace Intracto\ElasticSynonymBundle\Controller;

use Intracto\ElasticSynonym\Service\ConfigService;
use Intracto\ElasticSynonym\Service\SynonymService;
use Intracto\ElasticSynonymBundle\Form\SynonymCollectionType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class SynonymController
{
    private ConfigService $configService;
    private SynonymService $synonymService;
    private Environment $twig;
    private FormFactoryInterface $formFactory;
    private Session $session;
    private RouterInterface $router;
    private Request $request;
    private TranslatorInterface $translator;

    public function __construct(ConfigService $configService, SynonymService $synonymService, Environment $twig, FormFactoryInterface $formFactory, Session $session, RouterInterface $router, RequestStack $requestStack, TranslatorInterface $translator)
    {
        $this->configService = $configService;
        $this->synonymService = $synonymService;
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->session = $session;
        $this->router = $router;
        $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
    }

    public function index(string $id): Response
    {
        if (null === $config = $this->configService->getConfig($id)) {
            throw new NotFoundHttpException('Config not found');
        }

        $form = $this->formFactory->create(SynonymCollectionType::class, [
            'synonyms' => $this->synonymService->getSynonyms($config),
        ]);

        $form->handleRequest( $this->request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $this->synonymService->setSynonyms($config, $form->getData());
                $this->configService->refresh($config);

                $this->session->getFlashBag()->add('success', $this->translator->trans('synonym.index.flash.success', [], 'IntractoElasticSynonym'));

                return new RedirectResponse($this->router->generate('intracto_elastic_synonym.synonym.index', ['id' => $config->getId()]));
            } else {
                throw new BadRequestHttpException('Validation not implemented yet');
            }
        }

        return new Response($this->twig->render('@IntractoElasticSynonym/synonym/index.html.twig', [
            'config' => $config,
            'form' => $form->createView(),
        ]));
    }
}