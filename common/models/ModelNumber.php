<?php

namespace app\models;

use Yii;

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
class ModelNumber extends \yii\db\ActiveRecord
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
            [['manufacturer_id'], 'exist', 'targetClass' => Manufacturer::className(), 'targetAttribute' => 'id'],
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
            'manufacturer_id' => 'Manufacturer ID',
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
}
