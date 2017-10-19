<?php 

namespace FP\Shortcodes;

function full_grid_out() { 
?>
<!DOCTYPE html>
<meta charset="utf-8">
<style>

.links line {
	stroke: #999;
	stroke-opacity: 0.6;
}

.nodes circle {
	stroke: #fff;
	stroke-width: 1.5px;
}

</style>
<div style="overflow: scroll; width: 100%; height: 600px">
<svg width="1960" height="1600"></svg>
</div>
<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://d3js.org/d3-zoom.v1.min.js"></script>
<script>

var svg = d3.select("svg"),
		width = +svg.attr("width"),
		height = +svg.attr("height");

var color = d3.scaleOrdinal(d3.schemeCategory20);

var simulation = d3.forceSimulation()
		.force("link", d3.forceLink().id(function(d) { return d.id; }))
		.force("charge", d3.forceManyBody().strength( -5.5))
		.force("center", d3.forceCenter(width / 2, height / 2));

var url = "/wp-admin/admin-ajax.php?action=graphjson";
d3.json(url, function(error, graph) {
	if (error) throw error;

	var link = svg.append("g")
			.attr("class", "links")
		.selectAll("line")
		.data(graph.links)
		.enter().append("line")
			.attr("stroke-width", function(d) { return d.value; })
		.attr("stroke-dasharray", function(d) { return d.dash; });

	var node = svg.append("g")
			.attr("class", "nodes")
		.selectAll("circle")
		.data(graph.nodes)
		.enter().append("circle")
			.attr("r", 5)
			.attr("fill", function(d) { return color(d.group); })
			.call(d3.drag()
					.on("start", dragstarted)
					.on("drag", dragged)
					.on("end", dragended));

	node.append("title")
			.text(function(d) { return d.title; });
	node.append("slug")
			.text(function(d) { return d.slug; });

	node.on( 'click', function( n ) { 
		window.location = '/fp_member/' + n.slug;
	});

	simulation
			.nodes(graph.nodes)
			.on("tick", ticked);

	simulation.force("link")
			.links(graph.links);

	function ticked() {
		link
				.attr("x1", function(d) { return d.source.x; })
				.attr("y1", function(d) { return d.source.y; })
				.attr("x2", function(d) { return d.target.x; })
				.attr("y2", function(d) { return d.target.y; });

		node
				.attr("cx", function(d) { return d.x; })
				.attr("cy", function(d) { return d.y; });
	}
});

function dragstarted(d) {
	if (!d3.event.active) simulation.alphaTarget(0.3).restart();
	d.fx = d.x;
	d.fy = d.y;
}

function dragged(d) {
	d.fx = d3.event.x;
	d.fy = d3.event.y;
}

function dragended(d) {
	if (!d3.event.active) simulation.alphaTarget(0);
	d.fx = null;
	d.fy = null;
}

</script>
<?php 
}

add_shortcode( 'fullgrid', __NAMESPACE__ . '\full_grid_out' );

function fp_display_person( $args ) { 
	if ( !empty( $args['id'] ) ) {	
		$post_id = intval( $args['id'] );
 	} else { 
 		$post_id = get_query_var( 'fp_member' );
 	}

 	$details = \FP\Helpers\get_person_details( $post_id );
 	if ( $details === false ) { 
 		print "Failed to get person";
 		return;
 	}
?>
<div class="fp_output_person">
	<div class="fp_person_name"><?php print esc_html( $details['post_title'] ); ?></div>
	<div class="fp_person_image">
		<?php echo $details['img']; ?>
	</div>
	<div class="fp_person_details">
		<table>
			<?php if( !empty( $details['meta']['firstname'][0] ) ) : ?>
			<tr>
				<td>First Name</td>
				<td><?php print esc_html( $details['meta']['firstname'][0] ); ?></td>
			</tr>
			<?php endif; ?>
			<?php if( !empty( $details['meta']['middlename'][0] ) ) : ?>
			<tr>
				<td>Middle Name</td>
				<td><?php print esc_html( $details['meta']['middlename'][0] ); ?></td>
			</tr>
			<?php endif; ?>
			<?php if( !empty( $details['meta']['lastname'][0] ) ) : ?>
			<tr>
				<td>Last Name</td>
				<td><?php print esc_html( $details['meta']['lastname'][0] ); ?></td>
			</tr>
			<?php endif; ?>
			<?php if( !empty( $details['meta']['maidenname'][0] ) ) : ?>
			<tr>
				<td>Maiden Name</td>
				<td><?php print esc_html( $details['meta']['maidenname'][0] ); ?></td>
			</tr>
			<?php endif; ?>
			<?php if( !empty( $details['meta']['aliasname'][0] ) ) : ?>
			<tr>
				<td>Alias/AKA</td>
				<td><?php print esc_html( $details['meta']['aliasname'][0] ); ?></td>
			</tr>
			<?php endif; ?>
			<?php if( !empty( $details['meta']['nickname'][0] ) ) : ?>
			<tr>
				<td>Nick Name</td>
				<td><?php print esc_html( $details['meta']['nickname'][0] ); ?></td>
			</tr>
			<?php endif; ?>
			<?php if( !empty( $details['meta']['gender'][0] ) ) : ?>
			<tr>
				<td>Gender</td>
				<td><?php print esc_html( $details['meta']['gender'][0] ); ?></td>
			</tr>
			<?php endif; ?>
		</table>
	</div>
	<div class="fp_person_extended">
		<?php 
			$events = \FP\helpers\get_events_aa( $details['ID'] );
		?>
		<div class="fp_person_birthdeath">
			<?php if ( is_array( $events['BIRT'] ) ) : ?>
				Date of Birth: <?php echo is_numeric( $events['BIRT']['date'] ) ? date( 'Y-m-d', $events['BIRT']['date'] ) : esc_html( $events['BIRT']['date'] ); ?><br> 
				<?php if ( !empty( $events['BIRT']['place'] ) ) : ?>
					Location: <?php echo esc_html( $events['BIRT']['place'] ); ?><br>
				<?php endif; ?>
				<?php 
			endif; 
			if ( is_array( $events['DEAT'] ) ) : ?>
				Date of Death: <?php echo is_numeric( $events['DEAT']['date'] ) ? date( 'Y-m-d', $events['DEAT']['date'] ) : esc_html( $events['DEAT']['date'] ); ?><br> 
				<?php if ( !empty( $events['DEAT']['place'] ) ) : ?>
					Location: <?php echo esc_html( $events['DEAT']['place'] ); ?><br>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div class="fp_person_marrage">
			<?php if ( is_array( $events['MARR'] ) && count( $events['MARR'] ) > 0 ) : 
			?>
				<div class="fp_marragetable">
			<?php
				foreach( $events['MARR'] as $marrid => $marr ) :
			?>
				Married: <?php echo is_numeric( $marr['date'] ) ? date( 'Y-m-d', $marr['date'] ) : esc_html( $marr['date'] ); ?><br>
				<div class="fp_marrage_to">
					<?php if ( is_array( $marr['targets'] ) && count( $marr['targets'] ) > 0 ) : ?>
						To: <a href="<?php echo esc_url( $marr['targets'][0]['url'] ); ?>"><?php echo esc_html( $marr['targets'][0]['name'] ); ?></a>
					<?php endif; ?>
					<?php if ( !empty( $marr['place'] ) ) : ?>
						Location: <?php echo esc_html( $marr['place'] ); ?><br>
					<?php endif; ?>
					<?php 
						if ( is_array( $events['DIVO'] ) && count( $events['DIVO'] ) > 0  ) :
							$divorce = '';
							foreach( $events['DIVO'] as $divo ) { 
								if ( $divo['eventtargets'][0]['id'] == $marr['id'] ) { 
									$divorce = $divo;
								}
							}

							if ( !empty( $divorce ) ) :
					?>
						<table>
							<tr>
								<div>Divorced on: <?php print date( 'd-m-Y', $divorce['date'] ); ?></div>
							</tr>
						</table>
					<?php
							endif;
						endif; 
					?>
				</div>	
			<?php 
				endforeach;
			?>
			</div>
			<?php
				endif; 
			?>
		</div>
	</div>
	<?php do_action( 'fp_person_other_events' ); ?>
	<div class="fp_person_family">
		<div class="fp_person_children">
			<span class="fp_title">Children</span>
			<?php 

		 	$children = \FP\helpers\get_children( $details['ID'] );
			if ( is_array( $children ) ) :
				foreach( $children as $vals ) :
				?>
					<div>
						<a href="<?php echo esc_url( $vals['guid'] ); ?>"><?php echo esc_html( $vals['post_title'] ); ?></a><br>
					</div>
				<?php 
				endforeach;
			endif;		 
			?>
		</div>
		<div class="fp_person_parents">
			<span class="fp_title">Parents</span>
			<?php

		 	$parents = \FP\helpers\get_parents( $details['ID'] );

			if ( is_array( $parents ) ) :
			 	foreach( $parents as $vals ) :
				?>
					<div>
						<a href="<?php echo esc_url( $vals['guid'] ); ?>"><?php echo esc_html( $vals['post_title'] ); ?></a><br>
					</div>
			<?php 
				endforeach;
			endif;
		?>
		</div>
		<div class="fp_person_siblings">
			<span class="fp_title">Siblings</span>
			<?php
		 	$siblings = \FP\helpers\get_siblings( $details['ID'] );
		 	if ( is_array( $siblings ) ) :
				foreach( $siblings as $vals ) :
				?>
					<div>
						<a href="<?php echo esc_url( $vals['guid'] ); ?>"><?php echo esc_html( $vals['post_title'] ); ?></a><br>
					</div>
				<?php 
				endforeach;
			endif;
 			?>
		</div>
	</div>
</div>
<?php
}

add_shortcode( 'fp_display_person', __NAMESPACE__ . '\fp_display_person' );


function fp_map_people() { 
?>


<?php
}

add_shortcode( 'fp_map_people', __NAMESPACE__ . '\fp_map_people' );
