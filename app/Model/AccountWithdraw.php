<?php
declare(strict_types=1);

namespace App\Model;

use App\Enum\WithdrawMethod;
use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id
 * @property string $account_id
 * @property WithdrawMethod $method
 * @property float $amount
 * @property bool $scheduled
 * @property Carbon $scheduled_for
 * @property bool $done
 * @property bool $error
 * @property string $error_reason
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AccountWithdraw extends Model
{
    public bool $incrementing = false;

    protected ?string $table = 'account_withdraw';

    protected string $keyType = 'string';

    protected array $fillable = [
        'id', 'account_id', 'method', 'amount', 'scheduled', 'scheduled_for', 'done', 'error', 'error_reason',
    ];

    protected array $casts = [
        'method' => WithdrawMethod::class,
        'amount' => 'float',
        'scheduled' => 'boolean',
        'done' => 'boolean',
        'error' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function pix()
    {
        return $this->hasOne(AccountWithdrawPix::class, 'account_withdraw_id', 'id');
    }

    public function markAsFailed(string $reason): void
    {
        $this->done = true;
        $this->error = true;
        $this->error_reason = $reason;
        $this->save();
    }

    public function markAsDone(): void
    {
        $this->done = true;
        $this->error = false;
        $this->error_reason = null;
        $this->save();
    }
}
