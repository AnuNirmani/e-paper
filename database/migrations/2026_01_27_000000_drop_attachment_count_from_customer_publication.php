<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('customer_publication') && Schema::hasColumn('customer_publication', 'attachment_count')) {
            Schema::table('customer_publication', function (Blueprint $table) {
                $table->dropColumn('attachment_count');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customer_publication') && !Schema::hasColumn('customer_publication', 'attachment_count')) {
            Schema::table('customer_publication', function (Blueprint $table) {
                $table->unsignedInteger('attachment_count')->default(1);
            });
        }
    }
};
