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
    
<h3>Display Filters:</h3>

{form_start}

<div class="pageoverflow">
    <p class="pageinput">
        {$mod->Lang('filter_error_types_desc')}<br/><br/>
    {foreach from=$exceptions key=key item=label}
        <input type="checkbox" name="{$actionid}logsettings[]" value="{$key}" {if in_array($key, $selected_logsettings) || ($key == 'E_ALL' && empty($selected_logsettings))}checked{/if}/> {$label}<br/>
    {/foreach}
    </p>
</div>

<div class="pageoverflow">
<br>
    <p class="pageinput">
        <input type="submit" name="{$actionid}submit" value="{$mod->Lang('admin_save')}"/>
    </p>
</div>
{form_end}