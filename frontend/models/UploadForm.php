<?php

namespace frontend\models;

class UploadForm extends \yii\base\Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => '',
        ];
    }


}
