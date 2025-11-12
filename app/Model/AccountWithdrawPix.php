<?php

declare(strict_types=1);

namespace App\Model;

use App\Enum\PixKeyType;
use Hyperf\DbConnection\Model\Model;

/**
 * @property string $account_withdraw_id 
 * @property PixKeyType $type 
 * @property string $key 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AccountWithdrawPix extends Model
{
    public bool $incrementing = false;

    protected ?string $table = 'account_withdraw_pix';

    protected string $primaryKey = 'account_withdraw_id';

    protected string $keyType = 'string';

    protected array $fillable = ['account_withdraw_id', 'type', 'key'];

    protected array $casts = ['type' => PixKeyType::class, 'created_at' => 'datetime', 'updated_at' => 'datetime'];
    
    public function withdraw()
    {
        return $this->belongsTo(AccountWithdraw::class, 'account_withdraw_id', 'id');
    }
}
