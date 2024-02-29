<?php

namespace App\Http\Controllers\Corporation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Enums\EntityType;
use App\Trais\FlashMessageTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    use FlashMessageTrait;

    private NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * お知らせ一覧を表示
     * @return Response
     */
    public function index(): Response
    {
        // お知らせ情報取得
        $notificationList = $this->notificationService->list(EntityType::Corporation, Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/Notification/Index', [
            'notifications' => $notificationList->getList()
        ]);
    }

    /**
     * お知らせ詳細を表示
     * @param Int $notificationId
     */
    public function show(int $notificationId)
    {
        try {
            $notification = $this->notificationService->show($notificationId, EntityType::Corporation, Auth::guard('corporation')->user()->corporation_id);

            return Inertia::render('Corporation/Notification/Show', [
                'title'     => $notification->title,
                'content'   => $notification->content,
                'startAt'   => $notification->start_at->format('Y-m-d'),
                'file'      => $notification->file_name . '' . $notification->file_type
            ]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('corporation.notification.index');
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

            return Inertia::render('Corporation/Notification/ShowPrivate', [
                'title'     => $privateNotification->title,
                'content'   => $privateNotification->content,
                'startAt'   => $privateNotification->start_at->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            $this->flashMessageFailed();
            return Redirect::route('corporation.notification.index');
        }
    }

    /**
     * PDF表示処理
     */
    public function pdf(int $notificationId)
    {
        $path = $this->notificationService->pdf($notificationId, EntityType::Corporation, Auth::guard('corporation')->user()->corporation_id);

        return Inertia::render('Corporation/Notification/PdfViewer', [
            'path'  => $path
        ]);
    }
}
