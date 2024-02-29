<?php

namespace App\Services;

use App\Repositories\Corporation\CorporationRepositoryInterface;
use App\Enums\EntityType;
use App\Trais\FileTrait;
use App\Trais\TokenTrait;
use App\Repositories\Token\TokenRepositoryInterface;
use App\Trais\EmailTrait;
use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangeCorporationEmailMail;

class CorporationService
{
    use FileTrait;
    use TokenTrait;
    use EmailTrait;

    private CorporationRepositoryInterface $corporationRepository;
    private TokenRepositoryInterface $tokenRepository;

    public function __construct(CorporationRepositoryInterface $corporationRepository, TokenRepositoryInterface $tokenRepository)
    {
        $this->corporationRepository = $corporationRepository;
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * メールアドレス変更用トークン作成＆メール送信
     * @param int $corporationId
     * @param string $corporationName
     * @param string $email
     * @return void
     */
    public function registerTokenForEmail(int $corporationId, string $corporationName, string $email): void
    {
        $token = $this->generateToken();
        $this->tokenRepository->registerToken($corporationId, EntityType::Corporation, $email, $token);
        Mail::to($email)->send(new ChangeCorporationEmailMail($corporationName, $email, $token));
    }

    /**
     * メールアドレス変更
     * @param string $token
     * @param string $email
     * @return void
     */
    public function updateEmail(string $token, string $email): void
    {
        $corporationId = $this->tokenRepository->getCorporationIdByTokenAndEmail($token, $email);

        if (is_null($corporationId)) {
            throw new Exception('トークン管理に紐づくレコードがありませんでした。');
        }

        $emailHash = hash('sha256', $this->convertToLowercase($email));

        if (!is_null($this->corporationRepository->findByEmailHash($emailHash))) {
            throw new Exception();
        }

        $this->corporationRepository->updateEmail($corporationId, $email, $emailHash);
        $this->tokenRepository->deleteToken($token);
    }

    /**
     * パスワード更新
     */
    public function updatePassword(int $corporationId, string $password): void
    {
        $this->corporationRepository->updatePassword($corporationId, $password);
    }
}
