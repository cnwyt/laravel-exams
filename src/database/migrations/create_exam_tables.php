<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamTables extends Migration
{
    /**
     * Run the migrations.
     *
     * 试卷(papers):
     * (试卷描述)
     * -- Section A第一部分 单选题
     *       题目1. xxxxxxxxx (分数)
     *         选项A: xxx  选项B: xxx  选项C: xxx  选项D: xxx  选项E: xxx
     *       题目2. xxxxxxxxx (分数)
     *         选项A: xxx  选项B: xxx  选项C: xxx  选项D: xxx  选项E: xxx
     * -- 第二部分
     *    阅读理解1：
     *       xxxxxxxxx
     *       题目1： xxxx
     *       选项A: xxx  选项B: xxx  选项C: xxx  选项D: xxx  选项E: xxx
     *
     * -- 第三部分 Writing
     *    Part A
     *    Part B
     *
     * @return void
     */
    public function up()
    {
        // 试卷表
        Schema::create('exam_papers', function (Blueprint $table) {
            $table->increments('id')->comment('自增,试卷id');
            $table->unsignedInteger('user_id')->default(0);
            $table->string('subject', 255)->comment('课程名称');
            $table->string('name', 255)->comment('试卷名称');
            $table->string('summary', 255)->comment('试卷概述');
            $table->text('description')->comment('试卷描述');
            $table->unsignedTinyInteger('status')->default(1)->comment('[STATUS]1:default,0:hide,-1:delete');
            $table->unsignedInteger('join_num')->default(0)->comment('访问了');
            $table->unsignedInteger('view_num')->default(0)->comment('访问了');
            $table->string('remark', 255)->default('备注');
            $table->timestamps();
        });

        // 试卷章节表(大题模块)
        Schema::create('exam_paper_sections', function (Blueprint $table) {
            $table->increments('id')->comment('自增,试卷章节id');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('paper_id')->default(0);
            $table->text('section_desc')->comment('');
            $table->unsignedInteger('thumb_up')->default(0)->comment('thumb_up number');
            $table->unsignedInteger('thumb_down')->default(0)->comment('thumb_down number');
            $table->tinyInteger('status')->default(1)->comment('[STATUS]1:default,0:hide,-1:delete');
            $table->string('remark', 255)->default('');
            $table->timestamps();
        });

        // 题目表
        // 大题数
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->increments('id')->comment('自增,试卷题目id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级题目id，默认为0');
            $table->unsignedInteger('question_type')->default(0)->comment('试卷类型:1单选，2多选，3');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('paper_id')->default(0)->comment('试卷id');
            $table->unsignedInteger('section_id')->default(0)->comment('试卷的章节id');
            $table->mediumText('content')->comment('thumb_up number');
            $table->unsignedInteger('thumb_up')->default(0)->comment('thumb_up number');
            $table->unsignedInteger('thumb_down')->default(0)->comment('thumb_down number');
            $table->tinyInteger('status')->default(1)->comment('[STATUS]1:default,0:hide,-1:delete');
            $table->string('remark', 255)->default('');
            $table->timestamps();
        });

        // 大题数
        Schema::create('exam_question_options', function (Blueprint $table) {
            $table->increments('id')->comment('自增,试卷题目id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级题目id，默认为0');
            $table->unsignedInteger('question_type')->default(0)->comment('试卷类型:1单选，2多选，3');
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('paper_id')->default(0)->comment('试卷id');
            $table->unsignedInteger('section_id')->default(0)->comment('试卷的章节id');
            $table->mediumText('content')->comment('thumb_up number');
            $table->unsignedInteger('thumb_up')->default(0)->comment('thumb_up number');
            $table->unsignedInteger('thumb_down')->default(0)->comment('thumb_down number');
            $table->tinyInteger('status')->default(1)->comment('[STATUS]1:default,0:hide,-1:delete');
            $table->string('remark', 255)->default('');
            $table->timestamps();
        });

        // 试卷文本数据
        Schema::create('exam_texts', function (Blueprint $table) {
            $table->increments('id')->comment('自增,题目文本id');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级题目id，默认为0');
            $table->unsignedInteger('user_id')->default(0);
            $table->mediumText('content')->comment('thumb_up number');
            $table->unsignedTinyInteger('status')->default(0)->comment('[STATUS]0新建,1:审核通过,2审核失败,3待审核/暂停显示,4已删除');
            $table->string('remark', 255)->default('');
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
        Schema::dropIfExists('exam_papers');
        Schema::dropIfExists('exam_sections');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exam_texts');
    }
}
