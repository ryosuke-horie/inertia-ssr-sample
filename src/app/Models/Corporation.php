<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Notifications\Corporation\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Corporation
 *
 * @property int $corporation_id
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property int|null $corporation_application_id
 * @property string $corporation_name
 * @property string $zip_code
 * @property string $pref_code
 * @property string $city
 * @property string $phone
 * @property string $invoice
 * @property string|null $notes
 * @property bool $is_deleted
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CorporationApplication|null $corporation_application
 * @property Collection|BusinessOperator[] $business_operators
 * @property CorporationSetting|null $corporation_setting
 *
 * @package App\Models
 */
class Corporation extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    protected $table = 'corporations';
    protected $primaryKey = 'corporation_id';

    protected $casts = [
        'corporation_application_id' => 'int',
        'is_deleted' => 'bool'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $fillable = [
        'email',
        'password',
        'remember_token',
        'corporation_application_id',
        'corporation_name',
        'zip_code',
        'pref_code',
        'city',
        'phone',
        'invoice',
        'notes',
        'is_deleted'
    ];

    /**
     * 法人申請情報
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function corporationApplication()
    {
        return $this->belongsTo(CorporationApplication::class);
    }

    /**
     * 事業者情報
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function businessOperators()
    {
        return $this->hasMany(BusinessOperator::class, 'corporation_id');
    }

    /**
     * 法人設定情報
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function corporationSettings()
    {
        return $this->hasOne(CorporationSetting::class, 'corporation_id');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Invoiceの暗号化
     *
     * @param string $value
     * @return void
     */
    public function setInvoiceAttribute($value)
    {
        $this->attributes['invoice'] = Crypt::encryptString($value);
    }

    /**
     * invoiceの復号化
     *
     * @param string $value
     * @return string
     */
    public function getInvoiceAttribute($value)
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
     * Emailで検索
     *
     * @param string $email
     * @return Corporation|null
     */
    public static function findByEmail($email)
    {
        $emailHash = hash('sha256', $email);
        return self::where('email_hash', $emailHash)->first();
    }

    /**
     * Phoneの暗号化
     *
     * @param string $value
     * @return void
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = Crypt::encryptString($value);
    }

    /**
     * Phoneの復号化
     *
     * @param string $value
     * @return string
     */
    public function getPhoneAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
