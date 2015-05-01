<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chipset".
 *
 * @property integer $id
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class Chipset extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chipset';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'value' => 'Value',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
