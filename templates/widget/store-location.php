<?php
/**
 * The template for displaying demo plugin content.
 *
 * Override this template by copying it to yourtheme/dc-product-vendor/widget/store-location.php
 *
 * @author 		WC Marketplace
 * @package 	dc-product-vendor/Templates
 * @version     0.0.1
 */
extract( $instance );
global $WCMp;

?>
<div class="wcmp-store-location-wrapper">
<?php 
if(!empty($store_lat) && !empty($store_lng)) : ?>
    <div id="store-maps" class="store-maps" class="wcmp-gmap" style="height: 200px;"></div>
    <?php
    if (wcmp_mapbox_api_enabled()) {
        $WCMp->library->load_mapbox_api();
        $map_style = apply_filters( 'wcmp_store_location_widget_map_style', 'mapbox://styles/mapbox/streets-v11');
        ?>
        <script>
            mapboxgl.accessToken = '<?php echo wcmp_mapbox_api_enabled(); ?>';
            var map = new mapboxgl.Map({
                container: 'store-maps', // container id
                style: '<?php echo $map_style ?>',
                center: [<?php echo $store_lat ?>, <?php echo $store_lng ?>],
                zoom: 5
            });
            var marker1 = new mapboxgl.Marker()
            .setLngLat([<?php echo $store_lat ?>, <?php echo $store_lng ?>])
            .addTo(map);
            map.addControl(new mapboxgl.NavigationControl());
            map.addControl(new mapboxgl.FullscreenControl());
            // current location fetch
            map.addControl(
                new mapboxgl.GeolocateControl({
                    positionOptions: {
                        enableHighAccuracy: true
                    },
                    trackUserLocation: true
                })
            );
        </script>
        <?php
    } else {
        wp_add_inline_script( 'wcmp-gmaps-api', 
          '(function ($) {
            var myLatLng = {lat: '.$store_lat.', lng: '.$store_lng.'};
            var map = new google.maps.Map(document.getElementById("store-maps"), {
                zoom: 15,
                center: myLatLng
            });
            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: "'.$location.'"
            });
        })(jQuery);');
    }
endif; ?>
    <?php if (!wcmp_mapbox_api_enabled()) { ?>
        <a href="<?php echo esc_url($gmaps_link); ?>" target="_blank"><?php esc_html_e( 'Show in Google Maps', 'dc-woocommerce-multi-vendor' ) ?></a>
    <?php } ?>
</div>
