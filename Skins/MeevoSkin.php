<?php

namespace Meevo\Skin\Skins;

use Backend\Classes\Skin;
use October\Rain\Router\Helper as RouterHelper;

class MeevoSkin extends Skin
{
    public function __construct()
    {
        $this->skinPath = $this->defaultSkinPath = plugins_path() . '/meevo/skin/res';
        $this->publicSkinPath = $this->defaultPublicSkinPath = \File::localToPublic($this->skinPath);
    }

    public function skinDetails()
    {
        return [
            'name' => 'Meevo Skin'
        ];
    }

    /**
     * @inheritDoc
     */
    public function getPath($path = null, $isPublic = false)
    {
        $path = RouterHelper::normalizeUrl($path);

        return $isPublic
            ? $this->defaultPublicSkinPath . $path
            : $this->defaultSkinPath . $path;
    }

    /**
     * @inheritDoc
     */
    public function getLayoutPaths()
    {
        return [$this->skinPath.'/layouts'];
    }
}
