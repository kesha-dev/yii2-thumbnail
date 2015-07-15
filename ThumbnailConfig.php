<?php

namespace aquy\thumbnail;

use yii\base\Object;

class ThumbnailConfig extends Object
{

    public $cacheAlias = 'assets/thumbnails';

    public $cacheExpire = 0;
    public function init()
    {
        Thumbnail::$cacheAlias = $this->cacheAlias;
        Thumbnail::$cacheExpire = $this->cacheExpire;
    }
}