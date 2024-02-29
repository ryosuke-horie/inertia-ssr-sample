<?php

declare(strict_types=1);

namespace App\Http\Middleware\BusinessOperator;

use App\Repositories\AdminStaff\AdminStaffRepositoryInterface;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\UserTip\UserTipRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminStaffIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $adminStaffId = (int) $request->route()->parameter('adminStaffId');

        $businessId = Auth::guard('business-operator')->user()->business_id;

        $adminStaffRepository = app()->make(AdminStaffRepositoryInterface::class);
        $adminStaffs = $adminStaffRepository->getAdminStaffsByBusinessId($businessId);
        $adminStaffIds = $adminStaffs->pluck('admin_staff_id');

        $isMatch = $adminStaffIds->contains($adminStaffId);

        if (!$isMatch) {
            abort(404);
        }

        return $next($request);
    }
}
