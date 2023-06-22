<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class ReportProject2 extends Model
    {
        protected $fillable
            = [
                'miovisitions',
                'region',
                'webinar',
                'seminar',
                'meetings',
                'report_month',
                'report',
                'date',
                'author',
                'editor',
                'complete'
            ];
    }
