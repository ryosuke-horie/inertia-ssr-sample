<?php

declare(strict_types=1);

namespace App\Http\Middleware\GuestUser;

use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class ApiBusinessIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $businessId = (int) $request->route()->parameter('businessId');

        $businessOperatorRepository = app()->make(BusinessOperatorRepositoryInterface::class);
        $businessOperator = $businessOperatorRepository->findByBusinessId($businessId);

        if ($businessOperator) {
            return $next($request);
        }

        $response = [
            'message' => 'Operation not permitted.',
            'type'    => 'forbidden'
        ];
        return response()->json($response, 403);
    }
}
