<?php 
class TOROSCRAPPER_Ajax_Admin {

    public function insert_terms() {
        if( isset( $_POST[ 'action' ] ) ) {
            
            $languages = $_POST['languages'];
            $qualities = $_POST['qualities'];
            

            $langs = explode(',', $languages);
            $quals = explode(',', $qualities);


            foreach ($langs as $key => $lang) {
                wp_insert_term(
                    $lang,
                    'language',
                );
            }

            foreach ($quals as $key => $qual) {
                wp_insert_term(
                    $qual,
                    'quality',
                );
            }

    
            $res = [
              'res' => 'conexion'
            ];
            echo json_encode($res);
            wp_die();
        }
    }

    public function add_movie() {
        if( isset( $_POST[ 'action' ] ) ) {
            $tmdb_id_l = $_POST['tmdb_id'];
            $tmdb_id   = explode( "\n", str_replace( "\r", "", $tmdb_id_l ) );

            foreach($tmdb_id as $k => $tmdb) {
                
                $contents = file_get_contents('https://api.themoviedb.org/3/movie/'.$tmdb.'?api_key=94a2f36cd4e27626b6a7a07766a76196&append_to_response=alternative_titles,credits,similar,videos&language=en');
                $cosa = $contents;
                $result         = json_decode($contents);
                $title          = $result->title;
                $original_title = $result->original_title;
                $date           = $result->release_date;
                if($date) {
                    $date_ar = explode('-', $date);
                    $year = $date_ar[0];
                }

                $description    = $result->overview;
                $runtime        = $result->runtime;
                $runtimes       = convertToHoursMins($runtime);

                $poster         = $result->poster_path;
                $backdrop       = $result->backdrop_path;
                $trailer        = $result->videos->results[0]->key;
                $trailer_iframe = '<iframe width="1280" height="720" src="https://www.youtube.com/embed/'.$trailer.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                $vote_average   = $result->vote_average;
                $vote_count     = $result->vote_count;
                $imdb           = $result->imdb_id;

                $countries      = $result->production_countries;
                foreach ($countries as $cou) {
                    $country[] = $cou->name;
                }

                $categories = $result->genres;
                $category   = array();
                foreach ($categories as $categorie) {
                    $category[] = $categorie->name;
                }

                $actors = $result->credits->cast;
                $actor  = array();
                foreach ($actors as $act) {
                    $actor[] = $act->name;
                }

                $directors = $result->credits->crew;
                $director  = array();
                foreach ($directors as $dir) {
                    if($dir->job == 'Director')
                        $director[] = $dir->name;
                }


                $new_post = array(
                    'ID'            => '',
                    'post_title'    => $title,
                    'post_content'  => $description,
                    'post_status'   => 'publish',
                    'post_type'     => 'movies',
                    'meta_input'    =>
                        array(
                            'backdrop_hotlink'   => 'https://image.tmdb.org/t/p/w1066_and_h600_bestv2' . $backdrop,
                            'field_date'         => $date,
                            'field_id'           => $tmdb,
                            'field_imdbid'       => $imdb,
                            'field_release_year' => $date,
                            'field_runtime'      => $runtimes,
                            'field_title'        => $original_title,
                            'field_trailer'      => $trailer_iframe,
                            'poster_hotlink'     => 'https://image.tmdb.org/t/p/w500' . $poster,
                            'rating'             => $vote_average
                        ),
                    );
                $post_id = wp_insert_post($new_post);


                


                wp_set_object_terms( $post_id, $category, 'category', true );
                wp_set_object_terms( $post_id, $actor, 'cast', true );
                wp_set_object_terms( $post_id, $director, 'directors', true );
                wp_set_object_terms( $post_id, $country, 'country', true );
                wp_set_object_terms( $post_id, $year, 'annee', true );
            }
            $res = [
                'res'   => 'conexion',
                'title' => $result,
                'tmd' => $tmdb_id,
            ];

            echo json_encode($res);
            wp_die();
        }
    }


    public function scrapper_all() {
        if( isset( $_POST[ 'action' ] ) ) {
    
            $season = $_POST['season'];
            $tmdb   = $_POST['tmdb'];
            $serie  = $_POST['serie'];

            $contents = file_get_contents('https://api.themoviedb.org/3/tv/'.$tmdb.'/season/'.$season.'?api_key=94a2f36cd4e27626b6a7a07766a76196&append_to_response=alternative_titles,credits,similar,videos&language=en-US');
            $result   = json_decode($contents);

            $name = get_the_title($serie);

            /* Season */
            $season_title           = $result->name;
            $season_poster          = $result->poster_path;
            $season_number          = $result->season_number;
            $season_description     = $result->overview;
            $season_airdate         = $result->air_date;
            $season_id              = $result->id;
            $season_number_episodes = count($result->episodes);
            $episodes = $result->episodes;

            remove_action( 'create_seasons', 'save_seasons_custom_meta', 10, 2 );
            remove_action( 'edited_seasons', 'save_seasonsedit_custom_meta', 10, 2 );
            remove_action( 'create_episodes', 'save_episodes_custom_meta', 10, 2 );
            remove_action( 'edited_episodes', 'save_episodesedit_custom_meta', 10, 2 );

            $insert_season = wp_insert_term($name. ' - Season ' .$season_number, 'seasons' );
            wp_set_object_terms( $serie, $insert_season, 'seasons', true);
            update_term_meta( $insert_season['term_id'], 'tr_id_post', $serie );
            update_term_meta( $insert_season['term_id'], 'season_number', $season_number );
            update_term_meta( $insert_season['term_id'], 'name', $season_title );
            update_term_meta( $insert_season['term_id'], 'overview', $season_description );
            update_term_meta( $insert_season['term_id'], 'id', $season_id );
            update_term_meta( $insert_season['term_id'], 'poster_path_hotlink', $season_poster );
            update_term_meta( $insert_season['term_id'], 'number_of_episodes', $season_number_episodes );
            update_term_meta( $insert_season['term_id'], 'air_date', $season_airdate );


            foreach ($episodes as $key => $epi) {
                $episode_name     = $epi->name;
                $episode_id       = $epi->id;
                $episode_desc     = $epi->overview;
                $episode_poster   = $epi->still_path;
                $episode_number   = $epi->episode_number;
                $episode_air_date = $epi->air_date;
                $episode_actors   = $epi->guest_stars;
                $actor_array      = false;
                $episode_actor    = '';
                foreach ($episode_actors as $key => $act) {
                    $actor_array[] = $act->name;
                }
                if($actor_array) $episode_actor = implode(', ', $actor_array);

                $n = $name . ' ' . $season_number . 'x' . $episode_number;
                $insert_episode = wp_insert_term($n, 'episodes' );
                update_term_meta( $insert_episode['term_id'], 'tr_id_post', $serie );
                update_term_meta( $insert_episode['term_id'], 'season_number', $season_number );
                update_term_meta( $insert_episode['term_id'], 'episode_number', $episode_number );
                update_term_meta( $insert_episode['term_id'], 'air_date', $episode_air_date );
                update_term_meta( $insert_episode['term_id'], 'name', $episode_name );
                update_term_meta( $insert_episode['term_id'], 'overview', $episode_desc );
                update_term_meta( $insert_episode['term_id'], 'id', $episode_id );
                update_term_meta( $insert_episode['term_id'], 'still_path_hotlink', $episode_poster );
                update_term_meta( $insert_episode['term_id'], 'guest_stars', $episode_actor );
                wp_set_object_terms($serie, $insert_episode, 'episodes', true);

            }

            $res = [
              'res' => 'conexion'
            ];
            echo json_encode($res);
            wp_die();
        }
    }


    public function add_serie() {
        if( isset( $_POST[ 'action' ] ) ) {
            $tmdb_id_l = $_POST['tmdb_id'];
            $tmdb_id   = explode( "\n", str_replace( "\r", "", $tmdb_id_l ) );

            foreach($tmdb_id as $k => $tmdb) {
                
                $contents = file_get_contents('https://api.themoviedb.org/3/tv/'.$tmdb.'?api_key=94a2f36cd4e27626b6a7a07766a76196&append_to_response=alternative_titles,credits,similar,videos&language=en');
                $result         = json_decode($contents);
                $title          = $result->name;
                $original_title = $result->original_name;
                $date           = $result->first_air_date; // lanzamiento
                $last_date      = $result->last_air_date;
                if($date) {
                    $date_ar = explode('-', $date);
                    $year = $date_ar[0];
                }

                $description    = $result->overview;
                $runtime        = $result->episode_run_time[0];
                $runtimes       = $runtime;

                $poster         = $result->poster_path;
                $backdrop       = $result->backdrop_path;
                $trailer        = $result->videos->results[0]->key;
                $trailer_iframe = '';
                if($trailer){
                    $trailer_iframe = '<iframe width="1280" height="720" src="https://www.youtube.com/embed/'.$trailer.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                }
                $vote_average   = $result->vote_average;
                $vote_count     = $result->vote_count;
                $status         = $result->status;
               

                $inproduction = $result->in_production;  //1 active 2 inactive

                if($inproduction) { $inpro = 1; } else { $inpro = 2; }

                $categories = $result->genres;
                $category   = array();
                foreach ($categories as $categorie) {
                    $category[] = $categorie->name;
                }

                $actors = $result->credits->cast;
                $actor  = array();
                foreach ($actors as $act) {
                    $actor[] = $act->name;
                }

                $directors = $result->credits->crew;
                $director  = array();
                foreach ($directors as $dir) {
                    if($dir->job == 'Director')
                        $director[] = $dir->name;
                }

                $new_post = array(
                    'ID'            => '',
                    'post_title'    => $title,
                    'post_content'  => $description,
                    'post_status'   => 'publish',
                    'post_type'     => 'series',
                    'meta_input'    =>
                        array(
                            'backdrop_hotlink'   => $backdrop,
                            'field_date'         => $date,
                            'field_date_last'    => $last_date,
                            'field_id'           => $tmdb,
                            'field_inproduction' => $inpro,
                            'field_title'        => $original_title,
                            'field_trailer'      => $trailer_iframe,
                            'poster_hotlink'     => $poster,
                            'rating'             => $vote_average,
                            'status'             => $status,
                            'tr_post_type'       => 2,
                            'field_runtime'      => $runtimes,
                        ),
                    );
                $post_id = wp_insert_post($new_post);

                wp_set_object_terms( $post_id, $category, 'category', true );
                wp_set_object_terms( $post_id, $actor, 'cast_tv', true );
                wp_set_object_terms( $post_id, $director, 'directors_tv', true );
                wp_set_object_terms( $post_id, $year, 'annee', true );

            }
            $res = [
                'res'   => 'conexion',
                'title' => $result,
                'tmd' => $tmdb_id,
            ];

            echo json_encode($res);
            wp_die();
        }
    }


    /* PLAYERS */
    public function add_scrapper() {
        if( isset( $_POST[ 'action' ] ) ) {

            $adp  = $_POST['adp'];
            $ade1 = $_POST['ade1'];
            $ade2 = $_POST['ade2'];
            $ade3 = $_POST['ade3'];
            $ade4 = $_POST['ade4'];

            $languages = $_POST['languages'];
            $qualities = $_POST['qualities'];
            $players   = $_POST['players'];
            $downloads = $_POST['downloads'];
            $langs = explode(',', $languages);
            $quals = explode(',', $qualities);
            $plays  = explode("\n", str_replace("\r", "", $players));
            $downds = explode("\n", str_replace("\r", "", $downloads));
            $link = array();
            foreach($plays as $t => $pl) {
                if($pl != '')
                    $link[] = array('url' => $pl, 'type' => 1);
            }
            foreach($downds as $t => $pl) {
                if($pl != '')
                    $link[] = array('url' => $pl, 'type' => 2);
            }
            $count_total = count($plays) + count($downds);
            //servers
            if(!get_term_by('name', 'youtube', 'server')){
				$ser1 = wp_insert_term(
					'youtube',
					'server'
				);
				$sert1 = $ser1['term_id'];
			} else {
				$ser1 = get_term_by('name', 'youtube', 'server');
				$sert1 = $ser1->term_id;
			}
			
			if(!get_term_by('name', 'openload', 'server')){
				$ser2 = wp_insert_term(
					'openload',
					'server'
				);
				$sert2 = $ser2['term_id'];
			} else {
				$ser2 = get_term_by('name', 'openload', 'server');
				$sert2 = $ser2->term_id;
			}
			if(!get_term_by('name', 'vimeo', 'server')){
				$ser3 = wp_insert_term(
					'vimeo',
					'server'
				);
				$sert3 = $ser3['term_id'];
			} else {
				$ser3  = get_term_by('name', 'vimeo', 'server');
				$sert3 = $ser3->term_id;
			}
			$servers = array($sert1, $sert2, $sert3);
          
            if($adp == "true"){
                /* movies */
                $args = array(
                    'post_type'           => 'movies',
                    'posts_per_page'      => 100,
                    'post_status'         => 'publish',
                ); 
                $the_query = new WP_Query( $args );
                if ( $the_query->have_posts() ) :
                    while ( $the_query->have_posts() ) : $the_query->the_post();
                        $post_id     = get_the_ID();
                        $trailer     = htmlspecialchars_decode(get_post_meta( $post_id, 'field_trailer', true ));
                        $links_total = count($plays);
                        if($trailer) 
                        {
                            $ab = array('url' => $trailer, 'type' => 1);
                            $kl = $link;
                            array_unshift($kl, $ab);
                        }
                        foreach ($kl as $index => $pl)
                        {
                            $array_links[$index] = array(
                                'type'    => $pl['type'],
                                'server'  => $servers[ array_rand($servers, 1) ],
                                'lang'    => $langs[ array_rand($langs, 1) ],
                                'quality' => $quals[ array_rand($quals, 1) ],
                                'link'    => base64_encode ( stripslashes( esc_textarea( $pl['url'] ) ) ),
                                'date'    => date('d').'/'.date('m').'/'.date('Y'),
                            );
                            update_post_meta( $post_id, 'trglinks_'.$index, serialize( $array_links[$index] ) );
                        }
                        update_post_meta( $post_id, 'trgrabber_tlinks', count($kl) );
                    endwhile;
                endif; wp_reset_query();
            }

            if($ade1 == "true" or $ade2 ="true" or $ade3 ="true" or $ade4 ="true" ){

                if($ade1 == 'true'){
                    $off = 0;
                }
                if($ade2 == 'true'){
                    $off = 400;
                }
                if($ade3 == 'true'){
                    $off = 800;
                }
                if($ade4 == 'true'){
                    $off = 1200;
                }

               
                /* EPISODES */
                $episodes =  get_terms( 'episodes',  array(
                  
                    'number'   => 400,
                    'offset'   => $off,
                    'hide_empty' =>false,
                ) );
                foreach( $episodes as $eps ){
                    $bbb= 12;
                    $id           = $eps->term_id;
                    $serie_parent = get_term_meta( $id, 'tr_id_post', true );
                    $trailer      = get_post_meta( $serie_parent, 'field_trailer', true );
                    if($trailer) {
                        $abc = array('url' => $trailer, 'type' => 1);
                        $lnk = $link;
                        array_unshift($lnk, $abc);
                    }
                    $count = 0;
                    $a[] = $id;
                    foreach ($lnk as $ind => $pl) {
                        $t =12;
                        $array_linkss[$count] = array(
                            'type'    => $pl['type'],
                            'server'  => $servers[array_rand($servers, 1)],
                            'lang'    => $langs[array_rand($langs, 1)],
                            'quality' => $quals[array_rand($quals, 1)],
                            'link'    => base64_encode ( stripslashes( esc_textarea( $pl['url'] ) ) ),
                            'date'    => date('d').'/'.date('m').'/'.date('Y'),
                        );  
                        update_term_meta( $id, 'trglinks_'.$count, serialize( $array_linkss[$count] ) );
                        $count++;
                    }
                    update_term_meta( $id, 'trgrabber_tlinks', count( $lnk ) );
                }
            }


            $res = [
              'res'      => 'conexion',
              'langs'    => $langs,
              'quals'    => $quals,
              'plays'    => $plays,
              'downds'   => $downds,
              'link'     => $link,
              'episodes' => count($episodes),
              't'        => $count,
              'off' => $off,
              'bbb' => $bbb,
              'trailer' => $trailer,
              'serie_parent' => $serie_parent,
            
            ];
            echo json_encode($res);
            wp_die();
        }
    }
   
}