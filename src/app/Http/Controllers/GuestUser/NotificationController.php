<?php

namespace App\Http\Controllers\GuestUser;

use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    private $notificationService;

    public function __construct(
        NotificationService $notificationService,
    ) {
        $this->notificationService = $notificationService;
    }

    /**
     * お知らせ一覧を表示
     * @return Response
     */
    public function index(): Response
    {
        $list = $this->notificationService->list(EntityType::User, 1);

        return Inertia::render('GuestUser/Notification/Index', [
            'notifications' => $list->getList(),
        ]);
    }

    /**
     * 個人向けお知らせ詳細を表示
     * @param Int $notificationId
     */
    public function show(int $notificationId)
    {
        try {
            $notification = $this->notificationService->show($notificationId, EntityType::User, 1);

            return Inertia::render('GuestUser/Notification/Show', [
                'title'     => $notification->title,
                'content'   => $notification->content,
                'startAt'   => $notification->start_at->format('Y-m-d'),
                'file'      => $notification->file_name . '' . $notification->file_type
            ]);
        } catch (\Exception $e) {
            return Redirect::route('user.guest.notification.index')->with('fail', '不具合が発生しました。');
        }
    }

    /**
     * 個人向けお知らせ詳細を表示
     * @param Int $notificationId
     */
    public function showPrivate(int $notificationId)
    {
        try {
            $privateNotification = $this->notificationService->showPrivate($notificationId);

            return Inertia::render('GuestUser/Notification/ShowPrivate', [
                'title'     => $privateNotification->title,
                'content'   => $privateNotification->content,
                'startAt'   => $privateNotification->start_at->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            return Redirect::route('user.guest.notification.index')->with('fail', '不具合が発生しました。');
        }
    }

    /**
     * PDF表示処理
     * @param Int $notificationId
     * @return Response
     */
    public function pdf(int $notificationId): Response
    {
        $path = $this->notificationService->pdf($notificationId, EntityType::User, 1);

        return Inertia::render('GuestUser/Notification/PdfViewer', [
            'path'  => $path
        ]);
    }
}
