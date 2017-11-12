<?php

namespace Phosphorum\Utils;

use PHPThumb\GD;

class WzImageHelper
{
    const IMAGE_STAGE_FOLDER = 'stage/';

    public static function handleAvatar($baseLocation, $file, $rawId)
    {
        // Generate unique temp filename
        $ext = pathinfo($file->getName(), PATHINFO_EXTENSION);
        $stageImage = $baseLocation . uniqid() . '.' . $ext;

        // Move the avatars root folder
        //$file->moveTo($stageImage);
        move_uploaded_file($file['tmp_name'], $target);

        // Generate sub folder for avatars
        $thumbPath = $baseLocation.substr($rawId, 0, 3);

        if (!file_exists($thumbPath)) {
            mkdir($thumbPath, 0777, true);
        }

        // Use PHPThumb GD genreate thumbnail
        $absoluteFilename = BASE_DIR.'public/'.$stageImage;

        $thumb = new GD($absoluteFilename);

        $thumb->adaptiveResize(120, 120);
        $thumb->save($thumbPath.'/'.$rawId.'-120x120@2x.png');

        $thumb->adaptiveResize(60, 60);
        $thumb->save($thumbPath.'/'.$rawId.'-60x60@2x.png');

        // Delete temporary file
        unlink($absoluteFilename);
    }
}
