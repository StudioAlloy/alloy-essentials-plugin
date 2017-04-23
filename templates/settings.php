<div class="wrap">
	<h2><img src="http://studioalloy.nl/img/alloy-logo.svg" width="30"> Studio Alloy Essentials</h2>
	<p>Default settings for a Studio Alloy Wordpress project</p>
	<hr>
	<?php
		$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'admin_cleanup';
	?>
	<h2 class="nav-tab-wrapper">
	    <a href="?page=<?php echo $_GET['page'];?>&tab=admin_cleanup" class="nav-tab <?php echo $active_tab == 'admin_cleanup' ? 'nav-tab-active' : ''; ?>">Admin clean-up</a>
	    <a href="?page=<?php echo $_GET['page'];?>&tab=jaap_fixes" class="nav-tab <?php echo $active_tab == 'jaap_fixes' ? 'nav-tab-active' : ''; ?>">Jaap fixes<?php echo get_option('sae_jaap_fix_javascript_test') != '' || get_option('sae_jaap_fix_javascript_test') != NULL ? ' <span class="settings_alert"></span>' : '';?></a>
	    <a href="?page=<?php echo $_GET['page'];?>&tab=plugins" class="nav-tab <?php echo $active_tab == 'plugins' ? 'nav-tab-active' : ''; ?>">Plugins</a>
	</h2>

	<?php switch($active_tab):
	case 'admin_cleanup': ?>
	<form method="post" action="options.php">
		<?php settings_fields( 'sae-plugin-settings-group' ); ?>
		<?php do_settings_sections( 'sae-plugin-settings-group' ); ?>
		<table class="form-table">
			<tr>
				<th>set upload_size_limit</th>
				<td>
					<input type="number" min="0" max="10000000" name="sae_upload_size_limit" value="<?php echo (esc_attr( get_option('sae_upload_size_limit') ) == '')?'300':esc_attr( get_option('sae_upload_size_limit', 300) ); ?>" />KB
					<p class="description" id="tagline-description">Set to 0 to turn off</p>
				</td>
			</tr>
		</table>
		<hr/>
		<h2>Admin cleanup</h2>
		<table class="form-table">
			<tr>
				<th>Header</th>
				<td>
					<fieldset>
						<label for="sae_clean_admin"><input type="checkbox" id="sae_clean_admin" name="sae_clean_admin" value="1" <?php echo checked(1, get_option('sae_clean_admin', 1), false);?>/> Clean admin panel</label><br>
						<?php if(!get_option('sae_hide_all_comment_stuff')):?><label for="sae_clean_admin_header_comments"><input type="checkbox" id="sae_clean_admin_header_comments" name="sae_clean_admin_header_comments" value="1" <?php echo checked(1, get_option('sae_clean_admin_header_comments', 1), false);?>/> Hide comments</label><br><?php endif;?>
						<label for="sae_clean_admin_header_new_content"><input type="checkbox" id="sae_clean_admin_header_new_content" name="sae_clean_admin_header_new_content" value="1" <?php echo checked(1, get_option('sae_clean_admin_header_new_content', 1), false);?>/> Hide new content</label>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th>Menu</th>
				<td>
					<fieldset>
						<label for="sae_hide_posts"><input type="checkbox" id="sae_hide_posts" name="sae_hide_posts" value="1" <?php echo checked(0, get_option('sae_hide_posts', 1), false);?>/> Hide posts</label><br>
						<label for="sae_hide_links"><input type="checkbox" id="sae_hide_links" name="sae_hide_links" value="1" <?php echo checked(1, get_option('sae_hide_links', 1), false);?>/> Hide links</label><br>
						<label for="sae_hide_tools"><input type="checkbox" id="sae_hide_tools" name="sae_hide_tools" value="1" <?php echo checked(1, get_option('sae_hide_tools', 1), false);?>/> Hide tools</label><br>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th>Posts (normal)</th>
				<td>
					<fieldset>
						<label for="sae_hide_post_format"><input type="checkbox" id="sae_hide_post_format" name="sae_hide_post_format" value="1" <?php echo checked(1, get_option('sae_hide_post_format', 1), false);?>/> Hide format</label><br>
						<label for="sae_hide_post_categories"><input type="checkbox" id="sae_hide_post_categories" name="sae_hide_post_categories" value="1" <?php echo checked(1, get_option('sae_hide_post_categories', 1), false);?>/> Hide categories</label><br>
						<label for="sae_hide_post_tags"><input type="checkbox" id="sae_hide_post_tags" name="sae_hide_post_tags" value="1" <?php echo checked(1, get_option('sae_hide_post_tags', 1), false);?>/> Hide tags</label><br>
						<label for="sae_hide_post_featured_image"><input type="checkbox" id="sae_hide_post_featured_image" name="sae_hide_post_featured_image" value="1" <?php echo checked(1, get_option('sae_hide_post_featured_image', 1), false);?>/> Hide featured image</label><br>
					</fieldset>
				</td>
			</tr>
		</table>
		<hr/>
		<h2>Comments</h2>
		<table class="form-table">
			<tr>
				<th>Remove all comment stuff</th>
				<td>
					<fieldset>
						<label for="sae_hide_all_comment_stuff"><input type="checkbox" id="sae_hide_all_comment_stuff" name="sae_hide_all_comment_stuff" value="1" <?php echo checked(1, get_option('sae_hide_all_comment_stuff', 1), false);?>/> no comment...</label><br>
					</fieldset>
				</td>
			</tr>
		</table>
		<hr/>
		<h2>Dashboard</h2>
		<table class="form-table">
			<tr>
				<th>Cleaner dashboard</th>
				<td>
					<fieldset>
						<label for="sae_clean_up_dashboard"><input type="checkbox" id="sae_clean_up_dashboard" name="sae_clean_up_dashboard" value="1" <?php echo checked(1, get_option('sae_clean_up_dashboard', 1), false);?>/> Remove some mess from the dashboard</label><br>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
	<?php break;?>
	<?php case 'jaap_fixes':?>
	<form method="post" action="options.php">
		<?php settings_fields( 'sae-plugin-jaap-settings-group' ); ?>
		<?php do_settings_sections( 'sae-plugin-jaap-settings-group' ); ?>
		<table class="form-table">
			<tr>
				<th>Javascript (test)</th>
				<td>
					<textarea name="sae_jaap_fix_javascript_test" id="sae_jaap_fix_javascript_test" rows="7" cols="70"<?php echo get_option('sae_jaap_fix_javascript_test') != '' || get_option('sae_jaap_fix_javascript_test') != NULL ? ' style="outline:1px solid red;"' : '';?>><?php echo esc_attr( get_option('sae_jaap_fix_javascript_test') ); ?></textarea>
					<p class="description" id="tagline-description">This javascript is for admin only</p>
				</td>
			</tr>
			<tr>
				<th>Javascript</th>
				<td>
					<textarea name="sae_jaap_fix_javascript" id="sae_jaap_fix_javascript" rows="7" cols="70"><?php echo esc_attr( get_option('sae_jaap_fix_javascript') ); ?></textarea>
					<p class="description" id="tagline-description">This javascript will load in the footer</p>
				</td>
			</tr>
			<tr>
				<th>You need some help?</th>
				<td>
					<label for="sae_jaap_fix_need_jquery"><input type="checkbox" id="sae_jaap_fix_need_jquery" name="sae_jaap_fix_need_jquery" value="1" <?php echo checked(1, get_option('sae_jaap_fix_need_jquery', 1), false);?>/> jQuery to the rescue!</label><br>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
	<?php break;?>
	<?php case 'plugins':?>
	<?php
		$plugins = get_plugins();
		$list_plugins = array(
			"General" => array(
				array(
					'file' => 'contact-form-7/wp-contact-form-7.php',
					'name' => 'Contact form 7',
					'slug' => 'contact-form-7'
				),
				array(
					'file' => 'duplicate-post/duplicate-post.php',
					'name' => 'Duplicate Post',
					'slug' => 'duplicate-post'
				),
				array(
					'file' => 'enable-media-replace/enable-media-replace.php',
					'name' => 'Enable Media Replace',
					'slug' => 'enable-media-replace'
				),
				array(
					'file' => 'user-role-editor/user-role-editor.php',
					'name' => 'User Role Editor',
					'slug' => 'user-role-editor'
				),
				array(
					'file' => 'advanced-custom-fields/acf.php',
					'name' => 'Advanced Custom Fields',
					'slug' => 'advanced-custom-fields'
				)
			),
			"Visuals" => array(
				array(
					'file' => 'wp-admin-ui-customize/wp-admin-ui-customize.php',
					'name' => 'WP Admin UI Customize',
					'slug' => 'wp-admin-ui-customize'
				),
				array(
					'file' => 'js_composer/js_composer.php',
					'name' => 'WPBakery Visual Composer',
					'slug' => 'js_composer'
				)
			),
			"SEO" => array(
				array(
					'file' => 'wordpress-seo/wp-seo.php',
					'name' => 'Yoast SEO',
					'slug' => 'wp-seo'
				)
			)
		);
	?>
	<?php foreach($list_plugins AS $key => $value):?>
		<h3><?php echo $key;?></h3>
		<ul>
			<?php foreach($value AS $key2 => $value2):?>
				<li>
					<?php if(!array_key_exists($value2['file'], $plugins)):?>
					<span class="noticespan red"><?php echo $value2['name'];?></span> <a href="<?php echo wp_nonce_url(add_query_arg(array('action' => 'install-plugin','plugin' => $value2['slug']),admin_url( 'update.php' )),'install-plugin_'.$value2['slug']);?>">install</a>
					<?php else:?>
					<span class="noticespan green"><?php echo $value2['name'];?></span> <?php echo is_plugin_active($value2['file']) ? '(Active)': '(Not active)';?>
					<?php endif;?>
				</li>
			<?php endforeach;?>
		</ul>
	<?php endforeach;?>

	<?php break;?>
	<?php endswitch;?>
</div>
