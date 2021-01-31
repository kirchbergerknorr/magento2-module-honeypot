<?php
declare(strict_types=1);
/**
 * @author      Oleh Kravets <oleh.kravets@snk.de>
 * @copyright   Copyright (c) 2021 schoene neue kinder GmbH  (https://www.snk.de)
 * @license     MIT License
 */

namespace Snk\Honeypot\Dom;

use DOMNode;

/**
 * Represents a part of an HTML document
 * Source does not need to have <html> as root node unlike DOMDocument
 */
class Html extends \DOMDocument
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var bool
     */
    private $htmlTagAdded = false;

    /**
     * @inheritDoc
     */
    public function loadHTML($source, $options = 0)
    {
        // wrap html because DOMDocument needs a root node
        if (strpos($source, '<html') === false) {
            $source = '<html>' . $source . '</html>';
            $this->htmlTagAdded = true;
        }

        // do not throw errors
        libxml_use_internal_errors(true);

        $result = parent::loadHTML(
            mb_convert_encoding($source, 'HTML-ENTITIES', 'UTF-8'),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | $options
        );

        $this->errors = libxml_get_errors();
        libxml_clear_errors();

        return $result;
    }

    /**
     * @param DOMNode|null $node
     * @return string
     */
    public function saveHTML(DOMNode $node = null)
    {
        $result = parent::saveHTML($node);
        // remove the root node if was added
        if ($this->htmlTagAdded) {
            $result = str_replace(['<html>', '</html>'], '', $result);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
