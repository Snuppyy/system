<?php

    namespace App\Http\Controllers;

    use App\MioVisition;
    use App\Position;
    use App\Region;
    use App\TypesPharmacy;
    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;
    use phpDocumentor\Reflection\DocBlock\Description;

    class MiOVisitionsController extends Controller
    {
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        public function __construct ()
        {
            $this->middleware('auth');
        }

        public function index ()
        {
            $types_pharmacies = TypesPharmacy::select('id', 'name_' . app()->getLocale() . ' as name')->get();
            $regionPosition = Position::whereProject(2)->whereRegion(\Auth::user()->region->id)->get();
            $i=0;
            foreach ( $regionPosition as $regionPositionData ) {
                $staff[$i] = User::where('position', 'LIKE', '%"'.$regionPositionData->id.'"%')->where('status', '>=', 1)->first();
                $staff[$i]['positionName'] = $regionPositionData->name_ru;
                $i++;
            }

            return view('pages.miovisitions.registration-miovisitions', [ 'staff' => $staff, 'types_pharmacies' => $types_pharmacies ]);
        }

        public function set ( Request $request )
        {
            MioVisition::create(array_merge($request->except('_token'), [ 'author' => \Auth::user()->id ], [ 'region' => \Auth::user()->region->id === 0 ? 1 : \Auth::user()->region->id ]));
            $message = 'Мониторинговый визит успешно записан в базу данных!';
            return redirect()->back()->with('success', $message);
        }

        public function get ( Request $request )
        {
            $types_pharmacies = TypesPharmacy::select('id', 'name_' . app()->getLocale() . ' as name')->get();
            $regionPosition = Position::whereProject(2)->whereRegion(\Auth::user()->region->id)->get();
            $i=0;
            foreach ( $regionPosition as $regionPositionData ) {
                $staff[$i] = User::where('position', 'LIKE', '%'.$regionPositionData->id.'%')->where('status', '>=', 1)->first();
                $staff[$i]['positionName'] = $regionPositionData->name_ru;
                $i++;
            }
            $target           = MioVisition::find($request->id);
            if ( $target->author === \auth()->user()->id || $target->user === \auth()->user()->id || \auth()->user()->role === 1 ) return view('pages.miovisitions.edit-miovisitions', [ 'miovisitions' => $target, 'staff' => $staff, 'types_pharmacies' => $types_pharmacies ]);
            else abort(403);
        }

        public function delete ( Request $request )
        {
            $target = MioVisition::find($request->id);
            if ( $target->author === \auth()->user()->id || $target->user === \auth()->user()->id || \auth()->user()->role === 1 ) $target->update([ 'status' => 0 ]);
            else abort(403);
            $message = 'Запись успешно удалена!';
            return redirect()->back()->with('success', $message);
        }

        public function update ( Request $request )
        {
            $target = MioVisition::find($request->id);
            if ( $target->author === \auth()->user()->id || $target->user === \auth()->user()->id || \auth()->user()->role === 1 ) {
                $target->update($request->except('_token'));
                $message = 'Запись успешно изменена!';
                return redirect()->back()->with('success', $message);
            }
            else abort(403);
        }

        public function view ( Request $request )
        {
            if ( Auth::user()->region->id === 0 ) {
                $data       = DB::table('mio_visitions')->where('mio_visitions.status', '>=', 1)->where([ 'mio_visitions.project' => 2 ]);
                $noScan     = DB::table('mio_visitions')->where('mio_visitions.status', '>=', 1)->where([ 'mio_visitions.project' => 2 ]);
                $duplicates = DB::table('mio_visitions')->where('mio_visitions.status', '>=', 1)->where([ 'mio_visitions.project' => 2 ]);
                if ( $request->region ) {
                    $region = DB::table('regions')->where('encoding', $request->region)->first() ?? abort(404);
                    $data->where('region', $region->id);
                    $noScan->where('region', $region->id);
                    $duplicates->where('region', $region->id);
                }
            }
            else {
                $noScan     = MioVisition::where('mio_visitions.status', '>=', 1)->where([ 'mio_visitions.region' => \Auth::user()->region->id, 'mio_visitions.project' => 2 ]);
                $data       = MioVisition::where('mio_visitions.status', '>=', 1)->where([ 'mio_visitions.region' => \Auth::user()->region->id, 'mio_visitions.project' => 2 ]);
                $duplicates = MioVisition::where('mio_visitions.status', '>=', 1)->where([ 'mio_visitions.region' => \Auth::user()->region->id, 'mio_visitions.project' => 2 ]);
            }

            if ( isset($request->startDate) && isset($request->endDate) ) {
                $noScan->whereBetween('datetime', [ $request->startDate, $request->endDate ]);
                $data->whereBetween('datetime', [ $request->startDate, $request->endDate ]);
                $duplicates->whereBetween('datetime', [ $request->startDate, $request->endDate ]);
            }

            if ( isset($request->filter) && $request->filter === 'duplicates' ) {
                $duplicates        = $duplicates->select(DB::raw('GROUP_CONCAT(id) as ids'), 'region')->groupBy('author', 'datetime', 'phone', 'name', 'address', 'region')->having(DB::raw('COUNT(*)'), '>', 1)->get();
                $return_duplicates = '';
                foreach ( $duplicates as $duplicate ) {
                    if ( $request->region ) {
                        !isset($return_duplicates[$duplicate->region]) ? $return_duplicates[$duplicate->region] = $duplicate->ids . ',' : $return_duplicates[$duplicate->region] .= $duplicate->ids . ',';
                    }
                    else {
                        $return_duplicates .= $duplicate->ids . ',';
                    }
                }
                if ( $request->region ) {
                    $noScan->whereIn('mio_visitions.id', explode(',', $return_duplicates[$region->id]));
                    $data->whereIn('mio_visitions.id', explode(',', $return_duplicates[$region->id]));
                }
                else {
                    $noScan->whereIn('mio_visitions.id', explode(',', $return_duplicates));
                    $data->whereIn('mio_visitions.id', explode(',', $return_duplicates));
                }
            }

            $data = $data->join('users as user', 'user.id', '=', 'mio_visitions.user')
                         ->join('users as author', 'author.id', '=', 'mio_visitions.author')
                         ->join('types_pharmacies', 'types_pharmacies.id', '=', 'mio_visitions.type')
                         ->join('regions', 'regions.id', '=', 'mio_visitions.region')
                         ->select('mio_visitions.id', 'mio_visitions.scan', 'mio_visitions.address', 'mio_visitions.datetime', 'regions.encoding as region', 'user.name_' . app()->getLocale() . ' as user', 'user.id as user_id', 'author.name_' . app()->getLocale() . ' as author', 'author.id as author_id', 'types_pharmacies.name_' . app()->getLocale() . ' as type', 'mio_visitions.status', 'procurementSyringes2', 'procurementSyringes5', 'procurementSyringes10', 'procurementDoily', 'procurementCondomsM', 'procurementCondomsW', 'procurementHivBlood', 'procurementHivSpittle')
                         ->orderBy('id', 'desc')
                         ->paginate(10);

            $noScan = $noScan->whereNull('scan')->count();

            $regions = Region::all();

            return view('pages.miovisitions.view-miovisitions')->with([ 'data' => $data, 'regions' => $regions, 'noScan' => $noScan ]);
        }
    }
