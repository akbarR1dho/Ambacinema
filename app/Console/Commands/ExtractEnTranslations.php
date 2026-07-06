<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExtractEnTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:extract-en';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengekstrak file kamus id.json menjadi en.json';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $idLangFile = base_path('lang/id.json');
        $enLangFile = base_path('lang/en.json');

        if (!File::exists($idLangFile)) {
            $this->error("File lang/id.json tidak ditemukan!");
            return;
        }

        $idJson = json_decode(file_get_contents($idLangFile), true) ?? [];
        $enJson = [];

        foreach ($idJson as $key => $val) {
            $enJson[$key] = $key;
        }

        file_put_contents($enLangFile, json_encode($enJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $count = count($enJson);
        $this->info("Berhasil membuat atau memperbarui lang/en.json dengan $count entri terjemahan.");
    }
}
