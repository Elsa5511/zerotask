<?php

namespace Application\Utility;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;

/**
 * This class is for handle saving images
 */
class Image
{

    /**
     * this is a reference of different kind of files in order to validate them
     * 
     * Value 	Constant
      1 	IMAGETYPE_GIF
      2 	IMAGETYPE_JPEG
      3 	IMAGETYPE_PNG
      4 	IMAGETYPE_SWF
      5 	IMAGETYPE_PSD
      6 	IMAGETYPE_BMP
      7 	IMAGETYPE_TIFF_II (orden de byte intel)
      8 	IMAGETYPE_TIFF_MM (orden de byte motorola)
      9 	IMAGETYPE_JPC
      10 	IMAGETYPE_JP2
      11 	IMAGETYPE_JPX
      12 	IMAGETYPE_JB2
      13 	IMAGETYPE_SWC
      14 	IMAGETYPE_IFF
      15 	IMAGETYPE_WBMP
      16 	IMAGETYPE_XBM
      17 	IMAGETYPE_ICO
     * 
     * @param string $fileSource
     * @param array $imageTypes
     * @return boolean
     */
    public function imageType($fileSource, $imageTypes = array())
    {
        list($width, $height, $type, $attr) = getimagesize($fileSource);

        if (!in_array($type, $imageTypes)) {
            return false;
        }
        return true;
    }

    public function deleteImage($fileSource)
    {
        $extension = pathinfo($fileSource, PATHINFO_EXTENSION);

        $thumbSource = str_replace('.'.$extension, '-thumb.'.$extension, $fileSource);
        if (file_exists($thumbSource) && is_file($thumbSource)) {
            unlink($thumbSource);
        }

        if (file_exists($fileSource) && is_file($fileSource)) {
            unlink($fileSource);
            return true;
        }
        return false;
    }

    /**
     * This method is for resizing images. It uses  Imagine library and only are 
     * posible extensions like png,gif and jpg
     * 
     * @param string $fileSource
     * @param int $limitSize
     * @param string $destination
     * @param boolean $changeName
     */
    public function resizeImage($fileSource, $limitSize, $destination, $changeName = true)
    {

        $imagine = new Imagine();

        $image = $imagine->open($fileSource);

        $size = $image->getSize();
        $initWidth = $size->getWidth();
        $initHeight = $size->getHeight();

        /* Image is Landscape */
        if ($initWidth >= $initHeight and $initWidth > $limitSize):
            $finalWidth = $limitSize;
            $finalHeight = $initHeight * $limitSize / $initWidth;
        elseif ($initWidth < $initHeight and $initHeight > $limitSize):/* Image is  Portrait */
            $finalHeight = $limitSize;
            $finalWidth = $initWidth * $limitSize / $initHeight;
        else:
            $finalHeight = $initHeight;
            $finalWidth = $initWidth;
        endif;
        $options = array(
            'resolution-units' => ImageInterface::RESOLUTION_PIXELSPERINCH,
            'quality' => 80,
        );
        $imageInformation = pathinfo($destination);
        $fileName = $imageInformation['filename'];
        $fileExtension = $imageInformation['extension'];
        if ($changeName === true) {
            $fileName = sha1(time() . $fileName);
            $destination = $imageInformation['dirname'] . '/' . $fileName . '.' . $fileExtension;
            $destinationThumb = $imageInformation['dirname'] . '/' . $fileName . '-thumb.' . $fileExtension;
        }

        $saveOriginal = $image->resize(new Box($finalWidth, $finalHeight))->save($destination, $options);
        $saveThumb = $image->resize(new Box(300, $finalHeight/$finalWidth*300))->save($destinationThumb, $options);

        if ($saveOriginal && $saveThumb) {
            return $fileName . '.' . $fileExtension;
        }
        return false;
    }

    public static function hasImageFormat($fileName)
    {
        $fileInfo = pathinfo($fileName);
        $extension = $fileInfo['extension'];
        return in_array(strtolower($extension), array('jpg', 'gif', 'jpeg', 'png'));
    }

}