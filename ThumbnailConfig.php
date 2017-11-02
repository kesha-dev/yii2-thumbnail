<?php

namespace aquy\thumbnail;

use yii\base\Object;

class ThumbnailConfig extends Object
{

    public $cashBaseAlias = '@webroot';

    public $cashWebAlias = '@web';

    public $cacheAlias = 'assets/thumbnails';

    public $cacheExpire = 0;

    public $watermark;

    public $fontFile = '@aquy/thumbnail/fonts/OpenSans.ttf';

    public $fontSize = 16;

    public $fontColor = 'ffffff';

    public $fontAlpha = 50;

    public $fontAngle = 0;

    public $fontStart = [0,0];

    public $quality = 85;

    public $color = ['ffffff', 100];

    public function init()
    {
        Thumbnail::$cashBaseAlias = $this->cashBaseAlias;
        Thumbnail::$cashWebAlias = $this->cashWebAlias;
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
        Thumbnail::$quality = $this->quality;
        Thumbnail::$color = $this->color;
    }
}