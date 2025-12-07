<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to add 'renteng'
        DB::statement("ALTER TABLE `products` MODIFY `unit` ENUM('pcs','kg','liter','renteng') NOT NULL DEFAULT 'pcs'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE `products` MODIFY `unit` ENUM('pcs','kg','liter') NOT NULL DEFAULT 'pcs'");
    }
};
