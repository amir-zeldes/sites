/*
	TXT by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
*/

(function($) {

	var	$window = $(window),
		$body = $('body'),
		$nav = $('#nav');

	// Breakpoints.
		breakpoints({
			xlarge:  [ '1281px',  '1680px' ],
			large:   [ '981px',   '1280px' ],
			medium:  [ '737px',   '980px'  ],
			small:   [ '361px',   '736px'  ],
			xsmall:  [ null,      '360px'  ]
		});

	// Play initial animations on page load.
		$window.on('load', function() {
			window.setTimeout(function() {
				$body.removeClass('is-preload');
			}, 100);
		});

	// Dropdowns.
		$('#nav > ul').dropotron({
			mode: 'fade',
			noOpenerFade: true,
			speed: 300,
			alignment: 'center'
		});

	// Scrolly
		$('.scrolly').scrolly({
			speed: 1000,
			offset: function() { return $nav.height() - 5; }
		});

	// Nav.
		if ($('#logo').html().includes("AMALGUM")){
				minihtml = '<div id="titleBar">' +
					'<a href="#navPanel" class="toggle"></a>' +
					'<span class="title mini-nav left"><a href="index.html">GUM</a></span>' +
					'<span class="title mini-nav active right">AMALGUM</span>' +
				'</div>';
		}else{
			minihtml = '<div id="titleBar">' +
					'<a href="#navPanel" class="toggle"></a>' +
					'<span class="title mini-nav active left">GUM</span>' +
					'<span class="title mini-nav right"><a href="amalgum.html">AMALGUM</a></span>' +
				'</div>';
		}
		// Title Bar.
			$(
				minihtml
			)
				.appendTo($body);

		// Panel.
			$(
				'<div id="navPanel">' +
					'<nav>' +
						$('#nav').navList() +
					'</nav>' +
				'</div>'
			)
				.appendTo($body)
				.panel({
					delay: 500,
					hideOnClick: true,
					hideOnSwipe: true,
					resetScroll: true,
					resetForms: true,
					side: 'left',
					target: $body,
					visibleClass: 'navPanel-visible'
				});

})(jQuery);