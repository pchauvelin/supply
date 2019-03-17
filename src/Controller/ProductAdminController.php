<?php

namespace App\Controller;

use App\Service\InventoryService;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class ProductAdminController
 *
 * @package App\Controller
 */
class ProductAdminController extends CRUDController
{
    /**
     * Refresh stock and product state
     *
     * @return RedirectResponse
     */
    public function refreshStockAction()
    {

        $service = $this->get(InventoryService::class);

        $service->updateStocks();

        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}
