// Добавляем новый фильтр в раздел "товары" с/без фото
add_action('restrict_manage_posts', 'filter_products_by_photo');
function filter_products_by_photo() {
    global $typenow;
    if ($typenow === 'product') {
        echo '<select name="product_photo" id="dropdown_product_photo">';
        echo '<option value="">' . __('Фильтр фото', 'woocommerce') . '</option>';
        echo '<option value="has_photo" ' . (isset($_GET['product_photo']) && $_GET['product_photo'] === 'has_photo' ? 'selected' : '') . '>' . __('С фото', 'woocommerce') . '</option>';
        echo '<option value="no_photo" ' . (isset($_GET['product_photo']) && $_GET['product_photo'] === 'no_photo' ? 'selected' : '') . '>' . __('Без фото', 'woocommerce') . '</option>';
        echo '</select>';
    }
}

add_filter('parse_query', 'filter_products_by_photo_query');
function filter_products_by_photo_query($query) {
    global $pagenow, $typenow;
    if ($pagenow === 'edit.php' && $typenow === 'product' && isset($_GET['product_photo'])) {
        $meta_query = array();
        if ($_GET['product_photo'] === 'has_photo') {
            $meta_query[] = array(
                'key' => '_thumbnail_id',
                'compare' => 'EXISTS',
            );
        } elseif ($_GET['product_photo'] === 'no_photo') {
            $meta_query[] = array(
                'key' => '_thumbnail_id',
                'compare' => 'NOT EXISTS',
            );
        }
        $query->set('meta_query', $meta_query);
    }
}
