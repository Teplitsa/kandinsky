<?php
/**
 * Text PM Customisation Example
 **/

if(!class_exists('Leyka_Payment_Method'))
	return;

/** Custom donation functions */

add_filter('leyka_icons_text_text_box', 'knd_text_pm_icon');
function knd_text_pm_icon($icons){
	//size 155x80 px
	
	$icons = array(get_template_directory_uri().'/assets/images/text-box.png');
		
	return $icons;
}

/** Additionsl text PM */
class Leyka_Sms_Box extends Leyka_Text_Box {

    protected static $_instance = null;

    public function _set_attributes() {

        $this->_id = 'sms_box';
        $this->_gateway_id = 'text';

        $this->_label_backend = 'Платеж по СМС';
        $this->_label = 'Платеж по СМС';

        // The description won't be setted here - it requires the PM option being configured at this time (which is not)

        $this->_support_global_fields = false;

        $this->_icons = array(get_template_directory_uri().'/assets/images/sms-box.png');

        $this->_supported_currencies[] = 'rur';

        $this->_default_currency = 'rur';
    }

    protected function _set_dynamic_attributes() {

        $this->_custom_fields = array(
            'box_details' => apply_filters('leyka_the_content', leyka_options()->opt_safe('sms_box_details')),
        );
    }

    protected function _set_options_defaults() {

        if($this->_options){
            return;
        }

        $this->_options = array(
            $this->full_id.'_description' => array(
                'type' => 'html',
                'default' => '',
                'title' => 'Описание платежа по СМС',
                'description' => __('Please, set a text of comment to describe an additional ways to donate.', 'leyka'),
                'required' => 0,
                'validation_rules' => array(), // List of regexp?..
            ),
            'sms_box_details' => array(
                'type' => 'html',
                'default' => '',
                'title' => 'Инструкция к платежу по СМС',
                'description' => __('Please, set a text to describe an additional ways to donate.', 'leyka'),
                'required' => 1,
                'validation_rules' => array(), // List of regexp?..
            )
        );
    }
}



add_action('leyka_init_pm_list', 'knd_add_sms_pm');
function knd_add_sms_pm(Leyka_Gateway $gateway){

	if($gateway->id == 'text'){		
		$gateway->add_payment_method(Leyka_Sms_Box::get_instance());
	}
}

//no icon for text gateway
add_filter('leyka_icons_text_text_box', 'knd_empty_icons');	
function knd_empty_icons($icons){
	return array();
}

function knd_activate_leyka() {
    $imp = new KND_Import_Remote_Content(get_theme_mod('knd_site_scenario'));
    $imp->import_downloaded_content();
    
    $pdb = KND_Plot_Data_Builder::produce_builder($imp);
    $pdb->build_leyka_capmaigns();
}
register_activation_hook( 'leyka/leyka.php', 'knd_activate_leyka' );


/** Form template **/
//custom amount field
function knd_amount_field($form){
	
	if(!defined('LEYKA_VERSION'))
		return;
	
	$supported_curr = leyka_get_active_currencies();
	$current_curr = $form->get_current_currency();

	if(empty($supported_curr[$current_curr])) {
		return; // Current currency isn't supported
	}
?>

<div class="leyka-field amount-selector amount mixed">
	
<div class="currency-selector-row" >
	<div class="currency-variants">
<?php
	foreach($supported_curr as $currency => $data) {

	$variants = explode(',', $data['amount_settings']['fixed']);?>
		<div class="<?php echo $currency;?> amount-variants-container" <?php echo $currency == $current_curr ? '' : 'style="display:none;"';?> >
			<div class="amount-variants-row">
				<?php foreach($variants as $i => $amount) { ?>
					<label class="figure rdc-radio" title="<?php _e('Please, specify your donation amount', 'leyka');?>">
						<input type="radio" value="<?php echo (int)$amount;?>" name="leyka_donation_amount" class="rdc-radio__button" <?php checked($i, 0);?> <?php echo $currency == $current_curr ? '' : 'disabled="disabled"';?>>
						<span class="rdc-radio__label"><?php echo (int)$amount;?></span>
					</label>
				<?php } ?>
				
				<label class="figure-flex">
					<span class="figure-sep"><?php _e('or', 'knd');?></span>
					<input type="text" title="<?php echo __('Specify the amount of your donation', 'leyka');?>" name="leyka_donation_amount" class="donate_amount_flex" value="<?php echo esc_attr($supported_curr[$current_curr]['amount_settings']['flexible']);?>" <?php echo $currency == $current_curr ? '' : 'disabled="disabled"';?> maxlength="6" size="6">
				</label>
			</div>
		</div>	
	<?php } ?>
	</div>
	<div class="currency"><span class="currency-frame"><?php echo $form->get_currency_field();?></span></div>
</div>

<div class="leyka_donation_amount-error field-error"></div>
	
</div>
<?php
}

