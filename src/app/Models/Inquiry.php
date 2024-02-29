<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

/**
 * Class Inquiry
 *
 * @property int $inquiry_id
 * @property int $entity_type
 * @property int|null $entity_id
 * @property string $name
 * @property string $email
 * @property string $content
 * @property bool $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Inquiry extends Model
{
    protected $table = 'inquiries';
    protected $primaryKey = 'inquiry_id';

    protected $casts = [
        'entity_type' => 'int',
        'entity_id' => 'int',
        'status' => 'int'
    ];

    protected $fillable = [
        'entity_type',
        'entity_id',
        'name',
        'email',
        'content',
        'status'
    ];

    /**
     * Emailの暗号化
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
     * 名前の暗号化
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Crypt::encryptString($value);
    }

    /**
     * 名前の復号化
     *
     * @param string $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
