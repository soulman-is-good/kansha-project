<form>
    <span>Сортировать по:</span>
    <select name="sort" class="nameSort">
        <option value="popular">популярности</option>
        <option<?=X3::user()->CompanySort=='feedback'?' selected="selected"':''?> value="feedback">отзывам</option>
        <option<?=X3::user()->CompanySort=='marks'?' selected="selected"':''?> value="marks">рейтингу</option>
    </select>
</form>