<?php

declare(strict_types=1);

namespace App\Http\Middleware\Staff;

use App\Enums\EntityType;
use App\Models\Notification;
use App\Models\PrivateNotification;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\UserTip\UserTipRepositoryInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NotificationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $notificationId   = (int) $request->route()->parameter('notificationId');

        // 全体お知らせルートの場合
        if ($request->routeIs('staff.notification.show')) {
            $where = ['notification_id' => $notificationId, 'entity_type' => EntityType::Staff];
            $notification = Notification::where($where)->whereNot(function ($query) {
                $query->where('file_type', 'pdf');
            })->first();
            if (is_null($notification)) {
                abort(404);
            }
        }

        // 個人向けお知らせルートの場合
        if ($request->routeIs('staff.notification.show.private')) {
            $staffId = Auth::guard('staff')->user()->staff_id;
            $where = ['notification_id' => $notificationId,'entity_type' => EntityType::Staff, 'entity_id' => $staffId];
            $privateNotification = PrivateNotification::where($where)->first();
            if (is_null($privateNotification)) {
                abort(404);
            }
        }

        // PDFルートの場合
        if ($request->routeIs('staff.notification.pdf')) {
            $where = ['notification_id' => $notificationId, 'entity_type' => EntityType::Staff, 'file_type' => 'pdf'];
            $notification = Notification::where($where)->first();
            if (is_null($notification)) {
                abort(404);
            }
        }

        return $next($request);
    }
}
