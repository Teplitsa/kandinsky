<?php
/**
 * Text PM Customisation Example
 **/

if(!class_exists('Leyka_Payment_Method'))
	return;

/** Custom donation functions */

add_filter('leyka_icons_text_text_box', 'rdc_text_pm_icon');
function rdc_text_pm_icon($icons){
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



add_action('leyka_init_pm_list', 'rdc_add_sms_pm');
function rdc_add_sms_pm(Leyka_Gateway $gateway){

	if($gateway->id == 'text'){		
		$gateway->add_payment_method(Leyka_Sms_Box::get_instance());
	}
}

//no icon for text gateway
add_filter('leyka_icons_text_text_box', 'rdc_empty_icons');	
function rdc_empty_icons($icons){
	return array();
}


/** Form template **/
//custom amount field
function rdc_amount_field($form){
	
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
					<span class="figure-sep"><?php _e('or', 'rdc');?></span>
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

function rdc_donation_form($campaign_id = null){
	global $leyka_current_pm;	
		
	if(!defined('LEYKA_VERSION'))
		return;
	
	if(!$campaign_id)
		$campaign_id = get_queried_object_id();

	$active_pm = apply_filters('leyka_form_pm_order', leyka_get_pm_list(true));
	$agree_link = home_url('oferta'); //oferta page

	
	leyka_pf_submission_errors();
?>
<div id="leyka-payment-form" class="leyka-custom-template" data-template="toggles">
<?php
	$counter = 0;
	foreach($active_pm as $i => $pm) {
		leyka_setup_current_pm($pm); 
		$counter++;
?>
<div class="leyka-payment-option toggle <?php if($counter == 1) echo 'toggled';?> <?php echo esc_attr($pm->full_id);?>">
<div class="leyka-toggle-trigger <?php echo count($active_pm) > 1 ? '' : 'toggle-inactive';?>">
    <?php echo leyka_pf_get_pm_label();?>
</div>
<div class="leyka-toggle-area">
<form class="leyka-pm-form" id="<?php echo leyka_pf_get_form_id();?>" action="<?php echo leyka_pf_get_form_action();?>" method="post">
	
	<div class="leyka-pm-fields">
		
	<!-- amount -->
	<?php
		$supported_curr = leyka_get_active_currencies();
		$current_curr = $leyka_current_pm->get_current_currency();
		
		if($leyka_current_pm->is_field_supported('amount') ) {
			
			//echo leyka_pf_get_amount_field();
			rdc_amount_field($leyka_current_pm);
		} //if amount
		
		echo leyka_pf_get_hidden_fields();	
	?>
	<input name="leyka_payment_method" value="<?php echo esc_attr($pm->full_id);?>" type="hidden" />
	<input name="leyka_ga_payment_method" value="<?php echo esc_attr($pm->label);?>" type="hidden" />
			
	<!-- name -->
	<?php if($leyka_current_pm->is_field_supported('name') ) { ?>
	<div class="rdc-textfield leyka-field name">
		<input type="text" class="required rdc-textfield__input" name="leyka_donor_name" id="leyka_donor_name" value="">
		<label for="leyka_donor_name" class="leyka-screen-reader-text rdc-textfield__label"><?php _e('Your name', 'leyka');?></label>		
		<span id="leyka_donor_name-error" class="leyka_donor_name-error field-error rdc-textfield__error"></span>
	</div>
	<?php  }?>
	
	<!-- email -->
	<?php if($leyka_current_pm->is_field_supported('email') ) { ?>
	<div class="rdc-textfield leyka-field email">
		<input type="text" value="" id="leyka_donor_email" name="leyka_donor_email" class="required email rdc-textfield__input">
		<label class="leyka-screen-reader-text rdc-textfield__label" for="leyka_donor_email">Ваш email</label>
		<span class="leyka_donor_email-error field-error rdc-textfield__error" id="leyka_donor_email-error"></span>
	</div>
	<?php  }?>
	
	<!-- pm fields -->
	<?php
		//correct html
		
		if($leyka_current_pm->full_id == 'cp-card'){
			$f_html = $leyka_current_pm->get_pm_fields();
			preg_match("#<\s*?span\b[^>]*>(.*?)</span\b[^>]*>#s", $f_html, $l); 
			if(isset($l[1]) && !empty($l[1])){
				$f_html = str_replace('input', 'input class="rdc-checkbox__input"', $l[1]);
	?>
		<div class="leyka-field recurring">
			<label class="rdc-checkbox checkbox" for="leyka_cp-card_recurring">
				<?php echo $f_html; ?>
				<span class="rdc-checkbox__label"><?php _e('Monthly donation', 'rdc');?></span>           
			</label>
		</div>
	<?php
			}	
			else {
				echo $f_html;
			}
		}
		else {
			echo leyka_pf_get_pm_fields();
		}
	?>
	
	<!-- agree -->
	<?php
		if($leyka_current_pm->is_field_supported('agree') ) { 
		$agree_check_id = 'leyka_agree-'.$i; ?>
	<div class="leyka-field agree">
		<label class="rdc-checkbox checkbox" for="<?php echo $agree_check_id;?>">
			<input type="checkbox" name="leyka_agree" id="<?php echo $agree_check_id;?>" class="leyka_agree required rdc-checkbox__input" value="1" />
			<span class="rdc-checkbox__label">Согласен с <a class="leyka-custom-confirmation-trigger" href="<?php echo $agree_link;?>" data-lmodal="#leyka-agree-text">условиями сбора пожертвований</a></span>           
		</label>
		<p class="leyka_agree-error field-error rdc-checkbox__error" id="<?php echo $agree_check_id;?>-error"></p>
	</div>	
	<?php }?>
	
	<!-- submit -->	
	<div class="leyka-field submit">
	<?php if($leyka_current_pm->is_field_supported('submit') ) { ?>
		<input type="submit" class="rdc-submit-button" id="leyka_donation_submit" name="leyka_donation_submit" value="Пожертвовать" />
	<?php  }

		$icons = leyka_pf_get_pm_icons(); 
		if($icons) {
	
			$list = array();
			foreach($icons as $i) {
				$i = (is_ssl()) ? str_replace('http:', 'https:', $i) : $i;
				$list[] = "<li>{$i}</li>";
			}
	
			echo '<ul class="leyka-pm-icons cf">'.implode('', $list).'</ul>';
		}
	?>
	</div>
	
	
	</div> <!-- .leyka-pm-fields -->	
	
	<div class="leyka-pm-desc">
		<?php echo apply_filters('leyka_the_content', leyka_pf_get_pm_description()); ?>
	</div>
	
</form>
</div>
</div>
<?php } ?>	
</div><!-- #leyka-payment-form -->
<?php leyka_pf_footer();?>


<!-- agreement modal -->
<div id="leyka-agree-text" class="leyka-oferta-text leyka-custom-modal">
	<div class="leyka-modal-close"><?php rdc_svg_icon('icon-close');?></div>
	<div class="leyka-oferta-text-frame">
		<div class="leyka-oferta-text-flow">
			<?php echo apply_filters('leyka_terms_of_service_text', leyka_options()->opt('terms_of_service_text'));?>
		</div>
	</div>
</div>
<?php
}