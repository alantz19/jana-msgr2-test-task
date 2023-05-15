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
        Schema::create('sms_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
        Schema::create('sms_campaign_sends', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campaign_id')->nullable();
            $table->uuid('country_id')->nullable();
            $table->json('send_vars')->nullable();
            $table->integer('status')->nullable();
            $table->timestamp('date_created')->useCurrent();
        });
        Schema::create('sms_campaign_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('caller');
            $table->string('text');
            $table->json('meta');
            $table->timestamps();
        });
        Schema::create('sms_campaign_texts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('campaign_id')->nullable();
            $table->string('text')->nullable();
            $table->boolean('is_active')->nullable();
            $table->integer('parts')->nullable();
            $table->timestamps();
        });

        Schema::create('offers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('team_id');
            $table->string('name')->nullable();
            $table->string('url');
            $table->integer('profit')->nullable();
            $table->timestamp('date_created')->useCurrent();
        });

        Schema::create('offer_campaigns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('offer_id')->references('id')->on('offers');
            $table->foreignUuid('campaign_id')->references('id')->on('sms_campaigns');
            $table->boolean('is_active')->default(true);
            $table->timestamp('date_created')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns_structure');
    }
};
