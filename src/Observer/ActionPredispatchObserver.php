<?php
/**
 * @author      Oleh Kravets <oleh.kravets@snk.de>
 * @copyright   Copyright (c) 2021 schoene neue kinder GmbH  (https://www.snk.de)
 * @license     MIT License
 */

namespace Snk\Honeypot\Observer;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\ActionFlag;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Snk\Honeypot\Helper\Config;

class ActionPredispatchObserver implements ObserverInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @var RedirectInterface
     */
    private $redirect;
    /**
     * @var ActionFlag
     */
    private $actionFlag;

    public function __construct(
        Config $config,
        Session $session,
        ManagerInterface $messageManager,
        ResponseFactory $responseFactory,
        RedirectInterface $redirect,
        ActionFlag $actionFlag
    ) {
        $this->session = $session;
        $this->config = $config;
        $this->messageManager = $messageManager;
        $this->responseFactory = $responseFactory;
        $this->redirect = $redirect;
        $this->actionFlag = $actionFlag;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Http $request */
        $request = $observer->getEvent()->getData('request');

        if ($request->getParam('email') && $this->config->isEnabledForAction($request->getFullActionName())) {
            $this->messageManager->addErrorMessage('Please, check your data again.');
            $this->responseFactory->create()->setRedirect($this->redirect->getRefererUrl())->sendResponse();
            $this->actionFlag->set('', Action::FLAG_NO_DISPATCH, true);
            return;
        }

        $emailName = $this->session->getData(Config::EMAIL_NAME_SESSION_KEY);
        $request->setPostValue('email', $request->getParam($emailName));
    }
}
