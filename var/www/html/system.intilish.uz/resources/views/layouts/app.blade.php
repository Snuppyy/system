{{ Auth::user()->role !== 1 ? Debugbar::disable() : Debugbar::enable() }}
        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Meta -->
    <meta name="description" content="Электронная информационная система организации ННО РИОЦ 'INTILISH'">
    <meta name="author" content="Dee^xD">


    <title>{{ config('app.name') }}</title>

    <!-- Vendor css -->
    <link href="{{ asset('lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/Ionicons/css/ionicons.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/animate.css/animate.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- Custom CSS -->
@yield('styles')

<!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/shamcey.css') }}">
    <link href="{{ asset('lib/fullcalendar/fullcalendar.css') }}" rel="stylesheet">

</head>

<body class="{{ active(['report', 'yearReport', 'report4Audit'], 'hide-left') }} ">
@php
    $questionnaires = \Illuminate\Support\Facades\DB::table('questionnaires')->whereIn('project', [2])->select('encoding', 'name_'.app()->getLocale().' as name', 'id', 'author')->where('questionnaires.status', '>=', 1)->get();
    $questionnairesTb = \Illuminate\Support\Facades\DB::table('questionnaires')->whereIn('project', [4])->select('encoding', 'name_'.app()->getLocale().' as name', 'id', 'author')->where('questionnaires.status', '>=', 1)->get();
    $page = explode('/',url()->current());
    $page = last($page);
@endphp
<div class="sh-logopanel">
    <a href="{{ route('index') }}" class="sh-logo-text">INTILISH v3.1</a>
    <a id="navicon" href="" class="sh-navicon d-none d-xl-block"><i class="icon ion-navicon"></i></a>
    <a id="naviconMobile" href="" class="sh-navicon d-xl-none"><i class="icon ion-navicon"></i></a>
</div><!-- sh-logopanel -->

<div class="sh-sideleft-menu">
    <ul class="nav">

        <li class="nav-item">
            <a href="{{ route('index') }}" class="nav-link {{ active('index') }}">
                <i class="icon ion-ios-home-outline"></i>
                <span>Главная</span>
            </a>
        </li><!-- nav-item -->

        @if(Auth::user()->role <= 3 || array_key_exists(4, Auth::user()->positions()))
            {{--            <label class="sh-sidebar-label">ЦСП</label>--}}
            {{--            <li class="nav-item">--}}
            {{--                <a href="{{ route('SocialSupportSenterClients') }}"--}}
            {{--                   class="nav-link {{ active('SocialSupportSenterClients') }}">--}}
            {{--                    <i class="icon ion-person-stalker"></i>--}}
            {{--                    <span>Клиенты</span>--}}
            {{--                </a>--}}
            {{--            </li><!-- nav-item -->--}}

            {{--            <li class="nav-item">--}}
            {{--                <a href="" class="nav-link with-sub">--}}
            {{--                    <i class="icon ion-ios-book-outline"></i>--}}
            {{--                    <span>Библиотека</span>--}}
            {{--                </a>--}}
            {{--                <ul class="nav-sub">--}}
            {{--                    <li class="nav-item"><a href="{{ route('SocialSupportSenterRegistrationClients') }}"--}}
            {{--                                            class="nav-link {{ active('SocialSupportSenterRegistrationClients') }}">Клиенты</a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}
            {{--            </li><!-- nav-item -->--}}

            <label class="sh-sidebar-label">Психология</label>
            <li class="nav-item">
                <a href="" class="nav-link with-sub">
                    <i class="icon ion-ios-help-outline"></i>
                    <span>Опросники</span>
                </a>
                <ul class="nav-sub">
                    <li class="nav-item"><a href="{{ route('BussDurkeeView') }}"
                                            class="nav-link {{ active('BussDurkeeView') }}">Опросник Басса-Дарки</a>
                    </li>
                    <li class="nav-item"><a href="{{ route('RozenbergView') }}"
                                            class="nav-link {{ active('RozenbergView') }}">Опросник Розенберга</a>
                    </li>
                </ul>
            </li><!-- nav-item -->

            <li class="nav-item">
                <a href="" class="nav-link with-sub">
                    <i class="icon ion-stats-bars"></i>
                    <span>Сводка</span>
                </a>
                <ul class="nav-sub">
                    <li class="nav-item">
                        <a href="{{ route('BussDurkeeList') }}"
                           class="nav-link ">Результаты опросника Басса-Дарки</a>
                    </li>
                </ul>
            </li><!-- nav-item -->

            <label class="sh-sidebar-label">ГФ-ТБ</label>
            <li class="nav-item">
                <a href="" class="nav-link with-sub">
                    <i class="icon ion-ios-help-outline"></i>
                    <span>Опросники</span>
                </a>
                <ul class="nav-sub">
                    <li class="nav-item"><a href="{{ route('QuestionnaireOPTIndex') }}"
                                            class="nav-link {{ active('QuestionnaireOPTIndex') }}">Анкета первичной
                            оценки больных туберкулезом в местах исполнения наказания</a>
                    </li>
                    @foreach($questionnairesTb as $questionnaire)
                        <li class="nav-item">
                            <a href="{{ route('questionnaires_tb',['encoding' => $questionnaire->encoding]) }}"
                               class="nav-link {{ $page === $questionnaire->encoding ? ' active' : '' }}">
                                {{ $questionnaire->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="" class="nav-link with-sub">
                    <i class="icon ion-ios-book-outline"></i>
                    <span>Библиотека</span>
                </a>
                <ul class="nav-sub">
                    <li class="nav-item"><a href="{{ route('registration-client') }}"
                                            class="nav-link {{ active('registration-client') }}">Регистрация
                            клиента</a></li>
                </ul>
            </li><!-- nav-item -->
            <li class="nav-item">
                <a href="" class="nav-link with-sub">
                    <i class="icon ion-stats-bars"></i>
                    <span>Сводка</span>
                </a>
                <ul class="nav-sub">
                    <li class="nav-item">
                        <a href="{{ route('StatisticsClientsAPL', ['region' => 'all']) }}"
                           class="nav-link ">Приверженность</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('StatisticsClientsOPZ', ['region' => 'all']) }}"
                           class="nav-link ">Проверка уровня знаний по ТБ</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('StatisticsClientsOPT', ['region' => 'all']) }}"
                           class="nav-link ">Первичная оценка</a>
                    </li>
                </ul>
            </li><!-- nav-item -->
        @endif

        @if(Auth::user()->role <= 3 || array_key_exists(2, Auth::user()->positions()))
            <label class="sh-sidebar-label">ГФ-ВИЧ</label>
            @if(Auth::user()->role !== 10)
                <li class="nav-item">
                    <a href="" class="nav-link with-sub">
                        <i class="icon ion-ios-briefcase-outline"></i>
                        <span>Мониторинг мест продажи</span>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-item"><a href="{{ route('registration-MiOVisitions') }}"
                                                class="nav-link {{ active('registration-MiOVisitions') }}">Регистрация
                                визита</a></li>
                    </ul>
                </li><!-- nav-item -->

                <li class="nav-item">
                    <a href="" class="nav-link with-sub">
                        <i class="icon ion-ios-help-outline"></i>
                        <span>Опросники</span>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-item"><a href="{{ route('questionnaire-opu_001') }}"
                                                class="nav-link {{ active('questionnaire-opu_001') }}">Оценка
                                предоставленных
                                услуг</a></li>
                        @foreach($questionnaires as $questionnaire)
                            @if($questionnaire->encoding !== 'KPK-001' && $questionnaire->encoding !== 'PPN-001' && $questionnaire->encoding !== 'ATB-001')
                                @if($questionnaire->author == 1 || $questionnaire->author == auth()->user()->id)
                                    <li class="nav-item">
                                        <a href="{{ route('questionnaires',['encoding' => $questionnaire->encoding]) }}"
                                           class="nav-link {{ $page === $questionnaire->encoding ? ' active' : '' }}">
                                            {{ $questionnaire->name }}
                                        </a>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </li><!-- nav-item -->
                <li class="nav-item">
                    <a href="" class="nav-link with-sub">
                        <i class="icon ion-filing"></i>
                        <span>База данных</span>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-item"><a href="{{ route('view-MiOVisitions') }}"
                                                class="nav-link {{ active('view-MiOVisitions') }}">Мониторинговые
                                визиты</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('questionnaire-view-opu_001') }}"
                                                class="nav-link {{ active('questionnaire-view-opu_001') }}">Опросники
                                клиентов</a></li>
                        <li class="nav-item"><a href="{{ route('view-questionnaires', ['encoding' => 'all']) }}"
                                                class="nav-link {{ active('view-questionnaires') }}">Опросники аутрич
                                сотрудников</a></li>
                    </ul>
                </li><!-- nav-item -->

                <li class="nav-item">
                    <a href="" class="nav-link with-sub">
                        <i class="icon ion-ios-book-outline"></i>
                        <span>Библиотека</span>
                    </a>
                    <ul class="nav-sub">
                        <li class="nav-item"><a href="{{ route('registration-outreach-view') }}"
                                                class="nav-link {{ active('registration-outreach-view') }}">Аутрич-сотрудники
                                / ассистенты</a></li>
                        <li class="nav-item"><a href="{{ route('registration-outreach') }}"
                                                class="nav-link {{ active('registration-outreach') }}">Регистрация
                                аутрич-сотрудника / ассистента</a></li>
                        {{--                        @if(auth()->user()->id === 15)--}}
                        {{--                            <li class="nav-item"><a href="{{ route('registration-questionnaire-view') }}"--}}
                        {{--                                                    class="nav-link {{ active('registration-questionnaire-view') }}">Создание--}}
                        {{--                                    опросника</a></li>--}}
                        {{--                        @endif--}}
                    </ul>
                </li><!-- nav-item -->

            @endif
            <li class="nav-item">
                <a href="" class="nav-link with-sub">
                    <i class="icon ion-stats-bars"></i>
                    <span>Сводка</span>
                </a>
                <ul class="nav-sub">
                    @if(Auth::user()->role <= 3)
                        <li class="nav-item"><a
                                    href="{{ route('report', ['year' => now()->format('Y'), 'month' => now()-> format('m')]) }}"
                                    class="nav-link {{ active('report') }}">Статистика по деятельности регионов</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('yearReport', ['year' => now()->format('Y'), 'region' => '02-AN']) }}"
                                    class="nav-link {{ active('report') }}">Годовая статистика по деятельности
                                регионов</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('programReport', ['start' => '2019-10-01', 'end' => '2019-12-31']) }}"
                                    class="nav-link {{ active('report') }}">Программный отчет</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('StatisticsAll') }}"
                                    class="nav-link {{ active('StatisticsAll') }}">Общая сводка</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('outreachesAll') }}"
                                    class="nav-link {{ active('outreachesAll') }}">Количество посещеных мероприятий по
                                аутрич-сотрудникам</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('outreachesClients') }}"
                                    class="nav-link {{ active('outreachesClients') }}">Cводка по мониторингу сетей
                                аутрич-сотрудников</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('mioVisitions') }}"
                                    class="nav-link {{ active('mioVisitions') }}">Мониторинговые визиты в места
                                возможной продажи</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('mioVisitionsList', ['region' => 'all']) }}"
                                    class="nav-link {{ active('mioVisitionsList') }}">Список мониторинговых визитов в
                                места
                                возможной продажи</a>
                        </li>
                        <li class="nav-item"><a
                                    href="{{ route('actions') }}"
                                    class="nav-link {{ active('actions') }}">Мероприятия</a>
                        </li>
                    @endif
                    <li class="nav-item"><a
                                href="{{ route('yearReport', ['year' => now()->format('Y'), 'region' => auth()->user()->region->encoding]) }}"
                                class="nav-link {{ active('StatisticsOutreaches') }}">Общая сводка</a></li>
                    <li class="nav-item"><a
                                href="{{ route('StatisticsClients', ['region' => Auth::user()->region->id === 0 ? 'all' : Auth::user()->region->encoding]) }}"
                                class="nav-link {{ active('StatisticsClients') }}">Опросы клиентов</a></li>
                    <li class="nav-item"><a
                                href="{{ route('StatisticsOutreaches', ['region' => Auth::user()->region->id === 0 ? 'all' : Auth::user()->region->encoding, 'encoding' => 'all']) }}"
                                class="nav-link {{ active('StatisticsOutreaches') }}">Аутрич-сотрудники</a></li>

                    @if(Auth::user()->role == 10)
                        <li class="nav-item"><a
                                    href="{{ route('report4Audit', ['project' =>  2, 'year' => now()->format('Y'), 'month' => now()-> format('m')]) }}"
                                    class="nav-link {{ active('report4Audit') }}">Региональная сводка</a>
                            @endif
                        </li>
                </ul>
            </li><!-- nav-item -->
        @endif

        {{--        <li class="nav-item">--}}
        {{--        <a href="" class="nav-link with-sub">--}}
        {{--        <i class="icon ion-ios-filing-outline"></i>--}}
        {{--        <span>Почта</span>--}}
        {{--        </a>--}}
        {{--        <ul class="nav-sub">--}}
        {{--        <li class="nav-item"><a href="#" class="nav-link">Непрочитанные</a></li>--}}
        {{--        <li class="nav-item"><a href="#" class="nav-link">Входящие</a></li>--}}
        {{--        <li class="nav-item"><a href="#" class="nav-link">Отправленные</a></li>--}}
        {{--        <li class="nav-item"><a href="#" class="nav-link">Черновики</a></li>--}}
        {{--        </ul>--}}
        {{--        </li><!-- nav-item -->--}}

    </ul>
</div><!-- sh-sideleft-menu -->

<div class="sh-headpanel">
    <div class="sh-headpanel-left">
        <!-- START: HIDDEN IN MOBILE -->
        @if(auth()->user()->project->organization === 1)
            <a href="{{ route('file-manager') }}" class="sh-icon-link">
                <div>
                    <i class="icon ion-ios-folder-outline"></i>
                    <span>Файловый менеджер</span>
                </div>
            </a>
        @endif
        @if(Auth::user()->role <= 2)
            <a href="{{ route('assignments') }}" class="sh-icon-link">
                <div>
                    <i class="icon ion-ios-calendar-outline"></i>
                    <span>Поручения</span>
                </div>
            </a>
            <a href="{{ route('activity') }}" class="sh-icon-link">
                <div>
                    <i class="icon ion-android-watch"></i>
                    <span>Регистрация деятельности</span>
                </div>
            </a>
            @if(Auth::user()->role === 1)
                <a href="{{ route('settings') }}" class="sh-icon-link">
                    <div>
                        <i class="icon ion-ios-gear-outline"></i>
                        <span>Настройки</span>
                    </div>
                </a>
            @endif
            <a href="{{ Auth::user()->role === 1 ? route('ActivityUsers', ['project' => 'all']) : route('ActivityStatistics') }}"
               class="sh-icon-link">
                <div>
                    <i class="icon ion-stats-bars"></i>
                    <span>Статистика по деятельности</span>
                </div>
            </a>
        @else
            @if(array_key_exists(4, Auth::user()->positions()) || array_key_exists(6, Auth::user()->positions()) || array_key_exists(8, Auth::user()->positions()))
                <a href="{{ route('assignments') }}" class="sh-icon-link">
                    <div>
                        <i class="icon ion-ios-calendar-outline"></i>
                        <span>Поручения</span>
                    </div>
                </a>
                <a href="{{ route('activity') }}" class="sh-icon-link">
                    <div>
                        <i class="icon ion-android-watch"></i>
                        <span>Деятельность</span>
                    </div>
                </a>
        @endif
    @endif

    @if(array_key_exists(8, Auth::user()->positions()) || Auth::user()->role === 1)
        <a href="{{ route('indicators') }}"
           class="sh-icon-link">
            <div>
                <i class="icon ion-stats-bars"></i>
                <span>Индикаторы</span>
            </div>
        </a>
    @endif
    <!-- END: HIDDEN IN MOBILE -->

        <!-- START: DISPLAYED IN MOBILE ONLY -->
        <div class="dropdown dropdown-app-list">

            <a href="" data-toggle="dropdown" class="dropdown-link">
                <i class="icon ion-ios-keypad tx-18"></i>
            </a>
            <div class="dropdown-menu">
                <div class="row no-gutters wd-100p">

                    <div class="col-4">
                        <a href="{{ route('file-manager') }}" class="dropdown-menu-link mg-l-10-force">
                            <div>
                                <i class="icon ion-ios-folder-outline"></i>
                                <span>Файловый менеджер</span>
                            </div>
                        </a>
                    </div><!-- col-4 -->

                    @if(Auth::user()->role === 1 || array_key_exists(4, Auth::user()->positions()) || array_key_exists(8, Auth::user()->positions()))
                        <a href="{{ route('assignments') }}" class="dropdown-menu-link mg-l-10-force">
                            <div>
                                <i class="icon ion-ios-calendar-outline"></i>
                                <span>Поручения</span>
                            </div>
                        </a>
                    @endif
                    @if(Auth::user()->role === 1)
                        <a href="{{ route('activity') }}" class="dropdown-menu-link mg-l-10-force">
                            <div>
                                <i class="icon ion-android-watch"></i>
                                <span>Деятельность</span>
                            </div>
                        </a>
                        <a href="{{ route('settings') }}" class="dropdown-menu-link mg-l-10-force">
                            <div>
                                <i class="icon ion-ios-gear-outline"></i>
                                <span>Настройки</span>
                            </div>
                        </a>
                        <a href="{{ route('ActivityStatistics') }}" class="dropdown-menu-link mg-l-10-force">
                            <div>
                                <i class="icon ion-stats-bars"></i>
                                <span>Статистика по деятельности</span>
                            </div>
                        </a>
                    @else
                        @if(array_key_exists(4, Auth::user()->positions()))
                            <a href="{{ route('activity') }}"
                               class="dropdown-menu-link mg-l-10-force">
                                <div>
                                    <i class="icon ion-android-watch"></i>
                                    <span>Деятельность</span>
                                </div>
                            </a>
                        @endif
                    @endif

                    @if(Auth::user()->role === 1 || array_key_exists(8, Auth::user()->positions()))
                        <a href="{{ route('indicators') }}"
                           class="dropdown-menu-link mg-l-10-force">
                            <div>
                                <i class="icon ion-android-watch"></i>
                                <span>Индикаторы</span>
                            </div>
                        </a>
                    @endif
                </div><!-- row -->
            </div><!-- dropdown-menu -->
        </div><!-- dropdown -->
        <!-- END: DISPLAYED IN MOBILE ONLY -->

    </div><!-- sh-headpanel-left -->

    <div class="sh-headpanel-right">
        @if(app()->getLocale() == 'ru')
            <a href="{{ route('setlocale', ['lang' => 'uz']) }}" class="btn btn-outline-warning btn-icon mg-r-5">
                <div>UZ</div>
            </a>
        @else
            <a href="{{ route('setlocale', ['lang' => 'ru']) }}" class="btn btn-outline-warning btn-icon mg-r-5">
                <div>RU</div>
            </a>
        @endif
        {{--<div class="dropdown mg-r-10">--}}
        {{--<a href="" class="dropdown-link dropdown-link-notification">--}}
        {{--<i class="icon ion-ios-filing-outline tx-24"></i>--}}
        {{--</a>--}}
        {{--</div>--}}
        <div class="dropdown dropdown-profile">

            <a href="" data-toggle="dropdown" class="dropdown-link">
                <img src="{{ asset('img/avatar/no.png') }}" class="wd-60 rounded-circle" alt="">
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <div class="media align-items-center">
                    <img src="{{ Auth::user()->avatar ? Auth::user()->avatar : asset('img/avatar/no.png') }}"
                         class="wd-60 ht-60 rounded-circle bd pd-5" alt="">
                    <div class="media-body">
                        <h6 class="tx-inverse tx-15 mg-b-5">{{ auth()->user()['name_'.(app()->getLocale())] }}</h6>
                        <p class="mg-b-0 tx-12">{{ auth()->user()->email }}</p>
                    </div><!-- media-body -->
                </div><!-- media -->

                <hr>
                <form id="logout" method="POST" action="{{ route('logout') }}">
                    {{ csrf_field() }}
                </form>
                <ul class="dropdown-profile-nav">
                    <li><a href="{{ route('edit-profile', Auth::user()->id) }}"><i class="icon ion-ios-person"></i>
                            Профиль</a></li>
                    <li><a href="{{ route('activity-profile', ['id' => Auth::user()->id]) }}?project=8"><i
                                    class="icon ion-android-calendar"></i>
                            Регистрация деятельности</a></li>
                    @if(array_key_exists(4, Auth::user()->positions()))
                        <li><a href="{{ route('ActivityStatisticsClients', ['project' => 4]) }}"><i
                                        class="icon ion-android-calendar"></i>
                                Статистика</a></li>
                    @endif
                    {{--<li><a href=""><i class="icon ion-ios-gear"></i> Settings</a></li>--}}
                    {{--<li><a href=""><i class="icon ion-ios-download"></i> Downloads</a></li>--}}
                    {{--<li><a href=""><i class="icon ion-ios-star"></i> Favorites</a></li>--}}
                    <li><a href="javascript:void(0);" onclick="$('#logout').submit();"><i class="icon ion-power"></i>
                            Выход</a></li>
                </ul>

            </div><!-- dropdown-menu -->
        </div>
    </div><!-- sh-headpanel-right -->
</div><!-- sh-headpanel -->

<div class="sh-mainpanel">
    @yield('header')

    <div class="sh-pagebody">

        <div class="row row-sm">
            @yield('content')
        </div><!-- row -->

    </div><!-- sh-pagebody -->

    <div class="sh-footer">
        <div class="container">
            <div>Copyright &copy; 2020. NGO RIEC "INTILISH"</div>
            @php
                $data = \App\SupportMessage::where('chat_id', '-2147483648')
                ->leftJoin('users as author', 'author.id', '=', 'support_messages.author')
                ->where(function ($query) {
                    $query->where('author', Auth::user()->id)
                          ->orWhere('user', Auth::user()->id);
                })
                ->select('author.name_ru as author', 'support_messages.text', 'support_messages.update_id')
                ->get();
            @endphp
            <button id="show_chat"
                    class="btn btn-lg btn-outline-primary fixed-bottom btn-icon rounded-circle mg-50 pd-20-force"
                    style="font-size: 50px">
                <div><i class="far fa-comments"></i></div>
            </button>
            <div class="row chat-window float-right hidden-xs-up" id="chat_window">
                <div class="card bd-0">
                    <div class="card-header bg-primary tx-white lead">
                        <span class="fa fa-comment-o"></span>
                        Обратная связь
                        <div class="float-right">
                            <a href="javascript:void(0);" class="btn btn-outline-white btn-icon mg-r-5"
                               id="minim_chat_window">
                                <div><i class="icon ion-minus"></i></div>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-outline-white btn-icon mg-r-5"
                               id="close_chat_window">
                                <div><i class="icon ion-close"></i></div>
                            </a>
                        </div>
                    </div>
                    <div class="card-body msg_container_base bd bd-t-0" id="chat_container">
                        <div class="row msg_container base_sent">
                            <div class="col-md-2 col-xs-2 avatar pd-0-force">
                                <img src="{{ asset('img/avatar/no.png') }}"
                                     class="img-responsive">
                            </div>
                            <div class="col-md-10 col-xs-10">
                                <div class="messages msg_sent">
                                    <p>Если у Вас какие то вопросы связанные с системой. Можете написать в этот чат,
                                        я в близжайшее время Вам отвечу.</p>
                                    <time>Владислав Данилов</time>
                                </div>
                            </div>
                        </div>

                        @foreach($data as $datum)
                            @php
                                $startPos = strpos($datum->text, 'Message:');
                                $msg = substr($datum->text, $startPos);
                            @endphp
                            @if(is_null($datum->update_id))
                                <div class="row msg_container base_receive">
                                    <div class="col-md-10 col-xs-10">
                                        <div class="messages msg_receive">
                                            <p>{{ $msg }}</p>
                                            changer
                                            <time>{{ $datum->author }}</time>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xs-2 avatar pd-0-force">
                                        <img src="/img/avatar/no.png"
                                             проверка
                                             class="img-responsive">
                                    </div>
                                </div>
                            @else
                                <div class="row msg_container base_sent">
                                    <div class="col-md-2 col-xs-2 avatar pd-0-force">
                                        <img src="{{ asset('img/avatar/no.png') }}"
                                             class="img-responsive">
                                    </div>
                                    <div class="col-md-10 col-xs-10">
                                        проверка доступа
                                        <div class="messages msg_sent">
                                            <p>{{ $msg }}</p>
                                            <time>{{ $datum->author }}</time>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="card-footer pd-0-force" id="support_form">
                        <div class="input-group">
                            <input id="support_token" type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input id="support_text" autocomplete="off" id="chat-input" type="text" name="message"
                                   class="form-control"
                                   placeholder="Напишите Ваше сообщение здесь..."/>
                            <span class="input-group-btn">
                        <div class="btn btn-primary" id="btn-send-chat">Отправить</div>
                    </span>
                        </div>
                        <div class="style project in my house room case dead frog bird chalange fucking use step frag store application destanation californication"></div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- sh-footer -->
    <!--  -->

</div><!-- sh-mainpanel -->

<script src="{{ asset('lib/jquery/jquery.js') }}"></script>
<script src="{{ asset('lib/popper.js/popper.js') }}"></script>
<script src="{{ asset('lib/bootstrap/bootstrap.js') }}"></script>
<script src="{{ asset('lib/jquery-ui/jquery-ui.js') }}"></script>
<script src="{{ asset('lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js') }}"></script>
<script src="{{ asset('lib/moment/moment.js') }}"></script>
<script src="{{ asset('lib/fullcalendar/fullcalendar.js') }}"></script>

<!-- Custom Scripts -->
@yield('scripts')
<script src="{{ asset('js/shamcey.js') }}"></script>
<script>
    $('body').addClass(localStorage.hideleft);
    $('#filterDate').on('apply.daterangepicker', function (ev, picker) {
        if ($('#dateFilterSave').prop("checked")) {
            localStorage.startDate = picker.startDate.format('YYYY-MM-DD');
            localStorage.endDate = picker.endDate.format('YYYY-MM-DD');
        } else {
            localStorage.removeItem('startDate');
            localStorage.removeItem('endDate');
        }
    });

    if ($('div.card.card-body').is('#filterDate') && localStorage.startDate) {
        if (window.location.search) $gets = window.location.search.split('?')[1].split('&');
        else $gets = ['startDate=', 'endDate='];
        $startDate = 'startDate=' + localStorage.startDate;
        $endDate = 'endDate=' + localStorage.endDate;
        $('div.card.card-body').removeClass('bg-info').addClass('bg-primary');
        if ($gets.indexOf($startDate) == -1 && $gets.indexOf($endDate) == -1) {
            var $href;
            if ($gets[0].indexOf('project=') != -1) {
                $href = '&' + $gets[0];
            } else if ($gets[1].indexOf('project=') != -1) {
                $href = '&' + $gets[1];
            } else if ($gets[2].indexOf('project=') != -1) {
                $href = '&' + $gets[2];
            } else if ($gets[3].indexOf('project=') != -1) {
                $href = '&' + $gets[3];
            }
            window.location.search = '?startDate=' + localStorage.startDate + '&endDate=' + localStorage.endDate + $href
        }
    }

    $('#navicon').click(function () {
        if (localStorage.hideleft != 'hide-left') localStorage.hideleft = 'hide-left';
        else localStorage.removeItem('hideleft');
    });

    $(window).on('scroll', function () {
        var width = $('#filterMenu').width;
        var left = $('#filterMenu').position().left;
        var right = $('#filterMenu').position().right;
        if ($(this).scrollTop() > 200) {
            $('#filterMenu').addClass('fixed-top');
            $('#filterMenu').css('top', 110)
        } else {
            $('#filterMenu').removeClass('fixed-top');
            $('#filterMenu').removeAttr('style')
        }
    });

    $('.row').on('click', '#filterDate', function () {
        if (!$('div.drp-buttons label').is('.ckbox')) {
            $('div.drp-buttons').prepend('<label class="ckbox"><input id="dateFilterSave" type="checkbox" checked=""><span>Сохранить фильтр</span></label>');
        }
        ;
    });
</script>

@yield('scriptsFooter')

</body>
</html>