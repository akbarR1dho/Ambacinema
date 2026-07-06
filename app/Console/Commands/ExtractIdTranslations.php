<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractIdTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:extract-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengekstrak semua teks terjemahan dari file views dan controllers ke dalam file kamus id.json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directories = [
            resource_path('views'),
            app_path('Http/Controllers'),
        ];

        $strings = [];

        // Regex untuk mencari fungsi __('teks') atau @lang('teks')
        // Mendukung kutip satu ('') maupun kutip dua ("")
        $pattern = '/(?:__|@lang)\s*\(\s*([\'"])(.*?)\1\s*\)/ms';

        foreach ($directories as $directory) {
            if (!File::exists($directory)) continue;
            
            $files = File::allFiles($directory);
            foreach ($files as $file) {
                if ($file->getExtension() === 'php') {
                    $content = file_get_contents($file->getRealPath());
                    if (preg_match_all($pattern, $content, $matches)) {
                        foreach ($matches[2] as $match) {
                            $strings[] = $match;
                        }
                    }
                }
            }
        }

        // Hilangkan duplikat dan urutkan
        $strings = array_unique($strings);
        $strings = array_values($strings);
        sort($strings);

        $langFile = base_path('lang/id.json');
        
        $existingTranslations = [];
        if (File::exists($langFile)) {
            $existingTranslations = json_decode(file_get_contents($langFile), true) ?? [];
        }

        $newCount = 0;
        foreach ($strings as $string) {
            // Jika kata belum ada di kamus id.json, maka tambahkan
            if (!isset($existingTranslations[$string])) {
                // Set default valuenya sama dengan bahasa aslinya (Inggris)
                // agar pengguna bisa dengan mudah mencari dan menerjemahkannya secara manual
                $existingTranslations[$string] = $string; 
                $newCount++;
            }
        }

        if ($newCount > 0) {
            // Urutkan key secara alfabet agar rapi
            ksort($existingTranslations);
            
            // Simpan kembali ke file id.json
            $json = json_encode($existingTranslations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            file_put_contents($langFile, $json);
            
            $this->info("Berhasil menambahkan $newCount teks baru ke dalam lang/id.json.");
            $this->warn("Silakan buka lang/id.json dan ubah teks yang baru ditambahkan ke bahasa Indonesia yang sesuai.");
        } else {
            $this->info("Tidak ada teks terjemahan baru yang ditemukan. Kamus id.json sudah lengkap!");
        }
    }
}
