<?php
http_response_code(404);
$title = "Page Not Found - ECSS";
include(__DIR__.'/../templates/header.php');
?>
<div class="container">
    <section class="alert alert-danger">
        <h1>Page Not Found.</h1>
        <p><a href="/" class="alert-link">Go to homepage.</a></p>
    </section>
</div>
<?php
include(__DIR__.'/../templates/footer.php');
