jQuery(document).ready(function() {
  if (jQuery('#iconator')) jQuery('#shrsb-networks').sortable({
    delay: 250,
    cursor: 'move',
    scroll: true,
    revert: true,
    opacity: 0.7,
    placeholder: 'dropzoneNetworks',
    forcePlaceholderSize: true,
    items: 'li'
  });
  if (jQuery('.shrsb-bookmarks')) {
    jQuery('#shrsb-sortables').sortable({
      handle: '.box-mid-head',
      delay: 250,
      cursor: 'move',
      scroll: true,
      revert: true,
      opacity: 0.7
    });

    jQuery('#buttonPreviewsTop,#buttonPreviewsBottom').sortable({
      delay: 250,
      cursor: 'move',
      scroll: true,
      revert: true,
      opacity: 0.7,
      placeholder: 'dropzone',
      forcePlaceholderSize: true,
      items: 'li'
    });

    //Select all icons upon clicking
    jQuery('#sel-all').click(function() {
      jQuery('#shrsb-networks').each(function() {
        jQuery('#shrsb-networks input').attr('checked', 'checked');
      });
    });

    //Deselect all icons upon clicking
    jQuery('#sel-none').click(function() {
      jQuery('#shrsb-networks').each(function() {
        jQuery('#shrsb-networks input').removeAttr('checked');
      });
    });

    //Select most popular icons upon clicking
    jQuery('#sel-pop').click(function() {
      jQuery('#shrsb-networks').each(function() {
        jQuery('#shrsb-networks input').removeAttr('checked');
      });
      jQuery('#shrsb-networks').each(function() {
        jQuery('#shr-facebook').attr('checked', 'checked');
        jQuery('#shr-twitter').attr('checked', 'checked');
        jQuery('#shr-linkedin').attr('checked', 'checked');
        jQuery('#shr-googleplus').attr('checked', 'checked');
        jQuery('#shr-googlebookmarks').attr('checked', 'checked');
        jQuery('#shr-stumbleupon').attr('checked', 'checked');
        jQuery('#shr-pinterest').attr('checked', 'checked');
        jQuery('#shr-fastmail').attr('checked', 'checked');
        jQuery('#shr-printfriendly').attr('checked', 'checked');
      });
    });

    //Swap enabled/disabled between donation options onclick
    jQuery('#preset-amounts').parent('label').click(function() {
      jQuery('#custom-amounts').attr('disabled', 'disabled').css({
        'cursor': 'none'
      });
      jQuery('#preset-amounts').removeAttr('disabled');
    });

    //Swap enabled/disabled between donation options onclick
    jQuery('#custom-amounts').parent('label').click(function() {
      jQuery('#preset-amounts').attr('disabled', 'disabled').css({
        'cursor': 'none'
      });
      jQuery('#custom-amounts').removeAttr('disabled');
    });

    // Handle tiny form submission upon selecting option to hide sponsor messages
    jQuery('#hide-sponsors').click(function() {
      jQuery('#no-sponsors').submit();
    });

    // Create a universal click function to close status messages...
    jQuery('.del-x').click(function() {
      jQuery(this).parent('div').parent('div').fadeOut();
    });

    // if checkbox isn't already checked, open warning message...
    jQuery("#custom-mods").click(function() {
      if (jQuery(this).is(":not(:checked)")) {
        jQuery("#custom-mods-notice").css("display", "none");
      } else {
        jQuery("#custom-mods-notice").fadeIn("fast");
        jQuery("#custom-mods-notice").css("display", "table");
      }
    });

    // close custom mods warning when they click the X
    jQuery(".custom-mods-notice-close").click(function() {
      jQuery("#custom-mods-notice").fadeOut('fast');
    });

    // Apply "smart options" to BG image
    jQuery('#bgimg-yes').click(function() {
      if (jQuery(this).is(':checked')) {
        jQuery('#bgimgs').fadeIn('slow');
      } else {
        jQuery('#bgimgs').css('display', 'none');
      }
    });

    // Apply "smart options" to Twitter
    jQuery('#shr-twitter').click(function() {
      if (jQuery(this).attr('checked')) {
        jQuery('#twitter-defaults').fadeIn('fast');
      } else {
        jQuery('#twitter-defaults').fadeOut();
      }
    });

    jQuery('#shorty').change(function() {
      jQuery('#shortyapimdiv-bitly').fadeOut('fast');
      jQuery('#shortyapimdiv-awesm').fadeOut('fast');
      jQuery('#shortyapimdiv-supr').fadeOut('fast');
      jQuery('#shortyapimdiv-jmp').fadeOut('fast');
      if (this.value == 'bitly') {
        jQuery('#shortyapimdiv-bitly').fadeIn('fast');
      } else if (this.value == 'awesm') {
        jQuery('#shortyapimdiv-awesm').fadeIn('fast');
      } else if (this.value == 'supr') {
        jQuery('#shortyapimdiv-supr').fadeIn('fast');
      } else if (this.value == 'jmp') {
        jQuery('#shortyapimdiv-jmp').fadeIn('fast');
      }
    });


    jQuery('#shortyapichk-supr').click(function() {
      if (this.checked) {
        jQuery('#shortyapidiv-supr').fadeIn('fast');
      } else {
        jQuery('#shortyapidiv-supr').fadeOut('fast');
      }
    });


    jQuery('#likeButtonSetTop-yes').click(function() {
      if (this.checked) {
        jQuery('.likeButtonsAvailableTop').fadeIn('fast');
      }
    });
    jQuery('#likeButtonSetTop-no').click(function() {
      if (this.checked) {
        jQuery('.likeButtonsAvailableTop').fadeOut('fast');
      }
    });

    jQuery('#likeButtonSetBottom-yes').click(function() {
      if (this.checked) {
        jQuery('.likeButtonsAvailableBottom').fadeIn('fast');
      }
    });
    jQuery('#likeButtonSetBottom-no').click(function() {
      if (this.checked) {
        jQuery('.likeButtonsAvailableBottom').fadeOut('fast');
      }
    });

    jQuery('#fbLikeButtonTop-yes').click(function() {
      if (this.checked) {
        jQuery('.likebuttonpreviewTop').fadeIn('fast');
      }
    });
    jQuery('#fbLikeButtonBottom-yes').click(function() {
      if (this.checked) {
        jQuery('.likebuttonpreviewBottom').fadeIn('fast');
      }
    });

    jQuery('#fbLikeButtonTop-no').click(function() {
      if (this.checked) {
        jQuery('.likebuttonpreviewTop').fadeOut('fast');
      }
    });
    jQuery('#fbLikeButtonBottom-no').click(function() {
      if (this.checked) {
        jQuery('.likebuttonpreviewBottom').fadeOut('fast');
      }
    });

    jQuery('#fbSendButtonBottom-yes').click(function() {
      if (this.checked) {
        jQuery('.sendbuttonpreviewBottom').fadeIn('fast');
      }
    });
    jQuery('#fbSendButtonTop-yes').click(function() {
      if (this.checked) {
        jQuery('.sendbuttonpreviewTop').fadeIn('fast');
      }
    });

    jQuery('#fbSendButtonTop-no').click(function() {
      if (this.checked) {
        jQuery('.sendbuttonpreviewTop').fadeOut('fast');
      }
    });
    jQuery('#fbSendButtonBottom-no').click(function() {
      if (this.checked) {
        jQuery('.sendbuttonpreviewBottom').fadeOut('fast');
      }
    });

    jQuery('#googlePlusOneButtonTop-yes').click(function() {
      if (this.checked) {
        jQuery('.plusonepreviewTop').fadeIn('fast');
      }
    });

    jQuery('#googlePlusOneButtonTop-no').click(function() {
      if (this.checked) {
        jQuery('.plusonepreviewTop').fadeOut('fast');
      }
    });
    jQuery('#googlePlusOneButtonBottom-yes').click(function() {
      if (this.checked) {
        jQuery('.plusonepreviewBottom').fadeIn('fast');
      }
    });

    jQuery('#googlePlusOneButtonBottom-no').click(function() {
      if (this.checked) {
        jQuery('.plusonepreviewBottom').fadeOut('fast');
      }
    });
    jQuery('#tweetButtonTop-yes').click(function() {
      if (this.checked) {
        jQuery('.tweetbuttonpreviewTop').fadeIn('fast');
      }
    });

    jQuery('#tweetButtonTop-no').click(function() {
      if (this.checked) {
        jQuery('.tweetbuttonpreviewTop').fadeOut('fast');
      }
    });
    jQuery('#tweetButtonBottom-yes').click(function() {
      if (this.checked) {
        jQuery('.tweetbuttonpreviewBottom').fadeIn('fast');
      }
    });

    jQuery('#tweetButtonBottom-no').click(function() {
      if (this.checked) {
        jQuery('.tweetbuttonpreviewBottom').fadeOut('fast');
      }
    });

    jQuery('#fbLikeButtonTop-yes,#googlePlusOneButtonTop-yes,#fbSendButtonTop-yes,#tweetButtonTop-yes').click(function() {
      if (this.checked) {
        jQuery('.likeButtonSetOptionsTop').fadeIn('fast');
      }
    });
    jQuery('#fbLikeButtonBottom-yes,#googlePlusOneButtonBottom-yes,#fbSendButtonBottom-yes,#tweetButtonBottom-yes').click(function() {
      if (this.checked) {
        jQuery('.likeButtonSetOptionsBottom').fadeIn('fast');
      }
    });

    jQuery('#fbLikeButtonTop-no,#googlePlusOneButtonTop-no,#fbSendButtonTop-no,#tweetButtonTop-no').click(function() {
      if (jQuery('#fbLikeButtonTop-no').get(0).checked && jQuery('#googlePlusOneButtonTop-no').get(0).checked && jQuery('#tweetButtonTop-no').get(0).checked && jQuery('#fbSendButtonTop-no').get(0).checked) {
        jQuery('.likeButtonSetOptionsTop').fadeOut('fast');
      }
    });
    jQuery('#fbLikeButtonBottom-no,#googlePlusOneButtonBottom-no,#fbSendButtonBottom-no,#tweetButtonBottom-no').click(function() {
      if (jQuery('#fbLikeButtonBottom-no').get(0).checked && jQuery('#googlePlusOneButtonBottom-no').get(0).checked && jQuery('#tweetButtonBottom-no').get(0).checked && jQuery('#fbSendButtonBottom-no').get(0).checked) {
        jQuery('.likeButtonSetOptionsBottom').fadeOut('fast');
      }
    });

    jQuery('#designer_toolTips-yes').click(function() {
      if (this.checked) {
        jQuery('.designer_toolTip_prefs').fadeIn('fast');
      }
    });

    jQuery('#designer_toolTips-no').click(function() {
      if (this.checked) {
        jQuery('.designer_toolTip_prefs').fadeOut('fast');
      }
    });

    jQuery('input[name="pubGaSocial"]').on('click', function() {
      jQuery('.pubGaSocial_prefs')[jQuery('input[name="pubGaSocial"]:checked').val() == 1 ? "fadeIn" : "fadeOut"]('fast');
    })

    jQuery('#recommendations-yes').click(function() {
      if (this.checked) {
        jQuery('.recommendations_prefs-1').fadeIn('fast');
        var thumbEnableChecked = jQuery('#recommendations-style-image').get(0).checked;
        if (thumbEnableChecked) {
          jQuery('.recommendations_prefs-2').fadeIn('fast');
        }
      }
    });

    jQuery('#recommendations-no').click(function() {
      if (this.checked) {
        jQuery('.recommendations_prefs-1').fadeOut('fast');
        jQuery('.recommendations_prefs-2').fadeOut('fast');
      }
    });

    jQuery('#recommendations-style-image').click(function() {
      if (this.checked) {
        jQuery('.recommendations_prefs-2').fadeIn('fast');
      }
    });

    jQuery('#recommendations-style-text').click(function() {
      if (this.checked) {
        jQuery('.recommendations_prefs-2').fadeOut('fast');
      }
    });

    jQuery('#cb-yes').click(function() {
      if (this.checked) {
        jQuery('.cb_prefs').fadeIn('fast');
      }
    });

    jQuery('#cb-no').click(function() {
      if (this.checked) {
        jQuery('.cb_prefs').fadeOut('fast');
      }
    });

    jQuery('#useSbSettings-yes').click(function() {
      if (this.checked) {
        jQuery('.topbar_prefs').fadeOut('fast');
      }
    });

    jQuery('#useSbSettings-no').click(function() {
      if (this.checked) {
        jQuery('.topbar_prefs').fadeIn('fast');
      }
    });

    jQuery('#position-above').click(function() {
      if (jQuery('#info-manual').is(':visible')) {
        jQuery('#info-manual').fadeOut();
      }
    });

    jQuery('#position-below').click(function() {
      if (jQuery('#info-manual').is(':visible')) {
        jQuery('#info-manual').fadeOut();
      }
    });

    jQuery('#position-manual').click(function() {
      if (jQuery('#info-manual').is(':not(:visible)')) {
        jQuery('#info-manual').fadeIn('slow');
      }
    });

    jQuery('.dtags-info').click(function() {
      jQuery('#tag-info').fadeIn('fast');
    });

    jQuery('.dtags-close').click(function() {
      jQuery('#tag-info').fadeOut();
    });

    jQuery('.shebang-info').click(function() {
      jQuery('#info-manual').fadeIn('fast');
    });

    jQuery('#shrsbresetallwarn-cancel').click(function() {
      jQuery('#shrsbresetallwarn').fadeOut();
    });

    jQuery('#shrsbresetallwarn-yes').click(function() {
      this.checked = jQuery('#shrsbresetallwarn').fadeOut();
      this.checked = jQuery('#resetalloptionsaccept').submit();
      this.checked = !this.checked;
    });


    // Load character count and tweet output demo onload
    var dfaultload = 0;
    var dfaulttitle = 8;
    var dfaulturl = 13;
    if (typeof(jQuery("#tweetconfig")) != "undefined" && jQuery("#tweetconfig").length > 0) {
      if (jQuery("#tweetconfig").val().indexOf('${title}') != -1) {
        dfaultload = Math.floor(dfaultload + dfaulttitle);
      }
      if (jQuery("#tweetconfig").val().indexOf('${short_link}') != -1) {
        dfaultload = Math.floor(dfaultload + dfaulturl);
      }
      var mathdoneload = Math.floor(jQuery('#tweetconfig').val().length - dfaultload);
      if (mathdoneload >= 50) {
        jQuery('#tweetcounter span').addClass('error');
      } else {
        jQuery('#tweetcounter span').removeClass();
      }
      jQuery('#tweetcounter span').html(mathdoneload);
      var endvalueload = jQuery('#tweetconfig').val();
      endvalueload = endvalueload.replace('${title}', 'Some fancy post title');
      endvalueload = endvalueload.replace('${short_link}', 'http://goo.gl/dbqlx');
      var endtweetload = endvalueload;
      jQuery('#tweetoutput span').html(endtweetload);



      jQuery('#tweetconfig').keyup(function() {
        var dfaults = 0;
        var title = 8;
        var url = 13;

        if (jQuery("#tweetconfig").val().indexOf('${title}') != -1) {
          dfaults = Math.floor(dfaults + title);
        }
        if (jQuery("#tweetconfig").val().indexOf('${short_link}') != -1) {
          dfaults = Math.floor(dfaults + url);
        }

        var mathdone = Math.floor(jQuery(this).val().length - dfaults);

        if (mathdone >= 50) {
          jQuery('#tweetcounter span').addClass('error');
          alert("You need to leave room for the short URL and/or post title...");
          return false;
        } else {
          jQuery('#tweetcounter span').removeClass();
        }
        jQuery('#tweetcounter span').html(mathdone);

        var endvalue = jQuery(this).val();

        endvalue = endvalue.replace('${title}', 'Some fancy post title');
        endvalue = endvalue.replace('${short_link}', 'http://goo.gl/dbqlx');

        var endtweet = endvalue;

        jQuery('#tweetoutput span').html(endtweet);

      });
    }
    // Check if like button is included and show the position prefs
    //var likeBtnChecked = jQuery('#fbLikeButton-yes').get(0).checked || jQuery('#googlePlusOneButton-yes').get(0).checked || jQuery('#fbSendButton-yes').get(0).checked;

    if (typeof(jQuery('#likeButtonSetTop-yes')) != "undefined" && jQuery('#likeButtonSetTop-yes').length > 0) {
      if (jQuery('#likeButtonSetTop-yes').get(0).checked) {
        jQuery('.likeButtonsAvailableTop').fadeIn('fast');
      }


      if (jQuery('#fbLikeButtonTop-yes').get(0).checked || jQuery('#googlePlusOneButtonTop-yes').get(0).checked || jQuery('#tweetButtonTop-yes').get(0).checked || jQuery('#fbSendButtonTop-yes').get(0).checked) {
        jQuery('.likeButtonSetOptionsTop').fadeIn('fast');
      }

      if (jQuery('#fbLikeButtonTop-yes').get(0).checked) {
        jQuery('.likebuttonpreviewTop').fadeIn('fast');
      }

      if (jQuery('#fbSendButtonTop-yes').get(0).checked) {
        jQuery('.sendbuttonpreviewTop').fadeIn('fast');
      }

      if (jQuery('#googlePlusOneButtonTop-yes').get(0).checked) {
        jQuery('.plusonepreviewTop').fadeIn('fast');
      }

      if (jQuery('#tweetButtonTop-yes').get(0).checked) {
        jQuery('.tweetbuttonpreviewTop').fadeIn('fast');
      }
    }

    if (typeof(jQuery('#likeButtonSetBottom-yes')) != "undefined" && jQuery('#likeButtonSetBottom-yes').length > 0) {
      if (jQuery('#likeButtonSetBottom-yes').get(0).checked) {
        jQuery('.likeButtonsAvailableBottom').fadeIn('fast');
      }

      if (jQuery('#fbLikeButtonBottom-yes').get(0).checked || jQuery('#googlePlusOneButtonBottom-yes').get(0).checked || jQuery('#tweetButtonBottom-yes').get(0).checked || jQuery('#fbSendButtonBottom-yes').get(0).checked) {
        jQuery('.likeButtonSetOptionsBottom').fadeIn('fast');
      }

      if (jQuery('#fbLikeButtonBottom-yes').get(0).checked) {
        jQuery('.likebuttonpreviewBottom').fadeIn('fast');
      }

      if (jQuery('#fbSendButtonBottom-yes').get(0).checked) {
        jQuery('.sendbuttonpreviewBottom').fadeIn('fast');
      }

      if (jQuery('#googlePlusOneButtonBottom-yes').get(0).checked) {
        jQuery('.plusonepreviewBottom').fadeIn('fast');
      }
      if (jQuery('#tweetButtonBottom-yes').get(0).checked) {
        jQuery('.tweetbuttonpreviewBottom').fadeIn('fast');
      }

    }

    // Check if designer tooltips are included and show the color prefs
    if (typeof(jQuery('#designer_toolTips-yes')) != "undefined" && jQuery('#designer_toolTips-yes').length > 0) {
      var designerToolTipsChecked = jQuery('#designer_toolTips-yes').get(0).checked;
      if (designerToolTipsChecked) {
        jQuery('.designer_toolTip_prefs').fadeIn('fast');
      }

      jQuery('#tip_bg_color_picker_holder').ColorPicker({
        flat: true,
        color: jQuery("#tip_bg_color").val(),
        onChange: function(hsb, hex, rgb, el) {
          jQuery("#tip_bg_color").val('#' + hex);
          jQuery('#tip_bg_color_picker div').css('backgroundColor', '#' + hex);
        },
        onSubmit: function(hsb, hex, rgb, el) {
          jQuery("#tip_bg_color").val('#' + hex);
          jQuery('#tip_bg_color_picker div').css('backgroundColor', '#' + hex);
          jQuery('#tip_bg_color_picker_holder').toggle();
        }
      });

      // The below lines are to prevent a nasty input form control not focussable error in chrome/safari
      jQuery('#tip_bg_color_picker_holder').find('input').each(function(index) {
        jQuery(this).attr("maxlength", "50");
      });

      jQuery('#tip_bg_color_picker div').bind('click', function() {
        jQuery('#tip_bg_color_picker_holder').toggle();
        jQuery('#tip_bg_color_picker_holder').ColorPickerSetColor(jQuery("#tip_bg_color").val());
        // Attach click handler to the body to hide the color picker (if visible) for clicks outside the color picker
        jQuery('body').trigger('click');
        if (jQuery('#tip_bg_color_picker_holder').is(':visible')) {
          jQuery('body').bind("click", function() {
            jQuery('#tip_bg_color_picker_holder').hide();
            jQuery('body').unbind("click");
          });
        }
        return false;
      });

      jQuery('#tip_bg_color_reset').bind('click', function() {
        jQuery("#tip_bg_color").val('#000000');
        jQuery('#tip_bg_color_picker div').css('backgroundColor', '#000000');
      });
      // Prevent the body click handler from firing if the click is inside the color picker
      jQuery('#tip_bg_color_picker_holder').click(function() {
        return false;
      });

      jQuery('#tip_text_color_picker_holder').ColorPicker({
        flat: true,
        color: jQuery("#tip_text_color").val(),
        onChange: function(hsb, hex, rgb, el) {
          jQuery("#tip_text_color").val('#' + hex);
          jQuery('#tip_text_color_picker div').css('backgroundColor', '#' + hex);
        },
        onSubmit: function(hsb, hex, rgb, el) {
          jQuery("#tip_text_color").val('#' + hex);
          jQuery('#tip_text_color_picker div').css('backgroundColor', '#' + hex);
          jQuery('#tip_text_color_picker_holder').toggle();
        }
      });
      // The below lines are to prevent a nasty input form control not focussable error in chrome/safari
      jQuery('#tip_text_color_picker_holder').find('input').each(function(index) {
        jQuery(this).attr("maxlength", "50");
      });

      jQuery('#tip_text_color_picker div').bind('click', function() {
        jQuery('#tip_text_color_picker_holder').toggle();
        jQuery('#tip_text_color_picker_holder').ColorPickerSetColor(jQuery("#tip_text_color").val());
        // Attach click handler to the body to hide the color picker (if visible) for clicks outside the color picker
        jQuery('body').trigger('click');
        if (jQuery('#tip_text_color_picker_holder').is(':visible')) {
          jQuery('body').bind("click", function() {
            jQuery('#tip_text_color_picker_holder').hide();
            jQuery('body').unbind("click");
          });
        }
        return false;
      });
      // Prevent the body click handler from firing if the click is inside the color picker
      jQuery('#tip_text_color_picker_holder').click(function() {
        return false;
      });

      jQuery('#tip_text_color_reset').bind('click', function() {
        jQuery("#tip_text_color").val('#ffffff');
        jQuery('#tip_text_color_picker div').css('backgroundColor', '#ffffff');
      });

    }

    // Check if social analytics is enabled or not, if enabled show the preferences
    if (typeof(jQuery('#pubGaSocial-yes')) != "undefined" && jQuery('#pubGaSocial-yes').length > 0) {
      var socialEnableChecked = jQuery('#pubGaSocial-yes').get(0).checked;
      if (socialEnableChecked) {
        jQuery('.pubGaSocial_prefs').fadeIn('fast');
      }
    }

    // Check if social analytics is enabled or not, if enabled show the preferences
    if (typeof(jQuery('#recommendations-yes')) != "undefined" && jQuery('#recommendations-yes').length > 0) {
      var socialEnableChecked = jQuery('#recommendations-yes').get(0).checked;
      if (socialEnableChecked) {
        jQuery('.recommendations_prefs-1').fadeIn('fast');
        var thumbEnableChecked = jQuery('#recommendations-style-image').get(0).checked;
        if (thumbEnableChecked) {
          jQuery('.recommendations_prefs-2').fadeIn('fast');
        }
      }
    }

    // Check if classic bookmarks is enabled or not, if enabled show the preferences
    if (typeof(jQuery('#cb-yes')) != "undefined" && jQuery('#cb-yes').length > 0) {
      var socialEnableChecked = jQuery('#cb-yes').get(0).checked;
      if (socialEnableChecked) {
        jQuery('.cb_prefs').fadeIn('fast');
      }
    }

    //For the Top Sharebar custom background color option
    if (typeof(jQuery('#useSbSettings-no')) != "undefined" && jQuery('#useSbSettings-no').length > 0) {
      var useSbSettingsChecked = jQuery('#useSbSettings-no').get(0).checked;
      if (useSbSettingsChecked) {
        jQuery('.topbar_prefs').fadeIn('fast');
      }

      jQuery('#tb_bg_color_picker_holder').ColorPicker({
        flat: true,
        color: jQuery("#tb_bg_color").val(),
        onChange: function(hsb, hex, rgb, el) {
          jQuery("#tb_bg_color").val('#' + hex);
          jQuery('#tb_bg_color_picker div').css('backgroundColor', '#' + hex);
        },
        onSubmit: function(hsb, hex, rgb, el) {
          jQuery("#tb_bg_color").val('#' + hex);
          jQuery('#tb_bg_color_picker div').css('backgroundColor', '#' + hex);
          jQuery('#tb_bg_color_picker_holder').toggle();
        }
      });

      // The below lines are to prevent a nasty input form control not focussable error in chrome/safari
      jQuery('#tb_bg_color_picker_holder').find('input').each(function(index) {
        jQuery(this).attr("maxlength", "50");
      });

      jQuery('#tb_bg_color_picker div').bind('click', function() {
        jQuery('#tb_bg_color_picker_holder').toggle();
        jQuery('#tb_bg_color_picker_holder').ColorPickerSetColor(jQuery("#tb_bg_color").val());
        // Attach click handler to the body to hide the color picker (if visible) for clicks outside the color picker
        jQuery('body').trigger('click');
        if (jQuery('#tb_bg_color_picker_holder').is(':visible')) {
          jQuery('body').bind("click", function() {
            jQuery('#tb_bg_color_picker_holder').hide();
            jQuery('body').unbind("click");
          });
        }
        return false;
      });

      jQuery('#tb_bg_color_reset').bind('click', function() {
        jQuery("#tb_bg_color").val('#000000');
        jQuery('#tb_bg_color_picker div').css('backgroundColor', '#000000');
      });
      // Prevent the body click handler from firing if the click is inside the color picker
      jQuery('#tb_bg_color_picker_holder').click(function() {
        return false;
      });

      //For the Show/Hide Button color on the toolbar
      jQuery('#tb_border_color_picker_holder').ColorPicker({
        flat: true,
        color: jQuery("#tb_border_color").val(),
        onChange: function(hsb, hex, rgb, el) {
          jQuery("#tb_border_color").val('#' + hex);
          jQuery('#tb_border_color_picker div').css('backgroundColor', '#' + hex);
        },
        onSubmit: function(hsb, hex, rgb, el) {
          jQuery("#tb_border_color").val('#' + hex);
          jQuery('#tb_border_color_picker div').css('backgroundColor', '#' + hex);
          jQuery('#tb_border_color_picker_holder').toggle();
        }
      });

      // The below lines are to prevent a nasty input form control not focussable error in chrome/safari
      jQuery('#tb_border_color_picker_holder').find('input').each(function(index) {
        jQuery(this).attr("maxlength", "50");
      });

      jQuery('#tb_border_color_picker div').bind('click', function() {
        jQuery('#tb_border_color_picker_holder').toggle();
        jQuery('#tb_border_color_picker_holder').ColorPickerSetColor(jQuery("#tb_border_color").val());
        // Attach click handler to the body to hide the color picker (if visible) for clicks outside the color picker
        jQuery('body').trigger('click');
        if (jQuery('#tb_border_color_picker_holder').is(':visible')) {
          jQuery('body').bind("click", function() {
            jQuery('#tb_border_color_picker_holder').hide();
            jQuery('body').unbind("click");
          });
        }
        return false;
      });

      jQuery('#tb_border_color_reset').bind('click', function() {
        jQuery("#tb_border_color").val('#000000');
        jQuery('#tb_border_color_picker div').css('backgroundColor', '#000000');
      });
      // Prevent the body click handler from firing if the click is inside the color picker
      jQuery('#tb_border_color_picker_holder').click(function() {
        return false;
      });

    }
  }
});


/**
 *
 * Color picker
 * Author: Stefan Petre www.eyecon.ro
 *
 * Dual licensed under the MIT and GPL licenses
 *
 */
(function($) {
  var ColorPicker = function() {
      var
      ids = {},
          inAction, charMin = 65,
          visible, tpl = '<div class="colorpicker"><div class="colorpicker_color"><div><div></div></div></div><div class="colorpicker_hue"><div></div></div><div class="colorpicker_new_color"></div><div class="colorpicker_current_color"></div><div class="colorpicker_hex"><input type="text" maxlength="6" size="6" /></div><div class="colorpicker_rgb_r colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_g colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_rgb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_h colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_s colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_hsb_b colorpicker_field"><input type="text" maxlength="3" size="3" /><span></span></div><div class="colorpicker_submit"></div></div>',
          defaults = {
          eventName: 'click',
          onShow: function() {},
          onBeforeShow: function() {},
          onHide: function() {},
          onChange: function() {},
          onSubmit: function() {},
          color: 'ff0000',
          livePreview: true,
          flat: false
          },
          fillRGBFields = function(hsb, cal) {
          var rgb = HSBToRGB(hsb);
          $(cal).data('colorpicker').fields.eq(1).val(rgb.r).end().eq(2).val(rgb.g).end().eq(3).val(rgb.b).end();
          },
          fillHSBFields = function(hsb, cal) {
          $(cal).data('colorpicker').fields.eq(4).val(hsb.h).end().eq(5).val(hsb.s).end().eq(6).val(hsb.b).end();
          },
          fillHexFields = function(hsb, cal) {
          $(cal).data('colorpicker').fields.eq(0).val(HSBToHex(hsb)).end();
          },
          setSelector = function(hsb, cal) {
          $(cal).data('colorpicker').selector.css('backgroundColor', '#' + HSBToHex({
            h: hsb.h,
            s: 100,
            b: 100
          }));
          $(cal).data('colorpicker').selectorIndic.css({
            left: parseInt(150 * hsb.s / 100, 10),
            top: parseInt(150 * (100 - hsb.b) / 100, 10)
          });
          },
          setHue = function(hsb, cal) {
          $(cal).data('colorpicker').hue.css('top', parseInt(150 - 150 * hsb.h / 360, 10));
          },
          setCurrentColor = function(hsb, cal) {
          $(cal).data('colorpicker').currentColor.css('backgroundColor', '#' + HSBToHex(hsb));
          },
          setNewColor = function(hsb, cal) {
          $(cal).data('colorpicker').newColor.css('backgroundColor', '#' + HSBToHex(hsb));
          },
          keyDown = function(ev) {
          var pressedKey = ev.charCode || ev.keyCode || -1;
          if ((pressedKey > charMin && pressedKey <= 90) || pressedKey == 32) {
            return false;
          }
          var cal = $(this).parent().parent();
          if (cal.data('colorpicker').livePreview === true) {
            change.apply(this);
          }
          },
          change = function(ev) {
          var cal = $(this).parent().parent(),
              col;
          if (this.parentNode.className.indexOf('_hex') > 0) {
            cal.data('colorpicker').color = col = HexToHSB(fixHex(this.value));
          } else if (this.parentNode.className.indexOf('_hsb') > 0) {
            cal.data('colorpicker').color = col = fixHSB({
              h: parseInt(cal.data('colorpicker').fields.eq(4).val(), 10),
              s: parseInt(cal.data('colorpicker').fields.eq(5).val(), 10),
              b: parseInt(cal.data('colorpicker').fields.eq(6).val(), 10)
            });
          } else {
            cal.data('colorpicker').color = col = RGBToHSB(fixRGB({
              r: parseInt(cal.data('colorpicker').fields.eq(1).val(), 10),
              g: parseInt(cal.data('colorpicker').fields.eq(2).val(), 10),
              b: parseInt(cal.data('colorpicker').fields.eq(3).val(), 10)
            }));
          }
          if (ev) {
            fillRGBFields(col, cal.get(0));
            fillHexFields(col, cal.get(0));
            fillHSBFields(col, cal.get(0));
          }
          setSelector(col, cal.get(0));
          setHue(col, cal.get(0));
          setNewColor(col, cal.get(0));
          cal.data('colorpicker').onChange.apply(cal, [col, HSBToHex(col), HSBToRGB(col)]);
          },
          blur = function(ev) {
          var cal = $(this).parent().parent();
          cal.data('colorpicker').fields.parent().removeClass('colorpicker_focus');
          },
          focus = function() {
          charMin = this.parentNode.className.indexOf('_hex') > 0 ? 70 : 65;
          $(this).parent().parent().data('colorpicker').fields.parent().removeClass('colorpicker_focus');
          $(this).parent().addClass('colorpicker_focus');
          },
          downIncrement = function(ev) {
          var field = $(this).parent().find('input').focus();
          var current = {
            el: $(this).parent().addClass('colorpicker_slider'),
            max: this.parentNode.className.indexOf('_hsb_h') > 0 ? 360 : (this.parentNode.className.indexOf('_hsb') > 0 ? 100 : 255),
            y: ev.pageY,
            field: field,
            val: parseInt(field.val(), 10),
            preview: $(this).parent().parent().data('colorpicker').livePreview
          };
          $(document).bind('mouseup', current, upIncrement);
          $(document).bind('mousemove', current, moveIncrement);
          },
          moveIncrement = function(ev) {
          ev.data.field.val(Math.max(0, Math.min(ev.data.max, parseInt(ev.data.val + ev.pageY - ev.data.y, 10))));
          if (ev.data.preview) {
            change.apply(ev.data.field.get(0), [true]);
          }
          return false;
          },
          upIncrement = function(ev) {
          change.apply(ev.data.field.get(0), [true]);
          ev.data.el.removeClass('colorpicker_slider').find('input').focus();
          $(document).unbind('mouseup', upIncrement);
          $(document).unbind('mousemove', moveIncrement);
          return false;
          },
          downHue = function(ev) {
          var current = {
            cal: $(this).parent(),
            y: $(this).offset().top
          };
          current.preview = current.cal.data('colorpicker').livePreview;
          $(document).bind('mouseup', current, upHue);
          $(document).bind('mousemove', current, moveHue);
          },
          moveHue = function(ev) {
          change.apply(ev.data.cal.data('colorpicker').fields.eq(4).val(parseInt(360 * (150 - Math.max(0, Math.min(150, (ev.pageY - ev.data.y)))) / 150, 10)).get(0), [ev.data.preview]);
          return false;
          },
          upHue = function(ev) {
          fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
          fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
          $(document).unbind('mouseup', upHue);
          $(document).unbind('mousemove', moveHue);
          return false;
          },
          downSelector = function(ev) {
          var current = {
            cal: $(this).parent(),
            pos: $(this).offset()
          };
          current.preview = current.cal.data('colorpicker').livePreview;
          $(document).bind('mouseup', current, upSelector);
          $(document).bind('mousemove', current, moveSelector);
          },
          moveSelector = function(ev) {
          change.apply(ev.data.cal.data('colorpicker').fields.eq(6).val(parseInt(100 * (150 - Math.max(0, Math.min(150, (ev.pageY - ev.data.pos.top)))) / 150, 10)).end().eq(5).val(parseInt(100 * (Math.max(0, Math.min(150, (ev.pageX - ev.data.pos.left)))) / 150, 10)).get(0), [ev.data.preview]);
          return false;
          },
          upSelector = function(ev) {
          fillRGBFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
          fillHexFields(ev.data.cal.data('colorpicker').color, ev.data.cal.get(0));
          $(document).unbind('mouseup', upSelector);
          $(document).unbind('mousemove', moveSelector);
          return false;
          },
          enterSubmit = function(ev) {
          $(this).addClass('colorpicker_focus');
          },
          leaveSubmit = function(ev) {
          $(this).removeClass('colorpicker_focus');
          },
          clickSubmit = function(ev) {
          var cal = $(this).parent();
          var col = cal.data('colorpicker').color;
          cal.data('colorpicker').origColor = col;
          setCurrentColor(col, cal.get(0));
          cal.data('colorpicker').onSubmit(col, HSBToHex(col), HSBToRGB(col), cal.data('colorpicker').el);
          },
          show = function(ev) {
          var cal = $('#' + $(this).data('colorpickerId'));
          cal.data('colorpicker').onBeforeShow.apply(this, [cal.get(0)]);
          var pos = $(this).offset();
          var viewPort = getViewport();
          var top = pos.top + this.offsetHeight;
          var left = pos.left;
          if (top + 176 > viewPort.t + viewPort.h) {
            top -= this.offsetHeight + 176;
          }
          if (left + 356 > viewPort.l + viewPort.w) {
            left -= 356;
          }
          cal.css({
            left: left + 'px',
            top: top + 'px'
          });
          if (cal.data('colorpicker').onShow.apply(this, [cal.get(0)]) != false) {
            cal.show();
          }
          $(document).bind('mousedown', {
            cal: cal
          }, hide);
          return false;
          },
          hide = function(ev) {
          if (!isChildOf(ev.data.cal.get(0), ev.target, ev.data.cal.get(0))) {
            if (ev.data.cal.data('colorpicker').onHide.apply(this, [ev.data.cal.get(0)]) != false) {
              ev.data.cal.hide();
            }
            $(document).unbind('mousedown', hide);
          }
          },
          isChildOf = function(parentEl, el, container) {
          if (parentEl == el) {
            return true;
          }
          if (parentEl.contains) {
            return parentEl.contains(el);
          }
          if (parentEl.compareDocumentPosition) {
            return !!(parentEl.compareDocumentPosition(el) & 16);
          }
          var prEl = el.parentNode;
          while (prEl && prEl != container) {
            if (prEl == parentEl) return true;
            prEl = prEl.parentNode;
          }
          return false;
          },
          getViewport = function() {
          var m = document.compatMode == 'CSS1Compat';
          return {
            l: window.pageXOffset || (m ? document.documentElement.scrollLeft : document.body.scrollLeft),
            t: window.pageYOffset || (m ? document.documentElement.scrollTop : document.body.scrollTop),
            w: window.innerWidth || (m ? document.documentElement.clientWidth : document.body.clientWidth),
            h: window.innerHeight || (m ? document.documentElement.clientHeight : document.body.clientHeight)
          };
          },
          fixHSB = function(hsb) {
          return {
            h: Math.min(360, Math.max(0, hsb.h)),
            s: Math.min(100, Math.max(0, hsb.s)),
            b: Math.min(100, Math.max(0, hsb.b))
          };
          },
          fixRGB = function(rgb) {
          return {
            r: Math.min(255, Math.max(0, rgb.r)),
            g: Math.min(255, Math.max(0, rgb.g)),
            b: Math.min(255, Math.max(0, rgb.b))
          };
          },
          fixHex = function(hex) {
          var len = 6 - hex.length;
          if (len > 0) {
            var o = [];
            for (var i = 0; i < len; i++) {
              o.push('0');
            }
            o.push(hex);
            hex = o.join('');
          }
          return hex;
          },
          HexToRGB = function(hex) {
          var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
          return {
            r: hex >> 16,
            g: (hex & 0x00FF00) >> 8,
            b: (hex & 0x0000FF)
          };
          },
          HexToHSB = function(hex) {
          return RGBToHSB(HexToRGB(hex));
          },
          RGBToHSB = function(rgb) {
          var hsb = {
            h: 0,
            s: 0,
            b: 0
          };
          var min = Math.min(rgb.r, rgb.g, rgb.b);
          var max = Math.max(rgb.r, rgb.g, rgb.b);
          var delta = max - min;
          hsb.b = max;
          if (max != 0) {}
          hsb.s = max != 0 ? 255 * delta / max : 0;
          if (hsb.s != 0) {
            if (rgb.r == max) {
              hsb.h = (rgb.g - rgb.b) / delta;
            } else if (rgb.g == max) {
              hsb.h = 2 + (rgb.b - rgb.r) / delta;
            } else {
              hsb.h = 4 + (rgb.r - rgb.g) / delta;
            }
          } else {
            hsb.h = -1;
          }
          hsb.h *= 60;
          if (hsb.h < 0) {
            hsb.h += 360;
          }
          hsb.s *= 100 / 255;
          hsb.b *= 100 / 255;
          return hsb;
          },
          HSBToRGB = function(hsb) {
          var rgb = {};
          var h = Math.round(hsb.h);
          var s = Math.round(hsb.s * 255 / 100);
          var v = Math.round(hsb.b * 255 / 100);
          if (s == 0) {
            rgb.r = rgb.g = rgb.b = v;
          } else {
            var t1 = v;
            var t2 = (255 - s) * v / 255;
            var t3 = (t1 - t2) * (h % 60) / 60;
            if (h == 360) h = 0;
            if (h < 60) {
              rgb.r = t1;
              rgb.b = t2;
              rgb.g = t2 + t3
            } else if (h < 120) {
              rgb.g = t1;
              rgb.b = t2;
              rgb.r = t1 - t3
            } else if (h < 180) {
              rgb.g = t1;
              rgb.r = t2;
              rgb.b = t2 + t3
            } else if (h < 240) {
              rgb.b = t1;
              rgb.r = t2;
              rgb.g = t1 - t3
            } else if (h < 300) {
              rgb.b = t1;
              rgb.g = t2;
              rgb.r = t2 + t3
            } else if (h < 360) {
              rgb.r = t1;
              rgb.g = t2;
              rgb.b = t1 - t3
            } else {
              rgb.r = 0;
              rgb.g = 0;
              rgb.b = 0
            }
          }
          return {
            r: Math.round(rgb.r),
            g: Math.round(rgb.g),
            b: Math.round(rgb.b)
          };
          },
          RGBToHex = function(rgb) {
          var hex = [rgb.r.toString(16), rgb.g.toString(16), rgb.b.toString(16)];
          $.each(hex, function(nr, val) {
            if (val.length == 1) {
              hex[nr] = '0' + val;
            }
          });
          return hex.join('');
          },
          HSBToHex = function(hsb) {
          return RGBToHex(HSBToRGB(hsb));
          },
          restoreOriginal = function() {
          var cal = $(this).parent();
          var col = cal.data('colorpicker').origColor;
          cal.data('colorpicker').color = col;
          fillRGBFields(col, cal.get(0));
          fillHexFields(col, cal.get(0));
          fillHSBFields(col, cal.get(0));
          setSelector(col, cal.get(0));
          setHue(col, cal.get(0));
          setNewColor(col, cal.get(0));
          };
      return {
        init: function(opt) {
          opt = $.extend({}, defaults, opt || {});
          if (typeof opt.color == 'string') {
            opt.color = HexToHSB(opt.color);
          } else if (opt.color.r != undefined && opt.color.g != undefined && opt.color.b != undefined) {
            opt.color = RGBToHSB(opt.color);
          } else if (opt.color.h != undefined && opt.color.s != undefined && opt.color.b != undefined) {
            opt.color = fixHSB(opt.color);
          } else {
            return this;
          }
          return this.each(function() {
            if (!$(this).data('colorpickerId')) {
              var options = $.extend({}, opt);
              options.origColor = opt.color;
              var id = 'collorpicker_' + parseInt(Math.random() * 1000);
              $(this).data('colorpickerId', id);
              var cal = $(tpl).attr('id', id);
              if (options.flat) {
                cal.appendTo(this).show();
              } else {
                cal.appendTo(document.body);
              }
              options.fields = cal.find('input').bind('keyup', keyDown).bind('change', change).bind('blur', blur).bind('focus', focus);
              cal.find('span').bind('mousedown', downIncrement).end().find('>div.colorpicker_current_color').bind('click', restoreOriginal);
              options.selector = cal.find('div.colorpicker_color').bind('mousedown', downSelector);
              options.selectorIndic = options.selector.find('div div');
              options.el = this;
              options.hue = cal.find('div.colorpicker_hue div');
              cal.find('div.colorpicker_hue').bind('mousedown', downHue);
              options.newColor = cal.find('div.colorpicker_new_color');
              options.currentColor = cal.find('div.colorpicker_current_color');
              cal.data('colorpicker', options);
              cal.find('div.colorpicker_submit').bind('mouseenter', enterSubmit).bind('mouseleave', leaveSubmit).bind('click', clickSubmit);
              fillRGBFields(options.color, cal.get(0));
              fillHSBFields(options.color, cal.get(0));
              fillHexFields(options.color, cal.get(0));
              setHue(options.color, cal.get(0));
              setSelector(options.color, cal.get(0));
              setCurrentColor(options.color, cal.get(0));
              setNewColor(options.color, cal.get(0));
              if (options.flat) {
                cal.css({
                  position: 'relative',
                  display: 'block'
                });
              } else {
                $(this).bind(options.eventName, show);
              }
            }
          });
        },
        showPicker: function() {
          return this.each(function() {
            if ($(this).data('colorpickerId')) {
              show.apply(this);
            }
          });
        },
        hidePicker: function() {
          return this.each(function() {
            if ($(this).data('colorpickerId')) {
              $('#' + $(this).data('colorpickerId')).hide();
            }
          });
        },
        setColor: function(col) {
          if (typeof col == 'string') {
            col = HexToHSB(col);
          } else if (col.r != undefined && col.g != undefined && col.b != undefined) {
            col = RGBToHSB(col);
          } else if (col.h != undefined && col.s != undefined && col.b != undefined) {
            col = fixHSB(col);
          } else {
            return this;
          }
          return this.each(function() {
            if ($(this).data('colorpickerId')) {
              var cal = $('#' + $(this).data('colorpickerId'));
              cal.data('colorpicker').color = col;
              cal.data('colorpicker').origColor = col;
              fillRGBFields(col, cal.get(0));
              fillHSBFields(col, cal.get(0));
              fillHexFields(col, cal.get(0));
              setHue(col, cal.get(0));
              setSelector(col, cal.get(0));
              setCurrentColor(col, cal.get(0));
              setNewColor(col, cal.get(0));
            }
          });
        }
      };
      }();
  $.fn.extend({
    ColorPicker: ColorPicker.init,
    ColorPickerHide: ColorPicker.hidePicker,
    ColorPickerShow: ColorPicker.showPicker,
    ColorPickerSetColor: ColorPicker.setColor
  });
})(jQuery);