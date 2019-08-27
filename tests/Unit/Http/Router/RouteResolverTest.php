<?php

namespace Tests\Unit\Http\Router;

use App\Page;
use App\Domain;
use Tests\TestCase;
use App\Services\Pagie;
use Illuminate\Http\Request;
use App\Http\Router\RouteResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteResolverTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_throws_a_model_not_found_exception_when_the_given_domain_could_not_be_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $request = Request::create(faker()->url);

        $resolver = new RouteResolver($request);

        $resolver->domain();
    }

    /** @test */
    public function it_set_an_instance_of_the_given_domain_when_the_domain_could_be_found()
    {
        factory(Domain::class)->create([
            'domain_name' => 'test-domain.com',
        ]);

        $request = Request::create('http://test-domain.com/test-uri');

        (new RouteResolver($request))->domain();

        $this->assertInstanceOf(Domain::class, Pagie::getInstance()->resolvedDomain);
    }

    /** @test */
    public function it_throws_404_not_found_exception_when_no_resource_could_be_found()
    {
        $this->expectException(NotFoundHttpException::class);

        factory(Domain::class)->create([
            'domain_name' => 'test-domain.com',
        ]);

        $request = Request::create('http://test-domain.com/test-uri');

        $resolver = new RouteResolver($request);

        $resolver->resource($resolver->domain());
    }

    /** @test */
    public function it_correctly_returns_the_matched_resource_when_resolving_the_request()
    {
        $domain = factory(Domain::class)->create([
            'domain_name' => 'test-domain.com',
        ]);

        $page = factory(Page::class)->create([
            'domain_id' => $domain->id,
        ]);

        $request = Request::create('http://test-domain.com/'.$page->slug);

        $resolver = new RouteResolver($request);

        $resource = $resolver->resource($resolver->domain());

        $this->assertInstanceOf(Page::class, $resource);
    }
}
