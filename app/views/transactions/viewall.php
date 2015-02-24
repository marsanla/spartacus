<?php if (!empty($transactions)):?>
<table class="table table-hover table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Merchant</th>
            <th>Total</th> 
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $transaction): ?>
        <tr id="<?php echo $transaction->transactionID ?>">
            <th scope="row"><?php echo $transaction->transactionID ?></th>
            <td><?php echo $transaction->created ?></td>
            <td><?php echo $transaction->merchant ?></td>
            <td><?php echo $transaction->currency ?> <?php echo $transaction->amount ?></td>
        </tr>
        <?php endforeach?>
    </tbody>
</table>
<?php else: ?>
<div class="empty-text">Currently your transactions are empty. Start creating one!</div>
<?php endif?>