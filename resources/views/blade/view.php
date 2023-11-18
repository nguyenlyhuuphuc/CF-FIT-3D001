<h1>View PHP</h1>
<?php 
    $scores = [1, 8, 5, 2, 3, 7, 6];
?>

<table border="1">
    <tr>
        <th>STT</th>
        <th>Diem</th>
        <th>Ket Qua</th>
    </tr>
    <?php 
        foreach($scores as $key => $score) { 
    ?>
         <?php 
            $style = $key % 2 !== 0 ? "style='background: grey'" : '';
        ?>
        <tr <?= $style ?>>
            <td><?= ++$key; ?></td>
            <td><?= $score ?></td>
            <td>
                <?= $score < 5 ? 'khong dat' : 'dat'?>
            </td>
        </tr>
    <?php } ?>
</table>