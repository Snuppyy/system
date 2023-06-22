<?php

    namespace App\Http\Controllers;

    use App\BussDurkeeAnswer;
    use App\BussDurkeeKey;
    use App\BussDurkeeQuestion;
    use App\Client;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;

    class PsychController extends Controller
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

        public function BussDurkee ()
        {
            $questions   = BussDurkeeQuestion::where('status', 1)->get();
            $clientsData = Client::leftJoin('prisons', 'prisons.id', '=', 'clients.prison')
                                 ->where('prisons.status', '>=', 1)
                                 ->select(DB::raw('CONCAT(`clients`.`f_name`, " ", `clients`.`s_name`) as name'), 'encoding as prison', 'clients.id')
                                 ->get();

            foreach ( $clientsData as $client ) {
                $clients[$client->prison][$client->id] = $client->name;
            }
            return view('pages.psych.buss-durkee_view')->with([ 'questions' => $questions, 'clients' => $clients ]);
        }

        public function BussDurkeeSave ( Request $request )
        {
            $data = BussDurkeeAnswer::create(array_merge($request->except('_token'), [ 'author' => \Auth::user()->id ]));
            return redirect()->route('BussDurkeeView');
        }

        public function BussDurkeeList ()
        {
            $data         = BussDurkeeAnswer::leftJoin('clients', 'clients.id', '=', 'buss_durkee_answers.client')
                                            ->where('buss_durkee_answers.status', '>=', 1)
                                            ->select(DB::raw('CONCAT(`clients`.`f_name`, " ", `clients`.`s_name`) as name'), 'buss_durkee_answers.*')
                                            ->get();
            $coefficients = BussDurkeeKey::all();


            foreach ( $data as $datum ) {
                $result[$datum->client]['name'] = $datum->name;
                foreach ( $coefficients as $coefficient ) {
                    $result[$datum->client][$datum->type]['results'][$coefficient->name]  += count(array_intersect_key($coefficient->questions_true, $datum->answers));
                    $result[$datum->client][$datum->type]['results'][$coefficient->name] += count(array_diff_key($coefficient->questions_false, $datum->answers));
                }
            }
            return view('pages.psych.buss-durkee_result')->with([ 'data' => $result ]);
        }

        public function BussDurkeeResult ( Request $request )
        {
            $data           = BussDurkeeAnswer::find($request->id);
            $coefficients   = BussDurkeeKey::all();
            $result['name'] = $data->name;
            foreach ( $coefficients as $coefficient ) {
                $result['true'][$coefficient->name]  = count(array_intersect_key($coefficient->questions_true, $data->answers)) === count($coefficient->questions_true) ? true : false;
                $result['false'][$coefficient->name] = is_array(array_diff_key($coefficient->questions_false, $data->answers));
            }

            foreach ( $result['true'] as $key => $value ) {
                $result['result'][$key] = $value === $result['false'][$key] ? true : false;
            }

            return view('pages.psych.buss-durkee_result')->with([ 'data' => $result ]);
        }

        public function Rozenberg ()
        {
            dd($request);
        }

        public function RozenbergSave ( Request $request )
        {

        }

        public function RozenbergList ()
        {

        }

        public function RozenbergResult ( Request $request )
        {

        }
    }
