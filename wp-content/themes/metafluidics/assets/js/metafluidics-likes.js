/*
* Metafluidics Single Device page scripts
*
* Handles the image carousel on the single Device post page
*/

(function(window, document, $, ajaxurl) {

  'use strict';

  // device "likes"
  var $likeButton = $('.like-this');

  $likeButton.click(function(e){
    e.preventDefault();

    var $this = $(this);
    var $likeCount = $this.children('.like-count');

    var likeData = {
      action: 'metafluidics_likes_update',
      postID : $this.data('post'),
    };

    if ( ! $this.hasClass('liked') ) {
      likeData.liked = true;
    }
    else {
      likeData.liked = false;
    }

    // send post id and like status to AJAX
    $.post( ajaxurl, likeData, function( response ) {
      var json = JSON.parse(response);

      // should have number 'likes' and boolean 'liked'
      // update count and text

      $likeCount.html( json.likes );

      if ( json.liked === 'true' ) {
        $this.addClass('liked');
      }
      else {
        $this.removeClass('liked');
      }
    });
  });

}(window, document, window.jQuery, window.ajaxurl));
