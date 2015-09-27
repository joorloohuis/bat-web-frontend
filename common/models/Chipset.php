<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "chipset".
 *
 * @property integer $id
 * @property string $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class Chipset extends ActiveRecord
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

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
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

    public static function findOrCreateByValue($value)
    {
        if (!$value) {
            return null;
        }
        $chipset = static::findOne(['value' => $value]);
        if ($chipset) {
            return $chipset;
        }
        $chipset = new static();
        $chipset->value = $value;
        $chipset->save();
        return $chipset;
    }

}
