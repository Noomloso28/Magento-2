<?php

namespace DesignPattern\ApiTutorial\Controller\Git;

use DesignPattern\ApiTutorial\Service\GitApiService;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;


class Index extends Action
{

    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @var array
     */
    private array $apiService;


    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        GitApiService $apiService
    ) {
        $this->pageFactory = $pageFactory;
        $this->apiService = $apiService->execute();

        parent::__construct($context);
    }

    public function execute()
    {
        echo '<pre>';
            print_r($this->getData('status'));
            print_r($this->getData('body'));
            print_r($this->getData('content'));
        echo '</pre>';

        return $this->pageFactory->create();
    }

    /**
     * Retrieve Git data.
     *
     * @param string $key
     * @return mixed
     */
    private function getData(string $key)
    {
        return $this->apiService[$key] ?? '';
    }
}
