<?php

use App\Models\Addon;
use App\Models\Customize;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addon_customize', function (Blueprint $table) {
           $table->foreignIdFor(Addon::class);
           $table->foreignIdFor(Customize::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addon_customize');
    }
};
