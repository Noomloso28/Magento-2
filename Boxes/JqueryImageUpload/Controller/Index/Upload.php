<?php declare(strict_types=1);

namespace Boxes\JqueryImageUpload\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Upload extends Action {


    /**
     * @var UploaderFactory
     */
    protected UploaderFactory $_fileUploaderFactory;

    /**
     * @var Filesystem
     */
    private Filesystem $_filesystem;

    /**
     * @var WriterInterface
     */
    private WriterInterface $write;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;


    public function __construct(
        Context $context,
        UploaderFactory $fileUploaderFactory ,
        Filesystem $_filesystem,
        WriterInterface $writer,
        RedirectFactory $redirectFactory
    )
    {

        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_filesystem = $_filesystem;
        $this->write = $writer;
        $this->redirectFactory = $redirectFactory;

        parent::__construct($context);
    }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     * @throws \Exception
     */
    public function execute()
    {

        try {

            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image-file']);

            var_dump( $uploader );
            return;

            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

            $uploader->setAllowRenameFiles(false);

            $uploader->setFilesDispersion(false);

            $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)

                ->getAbsolutePath('images/');

            $uploader->save($path);

            if( $getUploadedFileName = $uploader->getUploadedFileName() ){

                /*
                 * Save Value to db.
                 */
                    $this->SaveValueToDb( DirectoryList::MEDIA.'/images/'.$getUploadedFileName );

                    $this->messageManager->addSuccessMessage('Uploaded had done.');
                    $redirect = $this->redirectFactory->create();
                    $redirect->setPath('*/*/index');
                    return $redirect;

            }



        } catch (Exception $e) {

            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
            die( $result );
        }
       // return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);

        /*
        return $this->_redirect(
            'jqueryimageupload/index/upload',
            ['params' => array()]
        );
        */
    }

    /**
     * @param string $value
     * @return bool
     */
    protected function SaveValueToDb( string  $value ): bool {

        $this->write->save('jQueryImageUpload/image/url' , $value );

        return true;
    }
}
