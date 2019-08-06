<?php

namespace Workspace\Library;

use Psr\Http\Message\UploadedFileInterface;

class Helpers
{

    public static function DebugDie($data){
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die();
    }

    public static function DateTime(){
        $currentTime=time();
        return strftime('%Y-%m-%d %H:%M:%S',$currentTime);
    }

    public static function moveUploadedFile($directory, UploadedFileInterface $uploadedFile)
    {
        $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
        $basename = bin2hex(random_bytes(8)); // see http://php.net/manual/en/function.random-bytes.php
        $filename = sprintf('%s.%0.8s', $basename, $extension);

        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return $filename;
    }
}