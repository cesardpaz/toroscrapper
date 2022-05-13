<div>
    <h5>Add Seasons & Episodes</h5>
    <hr>
    <div class="row">
        <div class="col s12">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Temporadas</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $args = array(
                        'post_type'           => 'series',
                        'posts_per_page'      => 100,
                        'post_status'         => 'publish',
                        'no_found_rows'       => true,
                        'ignore_sticky_posts' => true,
                    ); 
                    $the_query = new WP_Query( $args );
                    if ( $the_query->have_posts() ) :
                        $count = 1;
                        while ( $the_query->have_posts() ) : $the_query->the_post(); 

                        $tmdb = get_post_meta( get_the_ID(), 'field_id', true );
                        $contents = file_get_contents('https://api.themoviedb.org/3/tv/'.$tmdb.'?api_key=94a2f36cd4e27626b6a7a07766a76196&append_to_response=alternative_titles,credits,similar,videos&language=en');
                        $result = json_decode($contents);
                        $seasons = $result->seasons;
                        ?>
                                <tr>
                                    <td><?php echo $count; $count++; ?></td>
                                    <td><?php the_title(); ?></td>
                                    <td>
                                        <?php 
                                        $number_sea = 0;
                                        $number_epi = 0;
                                        foreach ($seasons as $key => $sea) { 
                                            if( $sea->season_number != 0 ) {
                                                $number_sea++; 
                                                $number_epi = $number_epi + $sea->episode_count;
                                            ?>
                                                <a data-serie="<?php the_ID(); ?>" data-tmdb="<?php echo $tmdb; ?>" data-season="<?php echo $sea->season_number; ?>" class="add-epi waves-effect waves-light btn-small"><?php echo $sea->name; ?></a> 
                                            <?php } 
                                        } ?>
                                    </td>
                                </tr>
                       <?php 
                            update_post_meta( get_the_ID(), 'number_of_seasons', $number_sea );
                            update_post_meta( get_the_ID(), 'number_of_episodes', $number_epi );
                       endwhile;
                    endif; wp_reset_query();  ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>