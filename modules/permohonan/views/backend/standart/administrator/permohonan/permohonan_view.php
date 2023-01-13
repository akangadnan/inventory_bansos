
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
      Permohonan      <small><?= cclang('detail', ['Permohonan']); ?> </small>
   </h1>
   <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class=""><a  href="<?= site_url('administrator/permohonan'); ?>">Permohonan</a></li>
      <li class="active"><?= cclang('detail'); ?></li>
   </ol>
</section>
<!-- Main content -->
<section class="content">
   <div class="row" >
     
      <div class="col-md-12">
         <div class="box box-warning">
            <div class="box-body ">

               <!-- Widget: user widget style 1 -->
               <div class="box box-widget widget-user-2">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header ">
                    
                     <div class="widget-user-image">
                        <img class="img-circle" src="<?= BASE_ASSET; ?>/img/view.png" alt="User Avatar">
                     </div>
                     <!-- /.widget-user-image -->
                     <h3 class="widget-user-username">Permohonan</h3>
                     <h5 class="widget-user-desc">Detail Permohonan</h5>
                     <hr>
                  </div>

                 
                  <div class="form-horizontal form-step" name="form_permohonan" id="form_permohonan" >
                  
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">ID </label>

                        <div class="col-sm-8">
                        <span class="detail_group-permohonan_id"><?= _ent($permohonan->permohonan_id); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Tanggal Permohonan </label>

                        <div class="col-sm-8">
                        <span class="detail_group-permohonan-tanggal"><?= _ent($permohonan->permohonan_tanggal); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Waktu </label>

                        <div class="col-sm-8">
                        <span class="detail_group-permohonan-waktu"><?= _ent($permohonan->permohonan_waktu); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Posko </label>

                        <div class="col-sm-8">
                           <?= _ent($permohonan->posko_posko_nama); ?>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Pemohon </label>

                        <div class="col-sm-8">
                        <span class="detail_group-permohonan-pemohon"><?= _ent($permohonan->permohonan_pemohon); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Keterangan </label>

                        <div class="col-sm-8">
                        <span class="detail_group-permohonan-keterangan"><?= _ent($permohonan->permohonan_keterangan); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Status </label>

                        <div class="col-sm-8">
                        <span class="detail_group-permohonan-status"><?= _ent($permohonan->permohonan_status); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Keterangan Posko </label>

                        <div class="col-sm-8">
                        <span class="detail_group-permohonan-respon-posko"><?= _ent($permohonan->permohonan_respon_posko); ?></span>
                        </div>
                    </div>
                                        
                    <div class="form-group ">
                        <label for="content" class="col-sm-2 control-label">Mengetahui Posko </label>

                        <div class="col-sm-8">
                           <?= _ent($permohonan->users_user_nama_lengkap); ?>
                        </div>
                    </div>
                                        
                    <br>
                    <br>


                     
                         
                    <div class="view-nav">
                        <?php is_allowed('permohonan_update', function() use ($permohonan){?>
                        <a class="btn btn-flat btn-info btn_edit btn_action" id="btn_edit" data-stype='back' title="edit permohonan (Ctrl+e)" href="<?= site_url('administrator/permohonan/edit/'.$permohonan->permohonan_id); ?>"><i class="fa fa-edit" ></i> <?= cclang('update', ['Permohonan']); ?> </a>
                        <?php }) ?>
                        <a class="btn btn-flat btn-default btn_action" id="btn_back" title="back (Ctrl+x)" href="<?= site_url('administrator/permohonan/'); ?>"><i class="fa fa-undo" ></i> <?= cclang('go_list_button', ['Permohonan']); ?></a>
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
        var permohonan_tanggal = $('.detail_group-permohonan-tanggal');
        var permohonan_waktu = $('.detail_group-permohonan-waktu');
        var posko_id = $('.detail_group-posko-id');
        var user_id = $('.detail_group-user-id');
        var permohonan_keterangan = $('.detail_group-permohonan-keterangan');
        var permohonan_status = $('.detail_group-permohonan-status');
        var permohonan_respon_posko = $('.detail_group-permohonan-respon-posko');
        var permohonan_mengetahui = $('.detail_group-permohonan-mengetahui');
    })()
      
   });
</script>