!(function () {
    'use strict';

    $(document).ready(function () {
        var numOfImages = window.location.search ? parseInt(window.location.search.match(/\d+$/)[0]) : 70,
            gallery = $('#gallery'),
            videos = [];
        // Get some photos from Flickr for the demo
        var appUrl = 'http://localhost:8000/';
        var album_id = $('#album_id').val();
        $.ajax({
            url: appUrl + 'api/get-all-album-image',
            data: {
                album_id: album_id
            },
            type: 'post'
        })
            .done(function (data) {
                var loadedIndex = 1, isVideo;
                // add the videos to the collection
                var listImage = data.data;
                $.each(listImage, function (index, photo) {
                    var imageUrl = appUrl + photo.image_path,
                        img = document.createElement('img');
                    // lazy show the photos one by one
                    img.onload = function (e) {
                        img.onload = null;
                        var link = document.createElement('a'),
                            li = document.createElement('li')
                        link.href = this.largeUrl;
                        link.appendChild(this);
                        li.appendChild(link);
                        gallery[0].appendChild(li);
                        setTimeout(function () {
                            $(li).addClass('loaded');
                        }, 25 * loadedIndex++);
                    };
                    img['largeUrl'] = imageUrl;
                    img.src = imageUrl;
                });
                // finally, initialize photobox on all retrieved images
                $('#gallery').photobox('a', {thumbs: true}, callback);
                // using setTimeout to make sure all images were in the DOM, before the history.load() function is looking them up to match the url hash
                setTimeout(window._photobox.history.load, 1000);

                function callback() {
                    console.log('callback for loaded content:', this);
                }
            });
    });
})();
