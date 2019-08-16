<?php

namespace App\Support;

use DOMDocument;

class DOM
{
    protected static $reservedAttributes = [
        'contenteditable',
    ];

    public static function createDocumentFromDom(array $dom): DOMDocument
    {
        $document = new DOMDocument();

        if (isset($dom['children'])) {
            foreach ($dom['children'] as $child) {
                $document->appendChild(
                    static::createDomNode($child, $document)
                );
            }
        }

        return $document;
    }

    public static function createDomNode(array $node, DOMDocument $document)
    {
        $element = $document->createElement(strtolower($node['nodeType']));
        $element->setAttribute('data-id', $node['uuid']);

        if (isset($node['textContent'])) {
            $element->textContent = $node['textContent'];
        }

        if (isset($node['nodeAttributes'])) {
            foreach ($node['nodeAttributes'] as $attribute => $value) {
                if (! empty($value) && ! in_array($attribute, static::$reservedAttributes)) {
                    $element->setAttribute($attribute, $value);
                }
            }
        }

        if (isset($node['children'])) {
            foreach ($node['children'] as $child) {
                $element->appendChild(static::createDomNode($child, $document));
            }
        }

        return $element;
    }
}
