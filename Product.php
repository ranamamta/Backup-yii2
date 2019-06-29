<?php

namespace app\models;

use Yii;
use app\components\Shopify;

/**
 * This is the model class for table "store".
 *
 * @property integer $id
 * @property string $shop
 * @property string $token
 * @property string $code
 * @property string $status
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'title', 'image_src','vendor', 'product_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
       
    }
}
