<?php

namespace backend\base\web;

/**
 * UploadAction is action for uploading files in redactor.
 */
class UploadRedactorAction extends \common\base\web\UploadAction
{
    /**
     * Main task: change response format
     */
    public function run()
    {
        $result = parent::run();

        if ($result['success']) {
            $result = [
                'filename' => $result['file']['name'],
                'filelink' => $result['file']['url'],
            ];
        }

        return $result;
    }

}
