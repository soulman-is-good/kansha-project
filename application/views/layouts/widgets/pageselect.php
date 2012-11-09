<form method="post">
    <span>Показать по:</span>
    <select name="product" class="showperpage" module="<?=$paginator->model?>">
        <option value="5" <?= ($paginator->limit == 5) ? 'selected' : '' ?>>5</option>
        <option value="10" <?= ($paginator->limit == 10) ? 'selected' : '' ?>>10 </option>
        <option value="20" <?= ($paginator->limit == 20) ? 'selected' : '' ?>>20</option>
        <option value="30" <?= ($paginator->limit == 30) ? 'selected' : '' ?>>30</option>
        <option value="50" <?= ($paginator->limit == 50) ? 'selected' : '' ?>>50</option>
        <option value="100" <?= ($paginator->limit == 100) ? 'selected' : '' ?>>100</option>
    </select>
</form>