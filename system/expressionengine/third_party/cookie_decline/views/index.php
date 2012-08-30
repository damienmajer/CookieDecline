<?=form_open('C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=cookie_decline'.AMP.'method=save_ext_settings')?>

<p><?=form_input('cp_cookie_message', $cookie_message)?>&nbsp;&nbsp;<span><?=lang('cp_cookie_message_label', 'cp_cookie_message_label')?></span></p>

<p><?=form_submit(array('name' => 'submit', 'value' => lang('submit'), 'class' => 'submit'))?></p>

<?=form_close()?>