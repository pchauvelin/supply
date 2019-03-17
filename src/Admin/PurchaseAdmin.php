<?php

namespace App\Admin;

use App\Service\InventoryService;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

final class PurchaseAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('supplier');
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
        $datagridMapper->add('supplier');
        $datagridMapper->add('createdAt');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('supplier');
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
            $item->setPurchase($object);
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
            $item->setPurchase($object);
        }
    }

    /**
     * Post persist method
     *
     * @param $object
     *
     * @throws \Sonata\AdminBundle\Exception\ModelManagerException
     */
    public function postPersist($object)
    {
        parent::postPersist($object);

        $this->postProcessObject($object);
    }

    /**
     * Post update method
     *
     * @param $object
     */
    public function postUpdate($object)
    {
        parent::postUpdate($object);

        $this->postProcessObject($object);
    }

    /**
     * Post process logic
     *
     * @param $object
     */
    private function postProcessObject($object)
    {
        $container = $this->getConfigurationPool()->getContainer();

        /**
         * @var InventoryService $inventoryService
         */
        $inventoryService = $container->get(InventoryService::class);

        foreach ($object->getItems() as $item) {
            $product = $item->getProduct();
            $inventoryService->updateStockByProduct($product);
        }
    }
}
