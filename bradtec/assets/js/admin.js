/* BRADTEC Admin — content management */
(function () {
  'use strict';

  var page = document.body.getAttribute('data-admin-page') || '';
  var API = '../api/crud.php?resource=';
  var DIVISIONS = { construction: 'Construction', mining: 'Mining', 'real-estate': 'Real Estate', transportation: 'Transportation', 'imports-exports': 'Imports/Exports & Distribution' };

  /* ---------- Toast ---------- */
  var toastWrap = document.querySelector('.toast-wrap');
  function toast(msg, type) {
    var t = document.createElement('div');
    t.className = 'toast' + (type === 'error' ? ' error' : '');
    t.textContent = msg;
    toastWrap.appendChild(t);
    requestAnimationFrame(function () { t.classList.add('show'); });
    setTimeout(function () { t.classList.remove('show'); setTimeout(function () { t.remove(); }, 400); }, 3800);
  }

  /* ---------- Built-in confirm dialog (replaces window.confirm) ---------- */
  var confirmBackdrop = null;
  var confirmResolver = null;
  function finishConfirm(result) {
    if (confirmBackdrop) confirmBackdrop.classList.remove('open');
    if (confirmResolver) { var r = confirmResolver; confirmResolver = null; r(result); }
  }
  function uiConfirm(message, opts) {
    opts = opts || {};
    if (!confirmBackdrop) {
      confirmBackdrop = document.createElement('div');
      confirmBackdrop.className = 'confirm-backdrop';
      confirmBackdrop.innerHTML =
        '<div class="confirm-modal">' +
          '<div class="confirm-head"><h3></h3><button type="button" class="modal-close" aria-label="Close">&times;</button></div>' +
          '<div class="confirm-body"><p></p></div>' +
          '<div class="confirm-actions">' +
            '<button type="button" class="btn btn-ghost" data-confirm-cancel>Cancel</button>' +
            '<button type="button" class="btn" data-confirm-ok>Confirm</button>' +
          '</div>' +
        '</div>';
      document.body.appendChild(confirmBackdrop);
      confirmBackdrop.addEventListener('click', function (e) {
        if (e.target === confirmBackdrop || e.target.closest('.modal-close') || e.target.closest('[data-confirm-cancel]')) {
          finishConfirm(false);
        } else if (e.target.closest('[data-confirm-ok]')) {
          finishConfirm(true);
        }
      });
      document.addEventListener('keydown', function (e) {
        if (confirmBackdrop.classList.contains('open') && e.key === 'Escape') finishConfirm(false);
      });
    }
    confirmBackdrop.querySelector('.confirm-head h3').textContent = opts.title || 'Please confirm';
    confirmBackdrop.querySelector('.confirm-body p').textContent = message;
    var okBtn = confirmBackdrop.querySelector('[data-confirm-ok]');
    okBtn.textContent = opts.confirmLabel || 'Confirm';
    okBtn.className = 'btn ' + (opts.danger === false ? 'btn-green' : 'btn-danger');
    confirmBackdrop.classList.add('open');
    (confirmBackdrop.querySelector('[data-confirm-cancel]') || okBtn).focus();
    return new Promise(function (resolve) { confirmResolver = resolve; });
  }

  /* ---------- Sidebar + logout ---------- */
  var menuToggle = document.getElementById('adminMenuToggle');
  var sidebar = document.querySelector('.admin-sidebar');
  if (menuToggle && sidebar) {
    menuToggle.addEventListener('click', function () { sidebar.classList.toggle('open'); });
    document.addEventListener('click', function (e) {
      if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
        sidebar.classList.remove('open');
      }
    });
  }
  var logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', function (e) {
      e.preventDefault();
      fetch('../api/logout.php', { method: 'POST' }).then(function () { window.location.href = 'login.php'; });
    });
  }

  /* ---------- API client ---------- */
  function api(resource, action, payload) {
    var url = API + encodeURIComponent(resource) + '&action=' + encodeURIComponent(action);
    var opts = { method: 'GET', headers: { 'Accept': 'application/json' } };
    if (payload) {
      opts.method = 'POST';
      opts.headers['Content-Type'] = 'application/json';
      opts.body = JSON.stringify(payload);
    }
    return fetch(url, opts).then(function (r) {
      return r.json().then(function (j) {
        if (!r.ok || !j.ok) { throw new Error((j && j.error) || 'Request failed'); }
        return j;
      });
    });
  }

  /* ---------- Escaping ---------- */
  function esc(v) {
    return String(v == null ? '' : v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  }

  /* ================================================================
     Field schemas
     kind: text | textarea | select | number | list | map
     ================================================================ */
  var SCHEMAS = {
    services: {
      fields: [
        { name: 'name', label: 'Division Name', required: true },
        { name: 'tagline', label: 'Tagline' },
        { name: 'hero_heading', label: 'Page Hero Heading' },
        { name: 'hero_sub', label: 'Page Hero Sub-text', kind: 'textarea' },
        { name: 'quote_label', label: 'Quote Button Label' },
        { name: 'accent', label: 'Accent Color', kind: 'select', options: ['blue', 'green'] },
        { name: 'image', label: 'Main Image', kind: 'image', hint: 'Upload an image from your computer, or paste an image URL.' },
        { name: 'gallery', label: 'Additional Photos', kind: 'gallery', hint: 'Add more photos of this division (optional)' },
        { name: 'description', label: 'Short Description', kind: 'textarea' },
        { name: 'overview', label: 'Overview', kind: 'textarea' },
        { name: 'capabilities', label: 'Capabilities', kind: 'list', hint: 'One capability per line' },
        { name: 'equipment', label: 'Equipment / Technology', kind: 'list', hint: 'One item per line (optional)' }
      ],
      columns: ['name', 'image', 'accent'],
      tableRow: function (d) {
        return '<td><div class="cell-title">' + esc(d.name || '') + '</div><div class="cell-sub">' + esc(d.tagline || '') + '</div></td>' +
          '<td><img class="cell-thumb" src="' + esc(d.image || '') + '" alt="" loading="lazy"></td>' +
          '<td><span class="badge ' + (d.accent === 'green' ? 'badge-green' : 'badge-blue') + '">' + esc(d.accent || 'blue') + '</span></td>';
      }
    },
    products: {
      fields: [
        { name: 'division', label: 'Division', kind: 'select', options: Object.keys(DIVISIONS), required: true },
        { name: 'name', label: 'Product / Service Name', required: true },
        { name: 'category', label: 'Category', hint: 'e.g. Building Construction, Freight, Extraction…' },
        { name: 'image', label: 'Image', kind: 'image', hint: 'Upload an image from your computer, or paste an image URL.' },
        { name: 'description', label: 'Description', kind: 'textarea' },
        { name: 'features', label: 'Features', kind: 'list', hint: 'One feature per line' },
        { name: 'specifications', label: 'Specifications', kind: 'map', hint: 'One "Key: value" per line' },
        { name: 'location', label: 'Location' },
        { name: 'availability', label: 'Availability' },
        { name: 'transport_type', label: 'Transport Type' },
        { name: 'coverage', label: 'Coverage' },
        { name: 'gallery', label: 'Gallery Images', kind: 'gallery', hint: 'Add photos from your computer, or paste image URLs (optional)' }
      ],
      columns: ['image', 'name', 'division'],
      tableRow: function (d) {
        return '<td><img class="cell-thumb" src="' + esc(d.image || '') + '" alt="" loading="lazy"></td>' +
          '<td><div class="cell-title">' + esc(d.name || '') + '</div><div class="cell-sub">' + esc(d.category || '') + '</div></td>' +
          '<td><span class="badge badge-blue">' + esc(DIVISIONS[d.division] || d.division || '') + '</span></td>';
      }
    },
    properties: {
      fields: [
        { name: 'name', label: 'Property Name', required: true },
        { name: 'type', label: 'Type', kind: 'select', options: ['House', 'Apartment', 'Land', 'Office', 'Other'] },
        { name: 'category', label: 'Category', kind: 'select', options: ['Residential', 'Commercial', 'Land'] },
        { name: 'status', label: 'Status', kind: 'select', options: ['Available', 'Reserved', 'Sold', 'Coming Soon'] },
        { name: 'price', label: 'Price', hint: 'e.g. $250,000 or On Request' },
        { name: 'size', label: 'Size', hint: 'e.g. 350 m²' },
        { name: 'bedrooms', label: 'Bedrooms', kind: 'number' },
        { name: 'bathrooms', label: 'Bathrooms', kind: 'number' },
        { name: 'location', label: 'Location' },
        { name: 'image', label: 'Main Image', kind: 'image', hint: 'Upload an image from your computer, or paste an image URL.' },
        { name: 'gallery', label: 'Image Gallery', kind: 'gallery', hint: 'Add photos from your computer, or paste image URLs (optional)' },
        { name: 'features', label: 'Features', kind: 'list', hint: 'One feature per line' },
        { name: 'description', label: 'Description', kind: 'textarea' }
      ],
      columns: ['image', 'name', 'type', 'price'],
      tableRow: function (d) {
        return '<td><img class="cell-thumb" src="' + esc(d.image || '') + '" alt="" loading="lazy"></td>' +
          '<td><div class="cell-title">' + esc(d.name || '') + '</div><div class="cell-sub">' + esc(d.location || '') + '</div></td>' +
          '<td><span class="badge badge-blue">' + esc(d.type || '') + '</span> <span class="badge ' + (d.status === 'Available' ? 'badge-green' : 'badge-gray') + '">' + esc(d.status || '') + '</span></td>' +
          '<td>' + esc(d.price || '') + '</td>';
      }
    },
    projects: {
      fields: [
        { name: 'name', label: 'Project Name', required: true },
        { name: 'category', label: 'Category', kind: 'select', options: ['Construction', 'Mining', 'Real Estate', 'Transportation', 'Imports/Exports & Distribution'] },
        { name: 'location', label: 'Location' },
        { name: 'status', label: 'Status', kind: 'select', options: ['Completed', 'Ongoing', 'In Progress', 'Planned'] },
        { name: 'date', label: 'Completion / Year', hint: 'e.g. 2025 or Q2 2026' },
        { name: 'image', label: 'Main Image', kind: 'image', hint: 'Upload an image from your computer, or paste an image URL.' },
        { name: 'images', label: 'Additional Images', kind: 'gallery', hint: 'Add more photos from your computer, or paste image URLs (optional)' },
        { name: 'description', label: 'Description', kind: 'textarea' }
      ],
      columns: ['image', 'name', 'category', 'status'],
      tableRow: function (d) {
        return '<td><img class="cell-thumb" src="' + esc(d.image || '') + '" alt="" loading="lazy"></td>' +
          '<td><div class="cell-title">' + esc(d.name || '') + '</div><div class="cell-sub">' + esc(d.location || '') + '</div></td>' +
          '<td><span class="badge badge-blue">' + esc(d.category || '') + '</span></td>' +
          '<td><span class="badge ' + (d.status === 'Completed' ? 'badge-green' : 'badge-gray') + '">' + esc(d.status || '') + '</span></td>';
      }
    },
    team: {
      fields: [
        { name: 'name', label: 'Full Name', required: true },
        { name: 'role', label: 'Job Title / Role', required: true },
        { name: 'bio', label: 'Bio', kind: 'textarea' },
        { name: 'image', label: 'Photo', kind: 'image', hint: 'Upload a photo from your computer, or paste an image URL.' },
        { name: 'social_instagram', label: 'Instagram URL' },
        { name: 'social_facebook', label: 'Facebook URL' },
        { name: 'social_linkedin', label: 'LinkedIn URL' },
        { name: 'social_discord', label: 'Discord URL' }
      ],
      columns: ['image', 'name', 'role'],
      tableRow: function (d) {
        return '<td><img class="cell-thumb" src="' + esc(d.image || '') + '" alt="" loading="lazy"></td>' +
          '<td><div class="cell-title">' + esc(d.name || '') + '</div><div class="cell-sub">' + esc(d.bio || '') + '</div></td>' +
          '<td><span class="badge badge-blue">' + esc(d.role || '') + '</span></td>';
      }
    }
  };

  /* ---------- List / map conversions ---------- */
  function toList(val) {
    if (Array.isArray(val)) return val;
    if (!val) return [];
    return String(val).split('\n').map(function (s) { return s.trim(); }).filter(Boolean);
  }
  function fromList(textarea) {
    return textarea.value.split('\n').map(function (s) { return s.trim(); }).filter(Boolean);
  }
  function toMap(val) {
    if (val && typeof val === 'object' && !Array.isArray(val)) return val;
    var out = {};
    if (!val) return out;
    String(val).split('\n').forEach(function (line) {
      var idx = line.indexOf(':');
      if (idx > 0) {
        var k = line.slice(0, idx).trim();
        var v = line.slice(idx + 1).trim();
        if (k) out[k] = v;
      }
    });
    return out;
  }
  function fromMap(textarea) {
    var out = {};
    textarea.value.split('\n').forEach(function (line) {
      var idx = line.indexOf(':');
      if (idx > 0) {
        var k = line.slice(0, idx).trim();
        var v = line.slice(idx + 1).trim();
        if (k) out[k] = v;
      }
    });
    return out;
  }

  /* ---------- Image uploads ---------- */
  function uploadImage(file) {
    var fd = new FormData();
    fd.append('file', file);
    return fetch('../api/upload.php', { method: 'POST', body: fd }).then(function (r) {
      return r.json().then(function (j) {
        if (!r.ok || !j.ok) { throw new Error((j && j.error) || 'Upload failed'); }
        return j;
      });
    });
  }

  /* Single-image field: preview + upload button + path/URL input */
  function initImageField(wrap, val) {
    var preview = wrap.querySelector('.iu-preview');
    var fileInput = wrap.querySelector('.iu-file');
    var pathInput = wrap.querySelector('.iu-path');
    var pickBtn = wrap.querySelector('.iu-pick');

    function setPreview(src) {
      if (src) {
        preview.classList.remove('empty');
        preview.innerHTML = '<img src="' + esc(src) + '" alt="">';
      } else {
        preview.classList.add('empty');
        preview.innerHTML = '<span>No image yet</span>';
      }
    }
    setPreview(val);

    pickBtn.addEventListener('click', function () { fileInput.click(); });
    fileInput.addEventListener('change', function () {
      if (!fileInput.files || !fileInput.files.length) return;
      var file = fileInput.files[0];
      pickBtn.disabled = true;
      pickBtn.textContent = 'Uploading…';
      uploadImage(file).then(function (res) {
        pathInput.value = res.url;
        setPreview(res.url);
        toast('Image uploaded');
        pickBtn.disabled = false;
        pickBtn.textContent = 'Upload Image';
      }).catch(function (err) {
        toast(err.message || 'Upload failed', 'error');
        pickBtn.disabled = false;
        pickBtn.textContent = 'Upload Image';
      });
      fileInput.value = '';
    });
    pathInput.addEventListener('input', function () { setPreview(pathInput.value); });
  }

  /* Multi-photo gallery field: thumbnails + add photos (multi-upload) + paste URL */
  function initGalleryField(wrap, list) {
    var container = wrap.querySelector('.gallery-upload') || wrap;
    container.innerHTML =
      '<div class="gu-grid"></div>' +
      '<div class="gu-actions">' +
        '<button type="button" class="btn btn-ghost btn-sm gu-pick">+ Add Photos</button>' +
        '<input type="file" class="gu-file" accept="image/*" multiple hidden>' +
        '<span class="gu-sep">or</span>' +
        '<input type="text" class="gu-url" placeholder="Paste an image URL">' +
        '<button type="button" class="btn btn-ghost btn-sm gu-addurl" disabled>Add</button>' +
      '</div>';
    var grid = container.querySelector('.gu-grid');
    var fileInput = container.querySelector('.gu-file');
    var pickBtn = container.querySelector('.gu-pick');
    var urlInput = container.querySelector('.gu-url');
    var addUrlBtn = container.querySelector('.gu-addurl');
    var items = (list || []).slice();

    function render() {
      grid.innerHTML = items.length
        ? items.map(function (src, i) {
            return '<div class="gu-thumb" data-src="' + esc(src) + '">' +
              '<img src="' + esc(src) + '" alt="Photo ' + (i + 1) + '" loading="lazy">' +
              '<button type="button" class="gu-remove" title="Remove photo">&times;</button>' +
            '</div>';
          }).join('')
        : '<p class="gu-empty">No photos yet — upload some from your computer, or paste image URLs.</p>';
    }
    render();

    grid.addEventListener('click', function (e) {
      var rm = e.target.closest('.gu-remove');
      if (!rm) return;
      var idx = Array.prototype.indexOf.call(grid.children, rm.closest('.gu-thumb'));
      if (idx >= 0) { items.splice(idx, 1); render(); }
    });

    pickBtn.addEventListener('click', function () { fileInput.click(); });
    fileInput.addEventListener('change', function () {
      var files = fileInput.files;
      if (!files || !files.length) return;
      var queue = Array.prototype.slice.call(files);
      var total = queue.length;
      var done = 0;
      pickBtn.disabled = true;
      function next() {
        if (!queue.length) {
          pickBtn.disabled = false;
          pickBtn.textContent = '+ Add Photos';
          fileInput.value = '';
          if (done > 0) toast(done + ' photo' + (done > 1 ? 's' : '') + ' uploaded');
          return;
        }
        var file = queue.shift();
        pickBtn.textContent = 'Uploading ' + (done + 1) + '/' + total + '…';
        uploadImage(file).then(function (res) {
          done++;
          items.push(res.url);
          render();
          next();
        }).catch(function (err) {
          toast(err.message || 'Upload failed', 'error');
          done++;
          next();
        });
      }
      next();
    });

    urlInput.addEventListener('input', function () { addUrlBtn.disabled = !urlInput.value.trim(); });
    urlInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); if (!addUrlBtn.disabled) addUrlBtn.click(); }
    });
    addUrlBtn.addEventListener('click', function () {
      var v = urlInput.value.trim();
      if (!v) return;
      items.push(v);
      urlInput.value = '';
      addUrlBtn.disabled = true;
      render();
    });
  }

  /* ---------- Modal form ---------- */
  var backdrop = null;
  function ensureBackdrop() {
    if (backdrop) return backdrop;
    backdrop = document.createElement('div');
    backdrop.className = 'modal-form-backdrop';
    backdrop.innerHTML = '<div class="modal-form"><div class="modal-form-head"><h3></h3><button type="button" class="modal-close" aria-label="Close">&times;</button></div><form class="form-grid"></form><div class="form-actions"></div></div>';
    document.body.appendChild(backdrop);
    backdrop.addEventListener('click', function (e) {
      if (e.target === backdrop || e.target.closest('.modal-close')) closeForm();
    });
    return backdrop;
  }
  function closeForm() {
    if (backdrop) backdrop.classList.remove('open');
  }
  function openForm(title, schema, item, onSave) {
    var bd = ensureBackdrop();
    bd.querySelector('.modal-form-head h3').textContent = title;
    var form = bd.querySelector('form');
    form.innerHTML = '';
    var fields = schema.fields;
    fields.forEach(function (f) {
      var val = item ? (item[f.name] != null ? item[f.name] : '') : '';
      var label = '<label for="f-' + f.name + '">' + esc(f.label) + (f.required ? ' <span style="color:#08ac3d">*</span>' : '') + '</label>';
      var input = '';
      if (f.kind === 'select') {
        input = '<select id="f-' + f.name + '" name="' + f.name + '"' + (f.required ? ' required' : '') + '>' +
          f.options.map(function (o) { return '<option value="' + esc(o) + '"' + (String(val) === String(o) ? ' selected' : '') + '>' + esc(o) + '</option>'; }).join('') +
          '</select>';
      } else if (f.kind === 'textarea') {
        input = '<textarea id="f-' + f.name + '" name="' + f.name + '"' + (f.required ? ' required' : '') + '>' + esc(val) + '</textarea>';
      } else if (f.kind === 'number') {
        input = '<input type="number" id="f-' + f.name + '" name="' + f.name + '" value="' + esc(val) + '">';
      } else if (f.kind === 'list') {
        input = '<textarea id="f-' + f.name + '" name="' + f.name + '" placeholder="One per line">' + esc(toList(val).join('\n')) + '</textarea>';
      } else if (f.kind === 'map') {
        var mapLines = Object.keys(toMap(val)).map(function (k) { return k + ': ' + toMap(val)[k]; }).join('\n');
        input = '<textarea id="f-' + f.name + '" name="' + f.name + '" placeholder="Key: value">' + esc(mapLines) + '</textarea>';
      } else if (f.kind === 'image') {
        input = '<div class="img-uploader">' +
          '<div class="iu-preview' + (val ? '' : ' empty') + '"></div>' +
          '<div class="iu-row">' +
            '<button type="button" class="btn btn-ghost btn-sm iu-pick">Upload Image</button>' +
            '<input type="file" class="iu-file" accept="image/*" hidden>' +
            '<input type="text" id="f-' + f.name + '" name="' + f.name + '" class="iu-path" value="' + esc(val) + '" placeholder="/assets/uploads/… or https://…"' + (f.required ? ' required' : '') + '>' +
          '</div>' +
        '</div>';
      } else if (f.kind === 'gallery') {
        input = '<div id="f-' + f.name + '" class="gallery-upload"></div>';
      } else {
        input = '<input type="text" id="f-' + f.name + '" name="' + f.name + '" value="' + esc(val) + '"' + (f.required ? ' required' : '') + '>';
      }
      var hint = f.hint ? '<span class="hint">' + esc(f.hint) + '</span>' : '';
      var wrap = document.createElement('div');
      wrap.className = 'form-group' + (f.kind === 'textarea' || f.kind === 'list' || f.kind === 'map' || f.kind === 'image' || f.kind === 'gallery' ? ' full' : '');
      wrap.innerHTML = label + input + hint;
      form.appendChild(wrap);
      if (f.kind === 'image') initImageField(wrap, val);
      else if (f.kind === 'gallery') initGalleryField(wrap, toList(val));
    });

    var actions = bd.querySelector('.form-actions');
    actions.innerHTML = '<button type="button" class="btn btn-ghost" data-cancel>Cancel</button>' +
      '<button type="submit" class="btn btn-green" data-save>Save Changes</button>';
    actions.querySelector('[data-cancel]').addEventListener('click', closeForm);

    function doSave() {
      if (!form.checkValidity()) { form.reportValidity(); return; }
      var data = {};
      fields.forEach(function (f) {
        var el = document.getElementById('f-' + f.name);
        if (!el) return;
        if (f.kind === 'list') data[f.name] = fromList(el);
        else if (f.kind === 'map') data[f.name] = fromMap(el);
        else if (f.kind === 'gallery') {
          data[f.name] = Array.prototype.map.call(el.querySelectorAll('.gu-thumb'), function (t) {
            return t.getAttribute('data-src');
          }).filter(Boolean);
        }
        else if (f.kind === 'number') data[f.name] = el.value === '' ? 0 : parseInt(el.value, 10) || 0;
        else data[f.name] = el.value.trim();
      });
      if (item && item.id) data.id = item.id;
      var saveBtn = actions.querySelector('[data-save]');
      saveBtn.disabled = true;
      saveBtn.textContent = 'Saving…';
      onSave(data).then(function () {
        closeForm();
        toast('Saved successfully');
      }).catch(function (err) {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Save Changes';
        toast(err.message || 'Could not save', 'error');
      });
    }
    /* The Save button lives outside the <form> element, so it needs an
       explicit click handler (form submit still covers Enter-key saves). */
    form.addEventListener('submit', function (e) { e.preventDefault(); doSave(); });
    actions.querySelector('[data-save]').addEventListener('click', function (e) { e.preventDefault(); doSave(); });
    bd.classList.add('open');
  }

  /* ---------- Trash (soft-deleted items) ---------- */
  var activeCrudReload = null; /* set by renderCrud; lets the trash modal refresh the main table */
  var trashBackdrop = null;
  function closeTrashModal() {
    if (trashBackdrop) trashBackdrop.classList.remove('open');
  }
  function openTrashModal(resource) {
    if (!trashBackdrop) {
      trashBackdrop = document.createElement('div');
      trashBackdrop.className = 'confirm-backdrop trash-backdrop';
      trashBackdrop.innerHTML =
        '<div class="confirm-modal trash-modal">' +
          '<div class="confirm-head"><h3></h3><button type="button" class="modal-close" aria-label="Close">&times;</button></div>' +
          '<div class="trash-body"></div>' +
          '<div class="confirm-actions trash-actions"></div>' +
        '</div>';
      document.body.appendChild(trashBackdrop);
      trashBackdrop.addEventListener('click', function (e) {
        if (e.target === trashBackdrop || e.target.closest('.modal-close')) closeTrashModal();
      });
      document.addEventListener('keydown', function (e) {
        if (trashBackdrop.classList.contains('open') && e.key === 'Escape') closeTrashModal();
      });
    }
    var head = trashBackdrop.querySelector('.confirm-head h3');
    var body = trashBackdrop.querySelector('.trash-body');
    var actions = trashBackdrop.querySelector('.trash-actions');
    head.textContent = 'Trash — ' + resource.charAt(0).toUpperCase() + resource.slice(1);

    function render(list) {
      body.innerHTML = list.length
        ? '<div class="trash-list">' + list.map(function (d) {
            return '<div class="trash-item">' +
              '<img class="cell-thumb trash-thumb" src="' + esc(d.image || '') + '" alt="" loading="lazy" onerror="this.style.visibility=\'hidden\'">' +
              '<div class="trash-info">' +
                '<strong>' + esc(d.name || 'Untitled') + '</strong>' +
                '<span class="cell-sub">' + esc(d.category || d.type || d.tagline || d.location || '') + '</span>' +
                (d.deleted_at ? '<span class="cell-sub">Deleted ' + esc(d.deleted_at) + '</span>' : '') +
              '</div>' +
              '<div class="row-actions">' +
                '<button type="button" class="btn btn-ghost btn-sm" data-restore="' + esc(d.id) + '">Restore</button>' +
                '<button type="button" class="btn btn-danger btn-sm" data-purge="' + esc(d.id) + '">Delete Forever</button>' +
              '</div>' +
            '</div>';
          }).join('') + '</div>'
        : '<p class="trash-empty">Trash is empty. Deleted items will appear here.</p>';

      actions.innerHTML =
        '<button type="button" class="btn btn-ghost" data-close>Close</button>' +
        (list.length ? '<button type="button" class="btn btn-danger btn-sm" data-empty>Empty Trash</button>' : '');
      actions.querySelector('[data-close]').addEventListener('click', closeTrashModal);

      body.querySelectorAll('[data-restore]').forEach(function (b) {
        b.addEventListener('click', function () {
          api(resource, 'restore', { id: b.getAttribute('data-restore') }).then(function () {
            toast('Item restored');
            load();
            if (activeCrudReload) activeCrudReload();
          }).catch(function (err) { toast(err.message, 'error'); });
        });
      });
      body.querySelectorAll('[data-purge]').forEach(function (b) {
        b.addEventListener('click', function () {
          var id = b.getAttribute('data-purge');
          var item = list.filter(function (x) { return x.id === id; })[0];
          var label = (item && item.name) ? item.name : 'this item';
          uiConfirm('Permanently delete "' + label + '"? This cannot be undone.', { title: 'Delete forever', confirmLabel: 'Delete Forever' }).then(function (ok) {
            if (!ok) return;
            api(resource, 'purge', { id: id }).then(function () {
              toast('Permanently deleted'); load();
            }).catch(function (err) { toast(err.message, 'error'); });
          });
        });
      });
      var emptyBtn = actions.querySelector('[data-empty]');
      if (emptyBtn) {
        emptyBtn.addEventListener('click', function () {
          uiConfirm('Permanently delete ALL items in the trash? This cannot be undone.', { title: 'Empty trash', confirmLabel: 'Empty Trash' }).then(function (ok) {
            if (!ok) return;
            api(resource, 'empty_trash').then(function () {
              toast('Trash emptied'); load();
            }).catch(function (err) { toast(err.message, 'error'); });
          });
        });
      }
    }

    function load() {
      api(resource, 'trash_list').then(function (res) {
        render(res.data || []);
      }).catch(function (err) {
        body.innerHTML = '<p class="trash-empty">' + esc(err.message) + '</p>';
      });
    }
    load();
    trashBackdrop.classList.add('open');
  }

  /* ---------- CRUD page rendering ---------- */
  function renderCrud(resource) {
    var schema = SCHEMAS[resource];
    if (!schema) return;
    var tableWrap = document.getElementById('crudTable');
    var addBtn = document.getElementById('addBtn');
    activeCrudReload = load;

    /* Trash button in the panel head */
    var panelHead = document.querySelector('.panel-head');
    if (panelHead && !panelHead.querySelector('#trashBtn')) {
      var trashBtn = document.createElement('button');
      trashBtn.type = 'button';
      trashBtn.className = 'btn btn-sm btn-ghost';
      trashBtn.id = 'trashBtn';
      trashBtn.textContent = 'Trash';
      panelHead.appendChild(trashBtn);
      trashBtn.addEventListener('click', function () { openTrashModal(resource); });
    }

    function render(list) {
      if (!list.length) {
        tableWrap.innerHTML = '<p class="panel-empty">Nothing here yet. Click “Add” to create the first item.</p>';
        return;
      }
      var head = '<table class="crud-table"><thead><tr><th>' + schema.columns.map(function (c) {
        return { name: 'Name', image: 'Image', accent: 'Accent', division: 'Division', type: 'Type / Status', category: 'Category', status: 'Status', price: 'Price' }[c];
      }).join('</th><th>') + '</th><th style="width:120px">Actions</th></tr></thead><tbody>';
      var rows = list.map(function (d) {
        return '<tr>' + schema.tableRow(d) +
          '<td><div class="row-actions">' +
          '<button type="button" class="btn btn-ghost btn-sm" data-edit="' + esc(d.id) + '">Edit</button>' +
          '<button type="button" class="btn btn-danger btn-sm" data-del="' + esc(d.id) + '">Delete</button>' +
          '</div></td></tr>';
      }).join('');
      tableWrap.innerHTML = head + rows + '</tbody></table>';

      tableWrap.querySelectorAll('[data-edit]').forEach(function (b) {
        b.addEventListener('click', function () {
          var item = list.filter(function (x) { return x.id === b.getAttribute('data-edit'); })[0];
          openForm('Edit ' + (item.name || 'Item'), schema, item, function (data) {
            return api(resource, 'update', data).then(function () { return load(); });
          });
        });
      });
      tableWrap.querySelectorAll('[data-del]').forEach(function (b) {
        b.addEventListener('click', function () {
          var id = b.getAttribute('data-del');
          var item = list.filter(function (x) { return x.id === id; })[0];
          var label = (item && item.name) ? item.name : 'this item';
          uiConfirm('Move "' + label + '" to the trash?', { title: 'Move to trash', confirmLabel: 'Move to Trash', danger: false }).then(function (ok) {
            if (!ok) return;
            api(resource, 'delete', { id: id }).then(function () { toast('Moved "' + label + '" to trash'); load(); }).catch(function (err) { toast(err.message, 'error'); });
          });
        });
      });
    }

    function load() {
      return api(resource, 'list').then(function (res) {
        var list = res.data || [];
        var filter = document.getElementById('divisionFilter');
        if (filter && filter.value) {
          list = list.filter(function (x) { return x.division === filter.value; });
        }
        render(list);
      }).catch(function (err) { tableWrap.innerHTML = '<p class="panel-empty">' + esc(err.message) + '</p>'; });
    }

    if (addBtn) {
      addBtn.addEventListener('click', function () {
        openForm('Add New', schema, null, function (data) {
          return api(resource, 'create', data).then(function () { return load(); });
        });
      });
    }
    var filter = document.getElementById('divisionFilter');
    if (filter) filter.addEventListener('change', load);
    load();
  }

  /* ================================================================
     Messages page
     ================================================================ */
  function renderMessages() {
    var listEl = document.getElementById('inquiriesList');
    api('inquiries', 'list').then(function (res) {
      var list = res.data || [];
      if (!list.length) {
        listEl.innerHTML = '<p class="panel-empty">No inquiries yet. Messages from the website contact form will appear here.</p>';
        return;
      }
      listEl.innerHTML = '<div class="inquiry-list">' + list.map(function (q) {
        return '<article class="inquiry-item' + (q.read ? '' : ' unread') + '" data-id="' + esc(q.id) + '">' +
          '<div class="iq-head">' +
            '<span class="iq-name">' + esc(q.name) + '</span>' +
            '<span class="iq-email">' + esc(q.email) + '</span>' +
            '<span class="badge badge-blue iq-interest">' + esc(q.interest || '') + '</span>' +
          '</div>' +
          (q.subject ? '<p class="iq-subject">' + esc(q.subject) + '</p>' : '') +
          '<div class="iq-message">' + esc(q.message) + '</div>' +
          '<div class="iq-meta">' +
            '<span>📞 ' + esc(q.phone || '—') + '</span>' +
            '<span>🏢 ' + esc(q.company || '—') + '</span>' +
            '<span>🕒 ' + esc(q.created_at || '') + '</span>' +
          '</div>' +
          '<div class="iq-actions">' +
            (q.read ? '' : '<button type="button" class="btn btn-ghost btn-sm" data-read="' + esc(q.id) + '">Mark as Read</button>') +
            '<a class="btn btn-ghost btn-sm" href="mailto:' + esc(q.email) + '?subject=' + encodeURIComponent('Re: ' + (q.subject || 'Your inquiry')) + '">Reply by Email</a>' +
            '<button type="button" class="btn btn-danger btn-sm" data-del="' + esc(q.id) + '">Delete</button>' +
          '</div>' +
        '</article>';
      }).join('') + '</div>';

      listEl.addEventListener('click', function (e) {
        var del = e.target.closest('[data-del]');
        if (del) {
          var qid = del.getAttribute('data-del');
          var inq = list.filter(function (x) { return x.id === qid; })[0];
          var who = (inq && inq.name) ? inq.name : ((inq && inq.email) ? inq.email : 'this inquiry');
          uiConfirm('Delete this inquiry from ' + who + '?', { title: 'Delete inquiry', confirmLabel: 'Delete' }).then(function (ok) {
            if (!ok) return;
            api('inquiries', 'delete', { id: qid }).then(function () { toast('Deleted'); renderMessages(); }).catch(function (err) { toast(err.message, 'error'); });
          });
          return;
        }
        var rd = e.target.closest('[data-read]');
        if (rd) {
          api('inquiries', 'mark_read', { id: rd.getAttribute('data-read') }).then(function () { renderMessages(); }).catch(function (err) { toast(err.message, 'error'); });
        }
      });
    }).catch(function (err) { listEl.innerHTML = '<p class="panel-empty">' + esc(err.message) + '</p>'; });
  }

  /* ================================================================
     Company page
     ================================================================ */
  function renderCompany() {
    var root = document.getElementById('companyForm');
    api('company', 'get').then(function (res) {
      var c = res.data || {};
      var statsRows = (c.stats || []).map(function (s) {
        return '<div class="stats-row">' +
          '<input type="text" data-stat-label value="' + esc(s.label) + '" placeholder="Label">' +
          '<input type="number" data-stat-value value="' + esc(s.value) + '" placeholder="Value">' +
          '<input type="text" data-stat-suffix value="' + esc(s.suffix || '') + '" placeholder="+">' +
          '<button type="button" class="icon-btn" data-stat-del title="Remove">×</button>' +
        '</div>';
      }).join('');

      root.innerHTML =
        '<form id="companyFormInner">' +
        '<div class="form-grid">' +
          '<div class="form-group"><label>Company Name</label><input type="text" data-c="name" value="' + esc(c.name || '') + '"></div>' +
          '<div class="form-group"><label>Short Name</label><input type="text" data-c="short_name" value="' + esc(c.short_name || '') + '"></div>' +
          '<div class="form-group full"><label>Tagline</label><input type="text" data-c="tagline" value="' + esc(c.tagline || '') + '"></div>' +
          '<div class="form-group full"><label>Company Description</label><textarea data-c="description">' + esc(c.description || '') + '</textarea></div>' +
          '<div class="form-group full"><label>Who We Are</label><textarea data-c="who_we_are">' + esc(c.who_we_are || '') + '</textarea></div>' +
          '<div class="form-group full"><label>Mission</label><textarea data-c="mission">' + esc(c.mission || '') + '</textarea></div>' +
          '<div class="form-group full"><label>Vision</label><textarea data-c="vision">' + esc(c.vision || '') + '</textarea></div>' +
          '<div class="form-group full"><label>Values</label><textarea data-c="values" placeholder="One value per line">' + esc(toList(c.values).join('\n')) + '</textarea></div>' +
          '<div class="form-group"><label>Phone</label><input type="text" data-c="phone" value="' + esc(c.phone || '') + '"></div>' +
          '<div class="form-group"><label>Phone 2</label><input type="text" data-c="phone2" value="' + esc(c.phone2 || '') + '"></div>' +
          '<div class="form-group"><label>Email</label><input type="email" data-c="email" value="' + esc(c.email || '') + '"></div>' +
          '<div class="form-group"><label>WhatsApp</label><input type="text" data-c="whatsapp" value="' + esc(c.whatsapp || '') + '" placeholder="+000 000 000 000"></div>' +
          '<div class="form-group full"><label>Address</label><input type="text" data-c="address" value="' + esc(c.address || '') + '"></div>' +
          '<div class="form-group full"><label>Working Hours</label><input type="text" data-c="hours" value="' + esc(c.hours || '') + '"></div>' +
          '<div class="form-group full"><label>Map Embed URL</label><input type="text" data-c="map_embed" value="' + esc(c.map_embed || '') + '" placeholder="https://www.google.com/maps/embed?pb=…"><span class="hint">Optional Google Maps embed iframe src</span></div>' +
          '<div class="form-group full"><label>Social — Facebook</label><input type="text" data-c="social_facebook" value="' + esc((c.social || {}).facebook || '') + '"></div>' +
          '<div class="form-group"><label>Social — Instagram</label><input type="text" data-c="social_instagram" value="' + esc((c.social || {}).instagram || '') + '"></div>' +
          '<div class="form-group"><label>Social — LinkedIn</label><input type="text" data-c="social_linkedin" value="' + esc((c.social || {}).linkedin || '') + '"></div>' +
          '<div class="form-group"><label>Social — YouTube</label><input type="text" data-c="social_youtube" value="' + esc((c.social || {}).youtube || '') + '"></div>' +
          '<div class="form-group"><label>Social — X / Twitter</label><input type="text" data-c="social_x" value="' + esc((c.social || {}).x || '') + '"></div>' +
          '<div class="form-group full"><label>Statistics</label><span class="hint">Shown across the site. Edit values freely — placeholders until you have real figures.</span><div class="stats-editor" id="statsEditor">' + statsRows + '</div>' +
            '<button type="button" class="btn btn-ghost btn-sm" id="addStat" style="align-self:flex-start">+ Add Statistic</button></div>' +
        '</div>' +
        '<div class="form-actions">' +
          '<button type="submit" class="btn btn-green">Save Company Information</button>' +
        '</div>' +
        '</form>';

      var editor = root.querySelector('#statsEditor');
      function addStatRow(s) {
        s = s || {};
        var row = document.createElement('div');
        row.className = 'stats-row';
        row.innerHTML =
          '<input type="text" data-stat-label value="' + esc(s.label || '') + '" placeholder="Label">' +
          '<input type="number" data-stat-value value="' + esc(s.value || '') + '" placeholder="Value">' +
          '<input type="text" data-stat-suffix value="' + esc(s.suffix || '') + '" placeholder="+">' +
          '<button type="button" class="icon-btn" data-stat-del title="Remove">×</button>';
        editor.appendChild(row);
      }
      editor.addEventListener('click', function (e) {
        if (e.target.closest('[data-stat-del]')) e.target.closest('.stats-row').remove();
      });
      root.querySelector('#addStat').addEventListener('click', function () { addStatRow(); });

      root.querySelector('#companyFormInner').addEventListener('submit', function (e) {
        e.preventDefault();
        var get = function (key) { var el = root.querySelector('[data-c="' + key + '"]'); return el ? el.value.trim() : ''; };
        var stats = Array.prototype.map.call(root.querySelectorAll('.stats-row'), function (row) {
          return {
            label: row.querySelector('[data-stat-label]').value.trim(),
            value: parseInt(row.querySelector('[data-stat-value]').value, 10) || 0,
            suffix: row.querySelector('[data-stat-suffix]').value.trim()
          };
        }).filter(function (s) { return s.label || s.value; });
        var payload = {
          name: get('name'), short_name: get('short_name'), tagline: get('tagline'),
          description: get('description'), who_we_are: get('who_we_are'),
          mission: get('mission'), vision: get('vision'),
          values: fromList(root.querySelector('[data-c="values"]')),
          stats: stats,
          phone: get('phone'), phone2: get('phone2'), email: get('email'),
          whatsapp: get('whatsapp'), address: get('address'), hours: get('hours'),
          map_embed: get('map_embed'),
          social: {
            facebook: get('social_facebook'), instagram: get('social_instagram'),
            linkedin: get('social_linkedin'), youtube: get('social_youtube'), x: get('social_x')
          }
        };
        var btn = e.target.querySelector('button[type="submit"]');
        btn.disabled = true; btn.textContent = 'Saving…';
        api('company', 'save', payload).then(function () {
          toast('Company information saved');
          btn.disabled = false; btn.textContent = 'Save Company Information';
        }).catch(function (err) {
          btn.disabled = false; btn.textContent = 'Save Company Information';
          toast(err.message || 'Could not save', 'error');
        });
      });
    }).catch(function (err) { root.innerHTML = '<p class="panel-empty">' + esc(err.message) + '</p>'; });
  }

  /* ---------- Dispatch ---------- */
  if (page === 'services' || page === 'products' || page === 'properties' || page === 'projects' || page === 'team') {
    renderCrud(page);
  } else if (page === 'messages') {
    renderMessages();
  } else if (page === 'company') {
    renderCompany();
  }
})();
