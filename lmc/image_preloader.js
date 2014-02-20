// Image Preloader  v1.0
// http://www.dithered.com/javascript/image_preloader/index.html
// code by Chris Nott (chris@NOSPAMdithered.com - remove NOSPAM)

function preloadImages() {
	document.preload = new Array();
	if (document.images) {
		for (var i = 0; i < preloadImages.arguments.length; i++) {
			document.preload[i] = new Image();
			document.preload[i].src = preloadImages.arguments[i];
		}
	}
}