<?php

namespace aquy\thumbnail;

use Yii;
use yii\helpers\Html;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use Imagine\Image\Color;
use Imagine\Image\Point;

class Thumbnail
{
    const THUMBNAIL_OUTBOUND = ManipulatorInterface::THUMBNAIL_OUTBOUND;
    const THUMBNAIL_INSET = ManipulatorInterface::THUMBNAIL_INSET;

    public static $cashBaseAlias = '@webroot';

    public static $cashWebAlias = '@web';

    public static $cacheAlias = 'assets/thumbnails';

    public static $cacheExpire = 0;

    public static $watermark;

    public static $watermarkConfig = [
        'fontFile' => '@aquy/thumbnail/fonts/OpenSans.ttf',
        'fontSize' => 16,
        'fontColor' => 'ffffff',
        'fontAlpha' => 50,
        'fontAngle' => 0,
        'fontStart' => [0,0]
    ];

    public static function thumbnail($filename, $width, $height, $mode = self::THUMBNAIL_OUTBOUND, $isWatermark = false)
    {
        return Image::getImagine()->open(self::thumbnailFile($filename, $width, $height, $mode, $isWatermark));
    }

    public static function thumbnailFile($filename, $width, $height, $mode = self::THUMBNAIL_OUTBOUND, $isWatermark = false)
    {
        $filename = FileHelper::normalizePath(Yii::getAlias($filename));
        if (!is_file($filename)) {
            throw new FileNotFoundException("File $filename doesn't exist");
        }
        $cachePath = Yii::getAlias(self::$cashBaseAlias . '/' . self::$cacheAlias);

        $thumbnailFileExt = strrchr($filename, '.');
        $thumbnailFileName = md5($filename . $width . $height . $mode . filemtime($filename));
        $thumbnailFilePath = $cachePath . DIRECTORY_SEPARATOR . substr($thumbnailFileName, 0, 2);
        $thumbnailFile = $thumbnailFilePath . DIRECTORY_SEPARATOR . $thumbnailFileName . $thumbnailFileExt;

        if (file_exists($thumbnailFile)) {
            if (self::$cacheExpire !== 0 && (time() - filemtime($thumbnailFile)) > self::$cacheExpire) {
                unlink($thumbnailFile);
            } else {
                return $thumbnailFile;
            }
        }
        if (!is_dir($thumbnailFilePath)) {
            mkdir($thumbnailFilePath, 0755, true);
        }

        $box = new Box($width, $height);
        $imagine = Image::getImagine();
        $image = $imagine->open($filename)->thumbnail($box, $mode);
        $image->save($thumbnailFile);
        if ($isWatermark && self::$watermark) {
            $point = new Point(
                self::$watermarkConfig['fontStart'][0],
                self::$watermarkConfig['fontStart'][1]
            );
            $color = new Color(
                self::$watermarkConfig['fontColor'],
                self::$watermarkConfig['fontSize']
            );
            $font = Image::getImagine()->font(
                Yii::getAlias(self::$watermarkConfig['fontFile']),
                Yii::getAlias(self::$watermarkConfig['fontSize']),
                $color
            );
            $image = Image::getImagine()->open($thumbnailFile);
            $image->draw()->text(
                self::$watermark,
                $font,
                $point,
                self::$watermarkConfig['fontAngle']
            );
            $image->save($thumbnailFile);
        }
        return $thumbnailFile;
    }

    public static function thumbnailFileUrl($filename, $width, $height, $mode = self::THUMBNAIL_OUTBOUND, $isWatermark = false)
    {
        $filename = FileHelper::normalizePath(Yii::getAlias($filename));
        $cacheUrl = Yii::getAlias(self::$cashWebAlias .'/' . self::$cacheAlias);
        $thumbnailFilePath = self::thumbnailFile($filename, $width, $height, $mode, $isWatermark);

        preg_match('#[^\\' . DIRECTORY_SEPARATOR . ']+$#', $thumbnailFilePath, $matches);
        $fileName = $matches[0];

        return $cacheUrl . '/' . substr($fileName, 0, 2) . '/' . $fileName;
    }

    public static function thumbnailImg($filename, $width, $height, $mode = self::THUMBNAIL_OUTBOUND, $options = [], $isWatermark = false)
    {
        $filename = FileHelper::normalizePath(Yii::getAlias($filename));
        try {
            $thumbnailFileUrl = self::thumbnailFileUrl($filename, $width, $height, $mode, $isWatermark);
        } catch (FileNotFoundException $e) {
            return 'File doesn\'t exist';
        } catch (\Exception $e) {
            Yii::warning("{$e->getCode()}\n{$e->getMessage()}\n{$e->getFile()}");
            return 'Error ' . $e->getCode();
        }

        return Html::img(
            $thumbnailFileUrl,
            $options
        );
    }

    public static function clearCache()
    {
        $cacheDir = Yii::getAlias(self::$cashBaseAlias . '/' . self::$cacheAlias);
        self::removeDir($cacheDir);
        return @mkdir($cacheDir, 0755, true);
    }

    protected static function removeDir($path)
    {
        if (is_file($path)) {
            @unlink($path);
        } else {
            array_map('self::removeDir', glob($path . DIRECTORY_SEPARATOR . '*'));
            @rmdir($path);
        }
    }
}
