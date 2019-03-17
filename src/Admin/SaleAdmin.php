<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

final class SaleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('saleNumber', TextType::class);
        $formMapper->add('createdAt', DateType::class);
        $formMapper->add(
            'items',
            CollectionType::class,
            [
                'type_options' => [
                    'delete' => true,
                    'delete_options' => [
                        'type' => CheckboxType::class,
                        'type_options' => [
                            'mapped'   => false,
                            'required' => false,
                        ]
                    ]
                ]
            ],
            [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ]
        );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('saleNumber');
        $datagridMapper->add('createdAt');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('saleNumber');
        $listMapper->addIdentifier('createdAt');
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
     * Before persist
     *
     * @param object $object Object
     *
     * @return void
     */
    public function prePersist($object)
    {
        foreach ($object->getItems() as $item) {
            $item->setSale($object);
        }
    }

    /**
     * Before udpate
     *
     * @param object $object Object
     *
     * @return void
     */
    public function preUpdate($object)
    {
        foreach ($object->getItems() as $item) {
            $item->setSale($object);
        }
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
        $collection->add('uploadSales');
    }

    /**
     * @return array
     */
    public function getDashboardActions()
    {
        $actions = parent::getDashboardActions();

        $actions['uploadSales'] = [
            'label' => 'Upload sales',
            'translation_domain' => 'SonataAdminBundle',
            'url' => $this->generateUrl('uploadSales'),
            'icon' => 'upload',
            'template'  => 'dashboard__uploadsales.html.twig'
        ];

        return $actions;
    }
}
