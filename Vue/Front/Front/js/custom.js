(function ($) {

	"use strict";

	$(function() {

		$("#tabs").tabs();
	});

	// Header background on scroll
	$(window).scroll(function() {
		var scroll = $(window).scrollTop();
		var box = $('.header-text').height();
		var header = $('header').height();

		if (scroll >= box - header) {
			$("header").addClass("background-header");
		} else {
			$("header").removeClass("background-header");
		}
	});

	$('.schedule-filter li').on('click', function() {
		var tsfilter = $(this).data('tsfilter');
		$('.schedule-filter li').removeClass('active');
		$(this).addClass('active');
		if (tsfilter == 'all') {
			$('.schedule-table').removeClass('filtering');
			$('.ts-item').removeClass('show');
		} else {
			$('.schedule-table').addClass('filtering');
		}
		$('.ts-item').each(function() {
			$(this).removeClass('show');
			if ($(this).data('tsmeta') == tsfilter) {
				$(this).addClass('show');
			}
		});
	});

	// Scroll animation init
	window.sr = new scrollReveal();

	if($('.menu-trigger').length){
		$(".menu-trigger").on('click', function() {
			$(this).toggleClass('active');
			$('.header-area .nav').slideToggle(200);
		});
	}

	$(document).ready(function () {
		$(document).on("scroll", onScroll);

		// Smooth scroll on clicking a link
		$('.scroll-to-section a[href^="#"]').on('click', function (e) {
			e.preventDefault();
			$(document).off("scroll");

			$('a').each(function () {
				$(this).removeClass('active');
			})
			$(this).addClass('active');

			var target = this.hash;
			var target = $(this.hash);
			$('html, body').stop().animate({
				scrollTop: (target.offset().top) + 1
			}, 500, 'swing', function () {
				window.location.hash = target;
				$(document).on("scroll", onScroll);
			});
		});
	});


	function onScroll(event){
		var scrollPos = $(document).scrollTop();
		$('.nav a').each(function () {
			var currLink = $(this);
			var refElement = $(currLink.attr("href"));
			if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
				$('.nav ul li a').removeClass("active");
				currLink.addClass("active");
			}
			else{
				currLink.removeClass("active");
			}
		});
	}


	$(window).on('load', function() {
		$('#js-preloader').addClass('loaded');
	});


	$(window).on('resize', function() {
		mobileNav();
	});


	function mobileNav() {
		var width = $(window).width();
		$('.submenu').on('click', function() {
			if(width < 767) {
				$('.submenu ul').removeClass('active');
				$(this).find('ul').toggleClass('active');
			}
		});
	}

	$(document).ready(function () {
		const icon = document.getElementById("openFormIcon");
		const modal = document.getElementById("channelModal");
		const closeBtn = document.getElementById("closeModal");
		const mainContainer = document.querySelector(".main-container");
		const chatWindow = document.querySelector(".chat-window");

		// Open modal when icon is clicked
		icon.addEventListener("click", function () {
			modal.style.display = "flex";
			mainContainer.classList.add("blurred");
			chatWindow.classList.add("blurred");
		});


		closeBtn.addEventListener("click", function () {
			modal.style.display = "none";
			mainContainer.classList.remove("blurred");
			chatWindow.classList.remove("blurred");
		});


		window.addEventListener("click", function (e) {
			if (e.target == modal) {
				modal.style.display = "none";
				mainContainer.classList.remove("blurred");
				chatWindow.classList.remove("blurred");
			}
		});
	});


	function previewImage(event) {
		var reader = new FileReader();
		reader.onload = function() {
			var output = document.getElementById('imagePreview');
			output.style.display = 'block'; // Show the image preview
			output.src = reader.result;
		};
		reader.readAsDataURL(event.target.files[0]);
	}


	function loadChannels() {
		fetch('controler/getChannels.php')
			.then(response => response.json())
			.then(channels => {
				const sidebar = document.getElementById('channelSidebar');
				sidebar.innerHTML = ''; // Clear previous channels
				channels.forEach(channel => {
					const div = document.createElement('div');
					div.classList.add('channel-icon');
					div.innerHTML = `<img src="${channel.image_url || 'img/default.png'}" alt="${channel.name}" title="${channel.name}" onclick="switchChannel('${channel.name}')">`;
					sidebar.appendChild(div);
				});
				if (channels.length > 0) {
					switchChannel(channels[0].name); // Automatically switch to the first channel
				}
			})
			.catch(error => console.error('Error loading channels:', error));
	}


	function switchChannel(name) {
		document.getElementById('channelTitle').textContent = `# ${name}`;
		document.getElementById('chat-messages').innerHTML = ''; // Clear the previous messages (you can load messages later)
	}


	document.addEventListener('DOMContentLoaded', loadChannels);

})(window.jQuery);