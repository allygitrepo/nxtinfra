
<div class="modal fade" id="modalDeleteItem<?php echo $tender_id;?><?php echo $approval_srno;?>" role="dialog" aria-labelledby="modalDeleteItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="text-align:left;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDeleteItemLabel">Delete Supplier </h4>
            </div>
            <input type="hidden" id="itemTempId">
            <div class="modal-body" id="modalDeleteContent" style="text-align:left;">
                Are you sure you want to delete supplier ?
            </div>
            <div class="modal-footer" style="text-align:center;">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="btnDeleteItemYes" onclick="delete_appquote(<?php echo $tender_id;?>,<?php echo $approval_srno; ?>)" >Yes</button>
            </div>
        </div>
    </div>
</div>
