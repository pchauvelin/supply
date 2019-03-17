<?php

namespace App\Controller;

use App\Service\FileService;
use App\Service\InventoryService;
use App\Service\SaleService;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SaleAdminController
 *
 * @package App\Controller
 */
class SaleAdminController extends CRUDController
{
    /**
     * Upload sales action
     *
     * @param Request $request
     * @param FileService $fileUploader
     * @param SaleService $saleService
     *
     * @return RedirectResponse
     */
    public function uploadSalesAction(Request $request, FileService $fileUploader, SaleService $saleService, InventoryService $inventoryService)
    {
        $referer = $request->headers->get('referer');

        $file = $request->files->get('file');

        if (!$file instanceof UploadedFile) {
            $this->addFlash(
                'error',
                'Error while the file uploading : the format is not valid'
            );

            return $this->redirect($referer);
        }

        $result = $fileUploader->upload($request->files->get('file'));

        if ($result['state'] == 'error') {
            $this->addFlash(
                'error',
                'Error while the file uploading : "' . $result['message'] .  '"'
            );
        } else {
            $this->addFlash(
                'success',
                'Sales have been uploaded with success'
            );

            $saleService->importSales($result['filename']);
            $inventoryService->updateStocks();
        }

        return $this->redirect($referer);
    }
}
