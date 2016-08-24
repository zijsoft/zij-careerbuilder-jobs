<?php
/*
Plugin Name: Zij CareerBuilder Jobs
Plugin URI: http://zijsoft.com/wordpress/zijcareerbuilderjobs
Description: Zijsoft provide the careerbuilder jobs integeration into your wordpress installation easily
Author: Habib Ahmed
Version: 1.0
Author URI: http://zijsoft.com/aboutus
*/

/**
 * Adds Zij careerbuilder jobs widget.
 */
class ZijCareerBuilderJobs extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'zijcareerbuilderjobs', // Base ID
			__( 'Zij CareerBuilder Jobs', 'zij-careerbuilder-jobs' ), // Name
			array( 'description' => __( 'Zij integrate careerbuilder jobs into your wordpress installation', 'zij-careerbuilder-jobs' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		if(!empty($instance['careerbuilder_developerkey'])){
            $querystring = "DeveloperKey=".$instance['careerbuilder_developerkey'].
            				"&CountryCode=".$instance['careerbuilder_countrycode'].
            				"&PerPage=".$instance['careerbuilder_joblimit'].
            				"&Category=".$instance['careerbuilder_category'].
            				"&EmpType=".$instance['careerbuilder_emptype'];
            $xml = simplexml_load_file("http://api.careerbuilder.com/V1/jobsearch?".$querystring);
            $careerbuilderjobs = array();
            foreach($xml->Results->JobSearchResult AS $xmljob){
                $job = array();
                $job['CompanyImageURL'] = $xmljob->CompanyImageURL;
                $job['CompanyDetailsURL'] = $xmljob->CompanyDetailsURL;
                $job['Company'] = $xmljob->Company;
                $job['DescriptionTeaser'] = $xmljob->DescriptionTeaser;
                $job['EmploymentType'] = $xmljob->EmploymentType;
                $job['EducationRequired'] = $xmljob->EducationRequired;
                $job['ExperienceRequired'] = $xmljob->ExperienceRequired;
                $job['JobDetailsURL'] = $xmljob->JobDetailsURL;
                $job['Location'] = $xmljob->Location;
                $job['PostedDate'] = $xmljob->PostedDate;
                $job['SimilarJobsURL'] = $xmljob->SimilarJobsURL;
                $job['JobTitle'] = $xmljob->JobTitle;
                $careerbuilderjobs[] = $job;
            }
		}        
        if(isset($careerbuilderjobs) && !empty($careerbuilderjobs)){
        	foreach($careerbuilderjobs AS $job){
        		$html = '<div class="zijcb_job_wrapper">
        					<div class="zijcb_job_title"><a href="'.$job['JobDetailsURL'].'" target="_blank" >'.$job['JobTitle'].'</a></div>
        					<div class="zijcb_field_wrapper">
        						<div class="zijcb_title">'.__('Company','zij-careerbuilder-jobs').'</div>
        						<div class="zijcb_value">'.$job['Company'].'</div>
        					</div>
        					<div class="zijcb_field_wrapper">
        						<div class="zijcb_title">'.__('Job Type','zij-careerbuilder-jobs').'</div>
        						<div class="zijcb_value">'.$job['EmploymentType'].'</div>
        					</div>
        					<div class="zijcb_field_wrapper">
        						<div class="zijcb_title">'.__('Posted','zij-careerbuilder-jobs').'</div>
        						<div class="zijcb_value">'.$job['PostedDate'].'</div>
        					</div>
        					<div class="zijcb_field_wrapper">
        						<div class="zijcb_title">'.__('Location','zij-careerbuilder-jobs').'</div>
        						<div class="zijcb_value">'.$job['Location'].'</div>
        					</div>
        					<div class="zijcb_field_wrapper">
        						<div class="zijcb_title">'.__('Snippet','zij-careerbuilder-jobs').'</div>
        						<div class="zijcb_snippet">'.$job['DescriptionTeaser'].'</div>
        					</div>
        				</div>';
				echo $html;
        	}
        }else{
        	echo '<h1>'.__('Please check your widget setting and make sure you have entered valid API(developer) key provided by career builder.','zij-careerbuilder-jobs').'</h1>';
        }
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Zij CareerBuilder Jobs', 'zij-careerbuilder-jobs' );
		echo '<p>';
		echo '<label for="'.$this->get_field_id( 'title' ).'">'.__('Title:','zij-careerbuilder-jobs').'</label>';
		echo '<input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.esc_attr( $title ).'">';
		echo '</p>';
		$careerbuilder_developerkey = ! empty( $instance['careerbuilder_developerkey'] ) ? $instance['careerbuilder_developerkey'] : '';
		echo '<p>';
		echo '<label for="'.$this->get_field_id( 'careerbuilder_developerkey' ).'">'.__('Developer Key:','zij-careerbuilder-jobs').'</label>';
		echo '<input class="widefat" id="'.$this->get_field_id( 'careerbuilder_developerkey' ).'" name="'.$this->get_field_name( 'careerbuilder_developerkey' ).'" type="text" value="'.esc_attr( $careerbuilder_developerkey ).'">';
		echo '</p>';
		$careerbuilder_category = ! empty( $instance['careerbuilder_category'] ) ? $instance['careerbuilder_category'] : 'PHP Developer';
		echo '<p>';
		echo '<label for="'.$this->get_field_id( 'careerbuilder_category' ).'">'.__('Category:','zij-careerbuilder-jobs').'</label>';
		echo '<input class="widefat" id="'.$this->get_field_id( 'careerbuilder_category' ).'" name="'.$this->get_field_name( 'careerbuilder_category' ).'" type="text" value="'.esc_attr( $careerbuilder_category ).'">';
		echo '</p>';
		$careerbuilder_countrycode = ! empty( $instance['careerbuilder_countrycode'] ) ? $instance['careerbuilder_countrycode'] : 'austin';
		echo '<p>';
		echo '<label for="'.$this->get_field_id( 'careerbuilder_countrycode' ).'">'.__('Country code:','zij-careerbuilder-jobs').'</label>';
		echo '<input class="widefat" id="'.$this->get_field_id( 'careerbuilder_countrycode' ).'" name="'.$this->get_field_name( 'careerbuilder_countrycode' ).'" type="text" value="'.esc_attr( $careerbuilder_countrycode ).'">';
		echo '</p>';
		$careerbuilder_emptype = ! empty( $instance['careerbuilder_emptype'] ) ? $instance['careerbuilder_emptype'] : 'JTFT';
		echo '<p>';
		echo '<label for="'.$this->get_field_id( 'careerbuilder_emptype' ).'">'.__('Job Type:','zij-careerbuilder-jobs').'</label>';
		$combovalue = esc_attr( $careerbuilder_emptype );
		$combo = '<select name="'.$this->get_field_name( 'careerbuilder_emptype' ).'" id="'.$this->get_field_id( 'careerbuilder_emptype' ).'" style="width:100%;">';
		$selected = ($combovalue == 'JTFT') ? 'selected' : '';
		$combo .= '<option value="JTFT" '. $selected .'>'.__('Fulltime','zij-careerbuilder-jobs').'</option>';
		$selected = ($combovalue == 'JTPT') ? 'selected' : '';
		$combo .= '<option value="JTPT" '. $selected .'>'.__('Parttime','zij-careerbuilder-jobs').'</option>';
		$selected = ($combovalue == 'JTFP') ? 'selected' : '';
		$combo .= '<option value="JTFP" '. $selected .'>'.__('Fulltime/parttime','zij-careerbuilder-jobs').'</option>';
		$selected = ($combovalue == 'JTCT') ? 'selected' : '';
		$combo .= '<option value="JTCT" '. $selected .'>'.__('Contractant','zij-careerbuilder-jobs').'</option>';
		$selected = ($combovalue == 'JTIN') ? 'selected' : '';
		$combo .= '<option value="JTIN" '. $selected .'>'.__('Stagiair','zij-careerbuilder-jobs').'</option>';
		$combo .= '</select>';
		echo $combo;
		echo '</p>';
		$careerbuilder_joblimit = ! empty( $instance['careerbuilder_joblimit'] ) ? $instance['careerbuilder_joblimit'] : 10;
		echo '<p>';
		echo '<label for="'.$this->get_field_id( 'careerbuilder_joblimit' ).'">'.__('No. of jobs:','zij-careerbuilder-jobs').'</label>';
		echo '<input class="widefat" id="'.$this->get_field_id( 'careerbuilder_joblimit' ).'" name="'.$this->get_field_name( 'careerbuilder_joblimit' ).'" type="text" value="'.esc_attr( $careerbuilder_joblimit ).'">';
		echo '</p>';
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['careerbuilder_developerkey'] = ( ! empty( $new_instance['careerbuilder_developerkey'] ) ) ? strip_tags( $new_instance['careerbuilder_developerkey'] ) : '';
		$instance['careerbuilder_category'] = ( ! empty( $new_instance['careerbuilder_category'] ) ) ? strip_tags( $new_instance['careerbuilder_category'] ) : '';
		$instance['careerbuilder_countrycode'] = ( ! empty( $new_instance['careerbuilder_countrycode'] ) ) ? strip_tags( $new_instance['careerbuilder_countrycode'] ) : '';
		$instance['careerbuilder_emptype'] = ( ! empty( $new_instance['careerbuilder_emptype'] ) ) ? strip_tags( $new_instance['careerbuilder_emptype'] ) : '';
		$instance['careerbuilder_joblimit'] = ( ! empty( $new_instance['careerbuilder_joblimit'] ) ) ? strip_tags( $new_instance['careerbuilder_joblimit'] ) : '';

		return $instance;
	}

} // class Foo_Widget


function zijcareerbuilder_stylesheet() {
    wp_enqueue_style( 'style-name', plugins_url( 'includes/style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'zijcareerbuilder_stylesheet' );

// register Zij indeed jobs widget
function register_zijcareebuilderjobswidget() {
    register_widget( 'ZijCareerBuilderJobs' );
}
add_action( 'widgets_init', 'register_zijcareebuilderjobswidget' );

?>