<?php

use App\Enum\EventTypeEnum;
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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('description');
            $table->dateTime('datetime');
            $table->string('location');
            $table->enum('type', [
                EventTypeEnum::MeetingWithAnExpert->value,
                EventTypeEnum::QuestionAnswer->value,
                EventTypeEnum::Webinar->value,
                EventTypeEnum::Conference->value
            ])->default(EventTypeEnum::QuestionAnswer->value);//тут enum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
