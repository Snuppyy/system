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
<div class="sh-mainpanel pd-t-20">
    @yield('header')

    <div class="sh-pagebody">

        <div class="row row-sm">
            @yield('content')
        </div><!-- row -->

    </div><!-- sh-pagebody -->

    <div class="sh-footer">
        <div class="container">
            <div>Copyright &copy; 2020. NGO RIEC "INTILISH"</div>
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
        if (!$gets.includes($startDate) && !$gets.includes($endDate)) {
            // console.log($gets);
            var $href = '';
            if($gets.length >= 1) {
                if ($gets[0].includes('project=')) {
                    $href = '&' + $gets[0];
                } else if ($gets[1].includes('project=')) {
                    $href = '&' + $gets[1];
                } else if ($gets[2].includes('project=')) {
                    $href = '&' + $gets[2];
                } else if ($gets[3].includes('project=')) {
                    $href = '&' + $gets[3];
                }
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
            $('#filterMenu').addClass('col-12');
            $('#filterMenu').css('top', 110)
        } else {
            $('#filterMenu').removeClass('fixed-top');
            $('#filterMenu').removeClass('col-12');
            $('#filterMenu').removeAttr('style')
        }
    });

    $('div').on('click', '#filterDate', function () {
        if (!$('div.drp-buttons label').is('.ckbox')) {
            $('div.drp-buttons').prepend('<label class="ckbox"><input id="dateFilterSave" type="checkbox" checked=""><span>Сохранить фильтр</span></label>');
        };
    });
</script>

@yield('scriptsFooter')

</body>
</html>