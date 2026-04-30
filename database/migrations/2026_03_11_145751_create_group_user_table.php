<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('group_user', function (Blueprint $table) {
            // 中間テーブルは基本idを持たない。
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['group_id', 'user_id']); // 同じ組み合わせは1回しか登録できない。とした。
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('group_user');
    }
};
