/**
 * Front-end JavaScript
 *
 * The JavaScript code you place here will be processed by esbuild. The output
 * file will be created at `../theme/js/script.min.js` and enqueued in
 * `../theme/functions.php`.
 *
 * For esbuild documentation, please see:
 * https://esbuild.github.io/
 */

import Swiper from 'swiper';
import { Navigation, Pagination } from 'swiper/modules';

window.Swiper = Swiper;

document.addEventListener('DOMContentLoaded', () => {
	const progressCircle = document.querySelector('.autoplay-progress svg');
	const progressContent = document.querySelector('.autoplay-progress span');

	// eslint-disable-next-line no-unused-vars
	const swiper = new Swiper('.swiper', {
		// Configure Swiper to use modules
		modules: [Navigation, Pagination],
		loop: true,
		slidesPerView: 1,
		centeredSlides: true,
		a11y: {
			prevSlideMessage: 'Immagine precedente',
			nextSlideMessage: 'Immagine successiva',
		},
		autoplay: {
			delay: 2500,
			disableOnInteraction: true,
		},
		keyboard: {
			enabled: true,
		},
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
			dynamicBullets: true,
		},

		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},

		on: {
			autoplayTimeLeft(s, time, progress) {
				progressCircle.style.setProperty('--progress', 1 - progress);
				progressContent.textContent = `${Math.ceil(time / 1000)}s`;
			},
		},
	});
});

document.addEventListener('DOMContentLoaded', () => {
	const cards = document.querySelectorAll('.portrait-card');
	if (!cards.length || matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	const seen = new IntersectionObserver((entries) => {
		const rising = entries
			.filter((entry) => entry.isIntersecting)
			.sort((a, b) => {
				const ar = a.target.getBoundingClientRect();
				const br = b.target.getBoundingClientRect();
				return ar.top - br.top || ar.left - br.left;
			});

		rising.forEach((entry, i) => {
			entry.target.style.setProperty('--rise', `${i * 80}ms`);
			entry.target.classList.add('is-in');
			seen.unobserve(entry.target);
		});
	}, { threshold: 0.2 });

	cards.forEach((card) => seen.observe(card));
});

document.addEventListener('DOMContentLoaded', () => {
	// MENU
	const menuState = document.querySelector('#menu-state');
	document.querySelector('a.menu-open').addEventListener('click', (e) => {
		e.preventDefault();
		menuState.checked = true;
	});
	document.querySelector('a.menu-close').addEventListener('click', (e) => {
		e.preventDefault();
		menuState.checked = false;
	});
});
