<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PointBuyHistory
 *
 * @property int $buy_id
 * @property int $user_id
 * @property int $buy_points
 * @property float $amount
 * @property int|null $remaining_points
 * @property string $payment_intent_id
 * @property bool $is_paid
 * @property Carbon $expiration_date
 * @property Carbon|null $payment_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property User $user
 * @property Collection|PointDetail[] $point_details
 * @property Collection|PointUsageHistory[] $point_usage_histories
 *
 * @package App\Models
 */
class PointBuyHistory extends Model
{
    protected $table = 'point_buy_histories';
    protected $primaryKey = 'buy_id';

    protected $casts = [
        'user_id' => 'int',
        'buy_points' => 'int',
        'amount' => 'float',
        'remaining_points' => 'int',
        'is_paid' => 'bool',
        'expiration_date' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'buy_points',
        'amount',
        'remaining_points',
        'payment_intent_id',
        'is_paid',
        'expiration_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pointUsageHistories()
    {
        return $this->hasMany(PointUsageHistory::class, 'buy_id');
    }
}
