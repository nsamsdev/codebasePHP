<?php
/* Smarty version 3.1.29, created on 2017-08-20 16:40:22
  from "/var/www/html/codebase/app/views/templates/footer.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5999ad66ef20b0_06463081',
  'file_dependency' => 
  array (
    'bb0c12a9f6fec71d7c1241bcceef80e9f4f2875f' => 
    array (
      0 => '/var/www/html/codebase/app/views/templates/footer.tpl',
      1 => 1503238651,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5999ad66ef20b0_06463081 ($_smarty_tpl) {
?>
<footer id="footer">
   

    
</footer>


    <!--Load Jquery -->
    <?php echo '<script'; ?>
 type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js'><?php echo '</script'; ?>
>
    
    <?php echo '<script'; ?>
 type='text/javascript'
            src='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js'><?php echo '</script'; ?>
>
            <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['url']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
public/js/main.js"><?php echo '</script'; ?>
>
</body>
</html>


<?php }
}
