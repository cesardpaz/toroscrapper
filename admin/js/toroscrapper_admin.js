Sequencer = function(arr, opts) {
    arr  = arr || [];
    var running = false;
    var self    = this;
    this.isRunning = function() {
      return running;
    };
    this.start = function(cb) {
      cb && self.add(cb);
      running = true;
      this.next();
    };
    this.stop = function() {
      running = false;
    };
    this.startCallback = function(cb) {
      return function() {
        self.next();
      };
    };
    this.add = function(cb) {
      arr.push(cb);
    };
    this.next = function() {
      if (running && (arr.length != 0)) {
        arr.shift()(function() {
            self.next();
          });
      }
    };
};
function sequencer(arr) {
    (function() {
        ((arr.length != 0) && (arr.shift()(arguments.callee)));
    })();
}
jQuery(document).ready(function($){
    $(document).on('click', '.add-epi', function(e){
        e.preventDefault();
        var actions = [];
		$( ".add-epi" ).each(function( index ) {
			var $this = $(this);
		    actions.push(function(callback) {
		    	var serie = $this.attr('data-serie');
		    	var tmdb  = $this.attr('data-tmdb');
		    	var season = $this.attr('data-season');
		    	$.ajax({
					url 	: toroscrapper_Admin.url,
					method 	: 'POST',
					dataType: 'json',
					data 	: {
						action: 'action_scrapper_all',
						season: season,
						tmdb  : tmdb,
						serie : serie,
					}, 
					success: function( data ) {
						console.log(data);
                        $this.text('Ready');
                        $this.addClass('blue');
						callback();
					},
					error: function(){
						console.log('error');
						callback();
					}
				});
		    });
		});
		var sequencer = new Sequencer(actions);
		sequencer.start();
    });
    $(document).on('click', '#add-players', function(e){
        e.preventDefault();
        var languages = $('#languages').val();
        var qualities = $('#qualities').val();
        var players   = $('#players').val();
        var downloads = $('#downloads').val();
        var adp  = $('#adp').prop('checked'); /* add movies */ 
        var ade1 = $('#ade1').prop('checked');
        var ade2 = $('#ade2').prop('checked');
        var ade3 = $('#ade3').prop('checked');
        var ade4 = $('#ade4').prop('checked');
        $.ajax({
            url 	: toroscrapper_Admin.url,
            method 	: 'POST',
            dataType: 'json',
            data 	: {
                action   : 'action_add_scrapper',
                languages: languages,
                qualities: qualities,
                players  : players,
                downloads: downloads,
                adp      : adp,
                ade1     : ade1,
                ade2     : ade2,
                ade3     : ade3,
                ade4     : ade4,
            }, 
            beforeSend: function(){
                console.info('cargando');
            },
            success: function( data ) {
                console.log(data);
            },
            error: function(){
                console.warn('error');
            }
        });
    })
    $(document).on('click', '#add-movies', function(e){
        e.preventDefault();
        let movies = [
            324786, 556574, 324786, 313106, 68718, 19995, 285, 206647, 49026, 49529, 559, 38757, 99861,
            767, 209112, 1452, 10764, 58, 57201, 49521, 2454, 24428, 1865, 41154, 122917, 1930, 20662,
            57158, 2268, 254, 597, 271110, 44833, 135397, 37724, 558, 68721, 12155, 36668, 62211, 8373,
            91314, 68728, 102382, 20526, 49013, 44912, 10193, 534, 168259, 72190, 127585, 54138, 81005,
            64682, 9543, 68726, 38356, 217, 105864, 62177, 188927, 10681, 5174, 14161, 17979, 76757,
            258489, 411, 246655, 155, 14160, 15512, 1726, 44826, 8487, 1735, 297761, 2698, 137113, 9804,
            14869, 150540, 278927, 10138, 58595, 102651, 119450, 79698, 64686, 100402, 10192, 158852,
            177572, 82690, 5255, 47933, 10191, 296, 118340
        ];
        let actions = [];
        movies.forEach(function(movieId) {
            actions.push(function(callback) {
                let tmdb_id = movieId;
		    	$.ajax({
					url 	: toroscrapper_Admin.url,
					method 	: 'POST',
					dataType: 'json',
					data 	: {
						action: 'action_add_movie',
                        tmdb_id: tmdb_id,
					},
					success: function( data ) {
						console.log(data);
						callback();
					},
					error: function(){
						console.log('error');
						callback();
					}
				});
		    });
        });
        var sequencer = new Sequencer(actions);
		sequencer.start();
        /* var tmdb_id = $('#movies_id_tmdb').val();
        $.ajax({
            url 	 : toroscrapper_Admin.url,
            method 	 : 'POST',
            dataType : 'json',
            data 	 : {
                action   : 'action_add_movie',
                tmdb_id  : tmdb_id,
            }, 
            beforeSend: function(){
                console.info('cargando');
            },
            success: function( data ) {
                console.log(data);
            },
            error: function(){
                console.warn('error');
            }
        }); */
    });
    $(document).on('click', '#add-series', function(e){
        e.preventDefault();
        var tmdb_id = $('#series_id_tmdb').val();
        $.ajax({
            url 	 : toroscrapper_Admin.url,
            method 	 : 'POST',
            dataType : 'json',
            data 	 : {
                action   : 'action_add_serie',
                tmdb_id  : tmdb_id,
            }, 
            beforeSend: function(){
                console.info('cargando');
            },
            success: function( data ) {
                console.log(data);
            },
            error: function(){
                console.warn('error');
            }
        });
    });
    $(document).on('click', '#insert-terms', function(e){
        e.preventDefault();
        var languages = $('#insert_languages').val();
        var qualities = $('#insert_qualities').val();
        $.ajax({
            url 	 : toroscrapper_Admin.url,
            method 	 : 'POST',
            dataType : 'json',
            data 	 : {
                action   : 'action_insert_terms',
                languages: languages,
                qualities: qualities,
            }, 
            beforeSend: function(){
                console.info('cargando');
            },
            success: function( data ) {
                console.log(data);
                location.reload();
            },
            error: function(){
                console.warn('error');
            }
        });
    });
})