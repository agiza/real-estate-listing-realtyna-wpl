<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<script type="text/javascript">
wplj(document).ready(function(){
});

function wpl_setting_save(setting_id, setting_name, setting_value, setting_category)
{
	wplj("#wpl_st_form_element"+setting_id).attr("disabled", "disabled");
	
	element_type = wplj("#wpl_st_form_element"+setting_id).attr('type');
	
	if(element_type == 'checkbox')
	{
		if(wplj("#wpl_st_form_element"+setting_id).is(':checked')) setting_value = 1;
		else setting_value = 0;
	}
	
	ajax_loader_element = '#wpl_ajax_loader_'+setting_id;
	wplj(ajax_loader_element).html('<img src="<?php echo wpl_global::get_wpl_asset_url('img/ajax-loader3.gif'); ?>" />');
	
	request_str = 'wpl_format=b:settings:ajax&wpl_function=save&setting_name='+setting_name+'&setting_value='+encodeURIComponent(setting_value)+'&setting_category='+setting_category;
	
	/** run ajax query **/
	ajax = wpl_run_ajax_query('<?php echo wpl_global::get_full_url(); ?>', request_str, ajax_loader_element);
	
	ajax.success(function(data)
	{
		wplj("#wpl_st_form_element"+setting_id).removeAttr("disabled");
		
		if(data.success == 1)
		{
			wplj(ajax_loader_element).html('');
		}
		else if(data.success != 1)
		{
			wplj(ajax_loader_element).html('');
		}
	});
}

function wpl_setting_show_shortcode(setting_id, shortcode_key, shortcode_value)
{
	wplj("#wpl_st_"+setting_id+"_shortcode_value").html(shortcode_key+'="'+shortcode_value+'"');
}
</script>