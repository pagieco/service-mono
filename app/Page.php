<?php

namespace App;

use App\Support\DOM;
use App\Support\Stylesheet;
use App\Renderers\PageRenderer;
use App\Database\Eloquent\Model;
use Illuminate\Contracts\Support\Responsable;

class Page extends Model implements Responsable
{
    use Concerns\HasUUID;
    use Concerns\BelongsToTeam;
    use Concerns\BelongsToDomain;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'dom',
    ];

    public function publish(array $dom, array $css)
    {
        $dom = DOM::createDocumentFromDom($dom);

        $this->update([
            'dom' => $dom->saveHTML(),
        ]);

        $this->domain()->update([
            'css_rules' => $css,
            'css_file' => (new Stylesheet)->compile($css)->store($this->domain),
        ]);
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $renderer = new PageRenderer($request);

        return $renderer
            ->fromResourceInstance($this)
            ->render();
    }
}
