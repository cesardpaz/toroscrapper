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
        var tmdb_id = $('#movies_id_tmdb').val();
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
        });

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