<?php

function converio_sidebar_menu() {  
    add_theme_page(  
        'Sidebar Management',           // The title to be displayed in the browser window for this page.  
        'Sidebars',                     // The text to be displayed for this menu item  
        'administrator',                // Which type of users can see this menu item  
        'converio_sidebars',        // The unique ID - that is, the slug - for this menu item  
        'converio_sidebars_page'    // The name of the function to call when rendering this menu's page  
    );  
}
add_action('admin_menu', 'converio_sidebar_menu'); 
 
// setting registration 
  
function converio_initialize_sidebar_options() { 
    register_setting('sidebars_section',  'custom_sidebar');   
} 
add_action('admin_init', 'converio_initialize_sidebar_options');  

  
// settings page rendering

function converio_sidebars_page() { 
    ?>

<div class="wrap">
    <h2><?php esc_attr_e('Manage Sidebars','converio');?></h2>
    <form method="post" action="options.php">  
        <?php settings_fields( 'sidebars_section' ); ?>  
        <?php do_settings_sections( 'sidebars_section' ); ?>             
        <?php converio_sidebars_form(); ?>
        <?php submit_button(); ?>  
    </form>
</div>
     <?php
}

// sidebar management form rendering

function converio_sidebars_form() {
    $custom_sidebar = get_option('custom_sidebar') ? get_option('custom_sidebar') : array();  
  
    $output = "<script type='text/javascript'>";  
    $output .= ' 
                var $ = jQuery; 
                $(document).ready(function(){ 
                    $(".sidebar_management").on("click", ".delete", function(){ 
                        $(this).parent().remove(); 
                    }); 
                     
                    $("#add_sidebar").click(function(){ 
                        $(".sidebar_management ul").append("<li>"+$("#new_sidebar_name").val()+" [<a href=\'#\' class=\'delete\'>'.esc_attr__("remove", "converio").'</a>] <input type=\'hidden\' name=\'custom_sidebar[]\' value=\'"+$("#new_sidebar_name").val()+"\' /></li>");  
                        $("#new_sidebar_name").val("");  
                    })  
                      
                })  
    ';
    $output .= "</script>";  
    $output .= "<div class='sidebar_management'>";  
    $output .= "<p><label for='new_sidebar_name'>".esc_attr__('Add a sidebar:', 'converio') ." </label><input type='text' id='new_sidebar_name' placeholder='".esc_attr__('Sidebar name', 'converio')."' /> <input class='button-primary' type='button' id='add_sidebar' value='".esc_attr__("add", "converio")."' /></p>";  
    $output .= "<ul>";

    if(isset($custom_sidebar)) {  
        $i = 0;  
        foreach($custom_sidebar as $sidebar) {  
            $output .= "<li>".esc_attr($sidebar)." [<a href='#' class='delete'>".esc_attr__("remove", "converio")."</a>] <input type='hidden' name='custom_sidebar[]' value='".esc_attr($sidebar)."' /></li>";  
            $i++;  
        }  
    }  
    $output .= "</ul>";  
    $output .= "</div>";  
      
    echo $output;  
}