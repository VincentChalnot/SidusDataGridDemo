<?php

namespace AppBundle\Action;

use Sidus\DataGridBundle\Registry\DataGridRegistry;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Basic search action
 */
class SearchAction
{
    /** @var EngineInterface */
    protected $renderEngine;

    /** @var DataGridRegistry */
    protected $dataGridRegistry;

    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var RouterInterface */
    protected $router;

    /**
     * @param EngineInterface      $renderEngine
     * @param DataGridRegistry     $dataGridRegistry
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface      $router
     */
    public function __construct(
        EngineInterface $renderEngine,
        DataGridRegistry $dataGridRegistry,
        FormFactoryInterface $formFactory,
        RouterInterface $router
    ) {
        $this->renderEngine = $renderEngine;
        $this->dataGridRegistry = $dataGridRegistry;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function __invoke(Request $request)
    {
        // Create form with filters
        $builder = $this->formFactory->createBuilder(
            FormType::class,
            null,
            [
                'method' => 'get',
                'csrf_protection' => false,
                'action' => $this->router->generate(self::class),
                'validation_groups' => ['filters'],
            ]
        );

        $datagrid = $this->dataGridRegistry->getDataGrid('news');
        $form = $datagrid->buildForm($builder);
        $datagrid->handleRequest($request);

        return $this->renderEngine->renderResponse(
            'Search/action.html.twig',
            [
                'form' => $form->createView(),
                'datagrid' => $datagrid,
            ]
        );
    }
}
