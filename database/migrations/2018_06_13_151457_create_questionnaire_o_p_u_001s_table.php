<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireOPU001sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_o_p_u_001s', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->integer('region');
            $table->integer('drop_inCenter');
            $table->date('date');
            $table->char('encoding', '7');
            $table->integer('type');
            $table->integer('interviewer');
            $table->integer('outreach');
            $table->json('opu_001_0001');
            $table->json('opu_001_0002');
            $table->integer('opu_001_0003_001');
            $table->integer('opu_001_0003_002');
            $table->integer('opu_001_0003_003');
            $table->integer('opu_001_0003_004');
            $table->integer('opu_001_0003_005');
            $table->integer('opu_001_0003_006');
            $table->integer('opu_001_0003_007');
            $table->integer('opu_001_0003_008');
            $table->integer('opu_001_0003_009');
            $table->integer('opu_001_0004_001');
            $table->integer('opu_001_0004_002');
            $table->integer('opu_001_0004_003');
            $table->integer('opu_001_0004_004');
            $table->integer('opu_001_0004_005');
            $table->integer('opu_001_0004_006');
            $table->integer('opu_001_0004_007');
            $table->integer('opu_001_0005_001');
            $table->integer('opu_001_0005_002');
            $table->integer('opu_001_0005_003');
            $table->integer('opu_001_0005_004');
            $table->integer('opu_001_0005_005');
            $table->integer('drug');
            $table->integer('meetings_0');
            $table->integer('meetings_1');
            $table->integer('Syringes2Get');
            $table->integer('Syringes2Want');
            $table->text('Syringes2NotLike');
            $table->text('Syringes2Take');
            $table->integer('Syringes5Get');
            $table->integer('Syringes5Want');
            $table->text('Syringes5NotLike');
            $table->text('Syringes5Take');
            $table->integer('Syringes10Get');
            $table->integer('Syringes10Want');
            $table->text('Syringes10NotLike');
            $table->text('Syringes10Take');
            $table->integer('DoilyGet');
            $table->integer('DoilyWant');
            $table->text('DoilyNotLike');
            $table->text('DoilyTake');
            $table->integer('CondomsMGet');
            $table->integer('CondomsMWant');
            $table->text('CondomsMNotLike');
            $table->text('CondomsMTake');
            $table->integer('CondomsWGet');
            $table->integer('CondomsWWant');
            $table->text('CondomsWNotLike');
            $table->text('CondomsWTake');
            $table->integer('PassHiv');
            $table->integer('PassFluorography');
            $table->date('date_hiv');
            $table->date('date_fluorography');
            $table->integer('OfferHiv');
            $table->integer('OfferFluorography');
            $table->integer('EscortHiv');
            $table->integer('EscortFluorography');
            $table->text('ProcedureHiv');
            $table->text('ProcedureFluorography');
            $table->text('DignityHiv');
            $table->text('DignityFluorography');
            $table->text('LimitationsHiv');
            $table->text('LimitationsFluorography');
            $table->integer('RegistrationHiv');
            $table->text('TalkOutreach');
            $table->text('services');
            $table->json('files')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_o_p_u_001s');
    }
}
