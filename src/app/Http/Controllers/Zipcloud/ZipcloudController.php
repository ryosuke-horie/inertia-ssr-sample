<?php

namespace App\Http\Controllers\Zipcloud;

use App\Http\Controllers\Controller;
use App\Services\ZipcloudService;

class ZipcloudController extends Controller
{
    private ZipcloudService $zipcloudService;

    public function __construct(ZipcloudService $zipcloudService)
    {
        $this->zipcloudService = $zipcloudService;
    }

    public function getAddress(string $zipCode)
    {
        $args = $this->zipcloudService->getAddress($zipCode);

        return response()->json($args);
    }
}
