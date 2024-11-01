<?php
/**
 *
 * @link              https://www.friedersdorf.de
 * @since             1.0.0
 * @package           Weg_Mit_219a
 *
 * @wordpress-plugin
 * Plugin Name:       WEG MIT §219a! 
 * Plugin URI:        https://www.friedersdorf.de/weg-mit-219a-ein-wordpress-plugin-zur-anzeige-von-informationen-zu-schwangerschaftsabbruchen/
 * Description:       Am 24.06.2022 wurde der Paragraph endlich aus dem Strafgesetzbuch gestrichen - daher ist dieses Plugin endlich unnötig. Trotzdem: Dieses Plugin fügt eine handliche Schaltfläche auf Deinem Wordpress-Blog ein, der deine Besucher über Schwangerschaftsabbrüche informiert. Bitte nur benutzen, wenn Du selbst keine anbietest, denn das wäre laut $219a verboten (ja, ehrlich).
 * Version:           1.0.4
 * Author:            Marco Friedersdorf
 * Author URI:        https://www.friedersdorf.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       weg-mit-219a
 * Domain Path:       /languages
 */
// Direkten Aufruf verhindern
defined('ABSPATH') or die('Are you ok?');

class add219content {

    public static $instance;

    public static function init() {
        null === self::$instance && self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        wp_register_style('ns219', plugins_url('/public/css/stylo.css', __FILE__), array(), '1');
        wp_enqueue_style('ns219');
        wp_enqueue_script('myscriptos', plugin_dir_url(__FILE__) . 'public/js/elscripto.js', array('jquery'), '1.0');
        wp_localize_script('myscriptos', 'myScript', array('pluginsUrl' => plugin_dir_url(__FILE__),));
        add_shortcode('wegmit219a', [$this, 'generateButtonShort']);


        add_action('admin_menu', [$this, 'admin_menu']);
        add_filter('wp_footer', [$this, 'the_content']);
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'action_links']);
    }

    public function action_links($links) {
        $settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">Einstellungen</a>';
        $links[] = $settings_link;
        return $links;
    }

    public function generateButtonShort() {
        $fontcolor = get_option('add219_fontcolor', '#ffffff');
        $backgroundcolor = get_option('add219_backgroundcolor', '#ff0080');
        $buttonContent = '<div id="btn219" style="color: ' . $fontcolor . '; background-color:' . $backgroundcolor . ';" class=" btn219-short" ><div class="firstline">WEG MIT</div><div class="secondline">§219a!</div></div><div id="modal219"></div>';
        

        return $buttonContent;
    }
	

    public function generateButton() {

        $fontcolor = get_option('add219_fontcolor', '#ffffff');
        $backgroundcolor = get_option('add219_backgroundcolor', '#ff0080');
        $buttonContent = '<div id="btn219" style="color: ' . $fontcolor . '; background-color:' . $backgroundcolor . ';" class="';
        $position = get_option('add219_position', 'bottom_right');



        switch ($position) {
            case 'top_right': $buttonContent .= 'btn219-top btn219-right btn219-fix';
                break;
            case 'top_left': $buttonContent .= 'btn219-left btn219-top btn219-fix';
                break;
            case 'bottom_left': $buttonContent .= 'btn219-left btn219-bottom btn219-fix';
                break;
            case 'bottom_right': $buttonContent .= 'btn219-right btn219-bottom btn219-fix';
                break;
        }

        $buttonContent .= '" ><div class="firstline">WEG MIT</div><div class="secondline">§219a!</div></div><div id="modal219"></div>';
        

        return $buttonContent;
    }

    public function admin_menu() {
        add_submenu_page('options-general.php', 'WEG MIT §219a!', 'WEG MIT §219a!', 8, \basename(__FILE__), [$this, 'main']);
    }

    function the_content() {

        if (get_option('add219_hideloggedin', '') == 'on') {
            return '';
        }
        $buttonContent = $this::generateButton();
        $publish = get_option('add219_where', 'both');
        if (true) {
            switch ($publish) {
                case 'both':
                    echo $buttonContent;
                    break;

                case 'posts':
                    if (!is_page(get_the_ID())) {
                        echo $buttonContent;
                    }
                    break;

                case 'pages':
                    if (is_page(get_the_ID())) {
                        echo $buttonContent;
                    }
                    break;

                default:
                    break;
            }
        }
	}

    public function main() {
        ?>
        <div class="wrap">
            <h2>WEG MIT §219a!</h2>



            <?php
            // Sanitizing Input Data
            $retrieved_nonce = $_REQUEST['_wpnonce'];
            if (wp_verify_nonce($retrieved_nonce, 'post_219a_action')) {
                if (isset($_POST['add219_where']) and isset($_POST['add219_position']) and isset($_POST['add219_fontcolor']) and isset($_POST['add219_backgroundcolor'])) {
                    $message = '<div class="message error"><p>Einstellungen konnten nicht aktualisiert werden</p></div>';
                    $add219_where = sanitize_text_field($_POST['add219_where']);
                    $add219_position = sanitize_text_field($_POST['add219_position']);
                    $add219_fontcolor = sanitize_hex_color($_POST['add219_fontcolor']);
                    $add219_backgroundcolor = sanitize_hex_color($_POST['add219_backgroundcolor']);
                    $add219_hideloggedin = sanitize_text_field($_POST['add219_hideloggedin']);
                    // Validating Input Data
                    if (\in_array($add219_where, ['both', 'posts', 'pages', 'nowhere'])) {
                        if (\in_array($add219_position, ['top_left', 'top_right', 'bottom_left', 'bottom_right'])) {
                            if ($add219_fontcolor != '' && $add219_backgroundcolor != '') {
                                if ($add219_hideloggedin == 'on' || $add219_hideloggedin == '' || !$add219_hideloggedin) {
                                    \update_option('add219_where', $add219_where);
                                    \update_option('add219_position', $add219_position);
                                    \update_option('add219_fontcolor', $add219_fontcolor);
                                    \update_option('add219_backgroundcolor', $add219_backgroundcolor);
                                    \update_option('add219_hideloggedin', $add219_hideloggedin);
                                    $message = '<div class="message updated"><p>Einstellungen aktualisiert</p></div>';
                                }
                            }
                        }
                    }
                    echo $message;
                }
            }

            $add219_where = \get_option('add219_where', 'both');
            $add219_position = \get_option('add219_position', 'bottom_right');
            $add219_fontcolor = \get_option('add219_fontcolor', '#ffffff');
            $add219_backgroundcolor = \get_option('add219_backgroundcolor', '#ff0080');
            $add219_hideloggedin = \get_option('add219_hideloggedin', '');
            ?>
                        <hr>
            <div style="width: 50%; float:left">
                <h3>Einstellungen</h3>
                <form method="post" action="?<?php echo $_SERVER['QUERY_STRING']; ?>">
                    <p>
                        Wo soll die Schaltfläche zu sehen sein?
                    </p>
                    <div style="margin-left: 1.5em;">
                        <p>
                            <label for="all_posts_pages">
                                <input type="radio" id="all_posts_pages" name="add219_where" value="both"<?php
                                echo esc_attr(\checked($add219_where, 'both'));
                                ?> />
                                Auf allen Seiten und Posts
                            </label>
                        </p>
                        <p>
                            <label for="posts_only">
                                <input type="radio" id="posts_only" name="add219_where" value="posts"<?php
                                echo esc_attr(\checked($add219_where, 'posts'));
                                ?> />
                                Nur in Posts
                            </label>
                        </p>
                        <p>
                            <label for="pages_only">
                                <input type="radio" id="pages_only" name="add219_where" value="pages"<?php
                                echo esc_attr(\checked($add219_where, 'pages'));
                                ?> />
                                Nur auf Seiten
                            </label>
                        </p>
                        <p>
                            <label for="nowhere">
                                <input type="radio" id="nowhere" name="add219_where" value="nowhere"<?php
                                echo esc_attr(\checked($add219_where, 'nowhere'));
                                ?> />
                                Nirgends
                            </label>

                        </p>
                        <i>Du kannst auch gerne den Shortcode <b>[wegmit219a]</b> benutzen und die Schaltfläche <br/>
                            zb. mittels <b>&lt;?php do_shortcode( '[wegmit219a]') ?&gt;</b> einbinden wo du möchtest.</i>
                    </div>
                    <div style="margin-left: 1.5em;">
                        <p>
                            <label for="hideloggedin">
                                <input type="checkbox" id="hideloggedin" name="add219_hideloggedin" <?php echo esc_attr(\checked($add219_hideloggedin, 'on')); ?> />
                                Schaltfläche vor angemeldeten Usern verbergen (falls zb. der Chef das nicht direkt sehen soll)
                            </label>
                        </p>
                    </div>


                    <p>
                        Wie möchtest du die Schaltfläche positionieren?
                    </p>
                    <div style="margin-left: 1.5em;">
                        <p>
                            <label for="top_left">
                                <input type="radio" id="top_left" name="add219_position" value="top_left"<?php
                                echo esc_attr(\checked($add219_position, 'top_left'));
                                ?> />
                                Oben links
                            </label>
                        </p>
                        <p>
                            <label for="top_right">
                                <input type="radio" id="top_right" name="add219_position" value="top_right"<?php
                                echo esc_attr(\checked($add219_position, 'top_right'));
                                ?> />
                                Oben rechts
                            </label>
                        </p>
                        <p>
                            <label for="bottom_right">
                                <input type="radio" id="bottom_right" name="add219_position" value="bottom_right"<?php
                                echo esc_attr(\checked($add219_position, 'bottom_right'));
                                ?> />
                                Unten rechts
                            </label>
                        </p>
                        <p>
                            <label for="bottom_left">
                                <input type="radio" id="bottom_left" name="add219_position" value="bottom_left"<?php
                                echo esc_attr(\checked($add219_position, 'bottom_left'));
                                ?> />
                                Unten links
                            </label>
                        </p>
                    </div>

                    <p>
                        Wie sieht's mit Farben aus?
                    </p>
                    <div style="margin-left: 1.5em;">
                        <p>
                            <label for="fontcolor">
                                <input type="color" id="fontcolor" name="add219_fontcolor" value="<?php echo esc_attr($add219_fontcolor); ?>"/>
                                Schriftfarbe
                            </label>
                        </p>
                        <p>
                            <label for="backgroundcolor">
                                <input type="color" id="backgroundcolor" name="add219_backgroundcolor" value="<?php echo esc_attr($add219_backgroundcolor); ?>" />
                                Hintergrundfarbe
                            </label>
                        </p>

                    </div>

                    <?php wp_nonce_field('post_219a_action'); ?>
                    <p>
                        <input type="submit" value="Einstellungen abspeichern" class="button-primary" />
                    </p>
                </form>
            </div>
            <div style="width: 50%; float:left">

                <h3>Wenn dir das Plugin gefällt, würde ich <br/>mich sehr über einen Kaffee freuen <3</h3>

                <style>img.kofiimg{display: initial!important;vertical-align:middle;height:13px!important;width:20px!important;padding-top:0!important;padding-bottom:0!important;border:none;margin-top:0;margin-right:5px!important;margin-left:0!important;margin-bottom:3px!important;content:url('<?php echo plugin_dir_url(__FILE__) . 'admin/images/cup-border.png'; ?>')}.kofiimg:after{vertical-align:middle;height:25px;padding-top:0;padding-bottom:0;border:none;margin-top:0;margin-right:6px;margin-left:0;margin-bottom:4px!important;content:url('<?php echo plugin_dir_url(__FILE__) . 'admin/images/whitelogo.svg'; ?>')}.btn-container{display:inline-block!important;white-space:nowrap;min-width:160px}span.kofitext{color:#fff !important;letter-spacing: -0.15px!important;text-wrap:none;vertical-align:middle;line-height:33px !important;padding:0;text-align:center;text-decoration:none!important; text-shadow: 0 1px 1px rgba(34, 34, 34, 0.05);}.kofitext a{color:#fff !important;text-decoration:none:important;}.kofitext a:hover{color:#fff !important;text-decoration:none}a.kofi-button{box-shadow: 1px 1px 0px rgba(0, 0, 0, 0.2);line-height:36px!important;min-width:150px;display:inline-block!important;background-color:#29abe0;padding:2px 12px !important;text-align:center !important;border-radius:7px;color:#fff;cursor:pointer;overflow-wrap:break-word;vertical-align:middle;border:0 none #fff !important;font-family:'Quicksand',Helvetica,Century Gothic,sans-serif !important;text-decoration:none;text-shadow:none;font-weight:700!important;font-size:14px !important}a.kofi-button:visited{color:#fff !important;text-decoration:none !important}a.kofi-button:hover{opacity:.85;color:#f5f5f5 !important;text-decoration:none !important}a.kofi-button:active{color:#f5f5f5 !important;text-decoration:none !important}.kofitext img.kofiimg {height:15px!important;width:22px!important;display: initial;animation: kofi-wiggle 3s infinite;}@keyframes kofi-wiggle{0%{transform:rotate(0) scale(1)}60%{transform:rotate(0) scale(1)}75%{transform:rotate(0) scale(1.12)}80%{transform:rotate(0) scale(1.1)}84%{transform:rotate(-10deg) scale(1.1)}88%{transform:rotate(10deg) scale(1.1)}92%{transform:rotate(-10deg) scale(1.1)}96%{transform:rotate(10deg) scale(1.1)}100%{transform:rotate(0) scale(1)}}</style><!-- comment -->
                <div class="btn-container"><a title="Support me on ko-fi.com" class="kofi-button" style="background-color:#29abe0;" href="https://ko-fi.com/H2H83K692" target="_blank"> <span class="kofitext"><img src="<?php echo plugin_dir_url(__FILE__) . 'admin/images/cup-border.png'; ?>" alt="Ko-fi donations" class="kofiimg">Support Me on Ko-fi</span></a></div>

                <p>
                    Alternativ würden sich aber auch folgende <br/>
                    Organisationen über eine Spende freuen:
                </p>
                <ul>
                    <li><a href="https://www.profamilia.de/ueber-pro-familia/spenden" target="_blank">pro familia</a></li>
                    <li><a href="https://www.telefonseelsorge.de/spenden/" target="_blank">TelefonSeelsorge</a></li>
                    <li><a href="https://www.berliner-stadtmission.de/wie-sie-helfen-koennen/unterstuetzen/jetzt-online-spenden" target="_blank">Berliner Stadtmission</a></li>
                    <li><a href="https://spenden.weisser-ring.de/" target="_blank">WEISSER RING e. V.</a></li>
                    <li><a href="https://sea-watch.org/spenden/" target="_blank">Sea-Watch e.V.</a></li>
                </ul>

            </div>
        </div>

        <?php
    }

}

add219content::init();
