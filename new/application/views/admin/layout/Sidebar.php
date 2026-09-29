<div class="sidebar-wrapper" data-simplebar="true">
  <div class="sidebar-header">
    <div> <img src="https://www.sekura.in/images/sekura-logo.png" class="logo-icon" alt="logo icon"> </div>
    <div>
      <h4 class="logo-text">Admin</h4>
    </div>
    <div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i> </div>
  </div>
  <!--navigation-->
  <ul class="metismenu" id="menu">
      <?php foreach($sidemainmenu as $smm){?>
    
    <li> <a href="javascript:;" class="has-arrow">
      <div class="parent-icon"><i class='bx bx-spa' ></i> </div>
      <div class="menu-title"><?php echo $smm['menu_name']?></div>
      </a>
      <ul>
        <?php  /*echo '<pre>';print_r($sidesubmenu[$smm['id']]); echo '</pre>';*/
		$array = json_decode(json_encode($sidesubmenu[$smm['id']]), true);
		foreach($array as $ssm){
			if($ssm['source']=='underconstruction'){$class='red';}else{$class='notred';}
			?>
        <li class="<?php echo $class?>"> <a href="<?= site_url($ssm['source']); ?>"><i class="bx bx-right-arrow-alt"></i><?php echo $ssm['sub_menu_name']?></a> </li> <?php 
		} ?>
      </ul>
    </li>
    <?php }?>
    
    <style>.red a{background:#BA0001 !important;color:#FFF !important}</style>
    
    

  </ul>
  <!--end navigation--> 
</div>
