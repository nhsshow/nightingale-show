<?php
/**
 * Template Name: Display Subpage Promo Blocks with Sidebar
 * The template for displaying all subpages as a 2 column Promo Blocks (under the content) with a sidebar
 *
 * This is the template that displays the subpages of a parent page and utilises the Promo class to display the title and excerpt. It also displays a sidebar.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Nightingale
 * @copyright SHOW Team, OT
 * @version 1.0.2 20th April 2023
 */

get_header();
flush();

function remove_excerpt_ellipsis($more) {
	return "";
}
add_filter("excerpt_more", "remove_excerpt_ellipsis");
?>
<div id="primary" class="nhsuk-grid-row">
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
		if (count($child_pages) > 1) {
			$class_name = "wp-block-nhsblocks-onehalfpro nhsuk-grid-column-one-half nhsuk-card-group__item";
		} elseif (count($child_pages) < 1) {
			$class_name = "wp-block-nhsblocks-onefull nhsuk-grid-column-full nhsuk-card-group__item";
		}
	?>

	<div class="nhsuk-grid-column-two-thirds page <?=nightingale_sidebar_location("sidebar-1"); ?>">
		<header class="entry-header"><h1 class="entry-title"><?=get_the_title($current_page_id); ?></h1></header>
		<?=get_the_content($current_page_id); ?>
		<?php
			$child_pages = get_children($args);
			if ($child_pages) {
				foreach ($child_pages as $child_page) {
					$excerpt = get_the_excerpt($child_page->ID);
					$excerpt = apply_filters("wp_trim_excerpt", $excerpt);
		?>
							<div class="CPS <?=$class_name; ?>">
								<div class="wp-block-nhsblocks-promo1 nhsuk-card nhsuk-card--clickable is-style-default">
								<?php
					/*
						if ( has_post_thumbnail( $child_page->ID ) ) : ?>
							<figure class="wp-block-image size-full is-resized">
								<a href="<?=get_permalink( $child_page->ID ); ?>"><?=get_the_post_thumbnail( $child_page->ID ); ?></a>
							</figure>
							<?php endif;
						*/
					?>

					<div class="nhsuk-card__content">
						<h2 class="nhsuk-card__heading nhsuk-heading-m">
						<a class="nhsuk-card__link" href="<?=get_permalink($child_page->ID)?>"><?=get_the_title($child_page->ID); ?></a>
					</h2>
										<div class="nhsuk-card__description">
											<?=$excerpt; ?>
										</div>
									</div>
								</div>
							</div>

				<?php
				}
			}
			?>
	</div>


	<div class="nhsuk-grid__item nhsuk-grid-column-one-third">
		<aside id="secondary" class="widget-area nhsuk-width-container">
			<?php dynamic_sidebar("sidebar-1"); ?>
	</aside>
	</div>
</div>

<script>
	let cardContents = document.getElementsByClassName('CPS');
	let maxHeight = 0;

	for (let i = 0; i < cardContents.length; i++) {
		if (cardContents[i].offsetHeight > maxHeight) {
			maxHeight = cardContents[i].offsetHeight;
		}
	}

	for (let i = 0; i < cardContents.length; i++) {
		cardContents[i].style.minHeight = maxHeight + 'px';
	}
</script>

<?php
flush();
get_footer();
