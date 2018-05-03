<?php
/*
Plugin Name: Variation Shop Page
Plugin URI: http://imran1.com
Description: Variation Dropdown & Image updation
Version: 1.0.0
Author: Imran Khan
Author URI: http://imran1.com
*/


add_action( 'woocommerce_before_shop_loop', 'ik_variation_shop_page' );

function ik_variation_shop_page() {
    
	 
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 ); 
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_single_add_to_cart', 30 );
}

 add_filter( 'woocommerce_before_shop_loop_item_title', 'woo_display_variation_dropdown_on_shop_page' );
 
 function woo_display_variation_dropdown_on_shop_page() {

global $product;
	if( $product->is_type( 'variable' )) {

    
  
  
  // get the product variations
        $product_variations = $product->get_available_variations();
        if ( !empty( $product_variations ) ) {
            ?>
        <div class="product-variation-images">
        <?php
       
        foreach($product_variations as $product_variation) {
            // get the slug of the variation
           $product_attribute_name = strtolower($product_variation['attributes']['attribute_pa_color']);
           $product_attribute_logo = strtolower($product_variation['attributes']['attribute_logo']);
           if($product_attribute_logo != ""){ $product_attribute_logo = "-". $product_attribute_logo;}
            ?>
            <div class="product-variation-image product-variation-<?php echo $product_attribute_name.$product_attribute_logo; ?>" id="product-variation-<?php echo $product_variation['variation_id']; ?>" data-attribute="<?php echo $product_attribute_name ?>">
            <img src="<?php echo $product_variation['image']['thumb_src'] ; ?>" alt="">
            </div><!-- #product-variation-image -->
        <?php } ?>
        </div>
        <?php }
}

}


function ik_variation_css() {
    ?>
    <style>
    .product-variation-image {
    display: none;
}
</style>
<?php
}

add_action('wp_head', 'ik_variation_css'); 

function ik_variation_jquery() {
    ?>
    <script>
       jQuery('select').on('change', function() {

         var value = "";
         var logoValue = "";
         var pa_color = "";
         var pa_size = "";
     
         if (jQuery(this).parents("li").find("#pa_color").length > 0){ var pa_colorValue = jQuery(this).parents("li").find("#pa_color").val().toLowerCase();  }              
         if (jQuery(this).parents("li").find("#pa_size").length > 0){  var pa_sizeValue = jQuery(this).parents("li").find("#pa_size").val().toLowerCase();  }
         if (jQuery(this).parents("li").find("#logo").length > 0){     var logoValue = jQuery(this).parents("li").find("#logo").val().toLowerCase();  }
       
                            
        // get the option that has been selected
        if(logoValue !="") {value = ".product-variation-" + pa_colorValue+"-"+logoValue;}
        else{ value = ".product-variation-" + pa_colorValue; }
               
        
            
         if(pa_colorValue != ""){    
             var Id = jQuery(this).parents("li").find(value).attr('id');
            var img_id = "#"+Id;
                 
        
             if(Id.length  > 0  );
             {         
             // Hide all custom_variation divs
            jQuery(this).parents("li").find('.product-variation-image').css( 'display', 'none' );
             jQuery(this).parents("li").find('.attachment-woocommerce_thumbnail').css( 'display', 'none' );
             }
        
             jQuery( img_id ).css( 'display', 'block' );
         
        }
        else
        {
            // Hide all custom_variation divs
            jQuery(this).parents("li").find('.product-variation-image').css( 'display', 'none' );
            jQuery(this).parents("li").find('.attachment-woocommerce_thumbnail').css( 'display', 'none' );
            jQuery('.attachment-woocommerce_thumbnail').css( 'display', 'block' );
        }
                   
    });
    
         
       jQuery(document).on('click', '.reset_variations', function() {      
        jQuery(this).parents("li").find('.attachment-woocommerce_thumbnail').css( 'display', 'block' );
       
      });  
         
</script>
    <?php
}
add_action('wp_footer', 'ik_variation_jquery', 0 ); 
