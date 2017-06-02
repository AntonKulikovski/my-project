<?php

namespace common\base\file;

/**
 * DefaultStorageTrait use [[FileSystemStorage]] as default storage.
 *
 * @author SeynovAM <sejnovalexey@gmail.com>
 * 
 * @property FileSystemStorage $storage Storage for keeping files.
 * @method FileSystemStorage getStorage() Storage for keeping files.
 */
trait DefaultStorageTrait
{
    /**
     * @inheritdoc
     */
    protected function defaultStorage()
    {
        return FileSystemStorage::className();
    }
}
