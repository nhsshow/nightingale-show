<?php
/**
 * Template Name: Display Subpage Promo Blocks
 * The template for displaying all pages
 *
 * This is the template that displays the subpages of a parent page and utalises the Promo class to display the title and excerpt.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nightingale
 * @copyright SHOW Team, OT
 * @version 1.0 18th April 2023
 */

get_header();

flush();

function remove_excerpt_ellipsis($more)
{
    return "";
}
add_filter("excerpt_more", "remove_excerpt_ellipsis");
?>

<div id="primary" class=" nhsuk-grid-row">

	<?php
 $current_page_id = get_the_ID();
 $args = [
     "post_parent" => $current_page_id,
     "post_type" => "page",
     "orderby" => "menu_order",
     "order" => "ASC",
     "post_status" => "publish",
 ];
 $child_pages = get_children($args);
 if (count($child_pages) % 2 !== 0 || count($child_pages) > 5) {
     $class_name =
         "wp-block-nhsblocks-onethirdpro nhsuk-grid-column-one-third nhsuk-card-group__item";
 } else {
     $class_name =
         "wp-block-nhsblocks-onehalfpro nhsuk-grid-column-one-half nhsuk-card-group__item";
 }
 ?>

	<header class="entry-header">
		<h1 class="entry-title"> <?php echo get_the_title($current_page_id); ?> </h1>
	</header>
	<div class="entry-content"> <?php echo get_the_content(
     $current_page_id
 ); ?></div>

	<div class="nhsuk-grid-column-full full-width">

		<?php
  $child_pages = get_children($args);

  if ($child_pages) {
      foreach ($child_pages as $child_page) {

          $excerpt = get_the_excerpt($child_page->ID);
          $excerpt = apply_filters("wp_trim_excerpt", $excerpt);
          ?>
					<div class="CPS <?php echo $class_name; ?>">
						<div class="wp-block-nhsblocks-promo1 nhsuk-card nhsuk-card--clickable is-style-default">
						<?php
          /*
						if ( has_post_thumbnail( $child_page->ID ) ) : ?>
									<figure class="wp-block-image size-full is-resized">
										<a href="<?php echo get_permalink( $child_page->ID ); ?>"><?php echo get_the_post_thumbnail( $child_page->ID ); ?></a>
									</figure>
								<?php endif; 
						*/
          ?>
							<div class="nhsuk-card__content">								
								<h2 class="nhsuk-card__heading nhsuk-heading-m"><a class="nhsuk-card__link" href="<?php echo get_permalink(
            $child_page->ID
        ); ?>"><?php echo get_the_title($child_page->ID); ?></a></h2>
								<div class="nhsuk-card__description">
									<?php echo $excerpt; ?>
								</div>
							</div>
						</div>
					</div>
					
					<?php
      }
  }
  ?> 
	</div>

</div>

<script>
	// Get all the elements with class 'nhsuk-card__content'
var cardContents = document.getElementsByClassName('CPS');

// Variable to store the maximum height
var maxHeight = 0;

// Loop through each element and find the maximum height
for (var i = 0; i < cardContents.length; i++) {
  if (cardContents[i].offsetHeight > maxHeight) {
    maxHeight = cardContents[i].offsetHeight;
  }
}

// Set the minimum height for all elements to the maximum height
for (var i = 0; i < cardContents.length; i++) {
  cardContents[i].style.minHeight = maxHeight + 'px';
}
</script>

<?php
flush();
get_footer();

?>
