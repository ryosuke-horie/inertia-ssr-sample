<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * ダッシュボード
     *
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard/Index');
    }
}
