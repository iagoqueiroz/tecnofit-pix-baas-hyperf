<?php

declare(strict_types=1);

use App\Model\Account;
use Hyperf\Database\Seeders\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::create([
            'id' => 'ef0001c3-c9cc-41a3-aa7a-b08b8a818108',
            'name' => 'John Doe',
            'balance' => 500.00,
        ]);
    }
}
