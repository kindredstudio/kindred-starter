<?php

function kindred_typekit()
{
  wp_enqueue_script('kindred_typekit', '//use.typekit.net/ulm6tzh.js');
}
add_action('wp_enqueue_scripts', 'kindred_typekit');

function kindred_typekit_inline()
{
  if (wp_script_is('kindred_typekit', 'done')) { ?>
    <script type="text/javascript">try{Typekit.load({classes:false});}catch(e){}</script>
<?php }
}
add_action('wp_head', 'kindred_typekit_inline');
