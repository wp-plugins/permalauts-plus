<?php
/*
   Plugin Name: Permalauts Plus
   Plugin URI: http://clearboth.de/
   Description: Ermöglicht das automatisierte Umschreiben von deutschen Umlauten und Sonderzeichen in URLs für Artikel, Seiten, Costum Post Types, Kategorien, Schlagwörter und Dateien in einen lesbaren Permalink. Basiert auf: <a a href="http://wordpress.org/extend/plugins/wp-permalauts/">http://wordpress.org/extend/plugins/wp-permalauts/</a> von <a a href="http://blogcraft.de/">Christoph Grabo</a>
   Version: 1.0
   Author: Frank Kugler
   Author URI: http://clearboth.de/
   License: GPL3
*/

$PP_VERSION = "1.0";

/**
 * Hilfsfunktionen
 */
function pp_u8e($c)
{
    return utf8_encode($c);
}
function pp_u8d($c)
{
    return utf8_decode($c);
}

/**
 * Zu ersetzende Zeichen
 */

$pp_chartable = array(
	'raw' => array('Á', 'Â', 'Ä', 'À', 'Å', 'Ã', 'Æ', 'Ç', 'É', 'Ê', 'Ë', 'È', 'Ð', 'Í', 'Î', 'Ï', 'Ì', 'Ñ', 'Ó', 'Ô', 'Ö', 'Ò', 'Ø', 'Õ', 'Š', 'Þ', 'Ú', 'Û', 'Ü', 'Ù', 'Ý', 'Ÿ', 'Œ', 'Ž', 'á', 'â', 'ä', 'à', 'å', 'ã', 'æ', 'ç', 'é', 'ê', 'ë', 'è', 'ð', 'ƒ', 'í', 'î', 'ï', 'ì', 'ñ', 'ó', 'ô', 'ö', 'ò', 'ø', 'õ', 'š', 'ß', 'þ', 'ú', 'û', 'ü', 'ù', 'ý', 'ÿ', 'ž', 'œ'),
	'in' => array(chr(193), chr(194), chr(196), chr(192), chr(197), chr(195), chr(198), chr(199), chr(201), chr(202), chr(203), chr(200), chr(208), chr(205), chr(206), chr(207), chr(204), chr(209), chr(211), chr(212), chr(214), chr(210), chr(216), chr(213), chr(138), chr(222), chr(218), chr(219), chr(220), chr(217), chr(221), chr(159), chr(140), chr(142), chr(225), chr(226), chr(228), chr(224), chr(229), chr(227), chr(230), chr(231), chr(233), chr(234), chr(235), chr(232), chr(240), chr(131), chr(237), chr(238), chr(239), chr(236), chr(241), chr(243), chr(244), chr(246), chr(242), chr(248), chr(245), chr(154), chr(223), chr(254), chr(250), chr(251), chr(252), chr(249), chr(253), chr(255), chr(158), chr(156)),
	'perma' => array('A',  'A', 'Ae', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'D', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'Oe', 'O', 'O', 'O', 'S', 'Y', 'U', 'U', 'Ue', 'U', 'Y', 'Y', 'OE', 'Z', 'a', 'a', 'ae', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'd', 'f', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'oe', 'o', 'o', 'o', 's', 'ss', 'y', 'u', 'u', 'ue', 'u', 'y', 'y', 'z', 'oe'),
	'post' => array('&Aacute;', '&Acirc;', '&Auml;', '&Agrave;', '&Aring;', '&Atilde;', '&AElig;', '&Ccedil;', '&Eacute;', '&Ecirc;', '&Euml;', '&Egrave;', '&ETH;', '&Iacute;', '&Icirc;', '&Iuml;', '&Igrave;', '&Ntilde;', '&Oacute;', '&Ocirc;', '&Ouml;', '&Ograve;', '&Oslash;', '&Otilde;', '&Scaron;', '&THORN;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Ugrave;', '&Yacute;', '&yuml;', '&OElig;', 'Z', '&aacute;', '&acirc;', '&auml;', '&agrave;', '&aring;', '&atilde;', '&aelig;', '&ccedil;', '&eacute;', '&ecirc;', '&euml;', '&egrave;', '&eth;', '&fnof;', '&iacute;', '&icirc;', '&iuml;', '&igrave;', '&ntilde;', '&oacute;', '&ocirc;', '&ouml;', '&ograve;', '&oslash;', '&otilde;', '&scaron;', '&szlig;', '&thorn;', '&uacute;', '&ucirc;', '&uuml;', '&ugrave;', '&yacute;', '&yuml;', 'z', '&oelig;'),
	'feed' => array('&#193;', '&#194;', '&#196;', '&#192;', '&#197;', '&#195;', '&#198;', '&#199;', '&#201;', '&#202;', '&#203;', '&#200;', '&#208;', '&#205;', '&#206;', '&#207;', '&#204;', '&#209;', '&#211;', '&#212;', '&#214;', '&#210;', '&#216;', '&#213;', '&#138;', '&#222;', '&#218;', '&#219;', '&#220;', '&#217;', '&#221;', '&#159;', '&#140;', '&#142;', '&#225;', '&#226;', '&#228;', '&#224;', '&#229;', '&#227;', '&#230;', '&#231;', '&#233;', '&#234;', '&#235;', '&#232;', '&#240;', '&#131;', '&#237;', '&#238;', '&#239;', '&#236;', '&#241;', '&#243;', '&#244;', '&#246;', '&#242;', '&#248;', '&#245;', '&#154;', '&#223;', '&#254;', '&#250;', '&#251;', '&#252;', '&#249;', '&#253;', '&#255;', '&#158;', '&#156;'),
	'utf8' => array(pp_u8e('Á'), pp_u8e('Â'), pp_u8e('Ä'), pp_u8e('À'), pp_u8e('Å'), pp_u8e('Ã'), pp_u8e('Æ'), pp_u8e('Ç'), pp_u8e('É'), pp_u8e('Ê'), pp_u8e('Ë'), pp_u8e('È'), pp_u8e('Ð'), pp_u8e('Í'), pp_u8e('Î'), pp_u8e('Ï'), pp_u8e('Ì'), pp_u8e('Ñ'), pp_u8e('Ó'), pp_u8e('Ô'), pp_u8e('Ö'), pp_u8e('Ò'), pp_u8e('Ø'), pp_u8e('Õ'), pp_u8e('Š'), pp_u8e('Þ'), pp_u8e('Ú'), pp_u8e('Û'), pp_u8e('Ü'), pp_u8e('Ù'), pp_u8e('Ý'), pp_u8e('Ÿ'), pp_u8e('Œ'), pp_u8e('Ž'), pp_u8e('á'), pp_u8e('â'), pp_u8e('ä'), pp_u8e('à'), pp_u8e('å'), pp_u8e('ã'), pp_u8e('æ'), pp_u8e('ç'), pp_u8e('é'), pp_u8e('ê'), pp_u8e('ë'), pp_u8e('è'), pp_u8e('ð'), pp_u8e('ƒ'), pp_u8e('í'), pp_u8e('î'), pp_u8e('ï'), pp_u8e('ì'), pp_u8e('ñ'), pp_u8e('ó'), pp_u8e('ô'), pp_u8e('ö'), pp_u8e('ò'), pp_u8e('ø'), pp_u8e('õ'), pp_u8e('š'), pp_u8e('ß'), pp_u8e('þ'), pp_u8e('ú'), pp_u8e('û'), pp_u8e('ü'), pp_u8e('ù'), pp_u8e('ý'), pp_u8e('ÿ'), pp_u8e('ž'), pp_u8e('œ')),
	'html' => array('&Aacute;', '&Acirc;', '&Auml;', '&Agrave;', '&Aring;', '&Atilde;', '&AElig;', '&Ccedil;', '&Eacute;', '&Ecirc;', '&Euml;', '&Egrave;', '&ETH;', '&Iacute;', '&Icirc;', '&Iuml;', '&Igrave;', '&Ntilde;', '&Oacute;', '&Ocirc;', '&Ouml;', '&Ograve;', '&Oslash;', '&Otilde;', '&Scaron;', '&THORN;', '&Uacute;', '&Ucirc;', '&Uuml;', '&Ugrave;', '&Yacute;', '&yuml;', '&OElig;', 'Z', '&aacute;', '&acirc;', '&auml;', '&agrave;', '&aring;', '&atilde;', '&aelig;', '&ccedil;', '&eacute;', '&ecirc;', '&euml;', '&egrave;', '&eth;', '&fnof;', '&iacute;', '&icirc;', '&iuml;', '&igrave;', '&ntilde;', '&oacute;', '&ocirc;', '&ouml;', '&ograve;', '&oslash;', '&otilde;', '&scaron;', '&szlig;', '&thorn;', '&uacute;', '&ucirc;', '&uuml;', '&ugrave;', '&yacute;', '&yuml;', 'z', '&oelig;')
);	
	
/**
 * Stopwords
 */
$pp_stopwords = array ("aber", "als", "am", "an", "auch", "auf", "aus", "bei", "bin", "bis", "bist", "da", "dadurch", "daher", "darum", "das", "daß", "dass", "dein", "deine", "dem", "den", "der", "des", "dessen", "deshalb", "die", "dies", "dieser", "dieses", "doch", "dort", "du", "durch", "ein", "eine", "einem", "einen", "einer", "eines", "er", "es", "euer", "eure", "für", "hatte", "hatten", "hattest", "hattet", "hier", "hinter", "ich", "ihr", "ihre", "im", "in", "ist", "ja", "jede", "jedem", "jeden", "jeder", "jedes", "jener", "jenes", "jetzt", "kann", "kannst", "können", "könnt", "machen", "mein", "meine", "mit", "muß", "mußt", "musst", "müssen", "müßt", "nach", "nachdem", "nein", "nicht", "nun", "oder", "seid", "sein", "seine", "sich", "sie", "sind", "soll", "sollen", "sollst", "sollt", "sonst", "soweit", "sowie", "und", "unser", "unsere", "unter", "vom", "von", "vor", "wann", "warum", "was", "weiter", "weitere", "wenn", "wer", "werde", "werden", "werdet", "weshalb", "wie", "wieder", "wieso", "wir", "wird", "wirst", "wo", "woher", "wohin", "zu", "zum", "zur", "über");


/**
 * Permalink generieren
 */
function pp_permalink($slug)
{
    global $pp_chartable;
    global $pp_stopwords;

    if (seems_utf8($slug)) {
        $invalid_latin_chars = array(
            chr(197) . chr(146) => 'OE',
            chr(197) . chr(147) => 'oe',
            chr(197) . chr(160) => 'S',
            chr(197) . chr(189) => 'Z',
            chr(197) . chr(161) => 's',
            chr(197) . chr(190) => 'z',
            chr(226) . chr(130) . chr(172) => 'E');
        $slug = pp_u8d(strtr($slug, $invalid_latin_chars));
    }

    $slug = str_replace($pp_chartable['raw'], $pp_chartable['perma'], $slug);
    $slug = str_replace($pp_chartable['utf8'], $pp_chartable['perma'], $slug);
    $slug = str_replace($pp_chartable['in'], $pp_chartable['perma'], $slug);
	$slug = str_replace($pp_chartable['html'], $pp_chartable['perma'], $slug);

    $current_pp_options = get_option('pp_options');

    if ($current_pp_options['stopwords'] == 1) { 
		// Stopwords entfernen
        $pp_stopwords_array = array_diff (split(" ", $slug), $pp_stopwords); 
        $slug = join("-", $pp_stopwords_array);
    } 

    return $slug;
}
/**
 * pp_permalink_with_dashes
 */
function pp_permalink_with_dashes($slug)
{
    $slug = pp_permalink($slug);
    $slug = sanitize_title_with_dashes($slug);
    return $slug;
}
/**
 * pp_permalink_with_dashes_media
 */
function pp_permalink_with_dashes_media($slug)
{
    $slug = pp_permalink($slug);
    return $slug;
}
/**
 * pp_restore_raw_title
 */
function pp_restore_raw_title($title, $raw_title = "", $context = "")
{
    if ($context == 'save')
        return $raw_title;
    else
        return $title;
}
/**
 * Creation Page
 */
 function pp_creation_page()
{
    global $PP_VERSION;
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
	}
?>

<div class="wrap">
<?php
if ( isset($_POST["regenerate"]) and ($_POST["regenerate"]==1 or $_POST["regenerate"]==2) ) {
	echo '<div id="message" class="updated">';
	
	global $wpdb;
	
	$posttypes[] = array("label" => "Beitr&auml;ge", "type" => "post");
	$posttypes[] = array("label" => "Seiten", "type" => "page");
	
	$args = array(
		'public'   => true,
		'_builtin' => false
	); 
	$output = 'objects'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	$post_types = get_post_types($args, $output, $operator); 
	foreach ($post_types as $post_type ) {
		$posttypes[] = array("label" => $post_type->label, "type" => $post_type->name);
	}
	
	// Preview
	if ( $_POST["regenerate"]==1 ) {
		foreach ($posttypes as $posttype ) {	
			$posts = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title, post_name FROM $wpdb->posts WHERE (post_type='%s') AND post_status='publish' LIMIT 10", $posttype["type"]), OBJECT);
			echo "<p><strong>" . $posttype["label"] . " (max. 10 St&uuml;ck)</strong><br /><br />";
			foreach ( $posts as $post) {
				echo "Titel: ".$post->post_title;
				echo "<br />";
				echo "Permalink AKTUELL: " . $post->post_name;
				echo "<br />";
				$step1 = pp_restore_raw_title($post->post_title);
				$step2 = pp_permalink_with_dashes($step1);
				echo "Permalink NEU: " . $step2;
				echo "<br /><br />";
			}	
		}
	}
	
	//Regenerate
	if ( $_POST["regenerate"]==2 ) {
		foreach ($posttypes as $posttype ) {	
			$posts = $wpdb->get_results($wpdb->prepare("SELECT ID, post_title, post_name FROM $wpdb->posts WHERE (post_type='%s') AND post_status='publish'", $posttype["type"]), OBJECT);
			foreach ( $posts as $post) {
				$step1 = pp_restore_raw_title($post->post_title);
				$step2 = pp_permalink_with_dashes($step1);
				$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET post_name=%s WHERE ID=%d", $step2, $post->ID));
			}	
		}
		echo "<p><strong>Regenerate erfolgreich durchgef&uuml;hrt</strong></p>";	
	}
	echo '</div>';
}
?>
<div id="icon-options-general" class="icon32"></div>
<h2>Permalauts Plus Regenerator</h2>
<div class="metabox-holder has-right-sidebar">
  <div class="inner-sidebar">
    <div class="postbox">
      <h3><span>Wichtig zu wissen</span></h3>
      <div class="inside">
        <p>Das automatisierte Umstellen der alten Permalinks kann nicht r&uuml;ckg&auml;ngig gemacht werden. URL-Struktur der Website wird ge&auml;ndert.</p>
      </div>
    </div>
    <div class="postbox">
      <h3><span>Blick in die Zukunft</span></h3>
      <div class="inside">
        <p>Pflege der Stopwords.</p>
      </div>
    </div>
    <div class="postbox">
      <h3><span>Kontakt</span></h3>
      <div class="inside">
        <p>E-Mail: <a href="mailto:frank.kugler@clearboth.de">frank.kugler@clearboth.de</a></p>
      </div>
    </div>
  </div>
  <!-- .inner-sidebar -->
  
  <div id="post-body">
    <div id="post-body-content">
      <div class="postbox">
        <h3><span>Regenerate</span></h3>
        <div class="inside">
          <form method="post" action="">
            <p>Alle Beitr&auml;ge, Seiten und Costum Post Types entsprechend den Einstellungen (mit oder ohne Stopwords) <strong>unwiderruflich(!)</strong> neu generieren.</p>
			<p class="select">
			    <select name="regenerate">
				  <option value="0" selected="selected">NEIN, nicht durchf&uuml;hren</option>
				  <option value="1">nur Vorschau</option>
				  <option value="2">JA, bitte durchf&uuml;hren</option>
				</select>
			</p>
            <p class="submit">
				<input type="submit" class="button-primary" value="Neue Permalinks erstellen" />
            </p>
          </form>
        </div>
        <!-- .inside --> 
      </div>
      <!-- #post-body-content --> 
    </div>
    <!-- #post-body --> 
  </div>
  <!-- .metabox-holder --> 
</div>
<!-- .wrap -->

<?php }
/**
 * Options Page
 */
function pp_options_page()
{
    global $PP_VERSION;
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
	}
?>

<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h2>Permalauts Plus Einstellungen</h2>
<?php settings_errors(); ?>
<div class="metabox-holder has-right-sidebar">
  <div class="inner-sidebar">
    <div class="postbox">
      <h3><span>Wichtig zu wissen</span></h3>
      <div class="inside">
        <p>Das automatisierte Umstellen der alten Permalinks kann nicht r&uuml;ckg&auml;ngig gemacht werden. URL-Struktur der Website wird ge&auml;ndert.</p>
      </div>
    </div>
    <div class="postbox">
      <h3><span>Blick in die Zukunft</span></h3>
      <div class="inside">
        <p>Pflege der Stopwords.</p>
      </div>
    </div>
    <div class="postbox">
      <h3><span>Kontakt</span></h3>
      <div class="inside">
        <p>E-Mail: <a href="mailto:frank.kugler@clearboth.de">frank.kugler@clearboth.de</a></p>
      </div>
    </div>
  </div>
  <!-- .inner-sidebar -->
  
  <div id="post-body">
    <div id="post-body-content">
      <div class="postbox">
        <h3><span>Einstellungen</span></h3>
        <div class="inside">
          <form method="post" action="options.php">
            <?php settings_fields('pp_setting_options'); ?>
            <?php $options = pp_options_validate(pp_options_defaults(get_option('pp_options'))); // pre validation and defaults ?>
            <table class="form-table">
              <tr valign="top">
                <th scope="row">Wo sollen Permalinks angepasst werden?</th>
                <td><label>
                    <input name="pp_options[clean_pp]" type="checkbox" value="1" <?php checked('1', $options['clean_pp']); ?> />
                    Beitr&auml;ge, Seiten und Costum Post Types</label>
                  <br />
                  <label>
                    <input name="pp_options[clean_ct]" type="radio"  value="2" <?php checked('2', $options['clean_ct']); ?>>
                    Alle Taxonomien (inklusive Kategorien)</label>
                  <br />
                  <label>
                    <input name="pp_options[clean_ct]" type="radio"  value="1" <?php checked('1', $options['clean_ct']); ?>>
                    Nur Kategorien</label>
                  <br />
                  <label>
                    <input name="pp_options[clean_ct]" type="radio"  value="0" <?php checked('0', $options['clean_ct']); ?>>
                    Keine Kategorien/Taxonomien</label>
                  <br />
                  <label>
                    <input name="pp_options[clean_m]" type="checkbox" value="1" <?php checked('1', $options['clean_m']); ?> />
                    Medien</label></td>
              </tr>
              
                <th scope="row">Verwendung von Stopwords?</th>
                <td><input name="pp_options[stopwords]" type="checkbox" value="1" <?php checked('1', $options['stopwords']); ?> />
                  <label for="pp_opt_stopwords">Setze einen Haken, um die Stopwords zu aktivieren.<br />
                    <small>Wörter werden aus den Permalinks entfernt</small> </label>
                  <br />
                  <textarea id="" name="" cols="80" rows="10" disabled="disabled">aber, als, am, an, auch, auf, aus, bei, bin, bis, bist, da, dadurch, daher, darum, das, daß, dass, dein, deine, dem, den, der, des, dessen, deshalb, die, dies, dieser, dieses, doch, dort, du, durch, ein, eine, einem, einen, einer, eines, er, es, euer, eure, für, hatte, hatten, hattest, hattet, hier, hinter, ich, ihr, ihre, im, in, ist, ja, jede, jedem, jeden, jeder, jedes, jener, jenes, jetzt, kann, kannst, können, könnt, machen, mein, meine, mit, muß, mußt, musst, müssen, müßt, nach, nachdem, nein, nicht, nun, oder, seid, sein, seine, sich, sie, sind, soll, sollen, sollst, sollt, sonst, soweit, sowie, und, unser, unsere, unter, vom, von, vor, wann, warum, was, weiter, weitere, wenn, wer, werde, werden, werdet, weshalb, wie, wieder, wieso, wir, wird, wirst, wo, woher, wohin, zu, zum, zur, über</textarea></td>
              </tr>
            </table>
            <p class="submit">
              <input type="submit" class="button-primary" value="Änderungen übernehmen" />
            </p>
          </form>
        </div>
        <!-- .inside --> 
      </div>
      <!-- #post-body-content --> 
    </div>
    <!-- #post-body --> 
  </div>
  <!-- .metabox-holder --> 
</div>
<!-- .wrap -->
<?php
}
/**
 * pp_options_menu
 */
function pp_options_menu()
{
	add_menu_page( 'Permalauts Plus Einstellungen', 'Permalauts Plus', 'manage_options', 'ppsetting', 'pp_options_page');
	add_submenu_page('ppsetting', 'Permalauts Plus Einstellungen', 'Einstellungen', 'manage_options', 'ppsetting', 'pp_options_page');
    add_submenu_page('ppsetting', 'Permalauts Plus Regenerator', 'Regenerator', 'manage_options', 'ppregenerator', 'pp_creation_page');
}
add_action('admin_menu', 'pp_options_menu');

/**
 * pp_options_defaults
 */
function pp_options_defaults($input)
{
    $defaults = array('clean_pp' => 1, 'clean_ct' => 2, 'clean_m' => 1,'stopwords' => - 1); // pre defaults for unset values
    $output = array('clean_pp' => 0, 'clean_ct' => 0, 'clean_m' => 0, 'stopwords' => 0); // init with zeros
    $output['clean_pp'] = ($input['clean_pp'] == 0 ? $defaults['clean_pp'] : $input['clean_pp']);
    $output['clean_ct'] = ($input['clean_ct'] == 0 ? $defaults['clean_ct'] : $input['clean_ct']);
	$output['clean_m'] = ($input['clean_m'] == 0 ? $defaults['clean_m'] : $input['clean_m']);
    $output['stopwords'] = ($input['stopwords'] == 0 ? $defaults['stopwords'] : $input['stopwords']);

    return $output;
}

/**
 * pp_options_validate
 */
function pp_options_validate($input)
{
	if ( !isset($input['clean_pp'] )) { $input['clean_pp'] = 0; }
	if ( !isset($input['clean_m'] )) { $input['clean_m'] = 0; }
	if ( !isset($input['stopwords'] )) { $input['stopwords'] = 0; }
    $input['clean_pp'] = ($input['clean_pp'] == 1 ? 1 : - 1);
    $input['clean_ct'] = ($input['clean_ct'] == 1 ? 1 : ($input['clean_ct'] == 2 ? 2 : - 1)); // 2-cascade embedded-if (difficult to read?)
	$input['clean_m'] = ($input['clean_m'] == 1 ? 1 : - 1);
    $input['stopwords'] = ($input['stopwords'] == 1 ? 1 : - 1);
    return $input;
}

/**
 * pp_options_init
 */
function pp_options_init()
{
    register_setting('pp_setting_options', 'pp_options', 'pp_options_validate');
}
add_action('admin_init', 'pp_options_init');

/**
 * always validate data! (and get defaults for unset values)
 */
$current_pp_options = pp_options_validate(pp_options_defaults(get_option('pp_options'))); 

if ($current_pp_options['clean_pp'] == 1) {
    remove_filter('sanitize_title', 'sanitize_title_with_dashes');
    add_filter('sanitize_title', 'pp_restore_raw_title', 9, 3);
    add_filter('sanitize_title', 'pp_permalink_with_dashes', 10);
} ;
if ($current_pp_options['clean_ct'] == 1) {
    remove_filter('sanitize_category', 'sanitize_title_with_dashes');
    add_filter('sanitize_category', 'pp_restore_raw_title', 9, 3);
    add_filter('sanitize_category', 'pp_permalink_with_dashes', 10);
} ;
if ($current_pp_options['clean_ct'] == 2) {
    remove_filter('sanitize_term', 'sanitize_title_with_dashes');
    add_filter('sanitize_term', 'pp_restore_raw_title', 9, 3);
    add_filter('sanitize_term', 'pp_permalink_with_dashes', 10);
};
if ($current_pp_options['clean_m'] == 1) {
    add_filter('sanitize_file_name', 'pp_restore_raw_title', 9, 3);
    add_filter('sanitize_file_name', 'pp_permalink_with_dashes_media', 10);
};

// Add settings link on plugin page
function pp_plugin_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=ppsetting">Einstellungen</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'pp_plugin_settings_link' );
?>
