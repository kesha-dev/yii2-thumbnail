<?php

namespace aquy\thumbnail;

use yii\base\Object;

class ThumbnailConfig extends Object
{

    public $cacheAlias = 'assets/thumbnails';

    public $cacheExpire = 0;

    public $watermark;

    public $fontFile = '@aquy/thumbnail/fonts/OpenSans.ttf';

    public $fontSize = 16;

    public $fontColor = 'ffffff';

    public $fontAlpha = 50;

    public $fontAngle = 0;

    public $fontStart = [0,0];

    public function init()
    {
        Thumbnail::$cacheAlias = $this->cacheAlias;
        Thumbnail::$cacheExpire = $this->cacheExpire;
        Thumbnail::$watermark = $this->watermark;
        Thumbnail::$watermarkConfig = [
            'fontFile' => $this->fontFile,
            'fontSize' => $this->fontSize,
            'fontColor' => $this->fontColor,
            'fontAlpha' => $this->fontAlpha,
            'fontAngle' => $this->fontAngle,
            'fontStart' => $this->fontStart
        ];
    }
}