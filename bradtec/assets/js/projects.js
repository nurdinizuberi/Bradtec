/* Projects portfolio — filtering and detail modal */
(function () {
  'use strict';

  var grid = document.getElementById('projectGrid');
  if (!grid) return;

  /* Escape admin-entered data before it reaches innerHTML (stored-XSS defence) */
  function esc(v) {
    return String(v == null ? '' : v)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  var cards = Array.prototype.slice.call(grid.querySelectorAll('.project-card'));
  var empty = document.getElementById('projectEmpty');
  var filters = Array.prototype.slice.call(document.querySelectorAll('.filter-btn'));

  var projects = [];
  try {
    projects = JSON.parse(document.getElementById('projectsData').textContent) || [];
  } catch (e) { /* noop */ }

  /* ---------- Filtering with fade/scale transition ---------- */
  function applyFilter(cat) {
    filters.forEach(function (b) {
      var active = b.getAttribute('data-filter') === cat;
      b.classList.toggle('active', active);
      b.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    var shown = 0;
    cards.forEach(function (card) {
      var match = !cat || card.getAttribute('data-category') === cat;
      if (match) {
        shown++;
        card.style.display = '';
        card.style.opacity = '0';
        card.style.transform = 'translateY(16px) scale(0.98)';
        card.style.transition = 'opacity .4s ease, transform .4s ease';
        requestAnimationFrame(function () {
          requestAnimationFrame(function () {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0) scale(1)';
          });
        });
      } else {
        card.style.display = 'none';
      }
    });
    if (empty) empty.style.display = shown === 0 ? 'block' : 'none';
  }

  filters.forEach(function (b) {
    b.addEventListener('click', function () {
      applyFilter(b.getAttribute('data-filter'));
    });
  });

  /* ---------- Detail modal ---------- */
  grid.addEventListener('click', function (e) {
    var opener = e.target.closest('[data-project]');
    if (!opener) return;
    var id = opener.getAttribute('data-project');
    var p = projects.filter(function (x) { return x.id === id; })[0];
    if (!p) return;
    openProjectModal(p);
  });

  function openProjectModal(p) {
    var images = (p.images && p.images.length) ? p.images : [p.image];
    var gallery = '';
    if (images.length) {
      var thumbs = images.slice(1).map(function (src) {
        return '<img src="' + esc(src) + '" alt="' + esc(p.name) + '" loading="lazy">';
      }).join('');
      gallery =
        '<div class="modal-gallery">' +
          '<div class="mg-main"><img src="' + esc(p.image) + '" alt="' + esc(p.name) + '"></div>' +
          (thumbs ? '<div class="mg-thumbs" data-lightbox="' + esc(JSON.stringify(images)) + '">' + thumbs + '</div>' : '') +
        '</div>';
    }
    var root = window.BRADTEC_ROOT || '';
    var html =
      gallery +
      '<div class="modal-body">' +
        '<h2>' + esc(p.name) + '</h2>' +
        '<div class="modal-tags">' +
          '<span class="tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 9l9-6 9 6M3 9v12m0-12h18m0 0v12"/></svg>' + esc(p.category) + '</span>' +
          '<span class="tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>' + esc(p.location) + '</span>' +
          '<span class="tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>' + esc(p.date) + '</span>' +
          '<span class="tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m9 9 6-6m0 6L9 3m6 12H5m16 0h-4m-2 6H3"/></svg>' + esc(p.status) + '</span>' +
        '</div>' +
        '<p>' + esc(p.description) + '</p>' +
        '<div class="cc-actions mt-16">' +
          '<a class="btn btn-primary" href="' + root + 'contact?interest=' + encodeURIComponent(p.category || '') + '&amp;subject=' + encodeURIComponent('Project Inquiry: ' + (p.name || '')) + '">Discuss a Similar Project</a>' +
        '</div>' +
      '</div>';
    window.bradtecOpenModal(html);
  }
})();
