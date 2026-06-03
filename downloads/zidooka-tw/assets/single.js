// TOC Generation & ScrollSpy
document.addEventListener('DOMContentLoaded', function() {
    const content = document.querySelector('.zenn-content');
    const tocPlaceholder = document.querySelector('.zenn-toc-placeholder');
    const sidebarTocContainer = document.querySelector('.widget-toc-content');
    if (!content) return;
    const headings = content.querySelectorAll('h2, h3');
    if (headings.length < 2) return;

    function buildToc(isSidebar) {
        const tocContainer = document.createElement('div');
        tocContainer.className = isSidebar ? 'zenn-sidebar-toc-list' : 'zenn-toc';
        if (!isSidebar) {
            const title = document.createElement('div');
            title.className = 'zenn-toc-title';
            title.textContent = window.zdkUiText?.tocTitle || 'Table of Contents';
            tocContainer.appendChild(title);
        }
        const list = document.createElement('ul');
        list.className = 'zenn-toc-list';
        headings.forEach((heading, index) => {
            const id = heading.id || 'toc-' + index;
            heading.id = id;
            const li = document.createElement('li');
            li.className = 'zenn-toc-item zenn-toc-' + heading.tagName.toLowerCase();
            const a = document.createElement('a');
            a.href = '#' + id;
            a.textContent = heading.textContent;
            a.className = 'zenn-toc-link';
            a.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.getElementById(id);
                if (target) {
                    const offset = 80;
                    window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
                    history.pushState(null, null, '#' + id);
                }
            });
            li.appendChild(a);
            list.appendChild(li);
        });
        tocContainer.appendChild(list);
        return tocContainer;
    }

    if (tocPlaceholder) {
        tocPlaceholder.appendChild(buildToc(false));
        tocPlaceholder.removeAttribute('aria-hidden');
    }
});

// Copy to clipboard (via data-url attribute)
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.zenn-copy-btn');
    if (!btn || !btn.dataset.url) return;
    navigator.clipboard.writeText(btn.dataset.url).then(function() {
        alert(window.zdkUiText?.copySuccess || 'Copied!');
    }, function() {
        alert(window.zdkUiText?.copyFail || 'Copy failed');
    });
});

// Like button
document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.zenn-like-btn, .zenn-like-btn-large');
    const ajaxUrl = window.zdkUiText?.ajaxUrl || '';
    const nonce = window.zdkUiText?.nonce || '';

    let likedPosts = [];
    try { likedPosts = JSON.parse(localStorage.getItem('liked_posts') || '[]'); } catch (e) { likedPosts = []; }

    likeButtons.forEach(function(button) {
        const postId = button.dataset.postId;
        if (!postId) return;
        if (likedPosts.includes(postId)) {
            button.classList.add('liked');
            const svg = button.querySelector('svg path');
            if (svg) svg.setAttribute('fill', 'currentColor');
            button.disabled = true;
        }
        button.addEventListener('click', function() {
            if (this.classList.contains('liked')) return;
            const self = this;
            self.classList.add('liked');
            const svg = self.querySelector('svg path');
            if (svg) svg.setAttribute('fill', 'currentColor');
            document.querySelectorAll('.zenn-like-btn[data-post-id="' + postId + '"] .zenn-like-count, .zenn-like-btn-large[data-post-id="' + postId + '"] .zenn-like-count-large').forEach(function(el) {
                el.textContent = parseInt(el.textContent || 0) + 1;
            });
            document.querySelectorAll('button[data-post-id="' + postId + '"]').forEach(function(btn) {
                btn.disabled = true;
                btn.classList.add('liked');
                var s = btn.querySelector('svg path');
                if (s) s.setAttribute('fill', 'currentColor');
            });
            var formData = new FormData();
            formData.append('action', 'process_simple_like');
            formData.append('post_id', postId);
            formData.append('nonce', nonce);
            fetch(ajaxUrl, { method: 'POST', body: formData })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.success) {
                        likedPosts.push(postId);
                        localStorage.setItem('liked_posts', JSON.stringify(likedPosts));
                    }
                });
        });
    });
});

// Image modal
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('zenn-image-modal');
    const modalImg = modal ? modal.querySelector('.zenn-image-modal-img') : null;
    const modalCaption = modal ? modal.querySelector('.zenn-image-modal-caption') : null;
    const closeBtn = modal ? modal.querySelector('.zenn-image-modal-close') : null;
    if (!modal || !modalImg || !modalCaption || !closeBtn) return;

    const targetSelector = '.zenn-content img, .zenn-featured-image img';

    function openModal(img) {
        const src = img.currentSrc || img.src;
        if (!src) return;
        modalImg.src = src;
        modalCaption.textContent = img.getAttribute('alt') || img.getAttribute('title') || '';
        modal.classList.add('is-visible');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('zenn-modal-open');
    }

    function closeModal() {
        modal.classList.remove('is-visible');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('zenn-modal-open');
        modalImg.src = '';
        modalCaption.textContent = '';
        modalImg.style.transform = 'scale(1)';
        modalImg.style.transformOrigin = 'center center';
    }

    function attachListeners(img) {
        if (img.dataset.modalBound) return;
        img.style.cursor = 'zoom-in';
        img.addEventListener('click', function() { openModal(img); });
        img.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openModal(img); }
        });
        img.setAttribute('tabindex', '0');
        img.dataset.modalBound = 'true';
    }

    document.querySelectorAll(targetSelector).forEach(attachListeners);
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (!(node instanceof HTMLElement)) return;
                if (node.matches && node.matches('img') && (node.closest('.zenn-content') || node.closest('.zenn-featured-image'))) {
                    attachListeners(node);
                }
                node.querySelectorAll && node.querySelectorAll(targetSelector).forEach(attachListeners);
            });
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });

    modalImg.addEventListener('mousemove', function(e) {
        const rect = modalImg.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        modalImg.style.transformOrigin = x + '% ' + y + '%';
        modalImg.style.transform = 'scale(1.8)';
    });
    modalImg.addEventListener('mouseleave', function() {
        modalImg.style.transform = 'scale(1)';
        modalImg.style.transformOrigin = 'center center';
    });
    closeBtn.addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) { if (e.target === modal) closeModal(); });
    window.addEventListener('keyup', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('is-visible')) closeModal();
    });
});
