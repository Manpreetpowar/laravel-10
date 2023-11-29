<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Attachment $attachment)
    {
        //
    }

    /**
     * save an uploaded file
     * @param object Request instance of the request object
     */
    public function save(Request $request) {
        //save the file in its own folder in the temp folder
        if ($file = $request->file('file')) {
            
            //defaults
            $file_type = 'file';

            $uniqueid = generateUniqueID(new Attachment, 4);
            $directory = $uniqueid;

            //original file name
            $filename = $file->getClientOriginalName();

            $extension = $file->getClientOriginalExtension();
            
            //filepath
            $file_path = base_path() . "/storage/app/public/files/attachments/$directory/$filename";

            //create directory
            Storage::disk('public')->makeDirectory("temp/$directory");

            //save file to directory
            Storage::disk('public')->putFileAs("temp/$directory", $file, $filename);

            $data = [
                'directory' => $directory,
                'filename' => $filename,
                'success' => true,
                'uniqueid' => $uniqueid,
            ];

            return response()->json($data);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attachment $attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        //
    }
}
