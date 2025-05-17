<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    public function up()
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->text('reply')->nullable();
            $table->enum('status', ['pending', 'replied'])->default('pending');
            $table->timestamp('replied_at')->nullable();
            $table->boolean('is_public')->default(false)->comment('If marked true, can be shown as testimonial or public feedback');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback');
    }
}
