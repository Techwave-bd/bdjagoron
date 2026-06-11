<?php
    $user_type = $this->session->userdata('user_type');
    if(($user_type==3) || ($user_type==4)){
        $this->load->view('admin/includes/__left_sideber'); 
    }else{  
        $this->load->view('admin/includes/__writer_left_menu.php');
    }
?>

    <div class="body-content">
        <div class="row">
            
            <div class="col-md-12">

                <?php if($this->session->flashdata('message')){ ?>
                    <div class="alert alert-success" role="alert">
                        <span class="close" data-dismiss="alert">×</span>
                        <h4 class="alert-heading"><?php echo $this->session->flashdata('message'); ?></h4>
                    </div> 
                <?php } ?>
                <?php if($this->session->flashdata('error')){ ?>
                    <div class="alert alert-danger" role="alert">
                        <span class="close" data-dismiss="alert">×</span>
                        <h4 class="alert-heading"><?php echo $this->session->flashdata('error'); ?></h4>
                    </div>
                <?php } ?>

                 <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fs-17 font-weight-600 mb-0">Live TV</h6>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">

                        <?php echo form_open('admin/view_setup/save_live_tv');?>

                                <div class="row form-group">
                                    <div class="col-sm-2"><label><?php echo display('status')?></label></div>
                                    <div class="col-sm-10">
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="on" <?php echo (@$live_tv->status=='on'?'checked':'')?>> <?php echo display('enable')?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="status" value="off" <?php echo (@$live_tv->status=='off'?'checked':'')?>> <?php echo display('disable')?>
                                        </label>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-2"><label>Channels</label></div>
                                    <div class="col-sm-10" id="channel-list">
                                        <?php if (!empty($live_tv->channels)): ?>
                                            <?php foreach ($live_tv->channels as $i => $ch): ?>
                                            <div class="channel-item row mb-2">
                                                <div class="col-sm-4">
                                                    <input type="text" name="channel_name[]" class="form-control" value="<?php echo html_escape($ch->name)?>" placeholder="Channel name">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" name="channel_url[]" class="form-control" value="<?php echo html_escape($ch->url)?>" placeholder="https://example.com/stream.m3u8">
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.channel-item').remove()"> &times; </button>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="channel-item row mb-2">
                                                <div class="col-sm-4">
                                                    <input type="text" name="channel_name[]" class="form-control" placeholder="Channel name">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" name="channel_url[]" class="form-control" placeholder="https://example.com/stream.m3u8">
                                                </div>
                                                <div class="col-sm-2">
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.channel-item').remove()"> &times; </button>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <button type="button" class="btn btn-sm btn-info" onclick="addChannel()">+ Add Channel</button>
                                    </div>
                                </div>

                                <div class="row form-group">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-sm btn-success"> <?php echo display('update')?></button>
                                    </div>
                                </div> 

                        <?php echo form_close();?>

                    </div>

<script>
function addChannel() {
    var list = document.getElementById('channel-list');
    var div = document.createElement('div');
    div.className = 'channel-item row mb-2';
    div.innerHTML = `
        <div class="col-sm-4">
            <input type="text" name="channel_name[]" class="form-control" placeholder="Channel name">
        </div>
        <div class="col-sm-6">
            <input type="text" name="channel_url[]" class="form-control" placeholder="https://example.com/stream.m3u8">
        </div>
        <div class="col-sm-2">
            <button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.channel-item').remove()"> &times; </button>
        </div>
    `;
    list.appendChild(div);
}
</script>
                </div>
            </div>
        </div>
    </div>
