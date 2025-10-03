<div class="row">
              <div class="col">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                      <div class="row">
                      <div class="col-md-9">
                        <h6 class="m-0">Pengaturan</h6>
                      </div>
                       </div>
                  </div>
                  <div class="card-body p-0 pb-3 text-center">
                       <?php if($this->session->flashdata('info')){ ?>
                          <div class="alert alert-accent alert-dismissible fade show mb-0" role="alert">

                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">×</span>
                        </button>
                        <i class="fa fa-info mx-2"></i>
                        <?php echo $this->session->flashdata('info');?></div>
                      <?php } ?>
                      <?php echo form_open('config/update');?><br />
                        <input type="hidden" name="banner_id" value="">
                          <div class="form-group row">
                            <label for="ket" class="col-sm-2 col-form-label">Running Text</label>
                            <div class="col-sm-6">
                              <textarea rows=5 class="md-textarea form-control" name="running_text"><?php echo $data[0]->running_text;?></textarea><small>Max:200 char, Jangan gunakan enter, tulisan harus bersambung terus.</small>
                            </div>
                          </div>




                          <div class="form-group row">
                              <div class="col-sm-2 col-form-label"></div>
                              <div class="col-sm-3">
                              <input type="submit" class="btn btn-sm btn-accent ml-auto" name="submit" value="Simpan">
                            </div>
                          </div>
                        </form>
 </div></div></div>
