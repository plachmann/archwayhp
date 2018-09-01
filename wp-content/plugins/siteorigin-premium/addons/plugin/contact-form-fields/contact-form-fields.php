<?php
/*
Plugin Name: SiteOrigin Contact Form Fields
Description: Additional fields for the Widgets Bundle Contact Form widget.
Version: 1.0.0
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
Documentation: https://siteorigin.com/premium-documentation/plugin-addons/contact-form-fields/
Tags: Widgets Bundle
Video: b4t5hae1lz
*/

class SiteOrigin_Premium_Plugin_Contact_Form_Fields {

	function __construct() {
		add_filter( 'siteorigin_widgets_form_options_sow-contact-form', array( $this, 'admin_form_options' ), 10, 2 );

		add_filter( 'siteorigin_widgets_contact_form_field_class_paths', array( $this, 'premium_contactform_fields_class_paths' ) );
	}

	static function single() {
		static $single;
		return empty( $single ) ? $single = new self() : $single;
	}

	/**
	 * Tell the autoloader where to look for premium contact form field classes.
	 *
	 * @param $class_paths
	 *
	 * @return array
	 */
	function premium_contactform_fields_class_paths( $class_paths ) {
		$class_paths[] = plugin_dir_path( __FILE__ ) . 'fields/';
		return $class_paths;
	}

	public function admin_form_options( $form_options, $widget ) {
		if ( empty( $form_options ) ) {
			return $form_options;
		}

		$fields = $form_options['fields']['fields'];
		$field_types = $fields['type']['options'];

		$field_types = array_merge( $field_types, array(
			'datepicker' => __( 'Datetime picker', 'siteorigin-premium' ),
			'location' => __( 'Location', 'siteorigin-premium' ),
		) );

		$fields = array_merge( $fields, array(

			// For location fields
			// These are only required when location is selected
			'location_options' => array(
				'type' => 'section',
				'label' => __( 'Location Options', 'siteorigin-premium' ),
				'fields' => array(
					'show_map' => array(
						'type' => 'checkbox',
						'label' => __( 'Show Google Map', 'siteorigin-premium' ),
						'default' => true,
						'description' => __( 'Clicking on the map will guess the closest address and the map will try to display the address entered into the text input', 'siteorigin-premium' ),
					),
					'default_location' => array(
						'type' => 'text',
						'label' => __( 'Default location', 'siteorigin-premium' ),
					),
					'gmaps_api_key' => array(
						'type'        => 'text',
						'label'       => __( 'Google Maps API Key', 'siteorigin-premium' ),
						'required'    => true,
						'description' => sprintf(
							__( 'Enter your %sAPI key%s. Your map may not function correctly without one.', 'siteorigin-premium' ),
							'<a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank" rel="noopener noreferrer">',
							'</a>'
						)
					),
				),

				'state_handler' => array(
					'field_type_{$repeater}[location]' => array('show'),
					'_else[field_type_{$repeater}]' => array( 'hide' ),
				),
			),

			// For datetime picker fields
			'datetime_options' => array(
				'type' => 'section',
				'label' => __( 'Datetime Options', 'siteorigin-premium' ),
				'fields' => array(
					'show_datepicker' => array(
						'type' => 'checkbox',
						'label' => __( 'Show Date Picker', 'siteorigin-premium' ),
						'default' => true,
					),
					'datepicker_label' => array(
						'type' => 'text',
						'label' => __( 'Date Picker Label', 'siteorigin-premium' ),
						'default' => __( 'Date', 'siteorigin-premium' ),
					),
					'disable_days' => array(
						'type' => 'radio',
						'label' => __( 'Disable', 'siteorigin-premium' ),
						'default' => 'none',
						'options' => array(
							'none' => __( 'None', 'siteorigin-premium' ),
							'weekends' => __( 'Weekends', 'siteorigin-premium' ),
							'weekdays' => __( 'Weekdays', 'siteorigin-premium' ),
						),
					),
					'disabled_dates' => array(
						'type' => 'text',
						'label' => __( 'Disable Dates', 'siteorigin-premium' ),
						'description' => __( 'Specify specific dates, date ranges or weekdays to disable. Dates should be of the format YYYYMMDD e.g. 20160806 and date ranges should be separated by a hyphen e.g. 20160902-20160918. Week days should be the first three letters of the day e.g. Mon,Wed,Fri. Multiple dates, ranges and days should separated by a comma e.g. 20160825,20160902-20160918,Sun,Fri.', 'siteorigin-premium' )
					),
					'show_timepicker' => array(
						'type' => 'checkbox',
						'label' => __( 'Show Time Picker', 'siteorigin-premium' ),
						'default' => false,
					),
					'timepicker_label' => array(
						'type' => 'text',
						'label' => __( 'Time Picker Label', 'siteorigin-premium' ),
						'default' => __( 'Time', 'siteorigin-premium' ),
					),
					'disabled_times' => array(
						'type' => 'text',
						'label' => __( 'Disabled Times', 'siteorigin-premium' ),
						'description' => __( 'Specify disabled times. Ranges should be specified with a dash and multiple ranges should be separated with a comma. E.g. 5:00pm-8:00am,1pm-14:30', 'siteorigin-premium' ),
					),
				),

				'state_handler' => array(
					'field_type_{$repeater}[datepicker]' => array('show'),
					'_else[field_type_{$repeater}]' => array( 'hide' ),
				),
			),
		) );

		$fields['type']['options'] = $field_types;
		$form_options['fields']['fields'] = $fields;

		return $form_options;
	}

}

SiteOrigin_Premium_Plugin_Contact_Form_Fields::single();
