<?php
/**
 * @author      Oleh Kravets <oleh.kravets@snk.de>
 * @copyright   Copyright (c) 2021 schoene neue kinder GmbH  (https://www.snk.de)
 * @license     MIT License
 */

namespace Snk\Honeypot\Plugin\Block\Form;

use Magento\Customer\Block\Form\Register;
use Magento\Customer\Model\Session;
use Snk\Honeypot\Helper\Config;
use Snk\Honeypot\Processor\FormHtmlProcessor;

class RegisterPlugin
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var FormHtmlProcessor
     */
    private $htmlProcessor;
    /**
     * @var Session
     */
    private $session;

    public function __construct(Config $config, FormHtmlProcessor $htmlProcessor, Session $session)
    {
        $this->config = $config;
        $this->htmlProcessor = $htmlProcessor;
        $this->session = $session;
    }

    /**
     * @param Register $subject
     * @param $result
     * @return string
     */
    public function afterToHtml(Register $subject, $result): string
    {
        if (!$this->config->isEnabledForAction(Config::ACTION_CUSTOMER_CREATE)) {
            return $result;
        }

        $newEmailName = sha1(time());
        $result = $this->htmlProcessor->process($result, $newEmailName);

        $this->session->setData(Config::EMAIL_NAME_SESSION_KEY, $newEmailName);

        return $result;
    }
}
