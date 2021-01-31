<?php
/**
 * @author      Oleh Kravets <oleh.kravets@snk.de>
 * @copyright   Copyright (c) 2021 schoene neue kinder GmbH  (https://www.snk.de)
 * @license     MIT License
 */

namespace Snk\Honeypot\Processor;

use Snk\Honeypot\Dom\Html;

class FormHtmlProcessor
{
    const XPATH_PATTERN_EMAIL = "//*[@name='email']";

    /**
     * @param string $html
     * @param string $emailFieldName
     * @return string
     */
    public function process($html, $emailFieldName)
    {
        if (empty($html)) {
            return $html;
        }

        $domHtml = new Html();
        $domHtml->loadHTML($html);

        $finder = new \DomXPath($domHtml);

        $elements = $finder->query(self::XPATH_PATTERN_EMAIL);
        if ($elements->length) {
            /** @var \DOMElement $element */
            $element = $elements[0];
            $element->setAttribute('name', $emailFieldName);
            $element->parentNode->appendChild($this->getFakeEmailElement($domHtml));

            $html = $domHtml->saveHTML();
        }

        return $html;
    }

    /**
     * @param Html $domDocument
     * @return \DOMElement
     */
    private function getFakeEmailElement($domDocument)
    {
        $element = $domDocument->createElement('input');
        $element->setAttribute('name', 'email');
        $element->setAttribute('type', 'email');
        $element->setAttribute('style', 'display: none');
        return $element;
    }
}
