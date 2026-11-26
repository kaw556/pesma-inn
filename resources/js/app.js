import './bootstrap';

function initHome() {
    /* ---------- Navbar transparan → solid saat scroll ---------- */
    const nav = document.getElementById('siteNav');
    if (nav) {
        const scrolledClasses = [
            'bg-base-100/90',
            'text-base-content',
            'shadow-sm',
            'backdrop-blur',
            'border-b',
            'border-base-200',
        ];

        const updateNav = () => {
            const scrolled = window.scrollY > 40;
            scrolledClasses.forEach((cls) => nav.classList.toggle(cls, scrolled));
            nav.classList.toggle('bg-transparent', !scrolled);
            nav.classList.toggle('text-white', !scrolled);
        };

        window.addEventListener('scroll', updateNav, { passive: true });
        updateNav();
    }

    /* ---------- Notifikasi ringan ---------- */
    function notify(message, type = 'warning') {
        const toast = document.createElement('div');
        toast.className = 'toast toast-top toast-center z-[100]';

        const alertBox = document.createElement('div');
        alertBox.className = `alert alert-${type} text-sm shadow-lg`;
        alertBox.textContent = message;

        toast.appendChild(alertBox);
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3200);
    }

    /* ---------- Tanggal check-in / check-out ---------- */
    const cekForm = document.getElementById('cekForm');
    const checkIn = document.getElementById('checkin');
    const checkOut = document.getElementById('checkout');
    const jumlahKamar = document.getElementById('jumlahkamar');

    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    if (checkIn && checkOut) {
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);

        checkIn.min = formatDate(today);
        checkOut.min = formatDate(tomorrow);

        const params = new URLSearchParams(window.location.search);
        if (params.get('checkin') && params.get('checkout')) {
            checkIn.value = params.get('checkin');
            checkOut.value = params.get('checkout');
        } else if (!checkIn.value || !checkOut.value) {
            checkIn.value = formatDate(today);
            checkOut.value = formatDate(tomorrow);
        }

        checkIn.addEventListener('change', () => {
            const selected = new Date(checkIn.value);
            if (Number.isNaN(selected.getTime())) return;

            const nextDay = new Date(selected);
            nextDay.setDate(selected.getDate() + 1);
            checkOut.min = formatDate(nextDay);

            if (new Date(checkOut.value) <= selected) {
                checkOut.value = formatDate(nextDay);
            }
        });
    }

    /* ---------- Cek ketersediaan kamar ---------- */
    if (cekForm) {
        cekForm.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!checkIn.value || !checkOut.value) {
                notify('Harap pilih tanggal check-in dan check-out terlebih dahulu.');
                return;
            }

            const cekUrl = document.body.dataset.checkKamar || '/cek-kamar';
            const query = new URLSearchParams({
                checkin: checkIn.value,
                checkout: checkOut.value,
                jumlah: jumlahKamar?.value || 1,
            });

            document.querySelectorAll('.availability-text').forEach((el) => {
                el.innerHTML = '<span class="text-white/60">Memeriksa…</span>';
            });

            try {
                const response = await fetch(`${cekUrl}?${query.toString()}`);
                const result = await response.json();

                document.querySelectorAll('.swiper-slide').forEach((slide) => {
                    slide.classList.remove('hidden');
                });

                Object.entries(result).forEach(([roomName, sisa]) => {
                    const textEl = Array.from(document.querySelectorAll('.availability-text'))
                        .find((el) => el.dataset.room === roomName);
                    if (!textEl) return;

                    const card = textEl.closest('.swiper-slide');
                    const bookBtn = card?.querySelector('.book-now');
                    const jumlah = Number(jumlahKamar?.value || 1);

                    if (sisa < jumlah) {
                        textEl.innerHTML = '<span class="text-rose-300">Tidak tersedia</span>';
                        card?.classList.add('hidden');
                        if (bookBtn) {
                            bookBtn.dataset.locked = 'true';
                            bookBtn.textContent = 'Cek dulu ▾';
                            bookBtn.tabIndex = -1;
                        }
                        return;
                    }

                    textEl.innerHTML = `<span class="text-emerald-300">Tersisa ${sisa} kamar</span>`;
                    if (bookBtn) {
                        bookBtn.dataset.locked = 'false';
                        bookBtn.textContent = 'Book Now ▾';
                        bookBtn.tabIndex = 0;
                    }
                });

                if (window.swiperInstance) window.swiperInstance.update();
            } catch (error) {
                console.error(error);
                notify('Gagal memuat ketersediaan kamar. Silakan coba lagi.', 'error');
            }
        });
    }

    /* ---------- Swiper ---------- */
    if (typeof window.Swiper !== 'undefined' && document.querySelector('.card-wrapper')) {
        window.swiperInstance = new window.Swiper('.card-wrapper', {
            spaceBetween: 20,
            slidesPerView: 1.05,
            grabCursor: true,
            watchOverflow: true,
            speed: 600,
            observer: true,
            observeParents: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.room-next',
                prevEl: '.room-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 24 },
                1024: { slidesPerView: 3, spaceBetween: 24 },
            },
        });
    }

    /* ---------- Booking ---------- */
    function processBooking(type, kamarId) {
        if (!checkIn?.value || !checkOut?.value) {
            notify('Harap cek ketersediaan dan pilih tanggal menginap terlebih dahulu.');
            cekForm?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const key = type === 'corporate' ? 'bookingCorporate' : 'bookingIndividu';
        const baseUrl = document.body.dataset[key];
        if (!baseUrl) return;

        const query = new URLSearchParams({
            kamar: kamarId,
            checkin: checkIn.value,
            checkout: checkOut.value,
            jumlah_kamar: jumlahKamar?.value || 1,
        });

        window.location.href = `${baseUrl}?${query.toString()}`;
    }
    window.processBooking = processBooking;

    document.querySelectorAll('.book-now').forEach((button) => {
        if (button.dataset.locked === 'true') button.tabIndex = -1;

        button.addEventListener('mousedown', (event) => {
            if (button.dataset.locked === 'true') event.preventDefault();
        });

        button.addEventListener('click', (event) => {
            if (button.dataset.locked !== 'true') {
                // Swiper menekan pointerdown default, jadi trigger tidak pernah dapat fokus
                // dan dropdown CSS (:focus-within) tidak terbuka. Fokuskan eksplisit.
                button.focus();
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            button.blur();
            notify('Silakan cek ketersediaan kamar terlebih dahulu.');
            cekForm?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    document.querySelectorAll('.booking-option').forEach((button) => {
        button.addEventListener('click', () => {
            const trigger = button.closest('.dropdown')?.querySelector('.book-now');
            if (trigger?.dataset.locked === 'true') {
                notify('Silakan cek ketersediaan kamar terlebih dahulu.');
                return;
            }
            processBooking(button.dataset.type, button.dataset.kamar);
        });
    });

    /* ---------- Dialog detail kamar ---------- */
    const roomPopup = document.getElementById('roomPopup');
    const popupBook = document.getElementById('popupBook');

    document.querySelectorAll('.see-more').forEach((button) => {
        button.addEventListener('click', () => {
            document.getElementById('popupTitle').textContent = button.dataset.room;

            const features = document.getElementById('popupFeatures');
            features.innerHTML = '';
            (button.dataset.fasilitas || '').split(',').forEach((item) => {
                if (!item.trim()) return;
                const li = document.createElement('li');
                li.className = 'flex gap-2';
                li.innerHTML = `<i class="fas fa-check mt-1 text-xs text-secondary"></i><span></span>`;
                li.querySelector('span').textContent = item.trim();
                features.appendChild(li);
            });

            const harga = Number(button.dataset.harga || 0);
            document.getElementById('popupPrice').textContent =
                `Rp ${harga.toLocaleString('id-ID')}`;

            popupBook.dataset.kamar = button.dataset.kamar;
            const relatedTrigger = button.closest('.swiper-slide')?.querySelector('.book-now');
            popupBook.dataset.locked = relatedTrigger?.dataset.locked || 'true';

            if (typeof roomPopup?.showModal === 'function') roomPopup.showModal();
        });
    });

    popupBook?.addEventListener('click', () => {
        if (popupBook.dataset.locked === 'true') {
            notify('Silakan cek ketersediaan kamar terlebih dahulu.');
            roomPopup?.close();
            cekForm?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }
        processBooking('individu', popupBook.dataset.kamar);
    });

    /* ---------- Galeri + lightbox ---------- */
    const galleryItems = Array.from(document.querySelectorAll('.gallery-item'));
    const lightbox = document.getElementById('galleryLightbox');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');

    if (lightbox && galleryItems.length) {
        let currentIndex = 0;

        const renderPhoto = () => {
            const image = galleryItems[currentIndex].querySelector('img');
            lightboxImage.src = image.src;
            lightboxImage.alt = image.alt;
            lightboxCaption.textContent = image.alt;
        };

        const openLightbox = (index) => {
            currentIndex = index;
            renderPhoto();
            lightbox.showModal();
        };

        const stepPhoto = (step) => {
            currentIndex = (currentIndex + step + galleryItems.length) % galleryItems.length;
            renderPhoto();
        };

        galleryItems.forEach((item, index) => {
            item.addEventListener('click', () => openLightbox(index));
        });

        document.getElementById('lightboxPrev')?.addEventListener('click', () => stepPhoto(-1));
        document.getElementById('lightboxNext')?.addEventListener('click', () => stepPhoto(1));

        lightbox.addEventListener('click', (event) => {
            if (event.target === lightbox) lightbox.close();
        });

        document.addEventListener('keydown', (event) => {
            if (!lightbox.open) return;
            if (event.key === 'Escape') lightbox.close();
            if (event.key === 'ArrowLeft') stepPhoto(-1);
            if (event.key === 'ArrowRight') stepPhoto(1);
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHome);
} else {
    initHome();
}
