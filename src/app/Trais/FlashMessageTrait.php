<?php

declare(strict_types=1);

namespace App\Trais;

use Illuminate\Support\Facades\Session;

trait FlashMessageTrait
{
    private const INIT_SUCCESS_MESSAGE = '更新が成功しました。';
    private const INIT_FAILED_MESSAGE  = '予期せぬエラーが発生しました。もう一度お試しください。';

    /**
     * 成功フラッシュメッセージ
     * @param ?string $message
     * @return void
     */
    public function flashMessageSuccess(?string $message = null): void
    {
        Session::flash('flash', ['type' => 'success', 'text' => $message ?? self::INIT_SUCCESS_MESSAGE]);
    }

    /**
     * 失敗フラッシュメッセージ
     * @param ?string $message
     * @return void
     */
    public function flashMessageFailed(?string $message = null): void
    {
        Session::flash('flash', ['type' => 'failed', 'text' => $message ?? self::INIT_FAILED_MESSAGE]);
    }
}
