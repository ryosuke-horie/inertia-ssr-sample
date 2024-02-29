<?php

namespace App\Services;

use App\Enums\EntityType;
use App\Mail\InquiryUserMail;
use App\Mail\InquiryAdminMail;
use App\Repositories\Inquiry\InquiryRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InquiryService
{
    private InquiryRepositoryInterface $inquiryRepository;

    public function __construct(InquiryRepositoryInterface $inquiryRepository)
    {
        $this->inquiryRepository = $inquiryRepository;
    }

    /**
     * リクエストURLのパス部分の各単語の頭文字を大文字に変換する
     * （user/inquiryの場合、User/Inquiryを返す）
     * 'Inquiry'の場合は'Web/Inquiry'に変換する
     * 'Inquiry/Confirm'や'Inquiry/Complete'の場合はそれぞれ'Web/Inquiry/Confirm'、'Web/Inquiry/Complete'に変換する
     * 'business-operator'の場合は'BusinessOperator'に変換するが、その他の部分も頭文字を大文字にする
     *
     * @param Request $request
     * @return string $convertedPath
     */
    public function convertPathToUpperCamelCase(Request $request): string
    {
        // 現在のリクエストパスを取得
        $path = $request->path();

        // パスの各部分の頭文字を大文字に変換（一旦'/'をスペースに置換してucwordsを適用し、再び'/'に置換）
        $convertedPath = str_replace(' ', '/', ucwords(str_replace('/', ' ', $path)));

        // 'business-operator'を'BusinessOperator'に変換
        $convertedPath = str_replace('Business-operator', 'BusinessOperator', $convertedPath);
        // 'admin-staff'を'AdminStaff'に変換
        $convertedPath = str_replace('Admin-staff', 'AdminStaff', $convertedPath);

        // WEBは頭文字をWEBにする
        if ($convertedPath === 'Inquiry') {
            $convertedPath = 'Web/Inquiry';
        }

        // WEBの確認画面と完了画面は頭文字をWEBにする
        if (in_array($convertedPath, ['Inquiry/Confirm', 'Inquiry/Complete'])) {
            $convertedPath = 'Web/' . $convertedPath;
        }

        return $convertedPath;
    }
    /**
     * $pathに基づいてentityTypeとuserIdを取得
     *
     * @param string $path
     * @return array
     */
    public function getEntityTypeAndUserId(string $path): array
    {
        // エンティティタイプ
        $entityTypeMappings = [
            'user' => EntityType::User,
            'staff' => EntityType::Staff,
            'business-operator' => EntityType::BusinessOperator,
            'corporation' => EntityType::Corporation,
            'admin-staff' => EntityType::AdminStaff,
            'web' => EntityType::Web,
        ];

        // パスの最初のセグメントを取得し、kebab-case、lowerに変換
        $firstSegment = explode('/', $path)[0];
        $kebabCaseSegment = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $firstSegment));
        $lowerFirstSegment = strtolower($kebabCaseSegment);

        // Webの場合はサービスサイトからの問い合わせとして扱う
        if ($lowerFirstSegment === 'web') {
            return [
                'entityType' => $entityTypeMappings[$lowerFirstSegment],
                'userId' => null,
            ];
        }

        // ユーザーを取得し、エンティティタイプとユーザーIDを決定
        $user = Auth::guard($kebabCaseSegment)->user();
        $entityType = $entityTypeMappings[$lowerFirstSegment];

        // ユーザーIDの属性名を決定
        $idAttributeName = match ($entityType) {
            EntityType::User => 'user_id',
            EntityType::Staff => 'staff_id',
            EntityType::BusinessOperator => 'business_id',
            EntityType::Corporation => 'corporation_id',
            EntityType::AdminStaff => 'admin_staff_id',
            default => 'user_id',
        };

        return [
            'entityType' => $entityType,
            'userId' => $user->$idAttributeName ?? null,
        ];
    }

    /**
     * お問い合わせを保存し、ユーザーとMITS管理にメールを送信する
     *
     * @param string $name
     * @param string $email
     * @param string $content
     * @param EntityType $entityType
     * @param int|null $userId
     * @return void
     */
    public function storeInquiry(string $name, string $email, string $content, EntityType $entityType, int|null $userId): void
    {
        $this->inquiryRepository->storeInquiry($name, $email, $content, $entityType, $userId);
        // ユーザーにメール送信
        Mail::to($email)->send(new InquiryUserMail($name, $email, $content));
        // MITS管理にメール送信
        Mail::to(config('app.email_from_address'))->send(new InquiryAdminMail($name, $email, $content));
    }
}
