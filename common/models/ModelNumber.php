<?php

namespace common\models;

use Yii;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\Manufacturer;

/**
 * This is the model class for table "model_number".
 *
 * @property integer $id
 * @property string $value
 * @property integer $manufacturer_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Manufacturer $manufacturer
 */
class ModelNumber extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'model_number';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value', 'manufacturer_id'], 'required'],
            [['value'], 'string', 'max' => 255],
            [['manufacturer_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'manufacturer_id' => 'Manufacturer',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getManufacturer()
    {
        return $this->hasOne(Manufacturer::className(), ['id' => 'manufacturer_id']);
    }

    public static function findOrCreateByValueAndManufacturer($value, $manufacturer_id)
    {
        if (!$value) {
            return null;
        }
        $model_number = static::findOne(['value' => $value, 'manufacturer_id' => $manufacturer_id]);
        if ($model_number) {
            return $model_number;
        }
        if ($manufacturer_id) {
            $manufacturer = Manufacturer::findOne(['id' => $manufacturer_id]);
        }
        $model_number = new static();
        $model_number->value = $value;
        $model_number->manufacturer_id = $manufacturer ? $manufacturer->id : null;
        $model_number->save();
        return $model_number;
    }

}
