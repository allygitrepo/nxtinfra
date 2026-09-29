<div class="modal fade" id="modalDeleteProducts<?php echo $tender_id;?><?php echo $rid;?>" role="dialog" aria-labelledby="modalDeleteProductsLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="text-align:left;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDeleteProductsLabel">Delete Product </h4>
            </div>
            <input type="hidden" id="itemTempId">
            <div class="modal-body" id="modalDeleteContent" style="text-align:left;" >
                Are you sure you want to delete Product ?
            </div>
            <div class="modal-footer" style="text-align:center;" >
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="btnDeleteItemYes" onclick="delete_product(<?php echo $tender_id;?>,<?php echo $rid; ?>)" >Yes</button>
            </div>
        </div>
    </div>
</div>
