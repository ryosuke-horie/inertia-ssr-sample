<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * Class CorporationApplication
 *
 * @property int $corporation_application_id
 * @property string $email
 * @property string $corporation_name
 * @property string $applicant_name
 * @property string $applicant_name_kana
 * @property string $zip_code
 * @property string $pref_code
 * @property string $city
 * @property string $phone
 * @property string $invoice
 * @property int $application_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|BusinessApplication[] $business_applications
 * @property Collection|Corporation[] $corporations
 *
 * @package App\Models
 */
class CorporationApplication extends Model
{
    protected $table = 'corporation_applications';
    protected $primaryKey = 'corporation_application_id';

    protected $casts = [
        'application_status' => 'int'
    ];

    protected $fillable = [
        'email',
        'corporation_name',
        'applicant_name',
        'applicant_name_kana',
        'zip_code',
        'pref_code',
        'city',
        'phone',
        'invoice',
        'application_status'
    ];

    public function businessApplications()
    {
        return $this->hasMany(BusinessApplication::class);
    }

    public function corporations()
    {
        return $this->hasMany(Corporation::class);
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
