<?php
/*
Plugin Name: Widget hiển thị bài viết category
Plugin URI: https://www.facebook.com/
Description: Thực hành tạo widget hiển thị bài viết category
Author: Nguyễn Ngọc Tiệp
Version: 1.0
Author URI: https://www.facebook.com/
*/
class show_post_category extends WP_Widget {
    function __construct() {
        parent::__construct(
            'show_post_category',
            'Hiển thị bài viết category',
            array( 'description'  =>  'Trang chủ - Hiển thị bài viết category' )
        );
    }
    function form( $instance ) {
        $default = array(
            'title' => 'Hiển thị bài viết category',
            'id_category' => 8,
            'number_post' => 4,
            'theme' => 'giao-dien'
        );
        $instance = wp_parse_args( (array) $instance, $default );
        $title = esc_attr($instance['title']);
        $id_category = esc_attr($instance['id_category']);
        $number_post = esc_attr($instance['number_post']);
        $theme = esc_attr($instance['theme']);

        echo '<p>';
            echo 'Tiêu đề :';
            echo '<input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/>';
        echo '</p>';

        echo '<p>';
            echo 'Chọn category :';
            echo '<select class="widefat" name="'.$this->get_field_name('id_category').'">';
                //
                $categories = get_categories( array(
                    'orderby' => 'name',
                    'parent'  => 0
                ) );
                foreach ( $categories as $category ) {
                    if($category->term_id == 1) {} else {

                        if($id_category == $category->term_id) {
                            echo '<option name="'.$this->get_field_name('$category->term_id').'" selected value="'.$category->term_id.'">'.$category->name.'</option>';
                        }else{
                            echo '<option name="'.$this->get_field_name('$category->term_id').'" value="'.$category->term_id.'">'.$category->name.'</option>';
                        }
                    }
                    $team_lg = $category->term_id;
                    //
                    $child_cats = get_term_children( $category->term_id, 'category' );
                    foreach ($child_cats as $childs) {
                        $child_cat = get_term_by( 'id', $childs, 'category' );

                        if($child_cat->parent == $team_lg){

                            if($id_category == $child_cat->term_id) {
                                echo '<option name="'.$this->get_field_name('$child_cat->term_id').'" selected value="'.$child_cat->term_id.'">&nbsp;&nbsp;&nbsp;'.$child_cat->name.'</option>';
                            }else{
                                echo '<option name="'.$this->get_field_name('$child_cat->term_id').'" value="'.$child_cat->term_id.'">&nbsp;&nbsp;&nbsp;'.$child_cat->name.'</option>';
                            }
                            //
                            $child_cats = get_term_children( $child_cat->term_id, 'category' );
                            foreach ( $child_cats as $childs ) {
                                $child_cat = get_term_by( 'id', $childs, 'category' );

                                if($id_category == $child_cat->term_id) {
                                    echo '<option name="'.$this->get_field_name('$child_cat->term_id').'" selected value="'.$child_cat->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$child_cat->name.'</option>';
                                }else{
                                    echo '<option name="'.$this->get_field_name('$child_cat->term_id').'" value="'.$child_cat->term_id.'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$child_cat->name.'</option>';
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
        $instance['id_category'] = strip_tags($new_instance['id_category']);
        $instance['number_post'] = strip_tags($new_instance['number_post']);
        $instance['theme'] = strip_tags($new_instance['theme']);
        return $instance;
    }
    function widget( $args, $instance ) {
        extract($args);
        $title = apply_filters( 'widget_title', $instance['title'] );
        $id_category = $instance['id_category'];
        $number_post = $instance['number_post'];
        $theme = $instance['theme'];

        $link = get_term($id_category);

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
                        $query = new WP_Query(array('cat'=>$id_category,'showposts'=>$number_post,'order' => 'DESC','orderby' => 'date'));
                        if($query->have_posts()) : while ($query->have_posts() ) : $query->the_post();
                    ?>

                        <article class="item">
                            <figure>
                                <a href="<?php the_permalink();?>">
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
function create_showpostcategory_widget() {
    register_widget('show_post_category');
}
add_action( 'widgets_init', 'create_showpostcategory_widget' );
?>