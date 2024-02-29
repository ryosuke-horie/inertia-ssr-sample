<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Prefecture;
use Illuminate\Support\Facades\Crypt;

/**
 * Class BusinessApplication
 *
 * @property int $business_application_id
 * @property string $email
 * @property int|null $corporation_id
 * @property string|null $corporation_name
 * @property string|null $applicant_name
 * @property string|null $applicant_name_kana
 * @property string $business_name
 * @property string $zip_code
 * @property string $pref_code
 * @property string $city
 * @property string $phone
 * @property string|null $invoice
 * @property string|null $business_form
 * @property string|null $notes
 * @property int $application_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property CorporationApplication|null $corporation_application
 * @property Collection|businessOperator $business_operator
 *
 * @package App\Models
 */
class BusinessApplication extends Model
{
    protected $table = 'business_applications';
    protected $primaryKey = 'business_application_id';

    protected $casts = [
        'corporation_id' => 'int',
        'application_status' => 'int',
        // 'pref_code' => Prefecture::class,
    ];

    protected $fillable = [
        'email',
        'corporation_id',
        'corporation_name',
        'applicant_name',
        'applicant_name_kana',
        'business_name',
        'zip_code',
        'pref_code',
        'city',
        'phone',
        'invoice',
        'business_form',
        'notes',
        'application_status'
    ];

    public function corporation()
    {
        return $this->belongsTo(CorporationApplication::class);
    }

    /**
     * 事業者
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function businessOperator()
    {
        return $this->hasOne(BusinessOperator::class, 'business_application_id');
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

    /**
     * 申込者氏名の暗号化
     *
     * @param string $value
     * @return void
     */
    public function setApplicantNameAttribute($value)
    {
        $this->attributes['applicant_name'] = Crypt::encryptString($value);
    }

    /**
     * 申込者氏名の復号化
     *
     * @param string $value
     * @return string
     */
    public function getApplicantNameAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    /**
     * 申込者氏名（カナ）の暗号化
     *
     * @param string $value
     * @return void
     */
    public function setApplicantNameKanaAttribute($value)
    {
        $this->attributes['applicant_name_kana'] = Crypt::encryptString($value);
    }

    /**
     * 申込者氏名（カナ）の復号化
     *
     * @param string $value
     * @return string
     */
    public function getApplicantNameKanaAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
