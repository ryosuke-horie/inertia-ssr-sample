<?php

declare(strict_types=1);

namespace App\Repositories\StaffReply;

use App\Models\StaffReply;
use App\Models\ReplyMedia;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StaffReplyRepository implements StaffReplyRepositoryInterface
{
    public function findByStaffReplyId(int $staffReplyId): ?StaffReply
    {
        return StaffReply::find($staffReplyId);
    }

    public function createStaffReply(int $tipId, string $message): StaffReply
    {
        $staffReply = new StaffReply();
        $staffReply->tip_id = $tipId;
        $staffReply->message = $message;
        $staffReply->created_at = Carbon::now();
        $staffReply->save();
        return $staffReply;
    }

    public function deleteStaffReply(int $replyId): void
    {
        ReplyMedia::where('reply_id', $replyId)->delete();
        StaffReply::destroy($replyId);
    }

    public function createReplyMedia(int $replyId, string $fileName, string $fileType, int $fileSize, int $duration): ReplyMedia
    {
        $create = [
            'reply_id'  => $replyId,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize,
            'duration'  => $duration
        ];
        return ReplyMedia::create($create);
    }

    public function getReplyMediaByBusiness(int $businessId): Collection
    {
        return ReplyMedia::selectRaw("
            to_char(reply_media.created_at, 'YYYY年FMMM月FMDD日') as date,
            user_tips.tip_id,
            staff.staff_name,
            staff_profile_images.file_name as staff_file_name,
            COALESCE(user_tips.guest_nickname, users.nickname) as user_name,
            reply_media.media_id,
            reply_media.file_name as reply_file_name,
            reply_media.file_type as reply_file_type
        ")
        ->join('staff_replies', 'reply_media.reply_id', 'staff_replies.reply_id')
        ->join('user_tips', 'staff_replies.tip_id', 'user_tips.tip_id')
        ->join('users', 'user_tips.user_id', 'users.user_id')
        ->join('staff', 'user_tips.staff_id', 'staff.staff_id')
        ->leftjoin('staff_profile_images', function ($leftJoin) {
            $leftJoin->on('staff.staff_id', '=', 'staff_profile_images.staff_id')
            ->where('staff_profile_images.order', '=', 1);
        })
        ->where('staff.business_id', $businessId)
        ->whereNull('reply_media.deleted_at')
        ->orderBy('reply_media.created_at', 'DESC')
        ->get();
    }

    public function deleteStaffMedia(int $mediaId): void
    {
        ReplyMedia::destroy($mediaId);
    }
}
