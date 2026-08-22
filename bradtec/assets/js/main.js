/* BRADTEC CO. LTD — shared front-end behaviour */
(function () {
  'use strict';

  /* ---------- Header / navigation ---------- */
  var header = document.getElementById('siteHeader');
  var toggle = document.getElementById('navToggle');
  var nav = document.getElementById('mainNav');

  function onScroll() {
    if (header) header.classList.toggle('scrolled', window.scrollY > 30);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      var open = nav.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
      toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
      if (header) header.classList.toggle('menu-open', open);
    });
    nav.addEventListener('click', function (e) {
      var link = e.target.closest('a');
      if (link && nav.classList.contains('open')) {
        nav.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
        if (header) header.classList.remove('menu-open');
      }
    });
  }

  /* Services dropdown (desktop hover + mobile click) */
  var dropdowns = document.querySelectorAll('.has-dropdown');
  dropdowns.forEach(function (dd) {
    var btn = dd.querySelector('.dropdown-toggle');
    if (!btn) return;
    btn.addEventListener('click', function () {
      var open = dd.classList.toggle('open');
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    dd.addEventListener('mouseenter', function () { dd.classList.add('open'); btn.setAttribute('aria-expanded', 'true'); });
    dd.addEventListener('mouseleave', function () { dd.classList.remove('open'); btn.setAttribute('aria-expanded', 'false'); });
  });
  document.addEventListener('click', function (e) {
    dropdowns.forEach(function (dd) {
      if (!dd.contains(e.target)) {
        dd.classList.remove('open');
        var b = dd.querySelector('.dropdown-toggle');
        if (b) b.setAttribute('aria-expanded', 'false');
      }
    });
  });

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in-view'); });
  }

  /* ---------- Animated counters ---------- */
  var counters = document.querySelectorAll('[data-counter]');
  function animateCounter(el) {
    var target = parseFloat(el.getAttribute('data-counter')) || 0;
    var dur = 1600;
    var start = null;
    function step(ts) {
      if (!start) start = ts;
      var p = Math.min((ts - start) / dur, 1);
      var eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * eased).toLocaleString();
      if (p < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  if ('IntersectionObserver' in window && counters.length) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          cio.unobserve(entry.target);
        }
      });
    }, { threshold: 0.4 });
    counters.forEach(function (el) { cio.observe(el); });
  } else {
    counters.forEach(function (el) {
      el.textContent = (parseFloat(el.getAttribute('data-counter')) || 0).toLocaleString();
    });
  }

  /* ---------- Toast ---------- */
  var toastWrap = document.querySelector('.toast-wrap');
  if (!toastWrap) {
    toastWrap = document.createElement('div');
    toastWrap.className = 'toast-wrap';
    document.body.appendChild(toastWrap);
  }
  window.bradtecToast = function (message, type) {
    var t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : '');
    t.innerHTML = '<span class="t-ico">' +
      (type === 'error'
        ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4m0 4h.01"/></svg>'
        : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.1V12a10 10 0 1 1-5.9-9.1"/><path d="M22 4 12 14l-3-3"/></svg>') +
      '</span><span></span>';
    t.querySelector('span:last-child').textContent = message;
    toastWrap.appendChild(t);
    requestAnimationFrame(function () { t.classList.add('show'); });
    setTimeout(function () {
      t.classList.remove('show');
      setTimeout(function () { t.remove(); }, 400);
    }, 4200);
  };

  /* ---------- Lightbox ---------- */
  var lb = document.querySelector('.lightbox-backdrop');
  var lbImages = [];
  var lbIndex = 0;
  if (!lb) {
    lb = document.createElement('div');
    lb.className = 'lightbox-backdrop';
    lb.innerHTML =
      '<button type="button" class="lb-close" aria-label="Close">&times;</button>' +
      '<button type="button" class="lb-nav lb-prev" aria-label="Previous">&#8249;</button>' +
      '<img src="" alt="">' +
      '<button type="button" class="lb-nav lb-next" aria-label="Next">&#8250;</button>' +
      '<span class="lb-count"></span>';
    document.body.appendChild(lb);
  }
  var lbImg = lb.querySelector('img');
  var lbCount = lb.querySelector('.lb-count');
  function showLightbox(i) {
    lbIndex = i;
    lbImg.src = lbImages[i];
    lbImg.alt = '';
    if (lbImages.length > 1) {
      lbCount.textContent = (i + 1) + ' / ' + lbImages.length;
      lbCount.style.display = 'block';
    } else {
      lbCount.style.display = 'none';
    }
    lb.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeLightbox() {
    lb.classList.remove('open');
    document.body.style.overflow = '';
  }
  lb.addEventListener('click', function (e) {
    if (e.target === lb || e.target.closest('.lb-close')) closeLightbox();
    if (e.target.closest('.lb-next')) showLightbox((lbIndex + 1) % lbImages.length);
    if (e.target.closest('.lb-prev')) showLightbox((lbIndex - 1 + lbImages.length) % lbImages.length);
  });
  document.addEventListener('keydown', function (e) {
    if (!lb.classList.contains('open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowRight') showLightbox((lbIndex + 1) % lbImages.length);
    if (e.key === 'ArrowLeft') showLightbox((lbIndex - 1 + lbImages.length) % lbImages.length);
  });
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('[data-lightbox]');
    if (trigger) {
      try {
        lbImages = JSON.parse(trigger.getAttribute('data-lightbox')) || [];
      } catch (err) {
        lbImages = [trigger.getAttribute('data-lightbox')];
      }
      if (lbImages.length) showLightbox(0);
    }
  });

  /* ---------- Generic modal ---------- */
  var modalBackdrop = document.querySelector('.modal-backdrop');
  if (!modalBackdrop) {
    modalBackdrop = document.createElement('div');
    modalBackdrop.className = 'modal-backdrop';
    modalBackdrop.innerHTML = '<div class="modal" role="dialog" aria-modal="true"></div>';
    document.body.appendChild(modalBackdrop);
  }
  var modalBox = modalBackdrop.querySelector('.modal');
  window.bradtecOpenModal = function (html) {
    modalBox.innerHTML = '<button type="button" class="modal-close" aria-label="Close">&times;</button>' + html;
    modalBackdrop.classList.add('open');
    document.body.style.overflow = 'hidden';
  };
  window.bradtecCloseModal = function () {
    modalBackdrop.classList.remove('open');
    document.body.style.overflow = '';
  };
  modalBackdrop.addEventListener('click', function (e) {
    if (e.target === modalBackdrop || e.target.closest('.modal-close')) bradtecCloseModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && modalBackdrop.classList.contains('open')) bradtecCloseModal();
  });

  /* ---------- Contact form ---------- */
  var contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = contactForm.querySelector('button[type="submit"]');
      var data = {};
      var fd = new FormData(contactForm);
      fd.forEach(function (v, k) { data[k] = v; });
      if (btn) { btn.disabled = true; btn.textContent = 'Sending…'; }
      fetch('api/contact.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })
        .then(function (r) { return r.json().then(function (j) { return { ok: r.ok, j: j }; }); })
        .then(function (res) {
          if (res.ok && res.j.ok) {
            bradtecToast(res.j.message || 'Thank you! Your inquiry has been sent.');
            contactForm.reset();
          } else {
            bradtecToast((res.j && res.j.error) || 'Something went wrong. Please try again.', 'error');
          }
        })
        .catch(function () { bradtecToast('Network error. Please try again.', 'error'); })
        .finally(function () {
          if (btn) { btn.disabled = false; btn.textContent = 'Send Inquiry'; }
        });
    });
  }

  /* Pre-fill contact form from URL params (?interest=&subject=&message=) */
  (function () {
    if (!contactForm) return;
    var params = new URLSearchParams(window.location.search);
    var map = { interest: 'interest', subject: 'subject', message: 'message', name: 'name', email: 'email', phone: 'phone' };
    Object.keys(map).forEach(function (key) {
      var val = params.get(key);
      if (!val) return;
      var field = contactForm.querySelector('[name="' + map[key] + '"]');
      if (field && !field.value) field.value = val;
    });
  })();

  /* ---------- Team auto-carousel ---------- */
  var teamCarousel = document.getElementById('teamCarousel');
  if (teamCarousel) {
    /* Duplicate children so the CSS translateX(-50%) loop is seamless */
    var cards = Array.prototype.slice.call(teamCarousel.children);
    cards.forEach(function (card) { teamCarousel.appendChild(card.cloneNode(true)); });
  }
})();
