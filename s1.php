<?php
/*
Plugin Name: Spam Blocker S1
Plugin URI: http://wordpress.org/extend/plugins/spam-blocker-s1/
Description: This Plugin blocks comments which is judged to be 'spam' by a standard WordPress function. (このプラグインは、WordPress標準のコメントブラックリストによる判定の結果'spam'となったコメントを、登録させないで、エラー画面を表示する。)
Version: 3
Author: Yoshimura Hiroyuki
*/

function sbs1_spam_comment_filter($approved, $commentdata) {
	if ($approved === 'spam') {
		$sbs1_message = get_option('sbs1_message');
		wp_die($sbs1_message ? $sbs1_message : 'NGワードを含むコメントは受け付けておりません。');
	}
	return $approved;
}
add_filter('pre_comment_approved', 'sbs1_spam_comment_filter', 1, 2);



if (is_admin()) {
	function sbs1_config_menu() {
			add_submenu_page('options-general.php', 'Spam Blocker S1 Configuration', 'Spam Blocker S1', 'manage_options', 'sbs1_config', 'sbs1_config');
	}
	add_action('admin_menu', 'sbs1_config_menu');

	function sbs1_config() {
		//update
		if (!empty($_POST['submit'])) {
	        update_option('sbs1_message', $_POST['sbs1_message']);
	    }
	    
	    //form
		$sbs1_message = get_option('sbs1_message');
?>
<div class="wrap">
<div id="icon-plugins" class="icon32"><br /></div><h2>Spam Blocker S1 Configuration</h2>
<?php if ( !empty($_POST['submit'] ) ) : ?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.') ?></strong></p></div>
<?php endif; ?>
<form method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<p>コメントを拒否したときに表示するメッセージを設定します。</p>
<p>default: NGワードを含むコメントは受け付けておりません。</p>
<p><textarea name="sbs1_message" cols="80" rows="5"><?php echo htmlspecialchars($sbs1_message); ?></textarea></p>
<?php settings_fields('sbs1_config'); ?>
<?php submit_button(); ?>
</form>
<hr>
<div>
<p>This Plugin blocks comments which is judged to be 'spam' by a standard WordPress function. When a 'spam' comment is submitted, an error message is shown and the comment will not registerd.</p>
<p>このプラグインは、WordPress標準のコメントブラックリストによる判定の結果'spam'となったコメントを、登録させないで、エラー画面を表示する。</p>
<p>スパム判定させるには、コメントブラックリストを設定してください。</p>
</div>
</div>
<?php
	}
}
