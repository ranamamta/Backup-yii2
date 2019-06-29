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

class AppSetup extends \yii\db\ActiveRecord

{

    /**

     * @inheritdoc

     */

    public static function tableName()

    {

        return 'store';

    }



 public function get_inventory($loc_id){
  $session = Yii::$app->session;
  $session->open();
  $shop_name = $session->get('shop');
  $app_install = AppSetup::find()->Where(['id' => 1])->one();
  $shop = $app_install['shop'];
  $token = $app_install['token'];
  $sc = new Shopify($shop,$token, Yii::$app->params['APP_KEY'], Yii::$app->params['SECRET_KEY']);
  $loc_inventory_details = $sc->call('GET', '/admin/api/2019-04/locations/'.$loc_id.'/inventory_levels.json');
  // $loc_inventory_details = $sc->call('GET', '/admin/api/2019-04/inventory_levels.json?inventory_item_id='.$inventory_item_id.'&location_id='.$loc_id);

   // echo '<pre>';

   // print_r($loc_inventory_details); exit;

   return $loc_inventory_details;
}




    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            [['shop', 'token', 'code', 'status'], 'string', 'max' => 255],

        ];

    }



    /**

     * @inheritdoc

     */

    public function attributeLabels()

    {

       return [

            'id' => 'ID',

            'shop' => 'Shop',

            'token' => 'Token',

            'code' => 'Code'

        ];

       

    }

}

