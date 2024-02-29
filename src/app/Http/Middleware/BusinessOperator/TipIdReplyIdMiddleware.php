<?php

declare(strict_types=1);

namespace App\Http\Middleware\BusinessOperator;

use App\Repositories\StaffReply\StaffReplyRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TipIdReplyIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $tipId   = (int) $request->route()->parameter('tipId');
        $replyId = (int) $request->route()->parameter('replyId');

        $staffReplyRepository = app()->make(StaffReplyRepositoryInterface::class);
        $staffReply = $staffReplyRepository->findByStaffReplyId($replyId);

        if (is_null($staffReply)) {
            abort(404);
        }

        if ($staffReply->tip_id !== $tipId) {
            abort(404);
        }

        return $next($request);
    }
}
