<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update existing admin users to super_admin
        DB::table('users')->where('role', 'admin')->update(['role' => 'super_admin']);

        // Alter enum column
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'staff') NOT NULL DEFAULT 'staff'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'staff') NOT NULL DEFAULT 'staff'");
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);
    }
};
