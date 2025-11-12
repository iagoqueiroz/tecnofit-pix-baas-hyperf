<?php
declare(strict_types=1);

namespace App\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id
 * @property string $name
 * @property float $balance
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Account extends Model
{
    public bool $incrementing = false;

    protected ?string $table = 'account';

    protected string $keyType = 'string';

    protected array $fillable = ['id', 'name', 'balance'];

    protected array $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime', 'balance' => 'float'];
}
