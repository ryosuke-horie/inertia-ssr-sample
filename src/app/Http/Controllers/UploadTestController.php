<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UploadTestController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('UploadTest/Index');
    }

    public function store(Request $request)
    {
        // S3へのアップロード処理
        Storage::disk('s3')->putFile('/output', $request->file('video'));

        return to_route('upload.get');
    }
}
