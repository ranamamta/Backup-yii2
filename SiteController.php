<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\AppSetup;
use app\models\Product;
use app\models\Variant;
use app\components\Shopify;
use yii\helpers\Url;
use yii\helpers\Html;

class SiteController extends Controller
{
  //public $enableCsrfValidation = false;



/**
* {@inheritdoc}
*/
// public function actions()
// {
//   return [
//     'error' => [
//       'class' => 'yii\web\ErrorAction',
//     ],
//     'captcha' => [
//       'class' => 'yii\captcha\CaptchaAction',
//       'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//     ],
//   ];
// }

public function beforeAction($action) { 
      $this->enableCsrfValidation = false; 
      return parent::beforeAction($action); 
  }
    
/**
* Displays homepage.
*
* @return string
*/
public function actionIndex()
{
  return $this->render('index');
}

/**
* Login action.
*
* @return Response|string
*/
public function actionLogin()
{
  if (!Yii::$app->user->isGuest) {
    return $this->goHome();
  }

  $model = new LoginForm();
  if ($model->load(Yii::$app->request->post()) && $model->login()) {
    return $this->goBack();
  }

  $model->password = '';
  return $this->render('login', [
    'model' => $model,
  ]);
}

/**
* Logout action.
*
* @return Response
*/
public function actionLogout()
{
  Yii::$app->user->logout();
  return $this->goHome();
}



public function actionProductlistapi()
{
  $app_setup = new AppSetup();
  $session = Yii::$app->session;
  $session->open();
  $shop_name = $session->get('shop');

  $app_install = AppSetup::find()->Where(['id' => 1])->one();
  $shop = $app_install['shop'];
  $token = $app_install['token'];
  $sc = new Shopify($shop,$token, Yii::$app->params['APP_KEY'], Yii::$app->params['SECRET_KEY']);
  $last_record = Product::find()->select(['product_id'])->orderBy(['product_id' => SORT_DESC])->one();
  //print_r(dirname(__FILE__)); exit;
  echo $count_product = $sc->call('GET', '/admin/api/2019-04/products/count.json');
  echo "<br/> Records in DB:".count(Product::find()->all());


  if (file_exists(dirname(__FILE__) . '/' . 'pageno.txt'))
  {    
       $fileContents = file_get_contents(dirname(__FILE__) . '/' . 'pageno.txt',FALSE, NULL); 
       $newContent = $fileContents + 1;
        file_put_contents(dirname(__FILE__) . '/' . 'pageno.txt', $newContent);
       if($fileContents >= 8){
         $fileContents = 2;
         file_put_contents(dirname(__FILE__) . '/' . 'pageno.txt', $fileContents);
       }
  } 
  else
  {   
      $fileContents = 2;
      file_put_contents(dirname(__FILE__) . '/' . 'pageno.txt', $fileContents);
  }

	if(!empty($last_record)){
    echo $fileContents;
    echo "<br/> last_record:".$last_id = $last_record->product_id;
		$products_details = $sc->call('GET', '/admin/api/2019-04/products.json?limit=100&page='.$fileContents); 
	} 
	else
	{
		$products_details = $sc->call('GET', '/admin/api/2019-04/products.json?limit=100');
	}

	echo "Records fetched:".count($products_details);
  $fileContents++;
	 
	if(!empty($products_details)){
		foreach ($products_details as $value) 
		{ 
			echo "<br/>'".$value['id']."'";
		  
			$product_exist = Product::find()->Where(['product_id' => $value['id']])->one();
			if(empty($product_exist))
			{
				  echo "==> new ";
				  $product = new Product();
				  $product->product_id = $value['id'];
				  $product->title = $value['title'];
				  $product->image_src = $value['image']['src'];
				  $product->vendor = $value['vendor'];
				  $product->product_type = $value['product_type'];

				  if ($product->save(false)) {
					foreach ($value['variants'] as $var) {
					  $variant = new Variant();
					  $variant->product_id = $value['id'];
					  $variant->title = $var['title'];
					  $variant->price = $var['price'];
					  $variant->inventory_item_id = $var['inventory_item_id'];
					  $variant->inventory_quantity = $var['inventory_quantity'];
					  $variant->old_inventory_quantity = $var['old_inventory_quantity'];
					  $variant->variants_id = $var['id'];
					   $variant->queenLevel = 100;
						$variant->ronLevel = 100;
						$variant->wilsonLevel = 100;
						$variant->queeninventory = 0;
						$variant->roninventory = 0;
						$variant->wilsoninventory =0;
						$variant->save(false);
					}
				  }
			}  
			else 
      {
				// already is not exist end;
				// already is exist then update the record end;
				echo "==> already";
				$product_exist->product_id = $value['id'];
				$product_exist->title = $value['title'];
				$product_exist->image_src = $value['image']['src'];
				$product_exist->vendor = $value['vendor'];
				$product_exist->product_type = $value['product_type'];
				if ($product_exist->save(false)) {

					foreach ($value['variants'] as $var) {
						$variant_exist = Variant::find()->Where(['variants_id' => $var['id']])->one();
						$variant_exist->product_id = $value['id'];
						$variant_exist->title = $var['title'];
						$variant_exist->price = $var['price'];
						$variant_exist->inventory_item_id = $var['inventory_item_id'];
						$variant_exist->inventory_quantity = $var['inventory_quantity'];
						$variant_exist->old_inventory_quantity = $var['old_inventory_quantity'];
						$variant_exist->variants_id = $var['id'];
						$variant_exist->queenLevel = 100;
						$variant_exist->ronLevel = 100;
						$variant_exist->wilsonLevel = 100;
						$variant_exist->queeninventory = 0;
						$variant_exist->roninventory = 0;
						$variant_exist->wilsoninventory =0;
						$variant_exist->save(false);
					}
		
				}
			} 
			// already is exist then update the record end;
		}
	} 
	else {
	  echo 'All product imported';
	}
}


public function actionQuenswaystock(){
$app_setup = new AppSetup();
$loc_id = 49848851;
$queen_details = Variant::find()->Where(['stock_queens_status' => 0])->limit(100)->all();
foreach ($queen_details as $value) {
  $variant_exist = Variant::find()->Where(['variants_id' => $value['variants_id']])->one();
  $queen_stock =  self::get_stock($loc_id, $value['inventory_item_id']);
  $variant_exist->variants_id = $value['variants_id'];
  $variant_exist->queeninventory = $queen_stock;
  $variant_exist->stock_queens_status = 1;
  $variant_exist->save(false);
 }
}

public function actionRonswaystock(){
$app_setup = new AppSetup();
$loc_id = 14372274199;
$ron_details = Variant::find()->Where(['stock_rons_status' => 0])->limit(100)->all();
foreach ($ron_details as $value){
  $variant_exist = Variant::find()->Where(['variants_id' => $value['variants_id']])->one();
  $ron_stock =  self::get_stock($loc_id, $value['inventory_item_id']);
  $variant_exist->variants_id = $value['variants_id'];
  $variant_exist->roninventory = $ron_stock;
  $variant_exist->stock_rons_status = 1;
  $variant_exist->save(false);
 }

}

public function actionWilsonstock(){
$app_setup = new AppSetup();
$loc_id = 14372306967;
$wilson_details = Variant::find()->Where(['stock_wils_status' => 0])->limit(100)->all();
foreach ($wilson_details as $value) {
  $variant_exist = Variant::find()->Where(['variants_id' => $value['variants_id']])->one();
  $wilson_stock =  self::get_stock($loc_id, $value['inventory_item_id']);
  $variant_exist->variants_id = $value['variants_id'];
  $variant_exist->wilsoninventory = $wilson_stock;
  $variant_exist->stock_wils_status = 1;
  $variant_exist->save(false);
}

}


public function actionSetstatus(){
    Yii::$app->db->createCommand()->update('variant', ['stock_wils_status' => 0,'stock_rons_status' => 0,'stock_queens_status' => 0])->execute();
}

public function get_stock($loc_id,$inventory_id){
  //echo $inventory_id;
    $session = Yii::$app->session;
    $session->open();
    $shop_name = $session->get('shop');
    $app_install = AppSetup::find()->Where(['id' => 1])->one();
    $shop = $app_install['shop'];
    $token = $app_install['token'];
    $sc = new Shopify($shop,$token, Yii::$app->params['APP_KEY'], Yii::$app->params['SECRET_KEY']);
    $loc_inventory_details = $sc->call('GET', '/admin/api/2019-04/inventory_levels.json?inventory_item_ids='.$inventory_id);
    $avial = 0;
    foreach ($loc_inventory_details as $value) {
       $current_loc = 0;
      if(isset($value['location_id'])){ 
          $current_loc = $value['location_id'];
      }
      if($current_loc == $loc_id){
        $avial = $value['available'];
      }
 }
 return $avial;

  }


public function actionProductlist()
{    
   $limit = 25;
   $product_type=isset($_GET['product_type'])? $_GET['product_type']:'';
   $vendor=isset($_GET['vendor'])? $_GET['vendor']:'';
    $query=isset($_GET['query'])? $_GET['query']:'';
    $pn=isset($_GET['page'])? $_GET['page']:1;
   $where =array();
   if(!empty($product_type)){
      $where['product_type']=$product_type;
   }
   if(!empty($vendor)){
      $where['vendor']=$vendor;
   }
  $offset = ($pn-1) * $limit;
  if(empty($query)){

    $total_records = count (Product::find()->Where($where)->all());
    $products_details = Product::find()->Where($where)->orderBy(['title'=> SORT_ASC])->limit($limit)->offset($offset)->all();
  }
  else{
    $total_records = count (Product::find()->Where($where)->andWhere(['like','title',$query])->all());
    $products_details = Product::find()->Where($where)->andWhere(['like','title',$query])->orderBy(['title'=> SORT_ASC])->limit($limit)->offset($offset)->all();
  }
  $variant_details = array();
  $model = new Variant();

  foreach ($products_details as $value) { 
    $variant_details[] = Variant::find()->Where(['product_id' => $value['product_id']])->all();
  }

  $vender_list = Product::find()->select(['vendor'])->distinct()->all();
  $product_type_list = Product::find()->select(['product_type'])->distinct()->all();
  return $this->render('productlist',array('productlist' => $products_details,'variant_details' => $variant_details,'vender_list'=>$vender_list,'product_type_list'=>$product_type_list,'model' => $model,'count'=>$total_records,'page_no'=>$pn,'sel_product'=>$product_type,'sel_vendor'=>$vendor,'query'=>$query));
}

public function actionEdit()
{  
  // echo '<pre>';
  // print_r($_POST); exit;
  if(isset($_POST['Variant']['variants_id'])){
    $id = $_POST['Variant']['variants_id'];
    $model = Variant::find()->Where(['variants_id' => $id])->one();
    if ($model->load(Yii::$app->request->post()))
    {
      $queenLevel = Yii::$app->request->post()['Variant']['queenLevel'];
      $ronLevel = Yii::$app->request->post()['Variant']['ronLevel'];
      $wilsonLevel = Yii::$app->request->post()['Variant']['wilsonLevel'];
      Yii::$app->db->createCommand()->update('variant', ['queenLevel' => $queenLevel,'ronLevel' => $ronLevel,'wilsonLevel' => $wilsonLevel], 'variants_id='.$id)->execute();    
	  Yii::$app->session->setFlash('packageFormSubmitted');
    }  
    else
    {
      $errors = $model->getErrors();
      if (isset($errors) && count($errors) > 0)
      {
        foreach($errors as $key => $val)
        {
          Yii::$app->session->setFlash('error', $val[0]);
        }
      }
    }
  } 
  else
  {
	Yii::$app->session->setFlash('ErrorWithoutid');
  }
  $variant_details = array();	  
  $limit = 25;
   $product_type=isset($_GET['product_type'])? $_GET['product_type']:'';
   $vendor=isset($_GET['vendor'])? $_GET['vendor']:'';
   $query=isset($_GET['query'])? $_GET['query']:'';
   $pn=isset($_GET['page'])? $_GET['page']:1;
   
   $where =array();
   if(!empty($product_type)){
	  $where['product_type']=$product_type;
   }
   if(!empty($vendor)){
	  $where['vendor']=$vendor;
   }
  $offset = ($pn-1) * $limit;
  if(empty($query)){

	$total_records = count (Product::find()->Where($where)->all());
	$products_details = Product::find()->Where($where)->limit($limit)->offset($offset)->all();
  }
  else{
	$total_records = count (Product::find()->Where($where)->andWhere(['like','title',$query])->all());
	$products_details = Product::find()->Where($where)->andWhere(['like','title',$query])->limit($limit)->offset($offset)->all();
  }
  foreach ($products_details as $value) { 
	$variant_details[] = Variant::find()->Where(['product_id' => $value['product_id']])->all();
  }
  $vender_list = Product::find()->select(['vendor'])->distinct()->all();
  $product_type_list = Product::find()->select(['product_type'])->distinct()->all();  
  
  return $this->render('productlist',array('productlist' => $products_details,'variant_details' => $variant_details,'vender_list'=>$vender_list,'product_type_list'=>$product_type_list,'model'=>$model,'count'=>$total_records,'page_no'=>$pn,'sel_product'=>$product_type,'sel_vendor'=>$vendor,'query'=>$query));

}
/*
public function actionProductsearch() 
{
  $app_setup = new AppSetup();
  $data = "";
  if(!empty($_GET))
  {
    $search_content = $_GET['search_content'];
    if(!empty($search_content)){
      $products_details = Product::find()->Where(['like','title', $search_content])->all();
      if(empty($products_details)){
        return "<tr>No Matching Found</tr>";
      }
    } else {
      $products_details = Product::find()->all();
    }

    $variant_details = array();
    foreach ($products_details as $value) {
      $variant_details[] = Variant::find()->Where(['product_id' => $value['product_id']])->all();
    }

    $vender_list = Product::find()->select(['vendor'])->distinct()->all();
    $product_type_list = Product::find()->select(['product_type'])->distinct()->all();
    $i= 1;

// echo '<pre>';
// print_r($products_details);
    foreach ($products_details as $value) {
      $data .= '<tr>';  
      $data .= '<td><img src="'.$value['image_src'].'" width="50px;" height="50px;"/>'.$value['title'].'</td>';
      $data .= '<td>'.$value['product_type'].'</td>';
      $data .= '<td>'.$value['vendor'].'</td>';
      $data .= '<td class="queen"></td><td class="queen"></td><td class="queen"></td>
      <td class="ron activehide"></td><td class="ron activehide"></td><td class="ron activehide"></td>
      <td class="wilson activehide"></td><td class="wilson activehide"></td><td class="wilson activehide"></td>';
      $data .= '</tr>';
      foreach ($variant_details as $var) { 
        $m =0;
        foreach ($var as $variant_pr) {
          if($variant_pr['product_id'] == $value['product_id']){
            $data .= '<tr class="active_row">';
            $data .='<td colspan="3" class="varient_name">'.$variant_pr['title'] .'</td>';
            $data .='<td class="queen">'. $q_level = $variant_pr['queenLevel'].'<a class="open-AddBookDialog" data-toggle="modal" data-target="#myModal-queen" data-id='.$variant_pr["variants_id"].'><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>';

// $loc_inventory_details = $app_setup->get_inventory('49848851');
            $q_inventory = $variant_pr['queeninventory'];
            $data .='<td class="queen">'.$q_inventory.'</td>';
            $order_to_be = $q_level - $q_inventory;
            $data .=' <td class="queen show_record_child">'.$order_to_be.'</td>';

//ron
            $data.='<td class="ron activehide">'. $r_level = $variant_pr['ronLevel'].'<a class="open-AddBookDialog" data-toggle="modal" data-target="#myModal-queen" data-id='.$variant_pr["variants_id"].'><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>';
// $loc_inventory_details = $app_setup->get_inventory('14372274199');
            $r_inventory = $variant_pr['roninventory'];
            $data .='<td class="ron activehide">'.$r_inventory.'</td>';
            $order_to_be = $r_level - $r_inventory;
            $data .=' <td class="ron show_record_child activehide">'.$order_to_be.'</td>';

//wilson

            $data .='<td class="wilson activehide">'. $w_level = $variant_pr['wilsonLevel'].'<a class="open-AddBookDialog" data-toggle="modal" data-target="#myModal-queen" data-id='.$variant_pr["variants_id"].'><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>';
// $loc_inventory_details = $app_setup->get_inventory('14372306967');
            $w_inventory = $variant_pr['wilsoninventory'];

            $data .='<td class="wilson activehide">'.$w_inventory.'</td>';
            $order_to_be = $w_level - $w_inventory;
            $data .=' <td class="wilson show_record_child activehide">'.$order_to_be.'</td>';
            $data .= '</tr>';
          }
          $m++;
        }
      }
      $i++;
    }
  } 
  else
  {
    $data .= '<tr>No Matching Found</tr>';
  }
  return $data;
}
*/
public function actionExport()
{  

  $app_setup = new AppSetup();
  
   $product_type=isset($_GET['product_type'])? $_GET['product_type']:'';
   $vendor=isset($_GET['vendor'])? $_GET['vendor']:'';
   $query=isset($_GET['query'])? $_GET['query']:'';
   
   $where =array();
   if(!empty($product_type)){
	  $where['product_type']=$product_type;
   }
   if(!empty($vendor)){
	  $where['vendor']=$vendor;
   }
  if(empty($query)){
	$products_details = Product::find()->Where($where)->all();
  }
  else{
	$products_details = Product::find()->Where($where)->andWhere(['like','title',$query])->all();
  }
  $variant_details = array();
  foreach ($products_details as $value){
    $variant_details[] = Variant::find()->Where(['product_id' => $value['product_id']])->all();
  }
  $delimiter = ",";
  $filename = "levelapp_" . date('Y-m-d') . ".csv";

  //create a file pointer
  $f = fopen('php://memory', 'w');
  $i = 0;
  $fields = array('Product ID', 'Title', 'Vendor', 'Product Type', 'Queensway Inventory', 'Roncesvalles Inventory', 'Wilson Inventory');
  fputcsv($f, $fields, $delimiter);
  $lineData = array();
  foreach ($products_details as $value) {
  $lineData = array($i, $value['title'], $value['vendor'], $value['product_type'], "","","");
  fputcsv($f, $lineData, $delimiter);
  foreach ($variant_details as $var) { 
  $m =0;
  foreach ($var as $variant_pr) {
  if($variant_pr['product_id'] == $value['product_id']){
  $lineData = array($variant_pr['variants_id'], $variant_pr['title'], $value['vendor'], $value['product_type'], $variant_pr['queeninventory'],$variant_pr['roninventory'],$variant_pr['wilsoninventory']);
  fputcsv($f, $lineData, $delimiter);

  }
  $m++;
  }
  }
  $i++;
  }
//move back to beginning of file
  fseek($f, 0);
//set headers to download file rather than displayed
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment; filename="' . $filename . '";');
//output all remaining data on a file pointer
  fpassthru($f);
}
/*
public function actionProductvender() 
{
  $app_setup = new AppSetup();

  if(!empty($_POST)){
    $selected_vender = $_POST['vender'];
    $selected_productType = $_POST['product_type'];

    if(!empty($selected_vender) && !empty($selected_productType)){
      $products_details = Product::find()->Where(['vendor' => $selected_vender])->andWhere(['product_type' => $selected_productType])->all();
    }
    elseif (!empty($selected_vender)) {
      $products_details = Product::find()->Where(['vendor' => $selected_vender])->all();
    }
    elseif (!empty($selected_productType)) {
      $products_details = Product::find()->Where(['product_type' => $selected_productType])->all();
    }
    else 
    {
      $products_details = Product::find()->all();
    }

    $variant_details = array();
    foreach ($products_details as $value) {
      $variant_details[] = Variant::find()->Where(['product_id' => $value['product_id']])->all();
    }

    $vender_list = Product::find()->select(['vendor'])->distinct()->all();
    $product_type_list = Product::find()->select(['product_type'])->distinct()->all();
    $i= 1;
    $data = "";
    foreach ($products_details as $value) {
      $data .= '<tr>';  
      $data .= '<td><img src="'.$value['image_src'].'" width="50px;" height="50px;"/>'.$value['title'].'</td>';
      $data .= '<td>'.$value['product_type'].'</td>';
      $data .= '<td>'.$value['vendor'].'</td>';
      $data .= '<td class="queen"></td>
      <td class="ron activehide"></td>
      <td class="wilson activehide"></td>';
      $data .= '</tr>';
      foreach ($variant_details as $var) { 
        $m =0;
        foreach ($var as $variant_pr) {
          if($variant_pr['product_id'] == $value['product_id']){
            $data .= '<tr class="active_row">';
            $data .='<td colspan="3" class="varient_name">'.$variant_pr['title'] .'</td>';
            $data .='<td class="queen">'. $q_level = $variant_pr['queenLevel'].'<a class="open-AddBookDialog" data-toggle="modal" data-target="#myModal-queen" data-id='.$variant_pr["variants_id"].'><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>';

//$loc_inventory_details = $app_setup->get_inventory('49848851');
            $q_inventory = $variant_pr['queeninventory'];
            $data .='<td class="queen">'.$q_inventory.'</td>';
            $order_to_be = $q_level - $q_inventory;
            $data .='<td class="queen show_record_child">'.$order_to_be.'</td>';

//ron
            $data.='<td class="ron activehide">'. $r_level = $variant_pr['ronLevel'].'<a class="open-AddBookDialog" data-toggle="modal" data-target="#myModal-queen" data-id='.$variant_pr["variants_id"].'><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>';
//$loc_inventory_details = $app_setup->get_inventory('14372274199');
            $r_inventory = $variant_pr['roninventory'];
            $data .='<td class="ron activehide">'.$r_inventory.'</td>';
            $order_to_be = $r_level - $r_inventory;
            $data .=' <td class="ron show_record_child activehide">'.$order_to_be.'</td>';

//wilson

            $data .='<td class="wilson activehide">'. $w_level = $variant_pr['wilsonLevel'].'<a class="open-AddBookDialog" data-toggle="modal" data-target="#myModal-queen" data-id='.$variant_pr["variants_id"].'><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>';
// $loc_inventory_details = $app_setup->get_inventory('14372306967');
            $w_inventory = $variant_pr['wilsoninventory'];

            $data .='<td class="wilson activehide">'.$w_inventory.'</td>';
            $order_to_be = $w_level - $w_inventory;
            $data .=' <td class="wilson show_record_child activehide">'.$order_to_be.'</td>';
            $data .= '</tr>';
          }
          $m++;
        }
      }
      $i++;
    }
  } 
  else
  {
    $data .= '<tr>No Matching Found</tr>';
  }
  return $data;
}
*/
public function actionInstall(){   
  $shopify_shop = isset($_GET['shop']) ? $_GET['shop'] : '';
  $code = isset($_GET['code']) ? $_GET['code'] : '';
  $app_key = Yii::$app->params['APP_KEY'];
  $app_secret = Yii::$app->params['SECRET_KEY'];
  $base_url = Yii::$app->params['BASE_URL'];

  $app_installed = AppSetup::find()->Where(['shop' => $shopify_shop])->one();
  if (!empty($app_installed)) { 
    $shopify_shop = $app_installed->shop;
    $shopify_token = $app_installed->token; 
  } else {
    $shopifyClient = new Shopify($shopify_shop, "", $app_key, $app_secret);
    $shopify_token = $shopifyClient->getAccessToken($code);
    if (empty($shopify_token)) {
      $shopify_scope = Yii::$app->params['SHOPIFY_SCOPE'];
      $redirect_uri = $base_url . '/site/install';
      header("Location: " . $shopifyClient->getAuthorizeUrl($shopify_scope, $redirect_uri));
      exit;
    } else {
      $sc = new Shopify($shopify_shop, $shopify_token, $app_key, $app_secret);
//   $shop = $sc->call('GET', '/admin/shop.json');
      $appSetup = new AppSetup();
      $appSetup->shop = $shopify_shop;
      $appSetup->token = $shopify_token;
      $appSetup->code = $code;
      $appSetup->status = 1;
      $appSetup->save(false);
    }
  }
  $sc = new Shopify($shopify_shop, $shopify_token, $app_key, $app_secret);
  $appSetup = AppSetup::find()->Where(['shop' => $shopify_shop])->one();
  $session = Yii::$app->session;
  $session->open();
  $session->set('shop', $shopify_shop);
  if (!empty($appSetup)) { 
    return $this->redirect('productlist');
  } else {
    echo "Something went wrong, please try again later.";
    exit();
  }
}  

/**
*  app uninstall hook to delete app entry from table
*/
public function actionAppuninstall(){
  $shop = Yii::$app->request->get('shop');
  $AppSetup = AppSetup::find()->where(['shop' => $shop])->one();
  $AppSetup->delete();

}


protected function findModel($id) {
  if (($model = level::findOne($id)) !== null) {
    return $model;
  }

  throw new NotFoundHttpException('The requested page does not exist.');
}

}
