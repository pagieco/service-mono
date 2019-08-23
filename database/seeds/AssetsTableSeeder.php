<?php

use App\Asset;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Symfony\Component\Finder\Finder;

class AssetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Throwable
     */
    public function run()
    {
        $domain = DomainsTableSeeder::getDemoDomain();
        $team = TeamsTableSeeder::getWildcatsTeam();

        foreach ($this->getSeedFiles() as $fileInfo) {
            $base = new UploadedFile($fileInfo->getPathName(), $fileInfo->getBaseName());

            $file = UploadedFile::createFromBase($base);

            $asset = tap(Asset::fromFile($file, $domain, $team)->upload('local'))->save();

            $asset->update(['mimetype' => 'image/jpeg']);

            $asset->createThumbnail();
        }
    }

    protected function getSeedFiles()
    {
        $finder = new Finder;

        $finder->files()->in(database_path('seeds/seed-resources/assets'));

        if (! $finder->hasResults()) {
            throw new Exception('No seed-resources found.');
        }

        $files = [];

        foreach ($finder as $file) {
            $files[] = $file;
        }

        return $files;
    }
}
