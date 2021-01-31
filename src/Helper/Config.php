<?php
/**
 * @author      Oleh Kravets <oleh.kravets@snk.de>
 * @copyright   Copyright (c) 2021 schoene neue kinder GmbH  (https://www.snk.de)
 * @license     MIT License
 */

namespace Snk\Honeypot\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const EMAIL_NAME_SESSION_KEY = 'email_field_name';

    // Currently only one supported form
    const ACTION_CUSTOMER_CREATE = 'customer_account_createpost';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @param ScopeConfigInterface $config
     * @return void
     */
    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $action
     * @return bool
     */
    public function isEnabledForAction($action)
    {
        $enabledActions = explode(
            ',',
            (string) $this->config->getValue('customer/honeypot/forms', ScopeInterface::SCOPE_STORES)
        );
        return in_array($action, $enabledActions, true);
    }
}
