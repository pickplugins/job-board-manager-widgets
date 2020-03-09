<?php
/*
* @Author 		pickplugins
* Copyright: 	2015 pickplugins
*/

if ( ! defined('ABSPATH')) exit;  // if direct access 
	

class WidgetFeaturedJob extends WP_Widget {

	function __construct() {
		
		parent::__construct(
			'job_bm_widget_featured_job', 
			__('Job Board Manager - Featured Job', 'job-board-manager-widgets'),
			array( 'description' => __( 'Show Featured jobs.', 'job-board-manager-widgets' ), )
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', isset($instance['title']) ? $instance['title'] : '' );
		$count = apply_filters( 'widget_title', isset($instance['count']) ? $instance['count'] : 5 );

        wp_enqueue_style( 'job-bm-widgets' );

		echo $args['before_widget'];
		if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
		
		$wp_query = new WP_Query(
			array (
				'post_type' => 'job',
				'orderby' => 'Date',
				'order' => 'DESC',
				'posts_per_page' => $count,
				'meta_query' => array(
					array(
						'key'     => 'job_bm_featured',
						'value'   => 'yes',
						'compare' => 'LIKE',
					),
				),
			) );
		
		echo '<ul class="job_bm_featured_jobs">';

        do_action('job_bm_featured_jobs_before');


        if ( $wp_query->have_posts() ) :
			while ( $wp_query->have_posts() ) : $wp_query->the_post();

            $job_id = get_the_id();

            ?>
            <li>
                <?php

                do_action('job_bm_featured_jobs_loop', $job_id);

                ?>
            </li>
            <?php


		
			//echo '<li><a href="'.get_the_permalink().'">'.get_the_title().'</a></li>';
			
			endwhile;

            do_action('job_bm_featured_jobs_after');

        endif;
		wp_reset_query();
		
		echo '</ul>';
		
		
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) $title = $instance[ 'title' ];
		else $title = __( 'Featured Job', 'job-board-manager-widgets' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
		
		if ( isset( $instance[ 'count' ] ) ) $count = $instance[ 'count' ];
		else $count = 5;
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Job count:', 'job-board-manager-widgets' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>" />
		</p>
		<?php 
		
		
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
		return $instance;
	}
}


add_action('job_bm_featured_jobs_loop','job_bm_featured_jobs_loop', 10, 1);

if(!function_exists('job_bm_featured_jobs_loop')){
    function job_bm_featured_jobs_loop($job_id){


        $class_job_bm_functions = new class_job_bm_functions();
        $job_status_list = $class_job_bm_functions->job_status_list();
        $job_type_list = $class_job_bm_functions->job_type_list();

        $job_bm_company_logo = get_post_meta($job_id,'job_bm_company_logo', true);
        $job_bm_location = get_post_meta($job_id,'job_bm_location', true);
        $job_bm_job_type = get_post_meta($job_id,'job_bm_job_type', true);
        $job_bm_job_status = get_post_meta($job_id,'job_bm_job_status', true);
        $post_date = get_the_time( 'U', $job_id );
        $job_bm_company_name = get_post_meta($job_id,'job_bm_company_name', true);


        //var_dump('ggggggg');

        ?>
        <div class="single">
            <div class="company_logo">
                <img src="<?php echo $job_bm_company_logo; ?>">
            </div>
            <div class="title"><a href="<?php echo get_permalink($job_id); ?>"><?php echo get_the_title($job_id); ?></a></div>

            <div class="job-meta">
                <?php if(!empty($job_bm_company_name)):?>
                    <span class="company-name"><?php echo $job_bm_company_name; ?></span>
                <?php endif; ?>

                <?php if(isset($job_type_list[$job_bm_job_type])):?>
                    <span class="meta-item job_type freelance"><i class="fas fa-briefcase"></i>  <?php echo $job_type_list[$job_bm_job_type]; ?></span>
                <?php endif; ?>

                <?php if(isset($job_status_list[$job_bm_job_status])):?>
                    <span class=" meta-item job_status open"><i class="fas fa-traffic-light"></i> <?php echo $job_status_list[$job_bm_job_status]; ?></span>
                <?php endif; ?>
                <?php if(!empty($job_bm_location)):?>
                    <span class="job-location meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo $job_bm_location; ?></span>
                <?php endif; ?>

                <span class="job-post-date meta-item"><i class="far fa-calendar-alt"></i> <?php echo sprintf(__('Posted %s ago','job-board-manager'), human_time_diff( $post_date, current_time( 'timestamp' ) ) )?></span>
            </div>
        </div>
        <?php

    }
}

add_action('job_bm_featured_jobs_after','job_bm_featured_jobs_after', 10);


if(!function_exists('job_bm_featured_jobs_after')){
    function job_bm_featured_jobs_after($job_id){

        ?>

        <style type="text/css">
            .job_bm_featured_jobs{}
            .job_bm_featured_jobs li{
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .job_bm_featured_jobs .single {
                clear: both;
                display: block;
                margin: 15px 0;
                border-bottom: 1px solid #ddd;
                padding-bottom: 15px;
            }
            .job_bm_featured_jobs .company_logo {
                width: 50px;
                height: 50px;
                overflow: hidden;
                float: left;
                margin-right: 15px;
            }
            .job_bm_featured_jobs .title {
                font-size: 15px;

            }

            .job_bm_featured_jobs a {
                text-decoration: none;

            }
            .job_bm_featured_jobs .company-name {
                display: inline-block;
                margin-right: 10px;
            }


            .job_bm_featured_jobs .job-meta {
                /*display: inline-block;*/
            }
            .job_bm_featured_jobs .job-meta span{
                display: inline-block;
                margin-right: 15px;
                font-size: 12px;
            }


        </style>
        <?php

    }
}
