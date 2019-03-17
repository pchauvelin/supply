<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Sonata\AdminBundle\Route\RouteCollection;

final class ProductAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class);
        $formMapper->add('sku', TextType::class);
        $formMapper->add('initialStock', IntegerType::class);
        $formMapper->add('stock', IntegerType::class);
        $formMapper->add('supplier');
        $formMapper->add('inventoryTurnover', NumberType::class);
        $formMapper->add('isNearlyOutOfStock', CheckboxType::class);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
        $datagridMapper->add('sku');
        $datagridMapper->add('isNearlyOutOfStock');
        $datagridMapper->add('supplier');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $listMapper->addIdentifier('sku');
        $listMapper->addIdentifier('initialStock');
        $listMapper->addIdentifier('stock');
        $listMapper->addIdentifier('inventoryTurnover');
        $listMapper->addIdentifier('isNearlyOutOfStock');
        $listMapper->addIdentifier('supplier.name');
        $listMapper->add(
            '_action',
            null,
            [
                'actions' => [
                    'edit' => [],
                ]
            ]
        );
    }

    /**
     * Configure routes
     *
     * @param RouteCollection $collection
     *
     * @return void
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('refreshStock');
    }

    /**
     * @return array
     */
    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();

        $actions['refreshStock'] = [
            'label' => 'Refresh stock',
            'translation_domain' => 'SonataAdminBundle',
            'url' => $this->generateUrl('refreshStock'),
            'icon' => 'refresh',
        ];

        return $actions;
    }
}
