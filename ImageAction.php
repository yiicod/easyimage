<?php

namespace yiicod\easyimage;

use CAction;
use Exception;
use Yii;

/**
 * Class ImageAction
 * @deprecated since version 0.2.5
 */
class ImageAction extends CAction
{

    /**
     * @author Dmitry Semenov <disemx@gmail.com>
     */
    public function run($params)
    {
        try {
            $eImage = Yii::app()->easyImage;
            //decrypt
            $params = @mcrypt_decrypt(MCRYPT_3DES, $eImage->password, urldecode($params), MCRYPT_MODE_ECB);
            $params = @unserialize(rtrim($params, "\0"));
            $image = @$eImage->thumbSrcOf($params['f'], $params['p'], '');
            if (!empty($image['cacheFile'])) {
                header('Content-Type: image/jpeg');
                @readfile($image['cacheFile']);
                Yii::app()->end();
            }
        } catch (Exception $e) {
            Yii::log($e->getMessage());
        }
        Yii::log(serialize($_GET));
        $this->emptyImage();
        Yii::app()->end();
    }

    /**
     * Renders empty image
     */
    private function emptyImage()
    {
        header('Content-Type: image/png');
        echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');
        Yii::app()->end();
    }

}
