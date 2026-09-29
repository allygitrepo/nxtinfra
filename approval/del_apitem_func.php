<div class="modal fade" id="modalDeleteAPItem<?php echo $approval_hdr_id;?><?php echo $rid;?>" role="dialog" aria-labelledby="modalDeleteAPItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDeleteAPItemLabel">Delete Material of Approval Memo - NEW</h4>
            </div>
            <input type="hidden" id="APitemTempId">
            <div class="modal-body" id="modalDeleteAPContent">
                Are you sure you want to delete item <?php echo $approval_hdr_id;?> <?php echo $rid;?> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="btnDeleteAPItemYes" onclick="delete_apItem(<?php echo $approval_hdr_id;?>,<?php echo $rid; ?>)" >Yes</button>
            </div>
        </div>
    </div>
</div>

