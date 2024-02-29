<?php

declare(strict_types=1);

namespace App\Http\Middleware\AdminStaff;

use App\Repositories\AdminStaff\AdminStaffRepositoryInterface;
use App\Repositories\UserTip\UserTipRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TipIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $tipId = (int) $request->route()->parameter('tipId');

        $adminStaffId = Auth::guard('admin-staff')->user()->admin_staff_id;

        $adminStaffRepository = app()->make(AdminStaffRepositoryInterface::class);
        $staffs = $adminStaffRepository->getAllStaffsByAdminStaffId($adminStaffId);
        $staffIds = $staffs->pluck('staff_id')->toArray();

        $userTipRepository = app()->make(UserTipRepositoryInterface::class);
        $userTips = $userTipRepository->getAllByStaffIds($staffIds);
        $userTipIds = $userTips->pluck('tip_id');

        $isMatch = $userTipIds->contains($tipId);

        if (!$isMatch) {
            abort(404);
        }

        return $next($request);
    }
}
