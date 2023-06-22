<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTuberculosisOPTsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tuberculosis_o_p_ts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('author');
            $table->integer('region');
            $table->date('date');
            $table->integer('place');
            $table->text('place_other')->nullable();
            $table->text('s_name');
            $table->text('f_name');
            $table->date('birthday');
            $table->text('encoding');
            $table->text('diagnosis');
            $table->date('date_tb_start');
            $table->date('date_tb_end');
            $table->date('date_release');
            $table->text('phone_home')->nullable();
            $table->text('phone_mobile')->nullable();
            $table->text('phone_alt')->nullable();
            $table->text('state');
            $table->text('address');
            $table->integer('have_home');
            $table->text('problem_home');
            $table->integer('help_home');
            $table->integer('problem_registration');
            $table->integer('problem_state');
            $table->integer('help_state');
            $table->integer('status_marital');
            $table->json('status_passport');
            $table->json('problems');
            $table->integer('help_problems');
            $table->text('type_problems');
            $table->json('childrens');
            $table->integer('statement_1');
            $table->integer('statement_2');
            $table->integer('statement_3');
            $table->integer('statement_4');
            $table->integer('statement_5');
            $table->integer('statement_6');
            $table->integer('statement_7');
            $table->integer('statement_8');
            $table->integer('statement_9');
            $table->integer('help_statement');
            $table->text('type_statement');
            $table->integer('education');
            $table->text('education_before');
            $table->text('profession_before');
            $table->json('education_alt');
            $table->integer('want_education');
            $table->text('want_education_name');
            $table->integer('relationships');
            $table->integer('have_family');
            $table->integer('have_family_problem');
            $table->integer('type_family_problem');
            $table->integer('hiv');
            $table->integer('help_hiv');
            $table->text('type_hiv');
            $table->integer('addiction');
            $table->integer('help_addiction');
            $table->integer('help_medical');
            $table->text('type_medical');
            $table->integer('help_disability');
            $table->text('type_disability');
            $table->json('emotions');
            $table->integer('job');
            $table->integer('return_job');
            $table->text('profession_jail');
            $table->integer('status_job');
            $table->integer('plans');
            $table->integer('lawyer');
            $table->integer('psychologist');
            $table->integer('social');
            $table->text('other_help');
            $table->text('other_notes');
            $table->json('recommendations');
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
        Schema::dropIfExists('tuberculosis_o_p_ts');
    }
}
