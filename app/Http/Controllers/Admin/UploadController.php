<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UploadController extends Controller
{
    /**
     * Generate a presigned URL for direct S3 upload.
     */
    public function presignedUrl(Request $request)
    {
        $request->validate([
            'file_name' => 'required|string',
            'content_type' => 'required|string|in:image/jpeg,image/png,image/webp,image/jpg',
            'file_size' => 'required|integer|max:2097152', // Max 2MB
        ]);

        $extension = pathinfo($request->file_name, PATHINFO_EXTENSION);
        $fileName = Str::uuid() . '.' . $extension;
        $path = 'posters/' . $fileName;

        // Generate temporary upload URL valid for 5 minutes
        $client = Storage::disk('s3')->getClient();
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $path,
            'ContentType' => $request->content_type,
        ]);

        $requestUrl = $client->createPresignedRequest($command, '+5 minutes');
        
        return response()->json([
            'url' => (string) $requestUrl->getUri(),
            'path' => $path,
        ]);
    }
}
