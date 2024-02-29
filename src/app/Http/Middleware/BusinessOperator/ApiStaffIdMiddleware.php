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

class ApiStaffIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $staffId = (int) $request->route()->parameter('staffId');

        $businessId = Auth::guard('business-operator')->user()->business_id;

        $staffRepository = app()->make(StaffRepositoryInterface::class);
        $staffs = $staffRepository->getAllStaffsByBusinessId($businessId);
        $staffIds = $staffs->pluck('staff_id');

        $isMatch = $staffIds->contains($staffId);

        if ($isMatch) {
            return $next($request);
        }

        $response = [
            'message' => 'Operation not permitted.',
            'type'    => 'forbidden'
        ];
        return response()->json($response, 403);
    }
}
