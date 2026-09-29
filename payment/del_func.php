
<div class="modal fade" id="modalDeleteItem<?php echo $_GET['id'];?><?php echo $rid;?>" role="dialog" aria-labelledby="modalDeleteItemLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalDeleteItemLabel">Delete Material of Supplier Invoice </h4>
            </div>
            <input type="hidden" id="itemTempId">
            <div class="modal-body" id="modalDeleteContent">
                Are you sure you want to delete item <?php echo $_GET['id'];?> <?php echo $rid;?> ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="btnDeleteItemYes" onclick="delete_pyAcct(<?php echo $_GET['id'];?>,<?php echo $rid; ?>)" >Yes</button>
            </div>
        </div>
    </div>
</div>