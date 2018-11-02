<?php require_once("../classes/message.php"); ?>
<?php require_once("../classes/product.php"); ?>
<?php require_once("../classes/keyword.php"); ?>

<?php 
	$extra_param = "";
	if($active_session !== true){ 
		$extra_param = "?set_gender=".str_replace("/", "", $_GET['set_gender']);
	}
?>

<?php 
  
    $product_class        = new product();
    $keyword_class        = new keyword();

  /* ====================================================================== *
        BRAND MALE KEYWORDS
   * ====================================================================== */      

    $products_male    = $product_class->get_brand_keywords_per_gender("/male/", "");
    $keywords_male    = array();

    foreach ($products_male as $row) {
      $arr = explode("/", $row->get_keywords());
      foreach ($arr as $word) {
        if($word != ""){
          $keywords_male[] = $word;
        }
      }
    }
    $keywords_male = array_unique($keywords_male);
    
  /* ====================================================================== *
        BRAND FEMALE KEYWORDS
   * ====================================================================== */      

    $products_female    = $product_class->get_brand_keywords_per_gender("/female/", "");
    $keywords_female    = array();

    foreach ($products_female as $row) {
      $arr = explode("/", $row->get_keywords());
      foreach ($arr as $word) {
        if($word != ""){
          $keywords_female[] = $word;
        }
      }
    }
    $keywords_female = array_unique($keywords_female);

?>

<!--
/* ====================================================================== *
      SIDEBAR
 * ====================================================================== */        
--> 

  <nav id="slideout_menu" class="slideout_menu">
  	<section class="menu-section">
    	<ul class="menu-section-list">
      		<li><a href="../feed<?php echo $extra_param; ?>"><span class="fa fa-home"></span> &nbsp;Home</a></li>

            <!-- ### FOR MEN ### -->
          	<li>
              <a class="open_sub_menu">
                <i class="fa fa-plus"></i> &nbsp;For Men 
                <span data-url="../feed?set_gender=male" class="label label-info pull-right">EXPLORE</span>
              </a>
              
              <ul class="sub_menu">
                <li>
                  <a class="open_sub_menu"><i class="fa fa-plus"></i> &nbsp;Brands</a>

                  <ul class="sub_menu">
                    <?php foreach ($keywords_male as $row) { ?>
                      <?php $keyword_class->map($row); ?>
                      <li>
                        <a href="../feed?q=<?php echo $row; ?>&set_gender=male">
                          <?php echo str_replace("Brand Store :", "", $row); ?>
                          <span><?php echo $keyword_class->get_description(); ?></span>
                        </a>
                      </li>
                    <?php } ?>
                  </ul>
                </li>
              </ul>
            </li>

            <!-- ### FOR WOMEN ### -->
          	<li>
              <a class="open_sub_menu">
                <i class="fa fa-plus"></i> &nbsp;For Women 
                <span data-url="../feed?set_gender=female" class="label label-info pull-right">EXPLORE</span>
              </a>
              
              <ul class="sub_menu">
                <li>
                  <a class="open_sub_menu"><i class="fa fa-plus"></i> &nbsp;Brands</a>

                  <ul class="sub_menu">
                    <?php foreach ($keywords_female as $row) { ?>
                      <?php $keyword_class->map($row); ?>
                      <li>
                        <a href="../feed?q=<?php echo $row; ?>&set_gender=female">
                          <?php echo str_replace("Brand Store :", "", $row); ?>
                          <span><?php echo $keyword_class->get_description(); ?></span>
                        </a>
                      </li>
                    <?php } ?>
                  </ul>
                </li>
              </ul>
            </li>

          	<?php if($active_session === true){ ?>
      			<?php if($disable_things !== true){ ?>
              <li><a href="../feed?usr=<?php echo $user['id']; ?><?php echo str_replace("?", "&", $extra_param); ?>"><span class="fa fa-save"></span> &nbsp;Saved Products</a></li>
      				<li><a href="../cart"><span class="fa fa-shopping-cart"></span> &nbsp;Cart</a></li>
      				<li><a href="../orders"><span class="fa fa-th-list"></span> &nbsp;My Orders</a></li>
              		<?php $message    = new message(); ?>
              		<li><a href="../messages"><span class="badge"><?php echo $message->get_unread_messages($user['id'], 'admin'); ?></span> &nbsp;Messages</a></li>
      			<?php } ?>
      			<li><a href="../signout.php"><span class="fa fa-power-off"></span> &nbsp;Logout</a></li>
      		<?php }else{ ?>
      			<li><a href="../signin"><span class="fa fa-arrow-right"></span> &nbsp;Sign in</a></li>
      		<?php } ?>
    	</ul>
  	</section>
</nav>

<script>
	$( document ).ready(function() {

    /* ==== SLIDEOUT +=== */

  	var slideout = new Slideout({
    	'panel': window.document.getElementById('menu-page-wraper'),
    	'menu': window.document.getElementById('slideout_menu')
  	});

		document.querySelector('.js-slideout-toggle').addEventListener('click', function() {
	  		slideout.toggle();
		});

		document.querySelector('.slideout_menu').addEventListener('click', function(eve) {
      console.log(eve.target.classList.value);
	  		if (eve.target.nodeName === 'A' && eve.target.classList.value != 'open_sub_menu') { slideout.close(); }
		});

    slideout.on('beforeopen', function() {
      //document.querySelector('.fixed-element').classList.add('fixed-open');
    });

    slideout.on('beforeclose', function() {
      //document.querySelector('.fixed-element').classList.remove('fixed-open');
    });

    /* ==== SUBMENU ==== */

    $('.open_sub_menu .label').on('click', function(e){
      e.stopPropagation();
      location.href=$(this).attr('data-url');
    })

    $('.open_sub_menu').on('click', function(e){
      e.preventDefault();

      var $this     = $(this);
      var sub_menu  = $this.siblings('.sub_menu');

      sub_menu.stop();
      if(sub_menu.parent('li').hasClass('open')){
        sub_menu.parent('li').removeClass('open');
        sub_menu.slideUp();
      }else{
        sub_menu.parent('li').addClass('open');
        sub_menu.slideDown();
      }

    });

  });
</script>