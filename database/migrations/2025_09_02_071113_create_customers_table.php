<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('phone', 20)->unique();
            $table->text('address');
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('phone');
        });
    }

    public function down(): void {
        Schema::dropIfExists('customers');
    }
};
