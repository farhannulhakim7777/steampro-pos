<section class="queue-board-horizontal" data-queue-board>
    <?php foreach ($statuses as $status): ?>
    <div class="queue-section">
        <h2><?= e($status) ?></h2>
        <div class="queue-row">
        <?php foreach ($queues as $q): if ($q['status'] !== $status) continue; ?>
            <article class="queue-card <?= $q['priority'] ? 'priority' : '' ?>" onclick="markAsFinished(<?= e($q['id']) ?>)">
                <strong><?= e($q['queue_no']) ?></strong>
                <span><?= e($q['customer_name']) ?></span>
                <small><?= e($q['plate_number']) ?> - <?= e($q['employee_name'] ?: 'Belum Ditugaskan') ?></small>
                <form method="post" action="<?= e(url('/queue/status')) ?>" id="form-<?= e($q['id']) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= e($q['id']) ?>">
                    <input type="hidden" name="status" value="Finished">
                    <select name="employee_id" onclick="event.stopPropagation()"><?php foreach ($employees as $e): ?><option value="<?= e($e['id']) ?>"><?= e($e['name']) ?></option><?php endforeach; ?></select>
                    <button class="ghost" type="submit" onclick="event.stopPropagation()">Update</button>
                </form>
            </article>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</section>
<script>
function markAsFinished(queueId) {
    const form = document.getElementById('form-' + queueId);
    if (form) {
        form.querySelector('input[name="status"]').value = 'Finished';
        form.submit();
    }
}
</script>

