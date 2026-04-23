<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function temp(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB limit
        ]);

        $file = $request->file('file');
        
        // Store in storage/app/public/temp
        $path = $file->store('temp', 'public');

        $media = Media::create([
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $media->id,
                'path' => $path,
                'url' => asset('storage/' . $path),
            ],
        ]);
    }
}
