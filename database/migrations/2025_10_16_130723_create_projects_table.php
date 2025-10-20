<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->unsignedBigInteger('forked_from')->nullable();
            $table->text('html')->nullable();
            $table->text('css')->nullable();
            $table->text('js')->nullable();
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('forks_count')->default(0);
            $table->unsignedInteger('views')->default(0);

            $table->foreign('forked_from')->references('id')->on('projects')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('projects');
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['forked_from']);
            $table->dropColumn(['is_public','forked_from','likes_count','forks_count','views']);
        });
    }
};
