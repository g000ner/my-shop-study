<?php /* Smarty version Smarty-3.1.6, created on 2021-12-24 12:36:15
         compiled from "../views/default\category.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1318061bd03618a5093-66149425%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '76efe512959f670dcfbe2dc8447081a8ad91a48b' => 
    array (
      0 => '../views/default\\category.tpl',
      1 => 1640345641,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1318061bd03618a5093-66149425',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.6',
  'unifunc' => 'content_61bd036199068',
  'variables' => 
  array (
    'pageTitle' => 0,
    'rsProducts' => 0,
    'item' => 0,
    'rsCategory' => 0,
    'rsChildCats' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_61bd036199068')) {function content_61bd036199068($_smarty_tpl) {?>
<h1><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</h1>
        
        <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['rsProducts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['iteration']=0;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['products']['iteration']++;
?>
            <div style="float: left; padding: 0px 30px 40px 0px">
                <a href="/product/<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
/">
                    <img src="/images/products/<?php echo $_smarty_tpl->tpl_vars['item']->value['image'];?>
" width="100" height="100"/>
                </a><br />
                <a href="/product/<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a>
            </div>
            <?php if ($_smarty_tpl->getVariable('smarty')->value['foreach']['products']['iteration']%3==0){?>
                <div style="clear: both;"></div>
            <?php }?>
        <?php }
if (!$_smarty_tpl->tpl_vars['item']->_loop) {
?>
            <?php if ($_smarty_tpl->tpl_vars['rsCategory']->value['parent_id']!=0){?>
                <h1>Нет товаров</h1>
            <?php }?>
        <?php } ?>

<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['rsChildCats']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
    <h2><a href="/category/<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
/"><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</a></h2>
<?php } ?>
<?php }} ?>