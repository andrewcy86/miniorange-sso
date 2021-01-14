<?php
// Add the UI of SSO Button in the plugin settings
function mo_saml_add_sso_button_settings(){

    $button_theme = get_option('mo_saml_button_theme') ? get_option('mo_saml_button_theme') : 'longbutton';
    $button_size = get_option('mo_saml_button_size') ? get_option('mo_saml_button_size') : '50';
    $button_width = get_option('mo_saml_button_width') ? get_option('mo_saml_button_width') : '100';
    $button_height = get_option('mo_saml_button_height') ? get_option('mo_saml_button_height') : '50';
    $button_curve = get_option('mo_saml_button_curve') ? get_option('mo_saml_button_curve') : '5';
    $button_text = get_option('mo_saml_button_text') ? get_option('mo_saml_button_text') : (get_option('saml_identity_name') ? get_option('saml_identity_name') : 'Login');
    $button_color = get_option('mo_saml_button_color') ? get_option('mo_saml_button_color') : '0085ba';
    $font_size = get_option('mo_saml_font_size') ? get_option('mo_saml_font_size') : '20';
    $font_color = get_option('mo_saml_font_color') ? get_option('mo_saml_font_color') : 'ffffff';
    $sso_button_position = get_option('sso_button_login_form_position') ? get_option('sso_button_login_form_position') : 'above';
    echo '
    <form id="mo_saml_redirect_to_wp_login_form" method="post" action="">
        <span>Enable this option to redirect your users to WordPress Login Page if they are not already logged in.</span>
        <br/>';
        if(get_option('mo_saml_registered_only_access') == 'true' && get_option('mo_saml_redirect_to_wp_login') == 'true')
        echo '<span class="mo_saml_help_desc"><b>NOTE: </b> Redirect to IDP option is also enabled and it will take priority over this option.</span><br/>';
        wp_nonce_field('mo_saml_redirect_to_wp_login_option');
        echo '<br/>
        <input type="hidden" name="option" value="mo_saml_redirect_to_wp_login_option"/>

        <label class="switch">
        <input type="checkbox" name="mo_saml_redirect_to_wp_login" value="true"';
        
        if(!mo_saml_is_sp_configured()) { echo 'disabled title="Disabled. Configure your Service Provider"'; } checked(get_option('mo_saml_redirect_to_wp_login') == "true");
    echo ' onchange="document.getElementById(\'mo_saml_redirect_to_wp_login_form\').submit();"/>
    <span class="slider round"></span>
    </label>

    <span style="padding-left:5px"><b>Redirect to WP Login page</b></span>
    </form><br/>
    <form id="mo_saml_add_sso_button_wp_form" method="post" action="">';
    wp_nonce_field('mo_saml_add_sso_button_wp_option');
    echo '<input type="hidden" name="option" value="mo_saml_add_sso_button_wp_option"/>
    <p>
    <label class="switch">
    <input type="checkbox" name="mo_saml_add_sso_button_wp" value="true"';
    checked(get_option('mo_saml_add_sso_button_wp') == "true");
    echo ' onchange="document.getElementById(\'mo_saml_add_sso_button_wp_form\').submit();"/>
    <span class="slider round"></span>
		</label>
        <span style="padding-left:5px"><b>Add a Single Sign on button on the Wordpress login page</b></span>
    </p>
    </form>
    <form id="mo_saml_use_button_as_shortcode_form" method="post" action="">';
    wp_nonce_field('mo_saml_use_button_as_shortcode_option');
    echo '<input type="hidden" name="option" value="mo_saml_use_button_as_shortcode_option"/>
    <p>
    <label class="switch">
    <input type="checkbox" name="mo_saml_use_button_as_shortcode" value="true"';
    checked(get_option('mo_saml_use_button_as_shortcode') == "true");
    echo ' onchange="document.getElementById(\'mo_saml_use_button_as_shortcode_form\').submit();"/>
    <span class="slider round"></span>
		</label>
        <span style="padding-left:5px"><b>Use button as ShortCode</b></span>
    </p>
    </form>

    <form id="mo_saml_use_button_as_widget_form" method="post" action="">';
    wp_nonce_field('mo_saml_use_button_as_widget_option');
    echo '<input type="hidden" name="option" value="mo_saml_use_button_as_widget_option"/>
    <p>
    <label class="switch">
    <input type="checkbox" name="mo_saml_use_button_as_widget" value="true"';
    checked(get_option('mo_saml_use_button_as_widget') == "true");
    echo ' onchange="document.getElementById(\'mo_saml_use_button_as_widget_form\').submit();"/>
    <span class="slider round"></span>
		</label>
        <span style="padding-left:5px"><b>Use button as Widget</b></span>
    </p>
    </form>
    <br/>
    <h3>Customize Login Button:</h3>
    <form name="mo_saml_custom_button_form" method="post" action="">';
    wp_nonce_field('mo_saml_custom_button_option');
    echo '<input type="hidden" name="option" value="mo_saml_custom_button_option"/>
    <table>
        <tr>
            <td style="width:200px;">
                <b>Shape</b>
            </td>
            <td style="width:200px;">
                <b>Theme</b>
            </td>
            <td style="width:200px;">
                <b>Size of Icons</b>
            </td>
        </tr>
        <tr>
            <td style="width:200px;">
                <input type="radio" name="mo_saml_button_theme" onclick="checkSSOButtonType();
                moPreviewButton(\'circle\',document.getElementById(\'mo_saml_button_size\').value,
                        document.getElementById(\'mo_saml_button_width\').value,
                        document.getElementById(\'mo_saml_button_height\').value,
                        document.getElementById(\'mo_saml_button_curve\').value,
                        document.getElementById(\'mo_saml_button_color\').value,
                        document.getElementById(\'mo_saml_font_color\').value,
                        document.getElementById(\'mo_saml_button_text\').value,
                        document.getElementById(\'mo_saml_font_size\').value);"
                        value="circle"';
                        checked($button_theme == 'circle');
                        echo '/> Round
            </td>
            <td style="width:250px;">
                <table>
                    <tr>
                        <td style="width:80px">Button Color:</td> 
                        <td>
                            <input id="mo_saml_button_color" type="text" style="width:135px;" name="mo_saml_button_color"  class="color" value="' . $button_color . '"
                            onchange="moLoginValidateButtonColor();updatePreviewButton()">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:200px;">
                    <table>
                        <tr id="commonIcon">
                            <td style="width:50px;">Size:</td>
                            <td><input style="width:50px" type="text" id="mo_saml_button_size" name="mo_saml_button_size" value="' . $button_size . '"
                            onchange="moLoginSizeValidate();updatePreviewButton();"/></td>
                            <td><input id="decrease-size" type="button" class="button button-primary" value="-" onclick="decreaseValue(jQuery(\'#mo_saml_button_size\'));moLoginSizeValidate();updatePreviewButton();"/></td>
                            <td><input id="increase-size" type="button" class="button button-primary" value="+" onclick="increaseValue(jQuery(\'#mo_saml_button_size\'));moLoginSizeValidate();updatePreviewButton();"/></td>
                        </tr>
                        <tr class="longButton">
                            <td style="width:50px;">Width:</td>
                            <td><input style="width:50px" type="text" id="mo_saml_button_width" name="mo_saml_button_width" value="' . $button_width . '"
                            onchange="moLoginWidthValidate();updatePreviewButton();"></td>
                            <td><input id="decrease-width" type="button" class="button button-primary" value="-" onclick="decreaseValue(jQuery(\'#mo_saml_button_width\'));moLoginWidthValidate();updatePreviewButton();"/></td>
                            <td><input id="increase-width" type="button" class="button button-primary" value="+" onclick="increaseValue(jQuery(\'#mo_saml_button_width\'));moLoginWidthValidate();updatePreviewButton();"/></td>
                        </tr>
                    </table>		
            </td>
        </tr>
        <tr>
            <td style="width:200px;">
                <input type="radio" name="mo_saml_button_theme" onclick="checkSSOButtonType();
                moPreviewButton(\'oval\',document.getElementById(\'mo_saml_button_size\').value,
                        document.getElementById(\'mo_saml_button_width\').value,
                        document.getElementById(\'mo_saml_button_height\').value,
                        document.getElementById(\'mo_saml_button_curve\').value,
                        document.getElementById(\'mo_saml_button_color\').value,
                        document.getElementById(\'mo_saml_font_color\').value,
                        document.getElementById(\'mo_saml_button_text\').value,
                        document.getElementById(\'mo_saml_font_size\').value);"
                        value="oval"';
                        checked($button_theme == 'oval');
                        echo '/> Rounded Edges
            </td>
            <td style="width:250px;">
                <table>
                    <tr>
                        <td style="width:80px">Button Text:</td> 
                        <td>
                            <input id="mo_saml_button_text" type="text" style="width:135px;" name="mo_saml_button_text" value="' . $button_text . '"
                            onchange="moLoginValidateButtonText();updatePreviewButton()" placeholder="##IDP##">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:200px;">
                <table>
                    <tr class="longButton">
                        <td style="width:50px;">Height:</td>
                        <td><input style="width:50px" type="text" id="mo_saml_button_height" name="mo_saml_button_height" value="'. $button_height . '"
                        onchange="moLoginHeightValidate();updatePreviewButton();"/></td>
                        <td><input id="decrease-height" type="button" class="button button-primary" value="-" onclick="decreaseValue(jQuery(\'#mo_saml_button_height\'));moLoginHeightValidate();updatePreviewButton();"/></td>
                        <td><input id="increase-height" type="button" class="button button-primary" value="+" onclick="increaseValue(jQuery(\'#mo_saml_button_height\'));moLoginHeightValidate();updatePreviewButton();"/></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="width:200px;">
                <input type="radio" name="mo_saml_button_theme" onclick="checkSSOButtonType();
                moPreviewButton(\'square\',document.getElementById(\'mo_saml_button_size\').value,
                        document.getElementById(\'mo_saml_button_width\').value,
                        document.getElementById(\'mo_saml_button_height\').value,
                        document.getElementById(\'mo_saml_button_curve\').value,
                        document.getElementById(\'mo_saml_button_color\').value,
                        document.getElementById(\'mo_saml_font_color\').value,
                        document.getElementById(\'mo_saml_button_text\').value,
                        document.getElementById(\'mo_saml_font_size\').value);"
                        value="square"';
                        checked($button_theme == 'square');
                        echo '/> Square
            </td>
            <td style="width:250px;">
                <table>
                    <tr>
                        <td style="width:80px">Font Color:</td> 
                        <td>
                            <input id="mo_saml_font_color" type="text" style="width:135px;" name="mo_saml_font_color" class="color" value="' . $font_color . '"
                            onchange="updatePreviewButton()">
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width:200px;">
            <table>
                <tr class="longButton">
                    <td style="width:50px;">Curve:</td>
                    <td><input style="width:50px" type="text" id="mo_saml_button_curve" name="mo_saml_button_curve" value="'. $button_curve .'"
                    onchange="moLoginCurveValidate();updatePreviewButton();"/></td>
                    <td><input id="decrease-curve" type="button" class="button button-primary" value="-" onclick="decreaseValue(jQuery(\'#mo_saml_button_curve\'));moLoginCurveValidate();updatePreviewButton();"/></td>
                    <td><input id="increase-curve" type="button" class="button button-primary" value="+" onclick="increaseValue(jQuery(\'#mo_saml_button_curve\'));moLoginCurveValidate();updatePreviewButton();"/></td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
        <td style="width:200px;">
            <input type="radio" id="longButtonWithText" name="mo_saml_button_theme" onclick="checkSSOButtonType();
            moPreviewButton(\'longbutton\',document.getElementById(\'mo_saml_button_size\').value,
                    document.getElementById(\'mo_saml_button_width\').value,
                    document.getElementById(\'mo_saml_button_height\').value,
                    document.getElementById(\'mo_saml_button_curve\').value,
                    document.getElementById(\'mo_saml_button_color\').value,
                    document.getElementById(\'mo_saml_font_color\').value,
                    document.getElementById(\'mo_saml_button_text\').value,
                    document.getElementById(\'mo_saml_font_size\').value);"
                    value="longbutton"';
                    checked($button_theme == 'longbutton');
                    echo '/> Long Button with Text
        </td>
        <td style="width:250px;">
            <table>
                <tr>
                    <td style="width:80px">Font Size:</td> 
                    <td>
                    <table>
                        <tr style="width:135px">
                            <td><input id="mo_saml_font_size" type="text" style="width:64px;" name="mo_saml_font_size" value="' . $font_size . '"
                                onchange="moLoginFontSizeValidate();updatePreviewButton();"></td>
                                <td><input id="decrease-font-size" type="button" class="button button-primary" value="-" onclick="decreaseValue(jQuery(\'#mo_saml_font_size\'));moLoginFontSizeValidate();updatePreviewButton();"/></td>
                                <td><input id="increase-font-size" type="button" class="button button-primary" value="+" onclick="increaseValue(jQuery(\'#mo_saml_font_size\'));moLoginFontSizeValidate();updatePreviewButton();"/></td>
                        </tr>
                    </table>
                    </td>
                </tr>
            </table>
        </td>
    </table>
    <br/>
    <div><b>Position of Login Button on WordPress Login Page : </b>
    <table style="padding-top:4px">
    <tr>
        <td style="width:200px;"><input type="radio" name="sso_button_login_form_position" value="above" ';
        checked($sso_button_position == 'above');
        echo '/> Above WP Login Form</td>
        <td style="width:200px;"><input type="radio" name="sso_button_login_form_position" value="below" ';
        checked($sso_button_position == 'below');
        echo '/> Below WP Login Form</td>
    </tr>
    </table>
    </div>
    <h3>Preview:</h3>
        <div style="padding-left:20px">
            <a><input type="button" class="sso_button"></a>
        </div>

    <br/>
    <br/>
    <div style="display:block;text-align:center; margin:2%;"><input type="submit" value="Update" class="button button-primary"/></div></form>
    </div>
    </form>';

    echo '<script>
                    var tempButtonSize = "'. $button_size .'";
                    var tempButtonWidth = "'. $button_width .'";
                    var tempButtonHeight = "'. $button_height .'";
                    var tempButtonCurve = "'. $button_curve .'";
                    var tempButtonTheme = "'. $button_theme .'";
                    var tempButtonColor = "'. $button_color .'";
                    var tempFontColor = "'. $font_color .'";
                    var tempButtonText = "'. $button_text .'";
                    var tempFontSize = "'. $font_size .'";
                    function checkSSOButtonType(){
                        if(!document.getElementById("longButtonWithText").checked){
                            jQuery(".longButton").hide();
                            jQuery("#commonIcon").show();
                        } else {
                            jQuery(".longButton").show();
                            jQuery("#commonIcon").hide();
                        }
                    }
                    checkSSOButtonType();
                    moLoginSizeValidate();
                    moLoginWidthValidate();
                    moLoginHeightValidate();
                    moLoginCurveValidate();
                    moLoginFontSizeValidate();
                    moLoginValidateButtonColor();
                    moPreviewButton(tempButtonTheme, tempButtonSize, tempButtonWidth, tempButtonHeight, tempButtonCurve, tempButtonColor, tempFontColor, tempButtonText, tempFontSize);
                    
                    moLoginValidateButtonText();
                    function moPreviewButton(type, s, w, h, curve, bg, color, text, fs){
                        
                        var a = "sso_button";
                        jQuery("."+a).css("backgroundColor","#"+bg);
                        jQuery("."+a).css("border-color","transparent");
                        jQuery("."+a).css("color","#"+color);
                        jQuery("."+a).val(text);
                        jQuery("."+a).css("font-size",fs+"px");
                        jQuery("."+a).css("padding:0px");
                        if(type == "longbutton"){
                            jQuery("."+a).css("width",w+"px");
                            jQuery("."+a).css("height",h+"px");
                            jQuery("."+a).css("border-radius",curve+"px");
                        } else {
                            if(type == "circle"){
                                jQuery("."+a).css({height:s,width:s});
                                jQuery("."+a).css("borderRadius","999px");
                                jQuery("."+a).css("padding-top","0px");
                                jQuery("."+a).css("padding-bottom","0px");
                            } else if(type == "oval"){
                                jQuery("."+a).css({height:s,width:s});
                                jQuery("."+a).css("borderRadius","5px");
                                jQuery("."+a).css("padding-top","0px");
                                jQuery("."+a).css("padding-bottom","0px");
                            } else if(type == "square"){
                                jQuery("."+a).css({height:s,width:s});
                                jQuery("."+a).css("borderRadius","0px");
                                jQuery("."+a).css("padding-top","0px");
                                jQuery("."+a).css("padding-bottom","0px");
                            }
                        }
                        
                    }

                    function moLoginSizeValidate(){
                        var e = document.getElementById(\'mo_saml_button_size\');
                        var val = e.value
                        if(!val.match(/^\d+$/) || val.trim() == ""){
                            e.value = 20;
                        }
                        var t=parseInt(e.value.trim());t>70?e.value=70:20>t&&(e.value=20)
                    }
                    function moLoginWidthValidate(){
                        var e = document.getElementById(\'mo_saml_button_width\');
                        var val = e.value;
                        if(!val.match(/^\d+$/) || val.trim() == ""){
                            e.value = 140;
                        }
                        var t=parseInt(e.value.trim());t>230?e.value=230:100>t&&(e.value=100)
                    }
                    function moLoginHeightValidate(){
                        var e = document.getElementById(\'mo_saml_button_height\');
                        var val = e.value;
                        if(!val.match(/^\d+$/) || val.trim() == ""){
                            e.value = 35;
                        }
                        var t=parseInt(e.value.trim());t>70?e.value=70:35>t&&(e.value=35)
                    }
                    function moLoginCurveValidate(){
                        var e = document.getElementById(\'mo_saml_button_curve\');
                        var val = e.value;
                        if(!val.match(/^\d+$/) || val.trim() == ""){
                            e.value = 5;
                        }
                    }
                    function moLoginFontSizeValidate(){
                        var e = document.getElementById(\'mo_saml_font_size\');
                        var val = e.value;
                        if(!val.match(/^\d+$/) || val.trim() == ""){
                            e.value = 20;
                        }
                        var t=parseInt(e.value.trim());t>50?e.value=50:10>t&&(e.value=10)
                        
                    }
                    function moLoginValidateButtonText(){
                        var e = document.getElementById(\'mo_saml_button_text\');
                        var val = e.value;
                        if(val.trim() == ""){
                            val = "##IDP##";
                        }
                        val = val.replace("##IDP##","'.get_option('saml_identity_name').'");
                        e.value = val;
                    }
                    function moLoginValidateButtonColor(){
                        var e = document.getElementById(\'mo_saml_button_color\');
                        var val = e.value;
                        if(val.trim() == ""){
                            val = "#0085ba";
                        }
                        e.value = val;
                    }

                    function updatePreviewButton(){
                        moPreviewButton(getButtonTheme(),
                            document.getElementById(\'mo_saml_button_size\').value,
                            document.getElementById(\'mo_saml_button_width\').value,
                            document.getElementById(\'mo_saml_button_height\').value,
                            document.getElementById(\'mo_saml_button_curve\').value,
                            document.getElementById(\'mo_saml_button_color\').value,
                            document.getElementById(\'mo_saml_font_color\').value,
                            document.getElementById(\'mo_saml_button_text\').value,
                            document.getElementById(\'mo_saml_font_size\').value);
                    }
                    function increaseValue(element){
                        element.val(element.val()- (-1));
                    }
                    function decreaseValue(element){
                        element.val(element.val()-1);
                    }
                    
                    function getButtonTheme(){return jQuery("input[name=mo_saml_button_theme]:checked").val();}
                    function getSizeOfIcons(){

                        if((jQuery("input[name=mo_saml_button_theme]:checked", "#mo_saml_add_sso_button_wp_form").val()) == "longbutton"){
                            return document.getElementById("mo_saml_button_width").value;
                        }else{
                            return document.getElementById("mo_saml_button_size").value;
                        }
                    }
                    
			</script>';
}