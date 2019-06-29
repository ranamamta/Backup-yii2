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
class Variant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'variant';
    }

  

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'title', 'price', 'inventory_item_id','inventory_quantity','old_inventory_quantity', 'variants_id','queenLevel','ronLevel','wilsonLevel'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
       
    }
}
