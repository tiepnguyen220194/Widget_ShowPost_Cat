<?php
/*
Plugin Name: Widget hiển thị bài viết taxonomy
Plugin URI: https://www.facebook.com/tj3ungunhj9
Description: Thực hành tạo widget hiển thị bài viết taxonomy
Author: Nguyễn Ngọc Tiệp
Version: 1.0
Author URI: https://www.facebook.com/tj3ungunhj9
*/
class show_post_taxonomy extends WP_Widget {
    function __construct() {
        parent::__construct(
            'show_post_taxonomy',
            'Hiển thị bài viết taxonomy',
            array( 'description'  =>  'Trang chủ - Hiển thị bài viết taxonomy' )
        );
    }
    function form( $instance ) {
        $default = array(
            'title' => 'Hiển thị bài viết taxonomy',
            'id_taxonomy' => 2,
            'number_post' => 4,
            'theme' => 'giao-dien'
        );
        $instance = wp_parse_args( (array) $instance, $default );
        $title = esc_attr($instance['title']);
        $id_taxonomy = esc_attr($instance['id_taxonomy']);
        $number_post = esc_attr($instance['number_post']);
        $theme = esc_attr($instance['theme']);

        echo '<p>';
            echo 'Tiêu đề :';
            echo '<input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/>';
        echo '</p>';

        echo '<p>';
            echo 'Chọn taxonomy :';
            echo '<select class="widefat" name="'.$this->get_field_name('id_taxonomy').'">';
                //
                $terms = get_terms('san-pham-category', array(
                    'parent'=> 0,
                    'hide_empty' => false
                ) );
                foreach($terms as $term){
                    $team_lg = $term->term_id;
                    if($id_taxonomy == $term->term_id) {
                        echo '<option name="'.$this->get_field_name('$term->term_id').'" selected value="'.$term->term_id.'">'.$term->name.'</option>';
                    }else{
                        echo '<option name="'.$this->get_field_name('$term->term_id').'" value="'.$term->term_id.'">'.$term->name.'</option>';
                    }
                    //
                    $term_childs = get_term_children( $term->term_id, 'san-pham-category' );
                    $count = count($term_childs);
                    if($count >= 0) {
                        foreach ( $term_childs as $child ) {
                            $term = get_term_by( 'id', $child, 'san-pham-category' );
                            if($term->parent == $team_lg){

                            if($id_taxonomy == $term->term_id) {
                                echo '<option name="'.$this->get_field_name('$term->term_id').'" selected value="'.$term->term_id.'">&nbsp;&nbsp;&nbsp;'.$term->name.'</option>';
                            }else{
                                echo '<option name="'.$this->get_field_name('$term->term_id').'" value="'.$term->term_id.'">&nbsp;&nbsp;&nbsp;'.$term->name.'</option>';
                            }
                                //
                                $term_childs = get_term_children( $term->term_id, 'san-pham-category' );
                                $count = count($term_childs);
                                if($count >= 0) {
                                    foreach ( $term_childs as $child ) {
                                        $term = get_term_by( 'id', $child, 'san-pham-category' );

                                        if($id_taxonomy == $term->term_id) {
                                            echo '<option name="'.$this->get_field_name('$term->term_id').'" selected value="'.$term->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$term->name.'</option>';
                                        }else{
                                            echo '<option name="'.$this->get_field_name('$term->term_id').'" value="'.$term->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$term->name.'</option>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            echo '</select>';
        echo '</p>';

        echo '<p>';
            echo 'Số lượng bài viết hiển thị :';
            echo '<input type="number" class="widefat" name="'.$this->get_field_name('number_post').'" value="'.$number_post.'" />';
        echo '</p>';

        echo '<p>';
            echo 'Chọn giao diện :';
            echo '<select class="widefat" name="'.$this->get_field_name('theme').'">';
                $gds=array("giao-dien" => "giao-dien","giao-dien-1" => "giao-dien-1","giao-dien-2" => "giao-dien-2");
                foreach ( $gds as $key=>$gd ) {
                    if($gd == $theme){
                        echo '<option name="'.$this->get_field_name('$key').'" selected value="'.$key.'">'.$gd.'</option>';
                    } else {
                        echo '<option name="'.$this->get_field_name('$key').'" value="'.$key.'">'.$gd.'</option>';
                    }
                }
            echo '</select>';
        echo '</p>';
    }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['id_taxonomy'] = strip_tags($new_instance['id_taxonomy']);
        $instance['number_post'] = strip_tags($new_instance['number_post']);
        $instance['theme'] = strip_tags($new_instance['theme']);
        return $instance;
    }
    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters( 'widget_title', $instance['title'] );
        $id_taxonomy = $instance['id_taxonomy'];
        $number_post = $instance['number_post'];
        $theme = $instance['theme'];

        $link = get_term($id_taxonomy);

        echo $before_widget; ?>
            <div class="widget-category <?php echo $theme; ?>">
                <div class="main-title">
                    <a href="<?php echo esc_url(get_term_link($link)); ?>">
                        <h2>
                            <?php echo $title; ?>
                        </h2>
                    </a>
                </div>
                <div class="<?php echo $theme.'-content'; ?>">
                    <?php
                        $query = gda_custom_posttype_query('san-pham', 'san-pham-category', $id_taxonomy, $number_post);
                        if($query->have_posts()) : while ($query->have_posts() ) : $query->the_post();
                    ?>

                        <article class="item">
                            <figure>
                                <a class="vongtron" href="<?php the_permalink();?>">
                                    <img class="img-responsive" src="<?php echo bicweb_get_thumbnail_url('full') ?>" alt="<?php the_title();?>" />
                                </a>
                            </figure>
                            <div class="info">
                                <div class="title">
                                    <a href="<?php the_permalink();?>">
                                        <h3>
                                            <?php the_title();?>
                                        </h3>
                                    </a>
                                </div>
                                <div class="date">
                                    (<?php echo get_the_date('Y-m-d'); ?>)
                                </div>
                                <div class="desc">
                                    <?php echo cut_string(get_the_excerpt(),120,'...');?>
                                </div>
                                <a href="<?php the_permalink();?>" class="btn-more">Xem thêm >></a>
                            </div>
                        </article>

                    <?php endwhile; wp_reset_postdata(); else: echo ''; endif; ?>
                </div>
            </div>
        <?php echo $after_widget;
    }
}
function create_showposttaxonomy_widget() {
    register_widget('show_post_taxonomy');
}
add_action( 'widgets_init', 'create_showposttaxonomy_widget' );
?>