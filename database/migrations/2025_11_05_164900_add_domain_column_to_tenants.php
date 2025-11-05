<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('domain')->nullable()->after('active');
        });

        // exiting tenants should have a domain set to their first domain
        $tenants = DB::table('tenants')->get();
        foreach ($tenants as $tenant) {
            $firstDomain = DB::table('domains')->where('tenant_id', $tenant->id)->value('domain');
            if ($firstDomain) {
                DB::table('tenants')->where('id', $tenant->id)->update(['domain' => $firstDomain]);
            }
        }

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('domain')->nullable(false)->change();
        });

        // delete all existing domains as they are now stored in the tenants table
        DB::table('domains')->delete();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tenants = DB::table('tenants')->get();

        foreach ($tenants as $tenant) {
            DB::table('domains')->insert([
                'tenant_id' => $tenant->id,
                'domain' => $tenant->domain,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('tenants', function (Blueprint $table): void {
            $table->dropColumn('domain');
        });

    }
};
