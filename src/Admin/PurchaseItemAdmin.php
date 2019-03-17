<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

final class PurchaseItemAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('product');
        $formMapper->add('quantity', IntegerType::class);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('product');
        $listMapper->addIdentifier('quantity');
    }
}
