<?php

declare(strict_types=1);

use Evinkuraga\Wallet\Models\Transaction;
use Evinkuraga\Wallet\Models\Transfer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    public function up(): void
    {
        Schema::create($this->table(), function (Blueprint $table) {
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::drop($this->table());
    }

    private function table(): string
    {
        return (new Transfer())->getTable();
    }

    private function transactionTable(): string
    {
        return (new Transaction())->getTable();
    }
}
