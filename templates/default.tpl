<div class="row mt-5">
    <div class="col-md-12">
        <h1 class="mb-4">Trigger PHP Errors</h1>
    </div>
    <div class="col-md-4">
    {form_start}
    <div class="btn-group d-grid gap-2" role="group" aria-label="Error Types">
        
        <button class="btn btn-primary" type="submit" name="{$actionid}submit_1">Trigger PHP Notice</button>
        <button class="btn btn-secondary" type="submit" name="{$actionid}submit_2">Trigger PHP Fatal Error</button>
        <button class="btn btn-success" type="submit" name="{$actionid}submit_3">Trigger PHP Error</button>
        <button class="btn btn-danger" type="submit" name="{$actionid}submit_4">Trigger PHP Fatal Error</button>
        <button class="btn btn-warning" type="submit" name="{$actionid}submit_5">Trigger PHP Deprecated Warning</button>
        <button class="btn btn-info" type="submit" name="{$actionid}submit_6">Trigger PHP User Error</button>
        <button class="btn btn-light" type="submit" name="{$actionid}submit_7">Trigger PHP User Warning</button>
        <button class="btn btn-dark" type="submit" name="{$actionid}submit_8">Trigger PHP User Notice</button>

    </div>
    {form_end}
    </div>
</div>