<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\QuestionnaireOPU_001
 *
 * @property int $id
 * @property int $author
 * @property int $project
 * @property int $region
 * @property int $drop_inCenter
 * @property \Illuminate\Support\Carbon $date
 * @property string $encoding
 * @property int $type
 * @property int $interviewer
 * @property int $outreach
 * @property array $opu_001_0001
 * @property array $opu_001_0002
 * @property int $opu_001_0003_001
 * @property int $opu_001_0003_002
 * @property int $opu_001_0003_003
 * @property int $opu_001_0003_004
 * @property int $opu_001_0003_005
 * @property int $opu_001_0003_006
 * @property int $opu_001_0003_007
 * @property int $opu_001_0003_008
 * @property int $opu_001_0003_009
 * @property int $opu_001_0004_001
 * @property int $opu_001_0004_002
 * @property int $opu_001_0004_003
 * @property int $opu_001_0004_004
 * @property int $opu_001_0004_005
 * @property int $opu_001_0004_006
 * @property int $opu_001_0004_007
 * @property int $opu_001_0005_001
 * @property int $opu_001_0005_002
 * @property int $opu_001_0005_003
 * @property int $opu_001_0005_004
 * @property int $opu_001_0005_005
 * @property int $drug
 * @property int $meetings_0
 * @property int $meetings_1
 * @property int $Syringes2Get
 * @property int $Syringes2Want
 * @property string $Syringes2NotLike
 * @property string $Syringes2Take
 * @property int $Syringes5Get
 * @property int $Syringes5Want
 * @property string $Syringes5NotLike
 * @property string $Syringes5Take
 * @property int $Syringes10Get
 * @property int $Syringes10Want
 * @property string $Syringes10NotLike
 * @property string $Syringes10Take
 * @property int $DoilyGet
 * @property int $DoilyWant
 * @property string $DoilyNotLike
 * @property string $DoilyTake
 * @property int $CondomsMGet
 * @property int $CondomsMWant
 * @property string $CondomsMNotLike
 * @property string $CondomsMTake
 * @property int $CondomsWGet
 * @property int $CondomsWWant
 * @property string $CondomsWNotLike
 * @property string $CondomsWTake
 * @property int $PassHiv
 * @property int $PassFluorography
 * @property \Illuminate\Support\Carbon $date_hiv
 * @property \Illuminate\Support\Carbon $date_fluorography
 * @property int $OfferHiv
 * @property int $OfferFluorography
 * @property int $EscortHiv
 * @property int $EscortFluorography
 * @property string $ProcedureHiv
 * @property string $ProcedureFluorography
 * @property string $DignityHiv
 * @property string $DignityFluorography
 * @property string $LimitationsHiv
 * @property string $LimitationsFluorography
 * @property int $RegistrationHiv
 * @property string $TalkOutreach
 * @property string $services
 * @property array|null $files
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsMGet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsMNotLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsMTake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsMWant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsWGet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsWNotLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsWTake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCondomsWWant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDateFluorography($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDateHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDignityFluorography($value
 *         )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDignityHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDoilyGet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDoilyNotLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDoilyTake($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDoilyWant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDropInCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereDrug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereEncoding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereEscortFluorography($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereEscortHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereInterviewer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereLimitationsFluorography(
 *         $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereLimitationsHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereMeetings0($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereMeetings1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOfferFluorography($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOfferHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010001($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010002($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003001($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003002($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003003($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003004($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003005($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003006($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003007($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003008($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010003009($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010004001($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010004002($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010004003($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010004004($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010004005($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010004006($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010004007($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010005001($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010005002($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010005003($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010005004($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOpu0010005005($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereOutreach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 wherePassFluorography($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 wherePassHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereProcedureFluorography(
 *         $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereProcedureHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereRegistrationHiv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereServices($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes10Get($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes10NotLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes10Take($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes10Want($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes2Get($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes2NotLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes2Take($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes2Want($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes5Get($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes5NotLike($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes5Take($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereSyringes5Want($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereTalkOutreach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $scan
 * @method static \Illuminate\Database\Eloquent\Builder|\App\QuestionnaireOPU_001 whereScan($value)
 */
class QuestionnaireOPU_001 extends Model
{
    protected $fillable
        = [
            'author',
            'region',
            'drop_inCenter',
            'date',
            'encoding',
            'type',
            'interviewer',
            'outreach',
            'opu_001_0001',
            'opu_001_0002',
            'opu_001_0003_001',
            'opu_001_0003_002',
            'opu_001_0003_003',
            'opu_001_0003_004',
            'opu_001_0003_005',
            'opu_001_0003_006',
            'opu_001_0003_007',
            'opu_001_0003_008',
            'opu_001_0003_009',
            'opu_001_0004_001',
            'opu_001_0004_002',
            'opu_001_0004_003',
            'opu_001_0004_004',
            'opu_001_0004_005',
            'opu_001_0004_006',
            'opu_001_0004_007',
            'opu_001_0005_001',
            'opu_001_0005_002',
            'opu_001_0005_003',
            'opu_001_0005_004',
            'opu_001_0005_005',
            'drug',
            'meetings_0',
            'meetings_1',
            'Syringes2Get',
            'Syringes2Want',
            'Syringes2NotLike',
            'Syringes2Take',
            'Syringes5Get',
            'Syringes5Want',
            'Syringes5NotLike',
            'Syringes5Take',
            'Syringes10Get',
            'Syringes10Want',
            'Syringes10NotLike',
            'Syringes10Take',
            'DoilyGet',
            'DoilyWant',
            'DoilyNotLike',
            'DoilyTake',
            'CondomsMGet',
            'CondomsMWant',
            'CondomsMNotLike',
            'CondomsMTake',
            'CondomsWGet',
            'CondomsWWant',
            'CondomsWNotLike',
            'CondomsWTake',
            'PassHiv',
            'PassFluorography',
            'date_hiv',
            'date_fluorography',
            'OfferHiv',
            'OfferFluorography',
            'EscortHiv',
            'EscortFluorography',
            'ProcedureHiv',
            'ProcedureFluorography',
            'DignityHiv',
            'DignityFluorography',
            'LimitationsHiv',
            'LimitationsFluorography',
            'RegistrationHiv',
            'TalkOutreach',
            'services',
            'files',
            'scan',
            'status',
            'TbStatus',
            'TbDoc',
            'TbOut',
            'TbRisk'
        ];

    protected $casts
        = [
            'opu_001_0001' => 'array',
            'opu_001_0002' => 'array',
            'date' => 'date',
            'date_hiv' => 'date',
            'date_fluorography' => 'date',
            'files' => 'array',
        ];
}
