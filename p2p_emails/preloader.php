<style type="text/css">
    #loadingDiv {
        position: absolute;;
        top:0;
        left:0;
        width: 50%; /*100%;*/
        height:100%;
        z-index: 1000;
    }
    
    .loader,
    .loader:after {
        border-radius: 50%;
        width: 10em;
        height: 10em;
    }
    .loader {            
        margin: 60px auto;
        font-size: 10px;
        position: relative;
        text-indent: -9999em;
        border-top: 1.1em solid #3498db;
        border-right: 1.1em solid #3498db;
        border-bottom: 1.1em solid #3498db;
        border-left: 1.1em solid #ffffff;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation: load8 1.1s infinite linear;
        animation: load8 1.1s infinite linear;
    }
    @-webkit-keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    @keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }
    
    /*.loader {
      border: 16px solid #f3f3f3; /* Light grey */
      /*border-top: 16px solid #3498db; /* Blue */
      /*border-radius: 50%;
      width: 120px;
      height: 120px;
      animation: spin 2s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }*/
</style>

<div class="col-md-offset-3 col-md-4 text-center" id="loadingDiv">
    <div class="loader"></div>
    <div id="divPreloaderText">Getting email(s) from server, it may take sometime...</div>
</div>
