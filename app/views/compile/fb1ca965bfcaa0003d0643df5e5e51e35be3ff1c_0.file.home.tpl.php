<?php
/* Smarty version 3.1.29, created on 2017-08-20 15:48:29
  from "/var/www/html/codebase/app/views/templates/home.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5999a13d34bb08_25593552',
  'file_dependency' => 
  array (
    'fb1ca965bfcaa0003d0643df5e5e51e35be3ff1c' => 
    array (
      0 => '/var/www/html/codebase/app/views/templates/home.tpl',
      1 => 1502983827,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5999a13d34bb08_25593552 ($_smarty_tpl) {
?>
<h1>Hello <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['name']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
</h1>
<?php }
}
