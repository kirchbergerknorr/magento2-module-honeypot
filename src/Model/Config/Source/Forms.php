<?php
/**
 * @author      Oleh Kravets <oleh.kravets@snk.de>
 * @copyright   Copyright (c) 2021 schoene neue kinder GmbH  (https://www.snk.de)
 * @license     MIT License
 */

namespace Snk\Honeypot\Model\Config\Source;

use Snk\Honeypot\Helper\Config;

class Forms implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => Config::ACTION_CUSTOMER_CREATE, 'label' => __('Create Account')],
        ];
    }
}
