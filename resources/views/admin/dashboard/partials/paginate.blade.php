<div class="row mt-2">
    <div class="col-md-10"></div>
    <div class="col-md-2 text-right">
        <div class="form-group">
            <select class="form-control form-control-sm" id="qtypaginate">
                <option value="5" selected="">5 registros</option>
                <option value="10" {{ request('qtyShow') === "10" ? 'selected' : '' }}>10 registros</option>
                <option value="20" {{ request('qtyShow') === "20" ? 'selected' : '' }}>20 registros</option>
            </select>
        </div>
    </div>
</div>
