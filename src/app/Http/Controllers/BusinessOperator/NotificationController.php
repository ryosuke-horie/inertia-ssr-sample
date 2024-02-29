<?php

namespace App\Http\Controllers\BusinessOperator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Enums\EntityType;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
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
        $notificationList = $this->notificationService->list(EntityType::BusinessOperator, Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Notification/Index', [
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
            $notification = $this->notificationService->show($notificationId, EntityType::BusinessOperator, Auth::guard('business-operator')->user()->business_id);

            return Inertia::render('BusinessOperator/Notification/Show', [
                'title'     => $notification->title,
                'content'   => $notification->content,
                'startAt'   => $notification->start_at->format('Y-m-d'),
                'file'      => $notification->file_name . '' . $notification->file_type
            ]);
        } catch (\Exception $e) {
            return Redirect::route('business-operator.notification.index')->with('fail', '予期せぬエラーが発生しました');
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

            return Inertia::render('BusinessOperator/Notification/ShowPrivate', [
                'title'     => $privateNotification->title,
                'content'   => $privateNotification->content,
                'startAt'   => $privateNotification->start_at->format('Y-m-d'),
            ]);
        } catch (\Exception $e) {
            return Redirect::route('business-operator.notification.index')->with('fail', '予期せぬエラーが発生しました');
        }
    }

    /**
     * PDF表示処理
     */
    public function pdf(int $notificationId)
    {
        $path = $this->notificationService->pdf($notificationId, EntityType::BusinessOperator, Auth::guard('business-operator')->user()->business_id);

        return Inertia::render('BusinessOperator/Notification/PdfViewer', [
            'path'  => $path
        ]);
    }
}
