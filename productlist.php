<?php
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\AppSetup;
use yii\bootstrap\ActiveForm;
$app_setup = new AppSetup();
?>
<input type="hidden" id="page_no" value="<?php echo $page_no ?>">
<?php if (Yii::$app->session->hasFlash('packageFormSubmitted')): ?>
	<div id="w1-error-0" class="alert-success alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">		</button>Your level has been successfully updated
	</div>
<?php endif;?>

<?php if (Yii::$app->session->hasFlash('ErrorWithoutid')): ?>
	<div id="w1-error-0" class="alert-error alert fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">			</button>Select the proper level
	</div>
<?php endif;?>

<div class="export_search">
	<div id="example_filter" class="dataTables_filter">
		<input type="search" class="example_filll" placeholder="Search Product" value="<?php echo $query?>" id="query" aria-controls="example" name="search_field"><span class="search_span"><i class="fa fa-search"></i></span>
	</div>
	<div class="export_btn">
		<a href="<?php echo Url::toRoute('site/export').'?product_type='.$sel_product.'&vendor='.$sel_vendor.'&query='.$query?>" class="export_btn button">Export Csv</a>
	</div>
</div>

<select id="location">
 <option value="Queensway">Queensway</option>
 <option value="Roncesvalles">Roncesvalles</option>
 <option value="Wilson">Wilson</option>
</select>

<select id="product" class="levelfilters">
 <option value="" selected>Select Product Type</option> 
<?php foreach ($product_type_list as $producttype):?> 
	<?php if(!empty($producttype['product_type'])):?>
		<option value="<?php echo $producttype['product_type'];?>" <?php if(strtolower($producttype['product_type'])==strtolower($sel_product)){echo "selected";}?> ><?php echo $producttype['product_type'];?></option>
	<?php endif;?>
<?php endforeach;?>
</select>
<select id="vender" class="levelfilters">
 <option value="">Select Vendor</option> 
	<?php foreach ($vender_list as $vender): ?>
		<option value="<?php echo $vender['vendor'];?>" <?php if(strtolower($vender['vendor'])==strtolower($sel_vendor)){echo "selected";}?>><?php echo $vender['vendor'];?></option>
	<?php endforeach; ?>
</select>

<input type="checkbox" name="order_tobe" id="order_tobe"> Only show what needs to be ordered 

<table id="example" class="display" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th>Name</th>
			<th>Product Type</th>
			<th>vender</th> 
			<th class="queen activeshow">Level</th>
			<th class="queen activeshow">In stock</th>
			<th class="queen activeshow">To order</th>
			<th class="ron activehide">Level</th>
			<th class="ron activehide">In stock</th>
			<th class="ron activehide">To order</th>
			<th class="wilson activehide">Level</th>
			<th class="wilson activehide">In stock</th>
			<th class="wilson activehide">To order</th>
		</tr>
	</thead>

<tbody id="product_list">
<?php 
$i= 1;
$k = 1;
foreach ($productlist as $value) {
?>
<tr>
<td><img src="<?php echo $value['image_src']; ?>" width="50px;" height="50px;"><?php echo $value['title']; ?></td>
<td><?php echo $value['product_type']; ?></td>
<td><?php echo $value['vendor']; ?></td> 
<td class="queen"></td><td class="queen"></td><td class="queen"></td>
<td class="ron activehide"></td><td class="ron activehide"></td><td class="ron activehide"></td>
<td class="wilson activehide"></td><td class="wilson activehide"></td><td class="wilson activehide"></td>
</tr>

<?php
foreach ($variant_details as $var) { 
$m =0;
foreach ($var as $variant_pr) {
if($variant_pr['product_id'] == $value['product_id']):
?>
<tr class="active_row">
<td colspan="3" class="varient_name" data-variant-id="<?php echo $variant_pr['variants_id']; ?>"><?php echo $variant_pr['title']; ?></td>
<td class="get_gueen queen activeshow"><?php echo $q_level = $variant_pr['queenLevel']; ?><a class="open-AddBookDialog" data-toggle="modal" data-target="#myModal-queen" data-class="queen" data-id="<?php echo $variant_pr['variants_id']; ?>" data-queen="<?php echo $variant_pr['queenLevel']; ?>" data-rons="<?php echo $variant_pr['ronLevel']; ?>" data-wilson="<?php echo $variant_pr['wilsonLevel']; ?>"><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>
<td class="queen activeshow"><?php // $loc_inventory_details = $app_setup->get_inventory('49848851');
echo $q_inventory = $variant_pr['queeninventory'];
?>
</td>
<td class="activeshow queen show_record_child"><?php echo $order_to_be = $q_level - $q_inventory; ?></td> 
<td class="get_ron ron activehide"><?php echo $r_level = $variant_pr['ronLevel']; ?><a class="open-AddBookDialog" data-class="ron" data-toggle="modal" data-target="#myModal-queen" data-id="<?php echo $variant_pr['variants_id']; ?>" data-queen="<?php echo $variant_pr['queenLevel']; ?>" data-rons="<?php echo $variant_pr['ronLevel']; ?>" data-wilson="<?php echo $variant_pr['wilsonLevel']; ?>"><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>
<td class="ron activehide">
<?php  //$loc_inventory_details = $app_setup->get_inventory('14372274199');
echo $r_inventory = $variant_pr['roninventory'];
?>
</td>
<td class="ron show_record_child activehide "><?php echo $order_to_be = $r_level - $r_inventory; ?></td>
<td class="get_wilson wilson activehide"><?php echo $w_level = $variant_pr['wilsonLevel']; ?><a class="open-AddBookDialog" data-class="wilson" data-toggle="modal" data-target="#myModal-queen" data-id="<?php echo $variant_pr['variants_id']; ?>" data-queen="<?php echo $variant_pr['queenLevel']; ?>" data-rons="<?php echo $variant_pr['ronLevel']; ?>" data-wilson="<?php echo $variant_pr['wilsonLevel']; ?>"><img src="https://img.icons8.com/windows/32/000000/edit.png"></a></td>
<td class="wilson activehide"><?php // $loc_inventory_details = $app_setup->get_inventory('31378899022');
echo $w_inventory = $variant_pr['wilsoninventory']; ?>
</td>
<td class="wilson show_record_child activehide"><?php echo $order_to_be = $w_level - $w_inventory; ?></td>
<?php 
endif;
$m++;
} 
}
?>
<?php 
$i++;
}
?>
</tbody>
</table>
<?php
$limit = 25;
$total_records = $count;  
$total_pages = ceil($total_records / $limit); 
if(!empty($count)){
?>

<div align="center">
<ul class='pagination text-center' id="pagination">
<?php if(!empty($total_pages)):for($i=1; $i<=$total_pages; $i++):  ?>
            <li id="<?php echo $i;?>" onclick="changepage(<?php echo $i?>);" <?php if($page_no==$i){echo 'class="active"';}?>>
              <a ><?php echo $i;?></a>
            </li>      
<?php endfor;endif;?>  
</ul>
</div>

<?php } ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"/>

<div class="modal fade" id="myModal-queen" role="dialog">
<div class="modal-dialog modal-sm">
<div class="modal-content">
<div class="modal-header">
 <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
<?php $form = ActiveForm::begin(['id' => 'form-level','action'=>'http://kurinato.com/levelapp/web/index.php/site/edit?page='.$page_no.'&product_type='.$sel_product.'&vendor='.$sel_vendor.'&query='.$query]); ?>
<?= $form->field($model, 'variants_id')->hiddenInput()->label(false); ?>
<?= $form->field($model, 'queenLevel')->textInput()->label('Queensway'); ?>
<?= $form->field($model, 'ronLevel')->textInput()->label('Roncesvalles'); ?>
<?= $form->field($model, 'wilsonLevel')->textInput()->label('Wilson'); ?>
<div class="form-group">
<?= Html::submitButton('Update', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
</div>
<?php ActiveForm::end(); ?>
</div>

</div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script> 

<script type="text/javascript">
  // edit functionality
$(document).on("click", ".open-AddBookDialog",function(event){
event.preventDefault();
var myBookId = $(this).attr('data-id');
var queenlavel = $(this).attr('data-queen');
var ronlevel = $(this).attr('data-rons');
var wils = $(this).attr('data-wilson');
$(".modal-body input#variant-variants_id").val(myBookId);
$(".modal-body input#variant-queenlevel").val(queenlavel);
$(".modal-body input#variant-ronlevel").val(ronlevel);
$(".modal-body input#variant-wilsonlevel").val(wils);

});
</script>

<script type="text/javascript">
$(document).ready(function (){
  var table = $('#example').DataTable({
   dom: 'lrtip'
  });
  // $('.example_filll').on( 'keyup', function () {
  //    table
  //    .columns(0)
  //    .search( this.value )
  //    .draw();
  //    });
  // $('#product').on('change', function(){
  //   table.column('1').search(this.value).draw();   
  // });
  // $('#vender').on('change', function(){
  //   table.column('2').search(this.value).draw();   
  // });
});

jQuery(document).ready(function(){
    $('#location').on('change', function () {
        if (this.value == 'Queensway') 
        {
          $(".queen").addClass('activeshow');
          $(".queen").removeClass('activehide');
          $(".ron").addClass('activehide');
          $(".ron").removeClass('activeshow');
          $(".wilson").addClass('activehide');
          $(".wilson").removeClass('activeshow');
          if($('input#order_tobe').prop("checked") == true)
          {
            var table = $("table tbody");
            table.find('tr.active_row').each(function (i) 
            {   $(this).removeClass("current");
                var tds = $(this).find('td.show_record_child');
                var queen = tds.eq(0).text();
                if((queen <= 0))
                {
                  var myclass= tds.eq(0).attr("class");
                  if ( myclass =="queen show_record_child activeshow" )
                   {
                      $(this).addClass("current");
                   }
                }
            });
          }
        }

        else if(this.value == 'Roncesvalles') 
        {
            $(".ron").addClass('activeshow');
            $(".ron").removeClass('activehide');
            $(".queen").addClass('activehide');
            $(".queen").removeClass('activeshow');
            $(".wilson").addClass('activehide');
            $(".wilson").removeClass('activeshow');
          if($('input#order_tobe').prop("checked") == true)
          {
            var table = $("table tbody");
            table.find('tr.active_row').each(function (i) 
            {  
              $(this).removeClass("current");
              var tds = $(this).find('td.show_record_child');
              var ron = tds.eq(1).text();
              if((ron <= 0))
              {
                var myclass= tds.eq(1).attr("class");
                if (myclass =="ron show_record_child activeshow" ) 
                {
                  $(this).addClass("current");
                }
              }
            });
          }
        }
        else if(this.value == 'Wilson')
        {
            $(".wilson").addClass('activeshow');
            $(".wilson").removeClass('activehide');
            $(".queen").addClass('activehide');
            $(".queen").removeClass('activeshow');
            $(".ron").addClass('activehide');
            $(".ron").removeClass('activeshow');
          if($('input#order_tobe').prop("checked") == true)
          {
            var table = $("table tbody");
            table.find('tr.active_row').each(function (i) 
            {
              $(this).removeClass("current");
              var tds = $(this).find('td.show_record_child');
              var wilson = tds.eq(2).text();
              if((wilson <= 0))
              {
                var myclass= tds.eq(2).attr("class");
                if ( myclass =="wilson show_record_child activeshow" ) 
                {
                   $(this).addClass("current");
                }
              }
            });
          }
        }
        else {
          $(".queen").removeClass('activehide');
          $(".ron").addClass('activehide');
          $(".wilson").addClass('activehide');
        }
    });
});


// jQuery on click of checkkbox and check the value of location

 $('input#order_tobe').click(function(){
    if($(this).prop("checked") == true){
   var selected = $("#location").children("option:selected").val();
   if(selected == 'Roncesvalles'){
   // console.log(selected);
     var table = $("table tbody");
     table.find('tr.active_row').each(function (i) {
     $(this).removeClass("current");
     var tds = $(this).find('td.show_record_child');
     var ron =  tds.eq(1).text();
     if((ron <= 0)){
      $(this).addClass("current");
    }
   });
  }
  else if(selected == 'Wilson'){
     var table = $("table tbody");
     table.find('tr.active_row').each(function (i) {
     $(this).removeClass("current");
     var tds = $(this).find('td.show_record_child');
     var wilson =  tds.eq(2).text();
     if((wilson <= 0)){
      $(this).addClass("current");
    }
   });
  } else {
    var table = $("table tbody");
    table.find('tr.active_row').each(function (i) {
    $(this).removeClass("current");
    var tds = $(this).find('td.show_record_child');
    var queen = tds.eq(0).text();
    if((queen <= 0)){
    $(this).addClass("current");
    }
    });


  }

 }
 else
 {
  $("#load").show();
  location.reload();
}
});
</script>
<script type="text/javascript">
 var url='http://kurinato.com/levelapp/web/index.php/site/';
jQuery(document).ready(function(){
 jQuery('select.levelfilters').on('change', function(){
   $("#order_tobe").prop("checked", false);
   var product_type = jQuery('select#product').children("option:selected").val();
   var vender = jQuery('select#vender').children("option:selected").val();
    var query = jQuery('#query').val();
   console.log(product_type); 
   console.log(vender);
   $("#load").show();
   window.location=url+'productlist?page=1&product_type='+product_type+'&vendor='+vender+'&query='+query;
    
  });

jQuery('.example_filll').on('keyup',function () { 
   $("#order_tobe").prop("checked", false);
   var search_content = jQuery(this).val();
   console.log(search_content);
   if(search_content.length>3){
    $("#load").show();
    $("#order_tobe").prop("checked", false);
     var product_type = jQuery('select#product').children("option:selected").val();
     var vender = jQuery('select#vender').children("option:selected").val();
     var query = jQuery('#query').val();
     console.log(product_type); 
     console.log(vender);
     $("#load").show();
     window.location=url+'productlist?page=1&product_type='+product_type+'&vendor='+vender+'&query='+query;
   }
   
 });



});

function changepage(page){
  $("#order_tobe").prop("checked", false);
   var product_type = jQuery('select#product').children("option:selected").val();
   var vender = jQuery('select#vender').children("option:selected").val();
   var query = jQuery('#query').val();
   console.log(product_type); 
   console.log(vender);
   $("#load").show();
   window.location=url+'productlist?page='+page+'&product_type='+product_type+'&vendor='+vender+'&query='+query;
}


</script>

<style>
.current{
  display: none;
}
.active{ font-weight:bold; font-color:#333333; }
</style>
