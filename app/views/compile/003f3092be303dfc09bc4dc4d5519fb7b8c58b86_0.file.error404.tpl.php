<?php
/* Smarty version 3.1.29, created on 2017-08-20 16:40:22
  from "/var/www/html/codebase/app/views/templates/error404.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_5999ad66eed645_82784366',
  'file_dependency' => 
  array (
    '003f3092be303dfc09bc4dc4d5519fb7b8c58b86' => 
    array (
      0 => '/var/www/html/codebase/app/views/templates/error404.tpl',
      1 => 1502983703,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5999ad66eed645_82784366 ($_smarty_tpl) {
?>
<div id="page-masterhead" class="general error404">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 page-heading">
                <h1>Error 404</h1>
            </div>
        </div>
    </div>
</div>


<main>
    <div class="container">
        <div class="row">

            <div class="col-xs-12 col-main-content">
                <div class="content-entry">
                    <i><?php if ($_smarty_tpl->tpl_vars['mode']->value == 'development') {?>
                            <div class="alert">
                                <?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['message']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>

                            </div>
                        <?php }?>
                    </i>
                    <br/>
                    The page you are looking for cannot be found. Please try searching for it.
                </div>
            </div>
        </div>
    </div>
</main>
<?php }
}
