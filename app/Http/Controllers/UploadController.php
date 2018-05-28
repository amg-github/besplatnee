<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Validator;

class UploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $res = ['success' => false, 'errors' => [], 'data' => []];

        $validator = Validator::make($request->all(), [
            'filetype' => 'required|in:image,video',
        ]);

        if($validator->fails()) {
            $res['errors'] = $validator->messages();
            return $res;
        }

        switch($request->input('filetype')) {
            case 'image': $file_condition = 'required|mimetypes:image/*|max:10000'; break;
            case 'video': $file_condition = 'required|mimetypes:video/*|max:50000'; break;
        }

        $validator = Validator::make($request->all(), [
            'uploadfiles.*' =>  $file_condition,
        ]);

        if($validator->fails()) {
            $res['errors'] = $validator->messages();
            return $res;
        }

        if($request->hasFile('uploadfiles')) {

            foreach($request->file('uploadfiles') as $file) {
                $filename = $file->store('uploads', 'public');

                switch($request->input('filetype')) {

                    case 'video': 
                            $video = \Youtube::upload(public_path(Storage::url($filename)), [
                                'title'       => 'Газета Ещё БЕСПЛАТНЕЕ',
                                'description' => 'besplatnee',
                                'tags'        => [],
                                'category_id' => 10
                            ]);

                            $video->getVideoId();

                            $res['data'][] = [
                                'preview' => 'https://i1.ytimg.com/vi/' . $video->getVideoId() . '/default.jpg',
                                'value' => $video->getVideoId(),
                                'filename' => $video->getVideoId(),
                            ];
                        break;

                    case 'image': 
                            $res['data'][] = [
                                'preview' => asset(Storage::url($filename)),
                                'value' => Storage::url($filename),
                                'filename' => $filename,
                            ];
                        break;

                }
            }
        }

        $res['success'] = true;
        return $res;
    }
}
