<?php

namespace aquy\thumbnail;

use yii\base\Object;

class ThumbnailConfig extends Object
{

    public $cacheAlias = 'assets/thumbnails';

    public $cacheExpire = 0;

    public $watermark;

    public $watermarkConfig = [
        'fontFile' => 'fonts/OpenSans.ttf',
        'fontSize' => 16,
        'fontColor' => '000',
        'fontAlpha' => 100,
        'fontAngle' => 0,
        'fontStart' => [0,0]
    ];

    public function init()
    {
        Thumbnail::$cacheAlias = $this->cacheAlias;
        Thumbnail::$cacheExpire = $this->cacheExpire;
        Thumbnail::$watermark = $this->watermark;
        Thumbnail::$watermarkConfig = $this->watermarkConfig;
    }
}