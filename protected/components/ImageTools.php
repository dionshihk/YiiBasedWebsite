<?php

class ImageTools
{
    public static function isImageExtension($f)
    {
        $pathInfo = pathinfo($f);
        $ext = strtolower($pathInfo["extension"]);

        return in_array($ext, array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'tiff'));
    }

    public static function checkImageFormat($file)
    {
        //$file here is a relative path

        if(!$file) throw new Exception('No image file detected');

        $absFilePath = Tools::getAbsUrl($file);
        if(!file_exists($absFilePath)) throw new Exception('File ['.$file.'] no exist');
        if(filesize($absFilePath) < 20) throw new Exception('File ['.$file.'] too small');

        $type = exif_imagetype($absFilePath);
        if(!in_array($type,
            array(IMAGETYPE_JPEG , IMAGETYPE_PNG, IMAGETYPE_GIF),
            true)) throw new Exception('Unrecognizable image format ['.$file.']');
    }

    public static function getImageSize($file)
    {
        //May throw Exception for invalid image
        //Return array(imageObject, w, h, needWhiteFill)

        ImageTools::checkImageFormat($file);
        $file = Tools::getAbsUrl($file);
        $type = exif_imagetype($file);
        $needWhiteFill = false;

        switch($type)
        {
            case IMAGETYPE_JPEG :
                $srcImg = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG :
                $srcImg = imagecreatefrompng($file);
                $needWhiteFill = true;
                break;
            case IMAGETYPE_GIF:
                $srcImg = imagecreatefromgif($file);
                $needWhiteFill = true;
                break;
        }

        $srcW = imagesx($srcImg);
        $srcH = imagesy($srcImg);

        return array($srcImg, $srcW, $srcH, $needWhiteFill);
    }

    public static function cropImage($relFile, $w, $h, $x, $y, $targetWidth, $targetHeight)
    {
        //May throw Exception for invalid image
        //$file should be JPEG only

        $file = Tools::getAbsUrl($relFile);
        if(exif_imagetype($file) != IMAGETYPE_JPEG) throw new Exception("Image ['.$file.'] not JPEG format");

        $srcImg = imagecreatefromjpeg($file);
        $destImg = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($destImg, $srcImg, 0, 0, $x, $y, $targetWidth, $targetHeight, $w, $h);

        imagejpeg($destImg, $file, 100);

        Tools::log('Crop image ['.$x.'(+'.$w.') x '.$y.'(+'.$h.')] to ['.$targetWidth.'x'.$targetHeight.']: <i>'.$relFile.'</i>');
    }

    public static function resizeImageInScale($file, $minSize, $maxSize, $destFile = null)
    {
        //May throw Exception for invalid image
        //Return dest-image in (w, h) array

        list($srcImg, $srcW, $srcH, $needWhiteFill) = ImageTools::getImageSize($file);
        if($srcH < $minSize || $srcW < $minSize) throw new Exception('['.$file.'] '.$srcW.'x'.$srcH.' less than '.$minSize.'x'.$minSize);

        $ratio = $srcW / $srcH;
        $ret = array();
        if ($srcW <= $maxSize && $srcH <= $maxSize)
        {
            $ret[0] = $srcW;
            $ret[1] = $srcH;
        }
        elseif ($srcW > $srcH)
        {
            $ret[0] = $maxSize;
            $ret[1] = (int)($maxSize / $ratio);
        }
        else
        {
            $ret[0] = (int)($maxSize * $ratio);
            $ret[1] = $maxSize;
        }

        if($ret[0] < $minSize || $ret[1] < $minSize) throw new Exception('['.$file.'] '.$ret[0].'x'.$ret[1].' less than '.$minSize.'x'.$minSize.' after scale');

        $newImg = imagecreatetruecolor($ret[0], $ret[1]);
        if($needWhiteFill)
        {
            $white = imagecolorallocate($newImg, 255, 255, 255);
            imagefill($newImg, 0, 0, $white);
        }

        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $ret[0], $ret[1], $srcW, $srcH);
        imagejpeg($newImg, Tools::getAbsUrl($destFile ? $destFile : $file), 100);

        Tools::log('Rescale image ['.$srcW.'x'.$srcH.'] to ['.$ret[0].'x'.$ret[1].']: <i>'.$file.'</i>');

        return $ret;
    }

    public static function resizeImageInScaleByWidth($file, $minWidth, $maxWidth, $minHeight)
    {
        //May throw Exception for invalid image
        //Return resized image in (w, h) array, where w in [minW, maxW], h > minH

        list($srcImg, $srcW, $srcH, $needWhiteFill) = ImageTools::getImageSize($file);
        if($srcW < $minWidth) throw new Exception('['.$file.'] width '.$srcW.' less than '.$minWidth);

        $ret = array($srcW, $srcH);
        if($srcW > $maxWidth)
        {
            $ret[0] = $maxWidth;
            $ret[1] = $srcH / $srcW * $maxWidth;
        }

        if($ret[1] < $minHeight) throw new Exception('['.$file.'] target height '.$ret[1].' less than '.$minHeight);

        $newImg = imagecreatetruecolor($ret[0], $ret[1]);
        if($needWhiteFill)
        {
            $white = imagecolorallocate($newImg, 255, 255, 255);
            imagefill($newImg, 0, 0, $white);
        }

        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $ret[0], $ret[1], $srcW, $srcH);
        imagejpeg($newImg, Tools::getAbsUrl($file), 100);

        Tools::log('Rescale image ['.$srcW.'x'.$srcH.'] to ['.$ret[0].'x'.$ret[1].']: <i>'.$file.'</i>');

        return $ret;
    }
} 