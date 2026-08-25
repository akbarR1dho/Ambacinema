<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    /**
     * Generate a presigned URL and path for S3 upload.
     * 
     * @param string $fileName
     * @param string $contentType
     * @return array
     */
    public function generatePresignedUrl(string $fileName, string $contentType): array
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = Str::uuid() . '.' . $extension;
        $path = 'posters/' . $newFileName;

        // Generate temporary upload URL valid for 5 minutes
        $client = Storage::disk('s3')->getClient();
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.s3.bucket'),
            'Key' => $path,
            'ContentType' => $contentType,
        ]);

        $requestUrl = $client->createPresignedRequest($command, '+5 minutes');
        
        return [
            'url' => (string) $requestUrl->getUri(),
            'path' => $path,
        ];
    }
}
