<?php

namespace Tests\Unit\Support;

use DOMDocument;
use Tests\TestCase;
use App\Support\DOM;

class DomTest extends TestCase
{
    /** @test */
    public function it_outputs_a_dom_document()
    {
        $dom = DOM::createDocumentFromDom([]);

        $this->assertInstanceOf(DOMDocument::class, $dom);
    }

    /** @test */
    public function it_can_add_child_nodes()
    {
        $dom = DOM::createDocumentFromDom([
            'children' => [
                [
                    'nodeType' => 'div',
                    'uuid' => faker()->uuid,
                ],
                [
                    'nodeType' => 'div',
                    'uuid' => faker()->uuid,
                ],
            ],
        ]);

        $this->assertTrue($dom->hasChildNodes());
        $this->assertCount(2, $dom->childNodes);
    }

    /** @test */
    public function it_removes_reserved_attributes_from_the_child_nodes()
    {
        $dom = DOM::createDocumentFromDom([
            'children' => [
                [
                    'nodeType' => 'div',
                    'uuid' => faker()->uuid,
                    'nodeAttributes' => [
                        'test-attribute' => faker()->name,
                        'contenteditable' => true,
                    ]
                ],
            ],
        ]);

        $node = $dom->childNodes[0]->attributes;

        $this->assertNull($node->getNamedItem('contenteditable'));
        $this->assertNotNull($node->getNamedItem('test-attribute'));
    }
}
