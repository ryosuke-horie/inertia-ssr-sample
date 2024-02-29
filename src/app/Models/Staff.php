<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Notifications\Staff\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Staff
 *
 * @property int $staff_id
 * @property string|null $email
 * @property string|null $email_hash
 * @property string|null $password
 * @property string|null $remember_token
 * @property int $business_id
 * @property string $real_name
 * @property string $staff_name
 * @property string|null $comment
 * @property bool $is_deleted
 * @property int $points
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property BusinessOperator $business_operator
 * @property Collection|StaffProfileImage[] $staff_profile_images
 * @property Collection|UserTip[] $user_tips
 * @property Collection|StaffFavorite[] $staff_favorites
 * @property Collection|StaffSchedule[] $staff_schedules
 * @property Collection|StaffNotification[] $staff_notifications
 * @property Collection|UserLike[] $user_likes
 *
 * @package App\Models
 */
class Staff extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use HasFactory;

    protected $table = 'staff';
    protected $primaryKey = 'staff_id';

    protected $casts = [
        'business_id' => 'int',
        'is_deleted' => 'bool',
        'points' => 'int'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'email',
        'password',
        'remember_token',
        'business_id',
        'real_name',
        'staff_name',
        'comment',
        'is_deleted',
        'points'
    ];

    /**
     * Emailの復号化
     *
     * @param string $value
     * @return string
     */
    public function getEmailAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * Emailの暗号化
     * Emailは検索で活用するため、ハッシュ化した値も保存する
     *
     * @param string $value
     * @return void
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = Crypt::encryptString($value);
        $this->attributes['email_hash'] = hash('sha256', $value);
    }

    /**
     * Emailで検索
     *
     * @param string $email
     * @return Staff|null
     */
    public static function findByEmail($email)
    {
        $emailHash = hash('sha256', $email);
        return self::where('email_hash', $emailHash)->first();
    }


    /**
     * 事業者情報
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function businessOperator()
    {
        return $this->belongsTo(BusinessOperator::class, 'business_id', 'business_id');
    }

    /**
     * 管理者スタッフ中間テーブル
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function adminStaffs()
    {
        return $this->belongsToMany(AdminStaff::class, 'admin_staff_staff', 'staff_id', 'admin_staff_id');
    }

    /**
     * スタッフ画像
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffProfileImages()
    {
        return $this->hasMany(StaffProfileImage::class, 'staff_id', 'staff_id');
    }

    public function userTips()
    {
        return $this->hasMany(UserTip::class);
    }

    /**
     * お気に入りスタッフ
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffFavorites()
    {
        return $this->hasMany(StaffFavorite::class, 'staff_id');
    }

    /**
     * スタッフのスケジュール
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function staffSchedules()
    {
        return $this->hasMany(StaffSchedule::class, 'staff_id');
    }

    public function staffNotifications()
    {
        return $this->hasMany(StaffNotification::class);
    }

    public function userLikes()
    {
        return $this->hasMany(UserLike::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
