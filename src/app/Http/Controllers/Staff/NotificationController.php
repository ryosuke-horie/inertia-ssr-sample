<?php

declare(strict_types=1);

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Enums\EntityType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * お知らせ一覧画面表示
     * @return Response
     */
    public function index(): Response
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $notificationList = $this->notificationService->list(EntityType::Staff, $staffId);
        return Inertia::render('Staff/Notification/Index', [
            'notifications' => $notificationList->getList()
        ]);
    }

    /**
     * お知らせ詳細画面表示
     * @param int $notificationId
     * @return Response|RedirectResponse
     */
    public function show(int $notificationId): Response|RedirectResponse
    {
        try {
            $staffId = Auth::guard('staff')->user()->staff_id;
            $notification = DB::transaction(function () use ($staffId, $notificationId) {
                return $this->notificationService->show($notificationId, EntityType::Staff, $staffId);
            });
            return Inertia::render('Staff/Notification/Show', [
                'title'     => $notification->title,
                'content'   => $notification->content,
                'startAt'   => $notification->start_at->format('Y-m-d'),
                'file'      => $notification->file_name . '' . $notification->file_type
            ]);
        } catch (\Exception $e) {
            return Redirect::route('staff.notification.index');
        }
    }

    /**
     * 個人向けお知らせ詳細画面表示
     * @param int $notificationId
     * @return Response|RedirectResponse
     */
    public function showPrivate(int $notificationId): Response|RedirectResponse
    {
        try {
            $privateNotification = DB::transaction(function () use ($notificationId) {
                return $this->notificationService->showPrivate($notificationId);
            });
            return Inertia::render('Staff/Notification/ShowPrivate', [
                'title'     => $privateNotification->title,
                'content'   => $privateNotification->content,
                'startAt'   => $privateNotification->start_at->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            return Redirect::route('staff.notification.index');
        }
    }

    /**
     * お知らせPDF画面表示
     * @param int $notificationId
     * @return Response|RedirectResponse
     */
    public function pdf(int $notificationId)
    {
        $staffId = Auth::guard('staff')->user()->staff_id;
        $path = $this->notificationService->pdf($notificationId, EntityType::Staff, $staffId);
        return Inertia::render('Staff/Notification/PdfViewer', ['path' => $path]);
    }
}
