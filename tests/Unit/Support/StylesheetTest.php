<?php

namespace Tests\Unit\Support;

use Tests\TestCase;
use App\Support\Stylesheet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StylesheetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_outputs_a_file_after_compilation()
    {
        $stylesheet = (new Stylesheet)->compile($this->getTestStylesheet());

        $this->assertEquals('@media screen{.test-element{background-color:red;}}', $stylesheet->getContents());
    }

    /** @test */
    public function it_successfully_stores_the_stylesheet_contents()
    {
        Storage::fake();

        $this->login();

        $contents = $this->getTestStylesheet();

        $path = (new Stylesheet)->compile($contents)->store($this->domain());
        $path = str_replace('/storage', '', parse_url($path, PHP_URL_PATH));

        Storage::assertExists($path);
    }

    protected function getTestStylesheet(): array
    {
        return [
            'screen' => [
                '.test-element' => [
                    'background-color' => 'red',
                ],
            ],
        ];
    }
}
