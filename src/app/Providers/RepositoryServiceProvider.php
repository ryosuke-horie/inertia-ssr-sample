<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Admin\AdminRepository;
use App\Repositories\Admin\AdminRepositoryInterface;
use App\Repositories\AdminStaff\AdminStaffRepository;
use App\Repositories\AdminStaff\AdminStaffRepositoryInterface;
use App\Repositories\BankAccount\BankAccountRepository;
use App\Repositories\BankAccount\BankAccountRepositoryInterface;
use App\Repositories\BusinessOperator\BusinessOperatorRepository;
use App\Repositories\BusinessOperator\BusinessOperatorRepositoryInterface;
use App\Repositories\Corporation\CorporationRepository;
use App\Repositories\Corporation\CorporationRepositoryInterface;
use App\Repositories\Inquiry\InquiryRepository;
use App\Repositories\Inquiry\InquiryRepositoryInterface;
use App\Repositories\LoginHistory\LoginHistoryRepository;
use App\Repositories\LoginHistory\LoginHistoryRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\Point\PointRepository;
use App\Repositories\Point\PointRepositoryInterface;
use App\Repositories\PointFluctuationHistory\PointFluctuationHistoryRepository;
use App\Repositories\PointFluctuationHistory\PointFluctuationHistoryRepositoryInterface;
use App\Repositories\Staff\StaffRepository;
use App\Repositories\Staff\StaffRepositoryInterface;
use App\Repositories\StaffNotification\StaffNotificationRepository;
use App\Repositories\StaffNotification\StaffNotificationRepositoryInterface;
use App\Repositories\StaffReply\StaffReplyRepository;
use App\Repositories\StaffReply\StaffReplyRepositoryInterface;
use App\Repositories\Token\TokenRepository;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Repositories\TotalTip\TotalTipRepository;
use App\Repositories\TotalTip\TotalTipRepositoryInterface;
use App\Repositories\TransferRequest\TransferRequestRepository;
use App\Repositories\TransferRequest\TransferRequestRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\UserTip\UserTipRepository;
use App\Repositories\UserTip\UserTipRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(StaffRepositoryInterface::class, StaffRepository::class);
        $this->app->bind(UserTipRepositoryInterface::class, UserTipRepository::class);
        $this->app->bind(StaffReplyRepositoryInterface::class, StaffReplyRepository::class);
        $this->app->bind(AdminStaffRepositoryInterface::class, AdminStaffRepository::class);
        $this->app->bind(StaffNotificationRepositoryInterface::class, StaffNotificationRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(BusinessOperatorRepositoryInterface::class, BusinessOperatorRepository::class);
        $this->app->bind(CorporationRepositoryInterface::class, CorporationRepository::class);
        $this->app->bind(BankAccountRepositoryInterface::class, BankAccountRepository::class);
        $this->app->bind(TransferRequestRepositoryInterface::class, TransferRequestRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(TokenRepositoryInterface::class, TokenRepository::class);
        $this->app->bind(StaffReplyRepositoryInterface::class, StaffReplyRepository::class);
        $this->app->bind(PointRepositoryInterface::class, PointRepository::class);
        $this->app->bind(PointFluctuationHistoryRepositoryInterface::class, PointFluctuationHistoryRepository::class);
        $this->app->bind(TotalTipRepositoryInterface::class, TotalTipRepository::class);
        $this->app->bind(LoginHistoryRepositoryInterface::class, LoginHistoryRepository::class);
        $this->app->bind(CorporationRepositoryInterface::class, CorporationRepository::class);
        $this->app->bind(InquiryRepositoryInterface::class, InquiryRepository::class);
    }
}
