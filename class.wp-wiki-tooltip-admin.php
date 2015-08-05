<?php
/**
 * Class WP_Wiki_Tooltip_Admin
 */
class WP_Wiki_Tooltip_Admin {

    private $options;

    public function __construct( $name='') {
        add_filter( 'plugin_action_links_' . $name, array( $this, 'add_action_links' ) );
        add_action( 'admin_menu', array( $this, 'init' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function init() {
        wp_enqueue_style( 'wp-wiki-tooltip-admin-css', plugins_url( 'static/css/wp-wiki-tooltip-admin.css', __FILE__ ), array(), '1.0', 'all' );

        add_options_page(
            __( 'Settings for Wiki-Tooltips', 'wp-wiki-tooltip' ),
            __( 'Wiki-Tooltips', 'wp-wiki-tooltip' ),
            'manage_options',
            'wp-wiki-tooltip-settings',
            array( $this, 'settings_page' )
        );

        $this->options = get_option( 'wp-wiki-tooltip-settings' );

        if( array_key_exists( 'btn_reset', $_REQUEST ) && $_REQUEST[ 'btn_reset' ] == __( 'Reset', 'wp-wiki-tooltip' ) ) {
            delete_option( 'wp-wiki-tooltip-settings' );
            header( 'Location: options-general.php?page=wp-wiki-tooltip-settings&settings-updated=reset' );
            die();
        }

        if( array_key_exists( 'settings-updated', $_REQUEST ) && $_REQUEST[ 'settings-updated' ] == 'reset' ) {
            add_settings_error(
                'wp-wiki-tooltip-settings-reset',
                'settings_updated',
                __( 'Settings reseted sucessfully.', 'wp-wiki-tooltip' ),
                'updated'
            );
        }
    }

    public function add_action_links( $links ) {
        return array_merge(
            $links,
            array( '<a href="' . admin_url( 'options-general.php?page=wp-wiki-tooltip-settings' ) . '">' . __( 'Settings', 'wp-wiki-tooltip' ) . '</a>', )
        );
    }

    public function register_settings() {
        global $wp_wiki_tooltip_default_options;

        register_setting(
            'wp-wiki-tooltip-settings',
            'wp-wiki-tooltip-settings',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'wp-wiki-tooltip-settings-base',
            __( 'Base Settings', 'wp-wiki-tooltip' ),
            array( $this, 'print_base_section_info' ),
            'wp-wiki-tooltip-settings-base'
        );

        add_settings_field(
            'wiki-url',
            __( 'ULR of Wiki', 'wp-wiki-tooltip' ),
            array( $this, 'print_wiki_url_field' ),
            'wp-wiki-tooltip-settings-base',
            'wp-wiki-tooltip-settings-base',
            $wp_wiki_tooltip_default_options
        );

        add_settings_field(
            'a-target',
            __( 'Open links to Wiki pages in', 'wp-wiki-tooltip' ),
            array( $this, 'print_a_target_field' ),
            'wp-wiki-tooltip-settings-base',
            'wp-wiki-tooltip-settings-base',
            $wp_wiki_tooltip_default_options
        );

        add_settings_section(
            'wp-wiki-tooltip-settings-design',
            __( 'Design Settings', 'wp-wiki-tooltip' ),
            array( $this, 'print_design_section_info' ),
            'wp-wiki-tooltip-settings-design'
        );

        add_settings_field(
            'theme',
            __( 'Design of the tooltips', 'wp-wiki-tooltip' ),
            array( $this, 'print_theme_field' ),
            'wp-wiki-tooltip-settings-design',
            'wp-wiki-tooltip-settings-design',
            $wp_wiki_tooltip_default_options
        );

        add_settings_field(
            'a-styles',
            __( 'CSS style of Wiki links', 'wp-wiki-tooltip' ),
            array( $this, 'print_a_style_field' ),
            'wp-wiki-tooltip-settings-design',
            'wp-wiki-tooltip-settings-design',
            $wp_wiki_tooltip_default_options
        );
    }

    public function print_base_section_info() {
        echo '<p>' . __( 'Set base options below:', 'wp-wiki-tooltip' ) . '</p>';
    }

    public function print_design_section_info() {
        echo '<p>' . __( 'Set design / style options below:' , 'wp-wiki-tooltip' ) . '</p>';
    }

    public function print_wiki_url_field( $args ) {
        printf(
            '<p><input type="text" id="wiki-url" name="wp-wiki-tooltip-settings[wiki-url]" value="%s" class="regular-text" /></p>',
            isset( $this->options['wiki-url'] ) ? esc_attr( $this->options[ 'wiki-url' ] ) : $args[ 'wiki-url' ]
        );
        echo '<p class="description">' . __( 'If you are not sure about the best URL take a look at <a href="https://wikipedia.org" target=_"blank">wikipedia.org</a> to find the right one.', 'wp-wiki-tooltip' ) . '</p>';
    }

    public function print_a_target_field( $args ) {
        $used_target = isset( $this->options[ 'a-target' ] ) ? $this->options[ 'a-target' ] : $args[ 'a-target' ];

        echo '<p><label><input type="radio" id="rdo-a-target-blank" name="wp-wiki-tooltip-settings[a-target]" value="_blank"' . checked( $used_target, '_blank', false ) . ' />' . __( 'new window / tab', 'wp-wiki-tooltip' ) . '</label></p>';
        echo '<p><label><input type="radio" id="rdo-a-target-self" name="wp-wiki-tooltip-settings[a-target]" value="_self" ' . checked( $used_target, '_self', false ) . ' />' . __( 'current window / tab', 'wp-wiki-tooltip' ) . '</label></p>';
    }

    public function print_theme_field( $args ) {
        $used_theme = isset( $this->options[ 'theme' ] ) ? $this->options[ 'theme' ] : $args[ 'theme' ];

        echo '<ul id="wiki-tooltip-admin-theme-list">';
        foreach( array( 'default', 'light', 'noir', 'punk', 'shadow' ) as $theme ) {
            echo '<li><label>';
            echo '<input type="radio" id="rdo-theme-' . $theme . '" name="wp-wiki-tooltip-settings[theme]" value="' . $theme . '"' . checked($used_theme, $theme, false) . ' />';
            echo '<span class="tooltipster-' . $theme . '-preview">' . $theme . '</span>';
            echo '</label></li>';
        }
        echo '</ul>';
    }

    public function print_a_style_field( $args ) {
        printf(
            '<p><input type="text" id="a-style" name="wp-wiki-tooltip-settings[a-style]" value="%s" class="regular-text" /></p>',
            isset( $this->options['a-style'] ) ? esc_attr( $this->options[ 'a-style' ] ) : $args[ 'a-style' ]
        );
        echo '<p class="description">' . __( 'All entered CSS settings will be put into the <strong>style</strong>-attribute of all links to Wiki pages.', 'wp-wiki-tooltip' ) . '</p>';
    }

    public function sanitize( $input ) {
        return $input;
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h2><?php _e( 'Settings for Wiki-Tooltips', 'wp-wiki-tooltip' ) ?></h2>
            <p id="wiki-usage"><?php _e( 'Use one of these shortcodes to enable Wiki-Tooltips:', 'wp-wiki-tooltip' ); ?>&nbsp;<span class="bold-teletyper">[wiki]WordPress[/wiki]</span>&nbsp;<?php _e( 'or', 'wp-wiki-tooltip' ); ?>&nbsp;<span class="bold-teletyper">[wiki title="WordPress"]a nice blogging software[/wiki]</span></p>
            <form method="post" action="options.php">
                <?php
                settings_fields( 'wp-wiki-tooltip-settings' );
                do_settings_sections( 'wp-wiki-tooltip-settings-base' );
                do_settings_sections( 'wp-wiki-tooltip-settings-design' );
                submit_button( __( 'Submit', 'wp-wiki-tooltip' ), 'primary', 'btn_submit', false );
                echo "&nbsp;&nbsp;&nbsp;";
                submit_button( __( 'Reset', 'wp-wiki-tooltip' ), 'secondary', 'btn_reset', false );
                ?>
            </form>
        </div>
        <?php
    }
}