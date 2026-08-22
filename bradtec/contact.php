<?php
$ROOT = './';
$PAGE_TITLE = 'Contact Us — BRADTEC CO. LTD';
$PAGE_DESC = 'Get in touch with BRADTEC CO. LTD. Send an inquiry about construction, mining, real estate, transportation or partnerships.';
$PAGE_OG_IMAGE = 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80';
$PAGE_CRUMBS = [['name' => 'Contact Us', 'url' => '/contact']];
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';

$company = load_company();
$interests = ['Construction', 'Mining', 'Real Estate', 'Transportation', 'Import/Export', 'Partnership', 'General Inquiry'];
?>

<main>
  <section class="page-hero">
    <div class="bg-img" style="background-image:url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1920&q=80')" aria-hidden="true"></div>
    <div class="container">
      <p class="crumb"><a href="<?= $ROOT ?>">Home</a> <span class="sep">/</span> Contact Us</p>
      <h1>Let's Build the Future Together.</h1>
      <p>Reach out to our team for construction, mining, real estate, transportation or partnership opportunities — we respond promptly.</p>
      <div class="accent-bar" aria-hidden="true"></div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="info-grid">
        <div class="card info-card reveal">
          <span class="ic-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2z"/></svg></span>
          <div>
            <h3>Phone</h3>
            <a href="tel:<?= e(preg_replace('/[^0-9+]/', '', $company['phone'])) ?>"><?= e($company['phone']) ?></a>
            <?php if (!empty($company['phone2'])): ?>
            <p><?= e($company['phone2']) ?></p>
            <?php endif; ?>
          </div>
        </div>
        <div class="card info-card reveal reveal-delay-1">
          <span class="ic-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 6L22 7"/></svg></span>
          <div>
            <h3>Email</h3>
            <a href="mailto:<?= e($company['email']) ?>"><?= e($company['email']) ?></a>
          </div>
        </div>
        <div class="card info-card reveal">
          <span class="ic-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
          <div>
            <h3>Address</h3>
            <p><?= e($company['address']) ?></p>
          </div>
        </div>
        <div class="card info-card reveal reveal-delay-1">
          <span class="ic-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.4 8.4 0 0 1-8.5 8.5 8.6 8.6 0 0 1-3.7-.8L3 21l1.8-5.8A8.4 8.4 0 0 1 4 11.5 8.4 8.4 0 0 1 12.5 3a8.4 8.4 0 0 1 8.5 8.5z"/></svg></span>
          <div>
            <h3>WhatsApp</h3>
            <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/', '', $company['whatsapp'])) ?>" target="_blank" rel="noopener">Chat with us on WhatsApp</a>
          </div>
        </div>
        <div class="card info-card reveal">
          <span class="ic-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
          <div>
            <h3>Working Hours</h3>
            <p><?= e($company['hours']) ?></p>
          </div>
        </div>
        <div class="card info-card reveal reveal-delay-1">
          <span class="ic-icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/></svg></span>
          <div>
            <h3>Follow Us</h3>
            <p>
              <a href="<?= e($company['social']['facebook']) ?>" target="_blank" rel="noopener">Facebook</a> ·
              <a href="<?= e($company['social']['instagram']) ?>" target="_blank" rel="noopener">Instagram</a> ·
              <a href="<?= e($company['social']['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Form -->
  <section class="section section-soft" id="inquiry">
    <div class="container split">
      <div class="split-copy reveal">
        <span class="eyebrow">Send an Inquiry</span>
        <h2>Tell Us About Your Project</h2>
        <p>Fill in the form and our team will get back to you as soon as possible. Whether it's a construction quote, a property viewing, or a logistics partnership — we're ready to help.</p>
        <div class="green-rule" aria-hidden="true"></div>
        <ul class="highlight-list">
          <li>
            <span class="hl-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg></span>
            <span><strong>Fast response</strong><p>We reply to every inquiry promptly.</p></span>
          </li>
          <li>
            <span class="hl-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22s8-3.6 8-10V5l-8-3-8 3v7c0 6.4 8 10 8 10z"/></svg></span>
            <span><strong>Confidential</strong><p>Your details stay private and are only used to respond to you.</p></span>
          </li>
        </ul>
      </div>

      <form class="card reveal reveal-delay-1" id="contactForm" style="padding:36px">
        <div class="form-grid">
          <div class="form-group">
            <label for="f-name">Full Name <span class="req">*</span></label>
            <input type="text" id="f-name" name="name" required autocomplete="name" placeholder="Your full name">
          </div>
          <div class="form-group">
            <label for="f-company">Company</label>
            <input type="text" id="f-company" name="company" autocomplete="organization" placeholder="Company name (optional)">
          </div>
          <div class="form-group">
            <label for="f-email">Email <span class="req">*</span></label>
            <input type="email" id="f-email" name="email" required autocomplete="email" placeholder="you@company.com">
          </div>
          <div class="form-group">
            <label for="f-phone">Phone</label>
            <input type="tel" id="f-phone" name="phone" autocomplete="tel" placeholder="+000 000 000 000">
          </div>
          <div class="form-group">
            <label for="f-interest">What are you interested in?</label>
            <select id="f-interest" name="interest">
              <?php foreach ($interests as $it): ?>
              <option value="<?= e($it) ?>"><?= e($it) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="f-subject">Subject</label>
            <input type="text" id="f-subject" name="subject" placeholder="e.g. Request a quote">
          </div>
          <div class="form-group full">
            <label for="f-message">Message <span class="req">*</span></label>
            <textarea id="f-message" name="message" required placeholder="Tell us about your project or inquiry…"></textarea>
          </div>
          <div class="hp-field" aria-hidden="true">
            <label for="f-website">Leave this field empty</label>
            <input type="text" id="f-website" name="website" tabindex="-1" autocomplete="off">
          </div>
          <div class="form-group full">
            <button type="submit" class="btn btn-primary btn-block">Send Inquiry</button>
            <p class="form-note">By submitting, you agree to be contacted by BRADTEC regarding your inquiry.</p>
          </div>
        </div>
      </form>
    </div>
  </section>

  <!-- Map -->
  <section class="section" id="map">
    <div class="container">
      <div class="section-head center reveal">
        <span class="eyebrow">Find Us</span>
        <h2>Our Location</h2>
        <p><?= e($company['address']) ?></p>
      </div>
      <div class="card reveal" style="overflow:hidden;border-radius:var(--radius-lg)">
        <?php if (!empty($company['map_embed'])): ?>
          <div style="position:relative;padding-top:56.25%">
            <iframe src="<?= e($company['map_embed']) ?>" style="position:absolute;inset:0;width:100%;height:100%;border:0" loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade" title="BRADTEC CO. LTD location map"></iframe>
          </div>
        <?php else: ?>
          <div style="padding:70px 30px;text-align:center;background:var(--bg-soft)">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="var(--blue-mid)" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 14px" aria-hidden="true"><path d="M12 21s-7-5.5-7-11a7 7 0 0 1 14 0c0 5.5-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
            <h3 style="margin-bottom:6px">Map placeholder</h3>
            <p class="muted">Add your Google Maps embed in the admin dashboard → Company Information.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
