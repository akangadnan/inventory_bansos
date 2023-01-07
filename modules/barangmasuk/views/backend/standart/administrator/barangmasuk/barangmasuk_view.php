
<script src="<?= BASE_ASSET; ?>/js/jquery.hotkeys.js"></script>
<script type="text/javascript">
//This page is a result of an autogenerated content made by running test.html with firefox.
function domo(){
 
   // Binding keys
   $('*').bind('keydown', 'Ctrl+e', function assets() {
      $('#btn_edit').trigger('click');
       return false;
   });

   $('*').bind('keydown', 'Ctrl+x', function assets() {
      $('#btn_back').trigger('click');
       return false;
   });
    
}


jQuery(document).ready(domo);
</script>
<!-- Content Header (Page header) -->
<section class="content-header">
   <h1>
      Barang Masuk      <small><?= cclang('detail', ['Barang Masuk']); ?> </small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class=""><a  href="<?= site_url('administrator/barangmasuk'); ?>">Barang Masuk</a></li>
      <li class="active"><?= cclang('detail'); ?></li>
   </ol>
</section>
<!-- Main content -->
<section class="content">
   <div class="row" >
     
      <div class="col-md-12">
         <div class="box box-primary">
            <div class="box-body ">

               <!-- Widget: user widget style 1 -->
               <div class="box box-widget widget-user-2">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header ">
                    
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= BASE_ASSET; ?>/img/view.png" alt="User Avatar">
                     </div>
                     <!-- /.widget-user-image -->
                     <h3 class="widget-user-username">Barang Masuk</h3>
                     <h5 class="widget-user-desc">Detail Barang Masuk</h5>
                     <hr>
                  </div>

                 
                  <div class="form-horizontal form-step" name="form_barangmasuk" id="form_barangmasuk" >
                  
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">ID </label>

                        <div class="col-sm-8">
                        <span class="detail_group-id_barangmasuk"><?= _ent($barangmasuk->id_barangmasuk); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Asal </label>

                        <div class="col-sm-8">
                           <?= _ent($barangmasuk->sumber_nama_sumber); ?>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Nama Barang </label>

                        <div class="col-sm-8">
                           <?= _ent($barangmasuk->barang_nama_barang); ?>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Jumlah Stok </label>

                        <div class="col-sm-8">
                        <span class="detail_group-jumlah"><?= _ent($barangmasuk->jumlah); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Keterangan </label>

                        <div class="col-sm-8">
                        <span class="detail_group-keterangan"><?= _ent($barangmasuk->keterangan); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Tanggal </label>

                        <div class="col-sm-8">
                        <span class="detail_group-tanggal"><?= _ent($barangmasuk->tanggal); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Waktu </label>

                        <div class="col-sm-8">
                        <span class="detail_group-waktu"><?= _ent($barangmasuk->waktu); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Created At </label>

                        <div class="col-sm-8">
                        <span class="detail_group-created_at"><?= _ent($barangmasuk->created_at); ?></span>
                        </div>
                    </div>
                                        
                    <br>
                    <br>


                     
                         
                    <div class="view-nav">
                        <?php is_allowed('barangmasuk_update', function() use ($barangmasuk){?>
                        <a class="btn btn-flat btn-info btn_edit btn_action" id="btn_edit" data-stype='back' title="edit barangmasuk (Ctrl+e)" href="<?= site_url('administrator/barangmasuk/edit/'.$barangmasuk->id_barangmasuk); ?>"><i class="fa fa-edit" ></i> <?= cclang('update', ['Barangmasuk']); ?> </a>
                        <?php }) ?>
                        <a class="btn btn-flat btn-default btn_action" id="btn_back" title="back (Ctrl+x)" href="<?= site_url('administrator/barangmasuk/'); ?>"><i class="fa fa-undo" ></i> <?= cclang('go_list_button', ['Barangmasuk']); ?></a>
                     </div>
                    
                  </div>
               </div>
            </div>
            <!--/box body -->
         </div>
         <!--/box -->

      </div>
   </div>
</section>
<!-- /.content -->

<script>
$(document).ready(function(){
   (function(){
        var asal = $('.detail_group-asal');
        var id_barang = $('.detail_group-id-barang');
        var jumlah = $('.detail_group-jumlah');
        var keterangan = $('.detail_group-keterangan');
    })()
      
   });
</script>