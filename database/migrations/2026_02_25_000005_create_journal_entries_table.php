<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table): void {
            $table->id();
            $table->date('date')->index();
            $table->string('account_name');
            $table->decimal('debit', 12, 2)->nullable();
            $table->decimal('credit', 12, 2)->nullable();
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
