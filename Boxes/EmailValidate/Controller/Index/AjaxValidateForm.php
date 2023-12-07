<?php
namespace Boxes\EmailValidate\Controller\Index;


use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Boxes\EmailValidate\Block\Index\FormValidate;


class AjaxValidateForm extends \Magento\Framework\App\Action\Action{

    /**
     * @var PageFactory
     */
    private PageFactory $pageFactory;

    /**
     * @var JsonFactory
     */
    private JsonFactory $jsonFactory;
    /**
     * @var FormValidate
     */
    private FormValidate $formValidate;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        JsonFactory $jsonFactory,
        FormValidate $formValidate
    )
    {
        $this->pageFactory = $pageFactory;
        $this->jsonFactory = $jsonFactory;
        $this->formValidate = $formValidate;

        parent::__construct($context);
    }

    public function execute()
    {
        // TODO: Implement execute() method.
        $result = $this->jsonFactory->create();
        $email = $this->getRequest()->getParam('email');
        $message = $this->messageManager->getMessages(true);


        return $result->setData([
                'data' => $this->checkEmailExit( $email ),
                'message' => $message
                //'data' => $email
            ]
        );
    }

    private function checkEmailExit( string $email ){

        if( is_array( $formValidate =  $this->formValidate->getEmailDetails()) ){

                if( in_array( $email , $formValidate )){
                    return  true;
                }
        }

        return false;
    }
}
