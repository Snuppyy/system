<?php

namespace App\Http\Controllers;

use App\Answer;
use App\MioVisition;
use App\QuestionnaireOPU_001;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use ZipArchive;

class UploaderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mio_visitions(Request $request)
    {
        try {
            if (
                !isset($_FILES['file']['error']) ||
                is_array($_FILES['file']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            $path = $request->file('file')->store('public/images/mio_visitions/' . auth()->user()->id);

            echo json_encode([
                'status' => 'ok',
                'path' => $path
            ]);

        } catch (RuntimeException $e) {

            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function assignment(Request $request)
    {
        try {
            if (
                !isset($_FILES['file']['error']) ||
                is_array($_FILES['file']['error'])
            ) {
                throw new RuntimeException('Invalid parameters.');
            }

            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('Exceeded filesize limit.');
                default:
                    throw new RuntimeException('Unknown errors.');
            }

            $path = $request->file('file')->store('public/documents/assignments/' . auth()->user()->id);

            echo json_encode([
                'status' => 'ok',
                'path' => $path
            ]);

        } catch (RuntimeException $e) {

            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function documents(Request $request)
    {
        $path = $request->file('file')->store('public/scan/' . $request->type . '/' . $request->id);
        $url = Storage::url($path);
        if($request->type === 'mio') MioVisition::find($request->id)->update(['scan' => $url]);
        if($request->type === 'opu') QuestionnaireOPU_001::find($request->id)->update(['scan' => $url]);
        if($request->type === 'questionnaires') Answer::find($request->id)->update(['scan' => $url]);
        return back();
    }

    public function zip(Request $request)
    {

        $regions = Region::select('encoding', 'id')->get();
        foreach ($regions as $region) {
            $regionEncoding[$region->id] = $region->encoding;
        }
        if ($request->document === 'answers') {
            $scan = Answer::whereRegion($request->region)->whereBetween('date', [$request->year . '-' . $request->month . '-01', $request->year . '-' . $request->month . '-31'])->select('scan')->get()->toArray();
        } else if ($request->document === 'mio') {
            $scan = MioVisition::whereRegion($request->region)->whereBetween('datetime', [$request->year . '-' . $request->month . '-01', $request->year . '-' . $request->month . '-31'])->select('scan')->get()->toArray();
        }
        $zip = new ZipArchive;
        $fileName = 'storage/scan/'.$request->document.'_'.$regionEncoding[$request->region].'_Y'.$request->year.'M'.$request->month.'.zip';
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
            foreach ($scan as $file) {
                $fileDir = explode('/', $file['scan']);
                unset($fileDir[5]);
                $fileDir = implode('/', $fileDir);
                $files = File::allFiles(public_path($fileDir));
                foreach ($files as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
            }
            $zip->close();
        }

        return response()->download(public_path($fileName));

    }
}
