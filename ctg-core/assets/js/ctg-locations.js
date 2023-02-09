jQuery(document).ready( function ($){

	const canvas = document.getElementById("ctg-confetti-canvas");
	const ctx = canvas.getContext("2d");

	let particles = [];
	let w, h;

	function resize() {
		w = canvas.width = window.innerWidth;
		h = canvas.height = window.innerHeight;
	}

	function animate() {
		requestAnimationFrame(animate);

		ctx.clearRect(0, 0, w, h);

		for (let i = 0; i < particles.length; i++) {
			let p = particles[i];
			ctx.save();
			ctx.translate(p.x + p.width / 2, p.y + p.height / 2);
			ctx.fillStyle = "#dbb778";
			ctx.globalAlpha = p.opacity;
			ctx.rotate(p.angle);
			ctx.fillRect(-p.width / 2, -p.height / 2, p.width, p.height);

			ctx.restore();
			p.x += p.vx;
			p.y += p.vy;
			p.z += p.vz;

			if (p.x + p.width > w || p.x < 0) {
				p.vx = -p.vx;
			}

			if (p.y + p.height > h || p.y < 0) {
				p.vy = -p.vy;
			}

			if (p.z < 0 || p.z > h) {
				p.vz = -p.vz;
			}

			p.width = (p.z / h) * 8 + 8;
			p.height = (p.z / h) * 4 + 4;
			p.opacity = (p.z / h);

			if ( p.rd < .49 ) {
				p.angle += Math.PI / 30;
			} else {
				p.angle -= Math.PI / 30;	
			}

		}

		if (particles.length < 200) {
			let p = {
				x: Math.random() * w,
				y: Math.random() * h,
				z: Math.random() * ( ( h + w ) / 2 ),
				vx: Math.random() * 4 - 2,
				vy: Math.random() * 4 - 2,
				vz: Math.random() * 4 - 2,
				width: Math.random() * 8 + 8,
				height: Math.random() * 4 + 4,
				angle: 0,
				opacity: 1,
				rd: Math.random()
			};

			particles.push(p);
		}
	}

	resize();
	animate();


	window.addEventListener("resize", resize);


	const rotationHeading = $('#ctg-confetti-text-rotation-heading');
	let rotateText = {
		start: 'Where every ',
		rotate: ['transaction', 'interaction', 'single action'],
		end: ' is a Celebration'
	};
	let spanned;
	
	rotateText = rotateText.rotate.forEach((text, i) => {
		let letters = text.split('');
		return letters.map((letter,i) => {
			let span = $('<span class="ctg-confetti-rotating-letters">');
			span.text(letter);
			span.css({'opacity':0})
			span.animate({'opacity':1}, 300);
			return span[0];
		});
	});
	
	rotationHeading.append(rotateText);


	/*	
	let start = $('<span>').text(rotateText.start);
	let end = $('<span>').text(rotateText.end);
	rotationHeading.append(start);
	rotationHeading.append(end);

	*/
	/*/	
		const mapContainerObjects = document.querySelectorAll(".ctg-location-template");
	for ( let i = 0; i < mapContainerObjects.length; i++ ) {
		console.log('we got the location: ' + mapContainerObjects[i].dataset.ctgLocationsPrimary );
	}
/*/

	const membersSection = $('#ctg-members');

	const members = async () => {
		let res = await $.get(
			ctg.get_members,
			{

			},
			(e) => {
				return console.log(JSON.parse(e));
			}
		);
	};
	let map;
	let service;
	let infowindow;



	function initMap() {
		const houston = new google.maps.LatLng(29.7604, -95.3698);

		infowindow = new google.maps.InfoWindow();

		map = new google.maps.Map(document.getElementById("ctg-location-1"), {
			zoom: 150
		});

		const request = {
			query: "Celebration Title Group 946 Heights Blvd Houston TX 77008",
			fields: ["name", "geometry"],
		};

		service = new google.maps.places.PlacesService(map);
		service.findPlaceFromQuery(request, (results, status) => {
			if (status === google.maps.places.PlacesServiceStatus.OK && results) {
				for (let i = 0; i < results.length; i++) {
					createMarker(results[i]);
				}

				map.setCenter(results[0].geometry.location);
			}
		});

	}

	function createMarker(place) {
		if (!place.geometry || !place.geometry.location) return;

		const marker = new google.maps.Marker({
			map,
			position: place.geometry.location,
		});

		google.maps.event.addListener(marker, "click", () => {
			infowindow.setContent(place.name || "");
			infowindow.open(map);
		});
	}

	//	window.initMap = initMap;
});