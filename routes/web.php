<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


    Route::get('storage/{filename}', function ($filename)
    {
        dd($filename);

        $path = storage_path('public/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });

    Route::post('process', function (Request $request) {
        // cache the file
        $file = $request->file('photo');

        // generate a new filename. getClientOriginalExtension() for the file extension
        $filename = 'profile-photo-' . time() . '.' . $file->getClientOriginalExtension();

        // save to storage/app/photos as the new $filename
        $path = $file->storeAs('photos', $filename);

        dd($path);
    });

Route::get('setlocale/{lang}', function ($lang) {
    $referer = Redirect::back()->getTargetUrl(); //URL предыдущей страницы
    $parse_url = parse_url($referer, PHP_URL_PATH); //URI предыдущей страницы

    //разбиваем на массив по разделителю
    $segments = explode('/', $parse_url);

    //Если URL (где нажали на переключение языка) содержал корректную метку языка
    if (in_array($segments[1], App\Http\Middleware\LocaleMiddleware::$languages)) {
        unset($segments[1]); //удаляем метку
    }

    //Добавляем метку языка в URL (если выбран не язык по-умолчанию)
    if ($lang != App\Http\Middleware\LocaleMiddleware::$mainLanguage){
        array_splice($segments, 1, 0, $lang);
    }

    //формируем полный URL
    $url = Request::root().implode("/", $segments);

    //если были еще GET-параметры - добавляем их
    if(parse_url($referer, PHP_URL_QUERY)){
        $url = $url.'?'. parse_url($referer, PHP_URL_QUERY);
    }
    return redirect($url); //Перенаправляем назад на ту же страницу
})->name('setlocale');


Route::group(['prefix' => App\Http\Middleware\LocaleMiddleware::getLocale()], function () {

    Auth::routes();

    Route::get('/testtest', 'TestController@test3');

    Route::get('/download/client/comments/{startDate}/{endDate}/{region}', 'DownloadController@CommentsClient')->name('DownloadCommentsClient');
    Route::get('/download/client/stats/{startDate}/{endDate}/{region}', 'DownloadController@StatsClient')->name('DownloadStatsClient');
    Route::get('/download/outreach/stats/{startDate}/{endDate}/{region}', 'DownloadController@StatsOutreach')->name('DownloadStatsOutreach');

    Route::get('/', 'HomeController@index')->name('index');

    Route::get('/file-manager', 'FileManagerController@index')->name('file-manager');
    Route::get('/faq', 'FaqController@index')->name('faq');

    Route::prefix('mio-visitions')->group(function () {
        Route::get('/registration', 'MiOVisitionsController@index')->name('registration-MiOVisitions');
        Route::get('/view', 'MiOVisitionsController@view')->name('view-MiOVisitions');
        Route::get('/get/{id}', 'MiOVisitionsController@get')->name('get-MiOVisitions');
        Route::get('/delete/{id}', 'MiOVisitionsController@delete')->name('delete-MiOVisitions');
        Route::post('/update/{id}', 'MiOVisitionsController@update')->name('update-MiOVisitions');
        Route::post('/set', 'MiOVisitionsController@set')->name('set-MiOVisitions');
    });

    Route::prefix('indicators')->group(function () {
        Route::get('/', 'IndicatorsController@index')->name('indicators');
        Route::post('/save', 'IndicatorsController@save')->name('indicatorsSave');
        Route::get('/statistic', 'IndicatorsController@statistic')->name('indicatorsStatistic');
        Route::get('/download', 'IndicatorsController@download')->name('download');
    });

    Route::prefix('questionnaires')->group(function () {
        Route::get('/{encoding}', 'QuestionnairesController@index')->name('questionnaires');
        Route::get('GF-TB/{encoding}', 'QuestionnairesController@indexTb')->name('questionnaires_tb');
        Route::get('/{encoding}/view', 'QuestionnairesController@view')->name('view-questionnaires');
        Route::get('/{encoding}/get/{id}', 'QuestionnairesController@get')->name('get-questionnaires');
        Route::get('/{encoding}/delete/{id}', 'QuestionnairesController@delete')->name('delete-questionnaires');
        Route::post('/{encoding}/update/{id}', 'QuestionnairesController@update')->name('update-questionnaires');
        Route::post('/{encoding}/set', 'QuestionnairesController@set')->name('set-questionnaires');
        Route::get('/custom/OPU-001', 'QuestionnairesController@opu_001')->name('questionnaire-opu_001');
        Route::get('/custom/OPU-001/get/{id}', 'QuestionnairesController@get_opu_001')->name('questionnaire-get-opu_001');
        Route::get('/custom/OPU-001/view', 'QuestionnairesController@view_opu_001')->name('questionnaire-view-opu_001');
        Route::get('/custom/OPU-001/delete/{id}', 'QuestionnairesController@delete_opu_001')->name('questionnaire-delete-opu_001');
        Route::post('/custom/OPU-001/update/{id}', 'QuestionnairesController@update_opu_001')->name('questionnaire-update-opu_001');
        Route::post('/custom/OPU-001/set', 'QuestionnairesController@set_opu_001')->name('questionnaire-set-opu_001');
    });

    Route::prefix('library')->group(function () {
        Route::prefix('GF-HIV')->group(function () {
            Route::prefix('registration')->group(function () {
                Route::prefix('outreach')->group(function () {
                    Route::get('/', 'RegistrationOutreachController@index')->name('registration-outreach');
                    Route::post('/set', 'RegistrationOutreachController@set')->name('registration-outreach-set');
                    Route::get('/view', 'RegistrationOutreachController@view')->name('registration-outreach-view');
                    Route::get('/dismiss/{id}', 'RegistrationOutreachController@dismiss')->name('registration-outreach-dismiss');
                    Route::get('/recruit/{id}', 'RegistrationOutreachController@recruit')->name('registration-outreach-recruit');
                });
                Route::get('/questionnaires/add', 'QuestionnairesController@createQuestionnaire')->name('registration-questionnaire-view');
                Route::post('/questionnaires/save', 'QuestionnairesController@createQuestionnaireSave')->name('registration-questionnaire-save');
            });
        });

        Route::prefix('GF-TB')->group(function () {
            Route::prefix('registration')->group(function () {
                Route::prefix('client')->group(function () {
                    Route::get('/', 'RegistrationClientController@index')->name('registration-client');
                    Route::post('/set', 'RegistrationClientController@set')->name('registration-client-set');
                });
            });
        });
    });

    Route::prefix('assignment')->group(function () {
        Route::get('/', 'AssignmentsController@index')->name('assignments');
        Route::get('/{project}', 'AssignmentsController@Assignments')->name('assignmentsProject');
        Route::post('/create', 'AssignmentsController@create')->name('assignments-create');
        Route::post('/responsibility/get/{project}', 'AssignmentsController@getResponsibility')->name('getResponsibility');
        Route::get('/getAll/{project}', 'AssignmentsController@getAll')->name('assignments-getAll');
        Route::get('/{id}/get', 'AssignmentsController@get')->name('assignments-get');
        Route::get('/{id}/activation', 'AssignmentsController@activation')->name('assignments-activation');
        Route::post('/{id}/save', 'AssignmentsController@editSave')->name('assignments-editSave');
        Route::get('/{id}/edit', 'AssignmentsController@edit')->name('assignments-edit');
        Route::prefix('download')->group(function () {
            Route::get('/file/{id}/{key}', 'DownloadController@assignmentsDownload')->name('assignments-download');
        });
    });

    Route::prefix('activity')->group(function () {
        Route::get('/', 'ActivityController@index')->name('activity');
        Route::get('/users/{project}', 'ActivityController@Users')->name('activityUsers');
        Route::post('/add', 'ActivityController@add')->name('activityAdd');
        Route::get('/user/{id}/{date}/assignment/{assignment}', 'ActivityController@redirectAssignment')->name('activityRedirectAssignment');
        Route::get('/user/{id}/{position}/{date}', 'ActivityController@redirectUser')->name('activityRedirectUser');
        Route::get('/getAll/user/{id}', 'ProfileController@getAllActivities')->name('getAllActivities');
//        Route::get('/assignments', 'ActivityController@redirectAssignment')->name('activityRedirectAssignment');
//        Route::post('/temp/add', 'ActivityController@addTemp')->name('activityAddTemp');
//        Route::get('/temp/registration', 'ActivityController@tempRegistration')->name('tempRegistration');
//        Route::get('/temp/registration/{user}', 'ActivityController@tempRegistrationPosition')->name('tempRegistrationPosition');
//        Route::get('/temp/registration/{user}/{position}', 'ActivityController@tempRegistrationUser')->name('tempRegistrationUser');
    });

    Route::prefix('user')->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('{id}/edit', 'ProfileController@edit_index')->name('edit-profile');
            Route::get('{id}/activity', 'ProfileController@activity')->name('activity-profile');
            Route::get('{id}/{project}/activity/edit', 'ProfileController@activityEdit')->name('activity-profile-edit');
            Route::post('{id}/{project}/activity/edit/save', 'ProfileController@activityEditSave')->name('activity-profile-editSave');
            Route::get('{id}/{project}/activity/delete', 'ProfileController@activityDelete')->name('activity-profile-delete');
            Route::get('{id}/activity/supervision', 'ProfileController@activitySupervision')->name('activity-profile-supervision');
            Route::get('{id}/activity/notsupervision', 'ProfileController@activityNotVerification')->name('activity-profile-notVerification');
            Route::get('{id}/assignment/supervision', 'ProfileController@assignmentSupervision')->name('activity-assignment-supervision');
            Route::get('{id}/activity/clone', 'ProfileController@activityClone')->name('activity-profile-clone');
            Route::post('{id}/password', 'ProfileController@edit_password')->name('edit-password');
        });
    });

    Route::prefix('statistics')->group(function () {
        Route::prefix('GF-HIV')->group(function () {
            Route::prefix('clients')->group(function () {
                Route::get('/{region}', 'StatisticsController@clients')->name('StatisticsClients');
            });
            Route::prefix('outreaches')->group(function () {
                Route::get('/{questionnaire}/{region}', 'StatisticsController@outreaches')->name('StatisticsOutreaches');
            });
            Route::get('/general', 'StatisticsController@statistics')->name('StatisticsAll');
            Route::get('/outreaches', 'StatisticsController@outreachesAll')->name('outreachesAll');
            Route::get('/clients', 'StatisticsController@outreachesClients')->name('outreachesClients');
            Route::get('/mioVisitions', 'StatisticsController@mioVisitions')->name('mioVisitions');
            Route::get('/actions', 'StatisticsController@actions')->name('actions');
            Route::get('/report/{year}/{month}', 'StatisticsController@report')->name('report');
            Route::get('/report/{region}/{year}/{month}/block', 'StatisticsController@reportBlock')->name('reportBlock');
            Route::get('{project}/reportAudit/{year}/{month}', 'StatisticsController@report4Audit')->name('report4Audit');
            Route::get('/year/report/{year}/{region}', 'StatisticsController@yearReport')->name('yearReport');
            Route::get('/program/report/{start}/{end}', 'StatisticsController@programReport')->name('programReport');
            Route::get('/mioVisitions/list', 'StatisticsController@mioVisitionsList')->name('mioVisitionsList');
            Route::get('/download/{document}/{region}/{year}/{month}','UploaderController@zip')->name('downloadZip');
        });
        Route::prefix('GF-TB')->group(function () {
            Route::prefix('clients')->group(function () {
                Route::get('/APL/{region}', 'StatisticsController@clients_APL')->name('StatisticsClientsAPL');
                Route::get('/OPZ/{region}', 'StatisticsController@clients_OPZ')->name('StatisticsClientsOPZ');
                Route::get('/OPT/{region}', 'StatisticsController@clients_OPT')->name('StatisticsClientsOPT');
            });
        });
        Route::get('/activities', 'ActivityStatisticsController@index')->name('ActivityStatistics');
        Route::get('/activities/users/{project}', 'ActivityStatisticsController@users')->name('ActivityUsers');
        Route::get('/activities/user/{id}', 'ActivityStatisticsController@user')->name('ActivityUser');
        Route::get('/activities/user/{id}/dump', 'ActivityStatisticsController@dump')->name('ActivityDump');
        Route::get('/activities/{project}', 'ActivityStatisticsController@project')->name('ActivityStatisticsProject');
        Route::get('/activities/{project}/clients', 'ActivityStatisticsController@clients')->name('ActivityStatisticsClients');
        Route::get('/activity/{id}', 'ActivityStatisticsController@activity')->name('ActivityStatistic');
        Route::get('/activity/verification/{user}/{project}/{start}/{end}', 'ActivityStatisticsController@verification')->name('ActivityVerification');
    });

    Route::prefix('test')->group(function () {
        Route::get('/{id}', 'TestController@test1');
    });

    Route::prefix('ssc')->group(function () {
        Route::get('/client', 'SocialSupportSenterClientsController@index')->name('SocialSupportSenterClients');
        Route::prefix('registration')->group(function () {
            Route::get('/client', 'SocialSupportSenterClientsController@registration')->name('SocialSupportSenterRegistrationClients');
        });
    });

    Route::prefix('upload')->group(function () {
        Route::post('/{type}/{id}', 'UploaderController@documents')->name('upload-documents');
        Route::post('/mio', 'UploaderController@mio_visitions')->name('upload-files-MiOVisitions');
        Route::post('/assignment', 'UploaderController@assignment')->name('upload-files-assignment');
    });

    Route::prefix('psych')->group(function () {
        Route::prefix('buss-durkee')->group(function () {
            Route::get('/view', 'PsychController@BussDurkee')->name('BussDurkeeView');
            Route::post('/save', 'PsychController@BussDurkeeSave')->name('BussDurkeeSave');
            Route::get('/result/{id}', 'PsychController@BussDurkeeResult')->name('BussDurkeeResult');
            Route::get('/list', 'PsychController@BussDurkeeList')->name('BussDurkeeList');
        });
        Route::prefix('rozenberg')->group(function () {
            Route::get('/view', 'PsychController@Rozenberg')->name('RozenbergView');
            Route::post('/save', 'PsychController@RozenbergSave')->name('RozenbergSave');
            Route::get('/result/{id}', 'PsychController@RozenbergResult')->name('RozenbergResult');
            Route::get('/list', 'PsychController@RozenbergList')->name('RozenbergList');
        });
    });

    Route::prefix('tuberculosis')->group(function () {
        Route::prefix('OPT')->group(function () {
            Route::get('/', 'Questionnaire_OPTController@index')->name('QuestionnaireOPTIndex');
            Route::post('/save', 'Questionnaire_OPTController@save')->name('QuestionnaireOPTSave');
            Route::get('/list', 'Questionnaire_OPTController@list')->name('QuestionnaireOPTList');
            Route::get('/view/{id}', 'Questionnaire_OPTController@view')->name('QuestionnaireOPTView');
            Route::post('/delete/{id}', 'Questionnaire_OPTController@delete')->name('QuestionnaireOPTDelete');
            Route::post('/update/{id}', 'Questionnaire_OPTController@update')->name('QuestionnaireOPTUpdate');
        });
    });


    Route::prefix('settings')->group(function () {
        Route::get('/', 'SettingsController@index')->name('settings');
        Route::prefix('organizations')->group(function () {
            Route::get('/', 'OrganizationController@index')->name('organizations');
            Route::post('/add', 'OrganizationController@add')->name('organizationsSet');
        });
        Route::prefix('projects')->group(function () {
            Route::get('/', 'ProjectController@index')->name('projects');
            Route::get('/logFrame', 'ProjectController@logframe')->name('projectsLogframe');
            Route::post('/getStaff', 'ProjectController@getStaff')->name('getStaff');
            Route::post('/createStaffGetForm', 'ProjectController@createStaffGetForm')->name('createStaffGetForm');
        });
    });

    Route::post('/telegram/support/send', 'TelegramController@SendMessage');

    Route::get('/test', 'StatisticsController@report');
    Route::get('/test1', 'TestController@test1');
    Route::get('/test2', function (){
        return view('test2');
    });


    Route::prefix('ajax')->group(function () {
        Route::get('/getAssignments', 'AjaxController@activityGetAssignments')->name('activityGetAssignments');
        Route::get('/getUsers', 'AjaxController@activityGetUsers')->name('activityGetUsers');
        Route::post('/save', 'AjaxController@activitySave')->name('activitySave');
        Route::post('/report/save', 'AjaxController@reportSave')->name('reportSave');
    });

    Route::get('/edit', function (){
        return view('pages.statistics.report');
    });

    Route::get('/project/swap/{project}', function ($project){
        \App\Position::where('id', Auth::user()->position)
            ->update(['project' => $project]);
        return redirect()->back();
    })->name('projectSwap');

});
