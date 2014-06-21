<?php
class nibl_Plugin_Settings {
	
	protected static $classobj;
	
	public function __construct() {
		
		if ( ! is_admin() )
			return;
		
		if ( is_multisite() && is_plugin_active_for_network( plugin_basename( NP_BASENAME ) ) ) {
			// multisite install
			add_filter( 'network_admin_plugin_action_links', array( $this, 'add_settings_link' ), 10, 2 );
			add_action( 'after_plugin_row_' . NP_BASENAME, array( 'nibl_Plugin_Settings', 'add_config_form'), 10, 3 );
		} else {
			// Single mode install of WP
			if ( version_compare( $GLOBALS['wp_version'], '2.7alpha', '>' ) ) {
				add_action( 'after_plugin_row_' . NP_BASENAME,    array( 'nibl_Plugin_Settings', 'add_config_form'), 10, 3 );
				add_filter( 'plugin_action_links_' . NP_BASENAME, array( $this, 'add_settings_link' ), 10, 2 );
			} else {
				add_action( 'after_plugin_row',     array( 'nibl_Plugin_Settings', 'add_config_form'), 10, 3 );
				add_filter( 'plugin_action_links',  array( $this, 'add_settings_link' ), 10, 2 );
			}
		}
	}
	
	/**
	 * Handler for the action 'init'. Instantiates this class.
	 *
	 * @since   2.0.0
	 * @access  public
	 * @return  $classobj
	 */
	public static function get_object() {
		if ( NULL === self :: $classobj ) {
			self :: $classobj = new self;
		}
	
		return self :: $classobj;
	}
	
	function add_settings_link( $links, $file ) {
		
		if ( plugin_basename( NP_BASENAME ) == $file  )
			array_unshift(
				$links,
				sprintf( '<a id="np-pluginconflink" href="javascript:void(0)" title="Configure this plugin">%s</a>', __('Settings') )
			);
		
		return $links;
	}
	
	
	function network_admin_add_settings_link( $links, $file ) {
		
		if ( plugin_basename( NP_BASENAME ) == $file )
			$links[] = '<a  id="np-pluginconflink" href="javascript:void(0)" title="Configure this plugin">' . __('Settings') . '</a>';
		
		return $links;
	}
	
	/**
	 * Add settings markup
	 * 
	 * @param   $np_pluginfile Object
	 * @param   $np_plugindata Object (array)
	 * @param   $np_context    Object (all, active, inactive)
	 * @return  void
	 */
	public function add_config_form( $np_pluginfile, $np_plugindata, $np_context ) {
		global $wp_roles;

		//if ( 0 < count($_POST['checked']) )
		//	return;
		$active_value = get_option('nibl_active');
		if ( is_multisite() && is_plugin_active_for_network( NP_BASENAME ) )
			$value = get_site_option( NP_TEXTDOMAIN );
		else
			$value = get_option( NP_TEXTDOMAIN );
		?>

    <link rel="stylesheet" type="text/css" media="all" href="<?php echo plugins_url('css/nibl-wordpress-plugin.css',__FILE__); ?>">
		<tr id="np_config_tr" >
			<td colspan="3">  
				<div id="np_config_row" style="display:none;">
						<div class="wrap">
						<div class="head">
							<img src="<?php echo plugins_url('Images/nibl_pop.png',__FILE__); ?>" />
						</div>
            <span class="nibl_setting_label">Active: </span>
						<select name="np_config-active" id="np_config-active">
							<option value="0"<?php if ( isset($active_value) && 0 == $active_value ) { echo ' selected="selected"'; } ?>><?php _e('False', NP_TEXTDOMAIN ); ?> </option>
							<option value="1"<?php if ( isset($active_value) && 1 == $active_value ) { echo ' selected="selected"'; } ?>><?php _e('True', NP_TEXTDOMAIN ); ?> </option>
						</select>
						<input id="np_permalink-setting" value="<?php echo get_option('permalink_structure'); ?>" type="hidden" />
            <br />
            <iframe style="margin-top:5px;" src="<?php echo get_option('nibl_URL'); ?>/client/acquiredomain.html" width="200" height="22" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe>
						<br />
						<hr />
            <table>
              <tr>
                <td>nibl URL:</td>
                <td><input class="nibl_inputs" type="text" id="np_config-nibl-url" name="np_config-nibl-url" value="<?php echo get_option('nibl_URL'); ?>"/></td>
              </tr>
              <tr>
                <td>nibl Key:</td>
                <td><input class="nibl_inputs" type="text" id="np_config-nibl-key" name="np_config-nibl-key" value="<?php echo get_option('nibl_key'); ?>"/></td>
              </tr>
              <tr>
                <td>nibl App Secret:</td>
                <td><input class="nibl_inputs" type="text" id="np_config-nibl-app-secret" name="np_config-nibl-app-secret" value="<?php echo get_option('nibl_app_secret'); ?>"/></td>
              </tr>
            <tr>
              <td>Verify SSL?:</td>
              <td><input type="checkbox" id="np_config-nibl-verify-ssl" name="np_config-nibl-verify-ssl" <?php if(get_option('nibl_verify_ssl') == "true"){ echo " checked='true' ";} ?>/></td>
              </tr>
            </table>
            <input id="np_config_submit" type="button" class="nibl_button" value="<?php _e( 'Save', NP_TEXTDOMAIN ); ?>" />
						<span class="label">
							New to nibl? <a onclick="CreateAccount()">Create Account</a>
						</span>
					</div>
					<script type="text/javascript">
					function CreateAccount()
					{
						var url = document.getElementById('nibl_URL').value;
						if(url == ""){
							alert("You must enter a nibl URL before you can create an account");
						}
						else{
							if(url.indexOf("http") == -1){
								url = "http://" + url;
							}
							newwindow=window.open(url +"/client/CreateAccount",'Create Account','height=500,width=500');
							newwindow.focus();
						}
					}
					</script>
				</div>
			</td>
		</tr>
		<?php
	}	
} // end class
?>
