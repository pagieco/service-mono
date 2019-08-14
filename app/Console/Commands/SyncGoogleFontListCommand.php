<?php

namespace App\Console\Commands;

use App\Font;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SyncGoogleFontListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pagie:sync-google-fonts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync the Google Fonts list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fonts = $this->fetchFontList()
            ->map(function ($font) {
                return [
                    'family' => $font->family,
                    'variants' => $font->variants,
                    'subsets' => $font->subsets,
                ];
            })
            ->each(function ($font) {
                $model = Font::firstOrNew([
                    'origin' => 'google-fonts',
                    'family' => $font['family'],
                ]);

                $model->variants = $font['variants'];
                $model->subsets = $font['subsets'];

                $model->save();
            });

        Storage::put('google-font-list.json', json_encode($fonts));

        $this->info('Successfully synced Google Fonts List.');
    }

    /**
     * Fetch the Google font list.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function fetchFontList(): Collection
    {
        $apiKey = config('services.google.fonts.key');

        $contents = file_get_contents('https://www.googleapis.com/webfonts/v1/webfonts?key=' . $apiKey);

        return collect(json_decode($contents)->items);
    }
}
