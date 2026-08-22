<?php
http_response_code(404);
$ROOT = './';
$PAGE_TITLE = 'Page Not Found (404) — BRADTEC CO. LTD';
$PAGE_DESC = 'The page you are looking for could not be found. Explore BRADTEC CO. LTD — construction, mining, real estate and transportation services.';
$PAGE_NOINDEX = true;
require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';
?>
<main>
  <section class="page-hero">
    <div class="bg-img" style="background-image:url('https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1920&q=80')" aria-hidden="true"></div>
    <div class="container">
      <p class="crumb"><a href="<?= $ROOT ?>">Home</a> <span class="sep">/</span> 404</p>
      <h1>This page could not be found.</h1>
      <p>The page you requested may have been moved or no longer exists. Let's get you back on track.</p>
      <div class="accent-bar" aria-hidden="true"></div>
    </div>
  </section>

  <section class="section">
    <div class="container" style="text-align:center;max-width:640px">
      <div class="card" style="padding:56px 40px">
        <div style="font-size:clamp(3.5rem,8vw,6rem);font-weight:800;line-height:1;font-family:var(--font-head);background:linear-gradient(120deg,var(--blue),var(--green));-webkit-background-clip:text;background-clip:text;color:transparent" aria-hidden="true">404</div>
        <h2 style="margin:14px 0 10px">Page Not Found</h2>
        <p class="muted" style="margin-bottom:28px">We couldn't find the page you're looking for. It may have been moved, renamed, or never existed.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
          <a href="<?= $ROOT ?>" class="btn btn-primary">Back to Home</a>
          <a href="<?= $ROOT ?>services" class="btn btn-outline">Explore Services</a>
          <a href="<?= $ROOT ?>contact" class="btn btn-ghost-green">Contact Us</a>
        </div>
        <div class="grid-3" style="margin-top:36px;text-align:left">
          <a href="<?= $ROOT ?>services/construction" class="value-chip" style="justify-content:flex-start">Construction</a>
          <a href="<?= $ROOT ?>services/mining" class="value-chip" style="justify-content:flex-start">Mining</a>
          <a href="<?= $ROOT ?>services/real-estate" class="value-chip" style="justify-content:flex-start">Real Estate</a>
          <a href="<?= $ROOT ?>services/transportation" class="value-chip" style="justify-content:flex-start">Transportation</a>
          <a href="<?= $ROOT ?>projects" class="value-chip" style="justify-content:flex-start">Our Projects</a>
          <a href="<?= $ROOT ?>about" class="value-chip" style="justify-content:flex-start">About Us</a>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
<?php require __DIR__ . '/partials/scripts.php'; ?>
</body>
</html>
