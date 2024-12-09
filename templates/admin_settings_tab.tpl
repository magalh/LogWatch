<script>
    function toggleCheckboxes() {
        var allCheckbox = document.querySelector('input[name="{$actionid}logsettings[]"][value="E_ALL"]');
        var checkboxes = document.querySelectorAll('input[name="{$actionid}logsettings[]"]:not([value="E_ALL"])');

        if (allCheckbox.checked) {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                    checkbox.disabled = true;
                });
            } else {
                checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = false;
                });
            }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var allCheckbox = document.querySelector('input[name="{$actionid}logsettings[]"][value="E_ALL"]');
        allCheckbox.addEventListener('change', toggleCheckboxes);

        toggleCheckboxes();
    });
</script>
    
<h3>{$mod->Lang('get_started')} LogWatch:</h3>
{form_start}
<div class="pageoverflow">
    <p class="pagetext">{$mod->Lang('logsettings')}:</p>
    <p class="pageinput">
    {foreach from=$exceptions key=key item=exception}
        <input type="checkbox" name="{$actionid}logsettings[]" value="{$key}" {if in_array($key, $selected_logsettings)}checked{/if}/> {$key}<br/>
    {/foreach}
    </p>
</div>
<div class="pageoverflow">
    <p class="pageinput">
        <input type="submit" name="{$actionid}submit" value="{$mod->Lang('admin_save')}"/>
    </p>
</div>
{form_end}

