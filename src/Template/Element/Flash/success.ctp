<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div id="toast-container" class="toast-top-right">
    <div class="toast toast-success" aria-live="assertive" style="">
        <div class="toast-message"><?= $message ?>
        </div>
    </div>
</div>

<script>
    $(function() {
        setTimeout(function (){
            $('#toast-container').hide();
        }, 10000);

    });
</script>
