<?php

declare(strict_types=1);

namespace App\Http\Middleware\GuestUser;

use App\Repositories\Staff\StaffRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class ApiBusinessIdStaffIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $businessId = (int) $request->route()->parameter('businessId');
        $staffId = (int) $request->route()->parameter('staffId');

        $staffRepository = app()->make(StaffRepositoryInterface::class);
        $staff = $staffRepository->findByStaffId($staffId);

        if ($staff && $staff->business_id === $businessId) {
            return $next($request);
        }

        $response = [
            'message' => 'Operation not permitted.',
            'type'    => 'forbidden'
        ];
        return response()->json($response, 403);
    }
}
