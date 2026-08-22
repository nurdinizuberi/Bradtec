/* Real Estate catalogue — search, filters and detail modal */
(function () {
  'use strict';

  var grid = document.getElementById('propertyGrid');
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

  var cards = Array.prototype.slice.call(grid.querySelectorAll('.property-card'));
  var empty = document.getElementById('propEmpty');
  var search = document.getElementById('propSearch');
  var typeSel = document.getElementById('propType');
  var statusSel = document.getElementById('propStatus');

  var props = [];
  try {
    props = JSON.parse(document.getElementById('propertiesData').textContent) || [];
  } catch (e) { /* noop */ }

  function applyFilters() {
    var q = (search.value || '').trim().toLowerCase();
    var t = typeSel.value;
    var s = statusSel.value;
    var shown = 0;
    cards.forEach(function (card) {
      var matchQ = !q
        || card.getAttribute('data-name').indexOf(q) !== -1
        || card.getAttribute('data-location').indexOf(q) !== -1;
      var matchT = !t || card.getAttribute('data-type') === t;
      var matchS = !s || card.getAttribute('data-status') === s;
      var show = matchQ && matchT && matchS;
      card.style.display = show ? '' : 'none';
      if (show) shown++;
      if (show) { card.classList.remove('reveal'); void card.offsetWidth; card.classList.add('reveal', 'in-view'); }
    });
    if (empty) empty.style.display = shown === 0 ? 'block' : 'none';
  }
  [search, typeSel, statusSel].forEach(function (el) {
    if (el) el.addEventListener('input', applyFilters);
    if (el) el.addEventListener('change', applyFilters);
  });

  /* Detail modal */
  grid.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-prop-open]');
    if (!btn) return;
    var id = btn.getAttribute('data-prop');
    var p = props.filter(function (x) { return x.id === id; })[0];
    if (!p) return;

    var gallery = '';
    var images = (p.gallery && p.gallery.length) ? p.gallery : [p.image];
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

    var specs = '<div class="pr-specs" style="border:none;padding:0 0 16px">';
    if (p.bedrooms > 0) specs += '<span>' + esc(p.bedrooms) + ' Beds</span>';
    if (p.bathrooms > 0) specs += '<span>' + esc(p.bathrooms) + ' Baths</span>';
    if (p.size) specs += '<span>' + esc(p.size) + '</span>';
    specs += '</div>';

    var features = (p.features && p.features.length)
      ? '<ul class="cc-list">' + p.features.map(function (f) { return '<li>' + esc(f) + '</li>'; }).join('') + '</ul>'
      : '';

    var root = window.BRADTEC_ROOT || '';
    var html =
      gallery +
      '<div class="modal-body">' +
        '<h2>' + esc(p.name) + '</h2>' +
        '<div class="modal-tags">' +
          '<span class="tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>' + esc(p.location) + '</span>' +
          '<span class="tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 9l9-6 9 6M3 9v12m0-12h18m0 0v12"/></svg>' + esc(p.type) + '</span>' +
          '<span class="tag"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m9 9 6-6m0 6L9 3m6 12H5m16 0h-4m-2 6H3"/></svg>' + esc(p.status) + '</span>' +
        '</div>' +
        specs +
        '<p style="font-size:1.6rem;font-weight:800;color:var(--blue);font-family:var(--font-head);margin-bottom:14px">' + esc(p.price) + '</p>' +
        '<p>' + esc(p.description) + '</p>' +
        features +
        '<div class="cc-actions mt-16">' +
          '<a class="btn btn-primary" href="' + root + 'contact?interest=Real%20Estate&amp;subject=' + encodeURIComponent('Property Inquiry: ' + (p.name || '')) + '">Contact Us</a>' +
          '<a class="btn btn-outline" href="' + root + 'contact?interest=Real%20Estate&amp;subject=' + encodeURIComponent('Request Viewing: ' + (p.name || '')) + '">Request Viewing</a>' +
        '</div>' +
      '</div>';

    window.bradtecOpenModal(html);
  });
})();
