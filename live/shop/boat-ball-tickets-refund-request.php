<?php
require_once('../../config/config.php');
$state = 'init';
if (!DEBUG) {
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    if (!$as->isAuthenticated()) {
        $state = 'not-authenticated';
    } else {
        $attributes = $as->getAttributes();
        $user_info = array(
            'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0]
        );
    }
} else {
    $user_info = array(
        'username' => 'exampleusername'
    );
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

}

/**
 * Render page
 */
$title = 'Boat Ball Ticket Refund Request - ECSS';
include('../includes/templates/header.php');
?>
<div class="container">
    <div class="alert alert-danger"><strong>Note:</strong> This page is under development.</div>
    <section>
        <h1>Boat Ball Tickets Refund Request</h1>
        <?php
        if ($state === 'not-authenticated') {
            include('../includes/templates/require-login.php');
        }
        ?>
        <?php if ($state === 'init'): ?>
        <form method="post">
            <p>If you need to request a refund for the boat ball, please fill in the form below and click the "Request refund" button.</p>
            <div class="alert alert-warning"><strong>Note:</strong> the refund request closes on {}.</div>
            <input type="hidden" name="csrftoken" value="">
            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input readonly id="username" class="form-control-plaintext" name="username" value="<?= $user_info['username'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="num-of-tickets" class="col-sm-4 col-form-label">Number of tickets</label>
                <div class="col-sm-8">
                    <input id="num-of-tickets" class="form-control" type="number" name="num-of-tickets" value="1" min="1">
                    <small class="form-text text-muted">If you have requested refund for boat ball tickets before, the number of tickets your request to refund will be updated to this one if you request again.</small>
                </div>
            </div>
            <div class="form-group row">
                <label for="comments" class="col-sm-4 col-form-label">Optional comments</label>
                <div class="col-sm-8">
                    <textarea id="comments" class="form-control" name="comments"></textarea>
                </div>
            </div>
            <button class="btn btn-primary">Request refund</button>
            <small class="form-text text-muted">Once your submitted a boat ball tickets refund request, we will refund your money to your {} by {}, or we will contact you through your university email. <a href="/about/contact.php">Contact us</a> if any questions.</small>
        </form>
        <?php endif ?>
    </section>
</div>

<?php
include('../includes/templates/footer.php');
