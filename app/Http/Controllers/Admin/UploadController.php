<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UploadController extends Controller
{
    protected $uploadService;

    public function __construct(\App\Services\UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

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

        try {
            $result = $this->uploadService->generatePresignedUrl(
                $request->file_name,
                $request->content_type
            );
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to generate upload URL: ' . $e->getMessage()
            ], 500);
        }
    }
}
