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
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->json('attribute_changes')->nullable()->after('causer_id');
            $table->dropColumn('batch_uuid');
        });

        DB::table(config('activitylog.table_name'))
            ->where(function ($query) {
                $query->whereNotNull('properties->attributes')
                    ->orWhereNotNull('properties->old');
            })
            ->eachById(function ($row) {
                $properties = json_decode($row->properties, true);
                $changes = array_intersect_key($properties, array_flip(['attributes', 'old']));
                $remaining = array_diff_key($properties, array_flip(['attributes', 'old']));

                DB::table(config('activitylog.table_name'))->where('id', $row->id)->update([
                    'attribute_changes' => empty($changes) ? null : json_encode($changes),
                    'properties' => empty($remaining) ? null : json_encode($remaining),
                ]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->dropColumn('attribute_changes');
            $table->uuid('batch_uuid')->nullable()->after('properties');
        });
    }
};
