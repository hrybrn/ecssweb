<?php
require_once('../../config/config.php');
if (!DEBUG) {
    require_once('/var/www/auth/lib/_autoload.php');
    $as = new SimpleSAML_Auth_Simple('default-sp');
    if (!$as->isAuthenticated()) { // if not authenticated
        // set state
        $state = 'not-authenticated';
    } else { // if authenticated
        $attributes = $as->getAttributes();
        $user_info = array(
            'username' => $attributes["http://schemas.microsoft.com/ws/2008/06/identity/claims/windowsaccountname"][0]
        );
        // set csrf token
        include_once('../includes/auth/csrf-token.php');
        $csrftoken = set_csrf_token();
        // set state
        $state = 'authenticated';
    }
} else { // if debug
    $user_info = array(
        'username' => 'exampleusername'
    );
    // set csrf token
    include_once('../includes/auth/csrf-token.php');
    $csrftoken = set_csrf_token();
    // set state
    $state = 'authenticated';
}

/**
 * Temp data state
 */
if ($state === 'authenticated' && isset($_SESSION['temp-data']['status']) && $_SESSION['temp-data']['status'] === 'success') {
    $state = 'success';
}
if ($state === 'authenticated' && isset($_SESSION['temp-data']['status']) && $_SESSION['temp-data']['status'] === 'error') {
    $state = 'return-error';
}

/**
 * Check database
 */
if ($state === 'authenticated') {
    $db_path = '../../db/boat-ball/boat-ball.sqlite3';
    $db = new PDO('sqlite:' . $db_path);

    // check if requested before
    $sql = 'SELECT * FROM refund_requests WHERE username=:username';
    $statement = $db->prepare($sql);
    $db_success = $statement->execute(array(
        ':username' => $user_info['username']
    ));
    if ($db_success) {
        if ($statement->fetchObject()) {
            $state = 'requested';
        }
    } else { // database error
        $state = 'error';
    }
}

/**
 * Render page
 */
$title = 'Boat Ball Ticket Refund Request - ECSS';
include('../includes/templates/header.php');
?>
<div class="container">
    <section>
        <h1>Boat Ball Tickets Refund Request</h1>

        <?php
        if ($state === 'not-authenticated') {
            include('../includes/templates/require-login.php');
        }
        ?>

        <?php if ($state === 'authenticated' || $state === 'requested' || $state === 'error' || $state === 'return-error') : ?>
            <div class="alert alert-warning"><strong>Note:</strong> the refund request closes on Wed 30th May.</div>
        <?php endif ?>

        <?php if ($state === 'success') : ?>
            <div class="alert alert-success"><?= isset($_SESSION['temp-data']['message']) ? ' ' . $_SESSION['temp-data']['message'] : '' ?></div>
        <?php endif ?>

        <?php if ($state === 'requested') : ?>
            <div class="alert alert-warning">You have already requested boat ball tickets refund. If you want to change your request, please <a href="/about/contact.php">contact us.</a></div>
        <?php endif ?>

        <?php if ($state === 'error') : ?>
            <div class="alert alert-danger">Unknown error, please <a href="/about/contact.php">contact us.</a></div>
        <?php endif ?>

        <?php if ($state === 'authenticated' || $state === 'return-error'): ?>
        <form method="post" action="boat-ball-tickets-refund-request-submit.php">
            <p>If you need to request a refund for the boat ball, please fill in the form below and click the "Request refund" button.</p>
            <?php if ($state === 'return-error') : ?>
                    <div class="alert alert-danger"><strong>Failed to request.</strong><?= isset($_SESSION['temp-data']['message']) ? ' ' . $_SESSION['temp-data']['message'] : '' ?></div>
            <?php endif ?>
            <input type="hidden" name="csrftoken" value="<?= $csrftoken ?>">
            <div class="form-group row">
                <label for="username" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input readonly id="username" class="form-control-plaintext" name="username" value="<?= $user_info['username'] ?>">
                </div>
            </div>
            <div class="form-group row">
                <label for="num-of-tickets" class="col-sm-4 col-form-label">Number of tickets to refund</label>
                <div class="col-sm-8">
                    <input id="num-of-tickets" class="form-control" type="number" name="num-of-tickets" value="1" min="1">
                    <small class="form-text text-muted">We will refund tickets up to the amount you originally purchased.</small>
                </div>
            </div>
            <div class="form-group row">
                <label for="comments" class="col-sm-4 col-form-label">Optional comments</label>
                <div class="col-sm-8">
                    <textarea id="comments" class="form-control" name="comments"></textarea>
                </div>
            </div>
            <button class="btn btn-primary">Request refund</button>
            <small class="form-text text-muted">Once your submitted a boat ball tickets refund request, we will refund your money to your PayPal, and we will contact you through your university email. <a href="/about/contact.php">Contact us</a> if any questions.</small>
        </form>
        <?php endif ?>

        <?php if ($state === 'success') : ?>
            <div>We will refund your money to your PayPal, and we will contact you through your university email. If you need to change your refund request, please <a href="/about/contact.php">contact us.</a></div>
        <?php endif ?>
    </section>
</div>

<?php
include('../includes/templates/footer.php');

unset($_SESSION['temp-data']);
