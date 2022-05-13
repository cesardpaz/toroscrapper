<?php 

/* Get qualitites */
$quals    = array();
$qualitys = get_terms( array(
    'taxonomy'   => 'quality',
    'hide_empty' => false,
) );
if ( ! empty( $qualitys ) && ! is_wp_error( $qualitys ) ){
    foreach( $qualitys as $qua ){
        $id      = $qua->term_id;
        $quals[] = $id;
    }
}

/* Get langs */
$langs  = array();
$langes = get_terms( array(
    'taxonomy'   => 'language',
    'hide_empty' => false,
) );

if ( ! empty( $langes ) && ! is_wp_error( $langes ) ){
    foreach( $langes as $lan ){
        $id      = $lan->term_id;
        $langs[] = $id;
    }
}
                          

?>


<div>


    <h5>Insert Players Movies</h5>
    <p>All data separated by dots</p>
    <hr>


    <div class="row">
        <div class="col s12">
            <div class="row">
                <h6>Insert languages y qualities </h6>
            </div>
        </div>

        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">edit</i>
                    <input type="text" id="insert_languages" class="autocomplete" value="">
                    <label for="insert_languages">Insert Languages</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">edit</i>
                    <input type="text" id="insert_qualities" class="autocomplete" value="">
                    <label for="insert_qualities">Insert Qualities</label>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="row">
                <a id="insert-terms" class="waves-effect waves-light btn">Insert</a>
            </div>
        </div>


    </div>



    <hr>


    <div class="row">
        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">language</i>
                    <input type="text" id="languages" class="autocomplete" value="<?php echo implode(',', $langs); ?>">
                    <label for="languages">Languages</label>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">textsms</i>
                    <input type="text" id="qualities" class="autocomplete" value="<?php echo implode(',', $quals); ?>">
                    <label for="qualities">Qualities</label>
                </div>
            </div>
        </div>


        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">cast</i>
                    <textarea id="players" class="materialize-textarea" cols="30" rows="6"></textarea>
                    <label for="players">Players</label>
                </div>
            </div>
        </div>

        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <i class="material-icons prefix">download</i>
                    <textarea id="downloads" class="materialize-textarea" cols="30" rows="6"></textarea>
                    <label for="downloads">Download</label>
                </div>
            </div>
        </div>


        <div class="col s12">
            <div class="row">
                <div class="input-field col s12">
                    <p>
                        <label>
                            <input id="adp" type="checkbox" />
                            <span>Add Players Movies</span>
                        </label>
                    </p>

                    <p>
                        <label>
                            <input id="ade1" type="checkbox" />
                            <span>Add Players Episodes (0-400)</span>
                        </label>
                    </p>

                    <p>
                        <label>
                            <input id="ade2" type="checkbox" />
                            <span>Add Players Episodes (400-800)</span>
                        </label>
                    </p>

                    <p>
                        <label>
                            <input id="ade3" type="checkbox" />
                            <span>Add Players Episodes (800-1200)</span>
                        </label>
                    </p>

                    <p>
                        <label>
                            <input id="ade4" type="checkbox" />
                            <span>Add Players Episodes (1200-1600)</span>
                        </label>
                    </p>

                </div>
            </div>
        </div>


        <div class="col s12">
            <div class="row">
                <a id="add-players" class="waves-effect waves-light btn">Add Players</a>
            </div>
        </div>


    </div>


</div>