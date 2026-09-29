<?php

include("../header.php");

$pgname = "index.php";
include("../viewonly.php");

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<link rel="stylesheet" href="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.css"?>">

<section class="content-header">
    <h1>
        Inbox
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo $baseurl.'dashboard.php' ?>"><i class="fa fa-tachometer-alt"></i> Home</a></li>
        <li class="active">Inbox</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php
                include("preloader.php");
            ?>
        </div>

    </div>
</section>


<?php 	
include("../footer.php");	
?>
<!-- DataTables -->
<script src="<?php echo $baseurl . "plugins/datatables/jquery.dataTables.js"?>"></script>
<script src="<?php echo $baseurl . "plugins/datatables/dataTables.bootstrap.js"?>"></script>

<script>
    $(document).ready(function() {
        //debugger;
        
        //var code = getUrlVars()["code"];
        $.ajax({
            url: "get_save_emails.php",    //the page containing php script
            type: "POST",    //request type,
            //dataType: 'json',
            //data: {},   //{registration: "success", name: "xyz", email: "abc@gmail.com"},
            success:function(result){
                //console.log(result);
                if(result.indexOf('Success') > -1) {
                    $('#divPreloaderText').html('<span class="alert alert-success">' + result + '</span>');
                    window.location.href = 'listing.php';
                } else {
                    //https://accounts.google.com/o/oauth2/v2/auth?
                    
                    if(result.indexOf('oauth2/v2/auth') >= 0) {
                        window.location.href = result;
                    } else {
                        $('#divPreloaderText').html('<span class="alert alert-danger">' + result + '</span>');
                    }
                    
                }
                
            }
        });
        
    });

    // Read a page's GET URL variables and return them as an associative array.
    function getUrlVars()
    {
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    }
</script>

</body>
</html>
