document.querySelectorAll('[data-image-upload]').forEach((uploader) => {
    const input = uploader.querySelector('[data-image-input]');
    const dropzone = uploader.querySelector('[data-image-dropzone]');
    const preview = uploader.querySelector('[data-image-preview]');
    const error = uploader.querySelector('[data-image-error]');
    const count = uploader.querySelector('[data-image-count]');

    const maxFiles = Number(uploader.dataset.maxFiles);
    const maxSize = Number(uploader.dataset.maxSize);
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    let selectedFiles = [];

    const showError = (message) => {
        error.textContent = message;
        error.classList.toggle('hidden', message === '');
    };

    const synchronizeInput = () => {
        const transfer = new DataTransfer();

        selectedFiles.forEach((file) => {
            transfer.items.add(file);
        });

        input.files = transfer.files;
    };

    const renderPreviews = () => {
        preview.replaceChildren();

        count.textContent = selectedFiles.length > 0
            ? `${selectedFiles.length} image${selectedFiles.length === 1 ? '' : 's'} selected`
            : '';

        selectedFiles.forEach((file, index) => {
            const card = document.createElement('div');
            card.className = 'overflow-hidden rounded-lg border border-slate-200 bg-white';

            const image = document.createElement('img');
            image.src = URL.createObjectURL(file);
            image.alt = file.name;
            image.className = 'h-32 w-full object-cover';

            const footer = document.createElement('div');
            footer.className = 'flex items-center justify-between gap-2 p-2';

            const name = document.createElement('p');
            name.textContent = file.name;
            name.className = 'truncate text-xs text-slate-600';

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'Remove';
            removeButton.className = 'text-xs font-semibold text-red-600';

            removeButton.addEventListener('click', () => {
                selectedFiles.splice(index, 1);
                synchronizeInput();
                renderPreviews();
                showError('');
            });

            footer.append(name, removeButton);
            card.append(image, footer);
            preview.append(card);
        });
    };

    const addFiles = (files) => {
        showError('');

        for (const file of files) {
            if (selectedFiles.length >= maxFiles) {
                showError(`You can select a maximum of ${maxFiles} images.`);
                break;
            }

            if (! allowedTypes.includes(file.type)) {
                showError(`${file.name} is not a supported image.`);
                continue;
            }

            if (file.size > maxSize) {
                showError(`${file.name} is larger than 4 MB.`);
                continue;
            }

            const duplicate = selectedFiles.some((selectedFile) => {
                return selectedFile.name === file.name
                    && selectedFile.size === file.size
                    && selectedFile.lastModified === file.lastModified;
            });

            if (! duplicate) {
                selectedFiles.push(file);
            }
        }

        synchronizeInput();
        renderPreviews();
    };

    input.addEventListener('change', () => {
        addFiles(Array.from(input.files));
    });

    ['dragenter', 'dragover'].forEach((eventName) => {
        dropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            dropzone.classList.add('bg-slate-100', 'ring-2', 'ring-slate-300');
        });
    });

    ['dragleave', 'drop'].forEach((eventName) => {
        dropzone.addEventListener(eventName, (event) => {
            event.preventDefault();
            dropzone.classList.remove('bg-slate-100', 'ring-2', 'ring-slate-300');
        });
    });

    dropzone.addEventListener('drop', (event) => {
        addFiles(Array.from(event.dataTransfer.files));
    });

    if (maxFiles === 0) {
        input.disabled = true;
        dropzone.classList.add('cursor-not-allowed', 'opacity-50');
        showError('Delete an existing image before uploading another one.');
    }
});

document.querySelectorAll('[data-hero-carousel]').forEach((carousel) => {
    const slides = Array.from(carousel.querySelectorAll('[data-hero-slide]'));
    const dots = Array.from(carousel.querySelectorAll('[data-hero-dot]'));
    const previousButton = carousel.querySelector('[data-hero-previous]');
    const nextButton = carousel.querySelector('[data-hero-next]');

    if (slides.length < 2) {
        return;
    }

    let currentSlide = 0;
    let timer;

    const showSlide = (index) => {
        currentSlide = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            slide.classList.toggle('hidden', slideIndex !== currentSlide);
            slide.setAttribute('aria-hidden', slideIndex === currentSlide ? 'false' : 'true');
        });

        dots.forEach((dot, dotIndex) => {
            const active = dotIndex === currentSlide;

            dot.classList.toggle('w-8', active);
            dot.classList.toggle('w-2.5', ! active);
            dot.classList.toggle('opacity-50', ! active);
        });
    };

    const startTimer = () => {
        window.clearInterval(timer);
        timer = window.setInterval(() => showSlide(currentSlide + 1), 5000);
    };

    previousButton.addEventListener('click', () => {
        showSlide(currentSlide - 1);
        startTimer();
    });

    nextButton.addEventListener('click', () => {
        showSlide(currentSlide + 1);
        startTimer();
    });

    dots.forEach((dot) => {
        dot.addEventListener('click', () => {
            showSlide(Number(dot.dataset.heroDot));
            startTimer();
        });
    });

    carousel.addEventListener('mouseenter', () => window.clearInterval(timer));
    carousel.addEventListener('mouseleave', startTimer);

    showSlide(0);
    startTimer();
});

document.querySelectorAll('[data-product-carousel]').forEach((carousel) => {
    const viewport = carousel.querySelector('[data-product-viewport]');
    const previousButton = carousel.querySelector('[data-product-previous]');
    const nextButton = carousel.querySelector('[data-product-next]');

    if (! viewport) {
        return;
    }

    const scroll = (direction) => {
        viewport.scrollBy({
            left: direction * viewport.clientWidth * 0.8,
            behavior: 'smooth',
        });
    };

    previousButton?.addEventListener('click', () => scroll(-1));
    nextButton?.addEventListener('click', () => scroll(1));
});
