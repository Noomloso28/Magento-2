<?php

namespace Immerce\FaktumNavConnector\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;

class ProductImageGenerator
{
    CONST IMAGE_DIRECTORY =  'nav-connector/import/products/';
    CONST MAX_IMAGE_SIZE = 1024 * 1024;
    CONST RESIZE_IMG_WIDTH = 1024;
    CONST RESIZE_IMG_HEIGHT = 1024;

    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    public function __construct(
        Filesystem $filesystem
    )
    {
        $this->filesystem = $filesystem;
    }


    public function execute()
    {
        $directoryUrl = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR)->getAbsolutePath() . self::IMAGE_DIRECTORY;
        $files = glob($directoryUrl."*.{jpg,jpeg,png,gif,JPG,JPEG,PNG,GIF}", GLOB_BRACE );
        $count = count($files);
        if($count > 0){
            $i = 1;
            exec(\Safe\sprintf('\r\n\t'));
            foreach ($files as $imageUrl) {
                $image = pathinfo($imageUrl);

                $destinationUrl = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath().'product_images/'.$image['basename'];

                /** check before upload has exit file */
                if(!$this->isExitImage($imageUrl, $destinationUrl)){

                    /** check image size before move if over limit , must resize */
                    if($this->isFileSizeOverLimit($imageUrl)){
                        $resize = $this->resizeImage($imageUrl, $destinationUrl, $image);
                        if($resize){
                            \Safe\unlink($imageUrl);
                        }
                    }else{
                        /** Move old image to new destination */
                        \Safe\rename($imageUrl, $destinationUrl);
                    }
                }
                exec(\Safe\sprintf('mogrify %s', $destinationUrl));

                echo $this->progressBar($i, $count) ;
                $i++;
            }
        }
    }
    private function resizeImage(string $url, string $destinationUrl, array $attributes): bool
    {
        // Load the image based on the file extension
        if (in_array($attributes['extension'], ['jpg', 'jpeg', 'JPG', 'JPEG'])) {
            $src = \Safe\imagecreatefromjpeg($url);
        } elseif (in_array($attributes['extension'], ['png', 'PNG'])) {
            $src = \Safe\imagecreatefrompng($url);
        } elseif (in_array($attributes['extension'], ['gif', 'GIF'])) {
            $src = \Safe\imagecreatefromgif($url);
        } else {
            return false;
        }

        // Get the current dimensions of the source image
        $srcWidth = \Safe\imagesx($src);
        $srcHeight = \Safe\imagesy($src);


        // Calculate ratio of desired maximum sizes and original sizes.
        $widthRatio = self::RESIZE_IMG_WIDTH / $srcWidth;
        $heightRatio = self::RESIZE_IMG_HEIGHT / $srcHeight;

        // Ratio used for calculating new image dimensions.
        $ratio = min($widthRatio, $heightRatio);

        $newWidth  = (int) $srcWidth  * $ratio;
        $newHeight = (int) $srcHeight * $ratio;

        // Create a new image with the calculated dimensions
        $dst =\Safe\imagecreatetruecolor($newWidth, $newHeight);

        // Resize the source image to the new dimensions
        \Safe\imagecopyresized($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $srcWidth, $srcHeight);

        // Save or output the resized image
        \Safe\imagejpeg($dst, $destinationUrl);

        // Clean up resources
        \Safe\imagedestroy($dst);
        \Safe\imagedestroy($src);

        return true;
    }

    /**
     * Check exit image.
     * @param string $imageUrl
     * @param string $destinationUrl
     * @return bool
     * @throws \Safe\Exceptions\FilesystemException
     */
    private function isExitImage(string $imageUrl, string $destinationUrl) : bool
    {
        /** Check exit file */
        if (!file_exists($destinationUrl)) {
            return false;
        }else{
            $imageSize = (int) \Safe\filesize($imageUrl);
            $destinationSize = (int) \Safe\filesize($destinationUrl);
            if($imageSize != $destinationSize){
                \Safe\unlink($destinationUrl); //remove duplicate image for new upload.
                return false;
            }
        }
        return true;
    }

    /**
     * Check file size Over condition ??
     * @param string $imageUrl
     * @return bool
     * @throws \Safe\Exceptions\FilesystemException
     */
    private function isFileSizeOverLimit(string $imageUrl): bool
    {
        if(\Safe\filesize($imageUrl) > self::MAX_IMAGE_SIZE){
            return true;
        }
        return false;
    }

    /**
     * Outputs the progress in the console.
     *
     * @param  int $done
     * @param  int $total
     * @param  string $info
     * @param  int $width
     * @return string
     */
    protected function progressBar(int $done, int $total, string $info = '', $width = 50)
    {
        $perc = round(($done * 100) / $total);
        $bar  = (int) round(($width * $perc) / 100);
        return sprintf("Product images uploading : %s%%[%s>%s]%s\r", $perc, str_repeat('=', $bar), str_repeat(' ', $width-$bar), $info);
    }
}
