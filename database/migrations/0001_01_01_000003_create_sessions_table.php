<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The standard Laravel sessions table.
 *
 * Upstream Laravel ships this inside the default create_users_table migration.
 * This demo replaced that migration with its own users schema and dropped the
 * sessions table along the way, while config/session.php kept the framework
 * default of `database`. The demo serves on that default today only because the
 * committed dump happens to carry a sessions table left over from an earlier
 * schema: nothing in this repo creates it, so a fresh `php artisan migrate`
 * against an empty database produces an unservable site.
 *
 * Production is unaffected either way because the Helm chart sets
 * SESSION_DRIVER=file, but the table should exist by migration rather than by
 * accident.
 *
 * The existence guard is what makes this safe to add to a demo that already has
 * the table: deployment loads the dump and then runs `php artisan migrate
 * --force`, so an unguarded create would abort the deploy.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sessions')) {
            return;
        }

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
