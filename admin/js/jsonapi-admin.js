(function($) {
	$(document).on( 'click', 'a.userid', function( event ) {
		event.preventDefault();
		// Ajax get Todos
		$.ajax({
			async: false, //will wait for all the  other api calls to finish first.!important
		 	type: "GET",
		    url: jsonapi.ajaxurl,
		    data: {
            'action': 'get_user_todos', 'userid':jQuery(this).data('userid'),
        	},
		    success: function(response){
		        //console.log(response);
		        var todos = '<ul class="wrap todos"><h1> Todo: </h1>';
		        var items = [];
		        $.each( response.data, function( index, todo ) {
		        	//console.log(todo);
				   todos += "<li id='" + todo.id + "'><h4>" + todo.title + "<h4>";
				   todos += "<p>" + todo.completed + "</p>";
				   todos += "</li>";
				 });
		        todos += '</ul>';
		        //console.log(todos);
		        jQuery('.todos').html(todos);
			  	},
			   error: function(errorThrown){
	            console.log('errorThrown');
	        	}
    	}); 
		// Ajax get posts
		$.ajax({
			async: false,
		 	type: "GET",
		    url: jsonapi.ajaxurl,
		    data: {
            'action': 'get_user_posts', 'userid':jQuery(this).data('userid'),
        	},
		    success: function(response){
		        //console.log(response);
		        var postLists = '<ul class="wrap postlist"><h1> User Posts: </h1>';
		        var items = [];
		        $.each( response.data, function( index, post ) {
		        	//console.log(post);
		        	postLists += "<li id='" + post.id + "'><h2>" + post.title + "<h2>";
		        	postLists += "<p>" + post.body + "</p></hr></br>";
				   	postLists += "<details><summary data-postid='" + post.id + "' class='commentclass' >Comments</summary>";
				   	postLists += "<div class='commentsarea'></div>";
				   	postLists += "</details></li>";
				 });
		        postLists += '</ul>';
		        //console.log(postLists);
		        jQuery('.postlist').html(postLists);
			  	},
			   error: function(errorThrown){
	            console.log('errorThrown');
	        	}
    	}); 
		// Ajax get Albums
		$.ajax({
			async: false,
		 	type: "GET",
		    url: jsonapi.ajaxurl,
		    data: {
            'action': 'get_user_albums', 'userid':jQuery(this).data('userid'),
        	},
		    success: function(response){
		        //console.log(response);
		        var albums = '<ul class="wrap albums"><h1> Albums </h1>';
		        var items = [];
		        $.each( response.data, function( index, album ) {
		        	//console.log(response.data);
		        	var albumID = album.id
		        	//console.log(albumID);
		        	albums += "<li><details><summary data-albumid='" +album.id + "' class='albumclass'>"+ album.title + "</summary>"
		        	albums += "<div class='photoz'></div>";
				   	albums += "</details></li>";
				 });
		        albums += '</ul>';
		        jQuery('.albums').html(albums);
			  	},
			   error: function(errorThrown){
	            console.log('errorThrown');
	        	}
    	}); 
    })

	$(document).on( 'click', 'summary.albumclass', function( event ) {
		$.ajax({
			//async: false,
		 	type: "GET",
		    url: jsonapi.ajaxurl,
		    data: {
            'action': 'get_photos', 'albumid':jQuery(this).data('albumid'),
        	},
		    success: function(response){
		        //console.log(response);
		        var photos = '<ul class="wrap photos">';
		        var items = [];
		        $.each( response.data, function( index, photo ) {
		        	//console.log(response.data);	        
		        	photos += "<li id='" + photo.id + "'>";
		        	photos += "<a href='" + photo.url +"' target='_blank' >" +photo.title+ "</a>";
				   	photos += "</li>";
		        	
		        	//console.log(photo);

				 });
		        photos += '</ul>';
		        jQuery('.photoz').html(photos);
			  	},
			   error: function(errorThrown){
	            console.log('errorThrown');
	        	}
    	}); 
	})

	$(document).on( 'click', 'summary.commentclass', function( event ) {
		$.ajax({
			//async: false,
		 	type: "GET",
		    url: jsonapi.ajaxurl,
		    data: {
            'action': 'get_comments', 'postid':jQuery(this).data('postid'),
        	},
		    success: function(response){
		        console.log(response);
		        var comments = '<ul class="wrap comments">';
		        $.each( response.data, function( index, comment ) {
		        	console.log(response.data);	        
		        	comments += "<li id='" + comment.id + "'>";
		        	comments += "<span><b>Name:</b>"+ comment.name +"</span><br/>";
		        	comments += "<span><b>comment:</b>"+ comment.body +"</span>";
				   	comments += "</li>";
		        	
		        	//console.log(photo);

				 });
		        comments += '</ul>';
		        jQuery('.commentsarea').html(comments);
			  	},
			   error: function(errorThrown){
	            console.log('errorThrown');
	        	}
    	}); 
	})

})(jQuery); 