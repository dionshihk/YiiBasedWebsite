<?php

class ImageTools
{
    public static function generateThumbInDb($model, $isReview)
    {
        //$model could be review or competition work

        $imagePic = $isReview ? ImageTools::getFirstImageFromReview($model) : ImageTools::getFirstImageFromCompetitionWork($model);

        try
        {
            if(!$imagePic) throw new Exception('First image not found');

            $pathInfo = pathinfo($imagePic);
            $ext = strtolower($pathInfo["extension"]);
            $destFile = '/uploadThumb/'.Tools::makeUniqueFileName().'.'.$ext;

            ImageTools::resizeImageInScale($imagePic, 100, 400, Tools::getAbsUrl($destFile));

            $model->thumb_url = $destFile;
            Tools::saveModel($model);

            return $destFile;
        }
        catch(Exception $ex)
        {
            Tools::log('Fail to generate thumb for '.get_class($model).' #'.$model->id.
                ': <i>'.$ex->getMessage().'</i>');

            return '';
        }
    }

    public static function isImageExtension($f)
    {
        $pathInfo = pathinfo($f);
        $ext = strtolower($pathInfo["extension"]);

        return in_array($ext, array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'tiff'));
    }

    public static function checkImageFormat($file)
    {
        //$file here is a relative path

        if(!$file) throw new Exception('No file detected');

        $file = Tools::getAbsUrl($file);
        if(!file_exists($file)) throw new Exception('File path invalid');
        if(filesize($file) < 20) throw new Exception('File too small to process');

        $type = exif_imagetype($file);
        if(!in_array($type, array(IMAGETYPE_JPEG , IMAGETYPE_PNG, IMAGETYPE_GIF), true)) throw new Exception('Unrecognizable image format');
    }

    public static function resizeImage($file, $minSize, $destWidth, $destHeight = 0, $destFile = null)
    {
        //May throw Exception for invalid image

        if($destHeight === 0) $destHeight = $destWidth;

        ImageTools::checkImageFormat($file);
        $relFile = $file;
        $file = Tools::getAbsUrl($file);
        $type = exif_imagetype($file);

        switch($type)
        {
            case IMAGETYPE_JPEG :
                $srcImg = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG :
                $srcImg = imagecreatefrompng($file);
                break;
            case IMAGETYPE_GIF:
                $srcImg = imagecreatefromgif($file);
                break;
        }

        $srcW = imagesx($srcImg);
        $srcH = imagesy($srcImg);
        if($srcH < $minSize || $srcW < $minSize) throw new Exception('Image size should be '.$minSize.'x'.$minSize.' pixel at least');

        $newImg = imagecreatetruecolor($destWidth, $destHeight);
        $white = imagecolorallocate($newImg, 255, 255, 255);
        imagefill($newImg, 0, 0, $white);
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $destWidth, $destHeight, $srcW, $srcH);
        imagejpeg($newImg, $destFile ? $destFile : $file, 100);

        Tools::log('Resize image ['.$srcW.'x'.$srcH.'] to ['.$destWidth.'x'.$destHeight.']: <i>'.$relFile.'</i>');
    }

    public static function resizeImageInScale($file, $minSize, $maxSize, $destFile = null)
    {
        //May throw Exception for invalid image
        //Return dest-image in (w, h) array

        ImageTools::checkImageFormat($file);
        $relFile = $file;
        $file = Tools::getAbsUrl($file);
        $type = exif_imagetype($file);

        switch($type)
        {
            case IMAGETYPE_JPEG :
                $srcImg = imagecreatefromjpeg($file);
                break;
            case IMAGETYPE_PNG :
                $srcImg = imagecreatefrompng($file);
                break;
            case IMAGETYPE_GIF:
                $srcImg = imagecreatefromgif($file);
                break;
        }

        $srcW = imagesx($srcImg);
        $srcH = imagesy($srcImg);


        if($srcH < $minSize || $srcW < $minSize) throw new Exception('Image size should be '.$minSize.'x'.$minSize.' pixel at least');

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

        Tools::log('Rescale image ['.$srcW.'x'.$srcH.'] to ['.$ret[0].'x'.$ret[1].']: <i>'.$relFile.'</i>');

        $newImg = imagecreatetruecolor($ret[0], $ret[1]);
        $white = imagecolorallocate($newImg, 255, 255, 255);
        imagefill($newImg, 0, 0, $white);
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $ret[0], $ret[1], $srcW, $srcH);
        imagejpeg($newImg, $destFile ? $destFile : $file, 100);

        return $ret;
    }

    public static function getFirstImageFromReview($review)
    {
        $c = $review->content;
        if(preg_match_all('/src=(\'|")(.*?)\1/i', $c, $matches))
        {
            foreach ($matches[2] as $v)
            {
                $v0 = strtolower($v);
                if(strpos($v0, '.gif') === false)
                {
                    //In case of emotions (GIF formats)
                    return $v;
                }
            }
        }

        return '';
    }

    public static function getFirstImageFromCompetitionWork($model)
    {
        $c = json_decode($model->data);
        foreach($c as $item)
        {
            if($item->pic)
            {
                return $item->pic;
            }
        }

        return '';
    }
} 