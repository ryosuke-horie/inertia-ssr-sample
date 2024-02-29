<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Models\UserProfileImage;
use App\Models\StaffFavorite;
use App\Models\UserLike;
use Illuminate\Support\Collection;
use Stripe\PaymentMethod;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUser(): Collection
    {
        return User::all();
    }

    public function getGuestStripeId(): string
    {
        return User::where('user_id', 1)->first()->stripe_id;
    }

    public function findByUserId(int $userId): User
    {
        return User::find($userId);
    }

    public function findByEmail(string $email): ?User
    {
        $hashEmail = hash('sha256', $email);
        return User::where('email_hash', $hashEmail)->first();
    }

    public function updateEmail(int $userId, string $email): User
    {
        $user = User::find($userId);
        $user->email = $email;
        $user->save();
        return $user;
    }

    public function updatePassword(int $userId, string $password): void
    {
        $user = User::find($userId);
        $user->password = Hash::make($password);
        $user->save();
    }

    public function updateStripeId(int $userId, string $stripeId): void
    {
        $user = User::find($userId);
        $user->stripe_id = $stripeId;
        $user->save();
    }

    public function getUserProfileImageByUserId(int $userId): User
    {
        return User::with('userProfileImage')->find($userId);
    }

    public function updateUserProfileByUserId(int $userId, string $nickname): User
    {
        $user = User::find($userId);
        $user->nickname = $nickname;
        $user->save();
        return $user;
    }


    public function findUserProfileImageByUserId(int $userId): ?UserProfileImage
    {
        $where = ['user_id' => $userId];
        return UserProfileImage::where($where)->first();
    }

    public function createUserProfileImage(int $userId, string $fileName, string $fileType, int $fileSize): UserProfileImage
    {
        $create = [
            'user_id'  => $userId,
            'file_name' => $fileName,
            'file_type' => $fileType,
            'file_size' => $fileSize
        ];
        return UserProfileImage::create($create);
    }

    public function updateUserProfileImage(int $userId, string $fileName, string $fileType, int $fileSize): UserProfileImage
    {
        $where = ['user_id' => $userId];
        $userProfileImage = UserProfileImage::where($where)->first();
        $userProfileImage->file_name = $fileName;
        $userProfileImage->file_type = $fileType;
        $userProfileImage->file_size = $fileSize;
        $userProfileImage->save();
        return $userProfileImage;
    }

    public function getUserMypageInfoByUserId(int $userId): User
    {
        return User::with([
            'userProfileImage',
            'staffFavorites.staff.staffProfileImages',
            'staffFavorites.staff.businessOperator',
            'staffFavorites.staff.staffFavorites' // N+1対策
        ])->find($userId);
    }

    public function getStaffFavoritesOrSchedulesByUserId(int $userId): ?User
    {
        $today = now()->startOfDay();

        return User::with([
            'staffFavorites.staff.staffProfileImages',
            'staffFavorites.staff.businessOperator',
            'staffFavorites.staff.staffSchedules' => function ($query) use ($today) {
                $query->where('schedule_date', $today);
            },
            'staffFavorites.staff.staffFavorites' // N+1対策
        ])->find($userId);
    }

    public function findUserWithFavoriteStaff(int $userId, int $staffId): ?StaffFavorite
    {
        return StaffFavorite::where('user_id', $userId)
                            ->where('staff_id', $staffId)
                            ->first();
    }

    public function deleteFavorite(int $favoriteId): null
    {
        StaffFavorite::destroy($favoriteId);
        return null; // 削除の場合はnullを返す
    }

    public function createFavorite(int $userId, int $staffId): int
    {
        $newFavorite = StaffFavorite::create([
            'user_id'  => $userId,
            'staff_id' => $staffId,
        ]);
        return $newFavorite->favorite_id;
    }

    public function deleteUserLike(int $likeId): null
    {
        UserLike::destroy($likeId);
        return null;
    }

    public function createUserLike(int $userId, int $staffId): int
    {
        $newFavorite = UserLike::create([
            'user_id'  => $userId,
            'staff_id' => $staffId,
        ]);
        return $newFavorite->like_id;
    }

    public function updateUserPoints(int $userid, int $freePoints, int $paidPoints, int $amount): void
    {
        $user = User::find($userid);

        $user->update([
            'free_points'  => $freePoints,
            'paid_points'  => $paidPoints,
            'total_amount' => $user->total_amount + $amount
        ]);
    }

    public function deleteUser(int $userId): void
    {
        User::where('user_id', $userId)->delete();
    }

    public function deleteFavoritesByStaffId(int $staffId): void
    {
        StaffFavorite::where('staff_id', $staffId)
        ->delete();
    }

    public function deleteFavoritesByUserId(int $userId): void
    {
        StaffFavorite::where('user_id', $userId)
        ->delete();
    }

    public function deleteLikesByStaffId(int $staffId): void
    {
        UserLike::where('staff_id', $staffId)
        ->delete();
    }

    public function deleteLikesByUserId(int $userId): void
    {
        UserLike::where('user_id', $userId)
        ->delete();
    }

    public function deleteUserProfileImageByUserId(int $userId): void
    {
        UserProfileImage::where('user_id', $userId)
        ->delete();
    }

    /***************************************************************
     * 以下よりStripe関係
     ***************************************************************/

    public function getPaymentMethods(string $stripeId): array
    {
        return PaymentMethod::all([
            'customer' => $stripeId,
            'type' => 'card',
        ])->data;
    }

    public function getStripeId(int $userId): string
    {
        return User::find($userId)->stripe_id;
    }
}
