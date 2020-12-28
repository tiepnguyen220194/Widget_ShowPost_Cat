<?php
//widget show post
class show_post_category extends WP_Widget {
    function __construct() {
        parent::__construct(
            'show_post_category',// ID widget
            'Hiển thị bài viết category',// Tên widget
            array( 'description'  =>  'Tiệp - Hiển thị bài viết category' )// Mô tả widget
        );
    }
    function form( $instance ) {
        $default = array(// Biến tạo các giá trị mặc định
            'title' => 'Hiển thị bài viết category',
            'id_category' => 22,
            'post_number' => 10
        );
        //Gộp các giá trị trong mảng $default vào biến $instance để nó trở thành các giá trị mặc định
        $instance = wp_parse_args( (array) $instance, $default );
        //lôi toàn bộ giá trị mảng của biến $default sang biến $instance

        //Tạo biến riêng cho giá trị mặc định trong mảng $default
        $title = esc_attr($instance['title']);//$title là biến sẽ chứa giá trị của tiêu đề lấy từ khóa title trong mảng $instance
        $id_category = esc_attr($instance['id_category']);
        $post_number = esc_attr($instance['post_number']);//loc ki tu roi moi lay
        //Hiển thị form trong option của widget
        echo '<p>Tiêu đề:<input type="text" class="widefat" name="'.$this->get_field_name('title').'" value="'.$title.'"/></p>';
        // echo '<p>ID của danh mục cần hiển thị bài viết:<input type="number" class="widefat" name="'.$this->get_field_name('id_category').'" value="'.$id_category.'" /></p>';
        echo 'Chọn category :<select name="'.$this->get_field_name('id_category').'">';
            $categories = get_categories(  );
            foreach ( $categories as $category ) {
                if($id_category == $category->term_id) {
                     echo '<option name="'.$this->get_field_name('$category->term_id').'"  selected value="'.$category->term_id.'">'.$category->name.'</option>';
                }else{
                     echo '<option name="'.$this->get_field_name('$category->term_id').'"  value="'.$category->term_id.'">'.$category->name.'</option>';
                }
            }
        echo '</select>';
        echo '<p>Số lượng bài viết hiển thị:<input type="number" class="widefat" name="'.$this->get_field_name('post_number').'" value="'.$post_number.'" /></p>';//placeholder="'.$post_number.'" max="30"
    }
    function update( $new_instance, $old_instance ) {
    //$new_instance được dùng để lưu những giá trị sau khi ấn nút Save và $old_instance là giá trị cũ trong cơ sở dữ liệu. Sau khi dữ liệu nhập vào được lưu thì ta sẽ return nó ra.
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['id_category'] = strip_tags($new_instance['id_category']);
        $instance['post_number'] = strip_tags($new_instance['post_number']);
        return $instance;
        //strip_tags() để cho cái form không thực thi PHP và HTML hiện ra chữ thường
    }
    function widget( $args, $instance ) {
    //$args để khai báo các giá trị thuộc tính của một widget (title, các thẻ HTML,..) và $instance là giá trị mà khách đã nhập vào form trong widget
        extract($args);//extract cái array trong widget ra thành từng biến riêng de sử dụng lại các thuộc tính bên trong widget
        //dua vao bien de lam viec voi tung bien
        $title = apply_filters( 'widget_title', $instance['title'] );//tiêu đề nên thêm filter hook cho nó để đúng chuẩn
        $id_category = $instance['id_category'];
        $post_number = $instance['post_number'];
        $link = get_term($id_category);

        echo $before_widget; //bọc nội dung widget bắt buộc
        echo '<div class="widget ask-sidebar news-sidebar">';
        echo '<div class="category-title"><a href="'.esc_url(get_term_link($link)).'"><h3>'.$title.'</h3></a></div>';
        echo '<div class="widget-content">';
            $query = new WP_Query(array('cat'=>$id_category,'order' => 'DESC','orderby' => 'date','showposts'=>$post_number));
            while ($query->have_posts() ) : $query->the_post(); ?>
                <article class="item">
                    <figure>
                        <a href="<?php the_permalink();?>">
                            <img src="<?php echo getPostImage(get_the_ID(),'thumbnail'); ?>" alt="<?php the_title();?>" />
                        </a>
                    </figure>
                    <div class="title-sidebar">
                        <a href="<?php the_permalink();?>">
                            <h4><?php the_title();?></h4>
                        </a>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata();
        echo '</div>';
        echo '</div>';
        echo $after_widget;//bọc nội dung widget bắt buộc
    }
}
function create_showpostcategory_widget() {
    register_widget('show_post_category');
}
add_action( 'widgets_init', 'create_showpostcategory_widget' );