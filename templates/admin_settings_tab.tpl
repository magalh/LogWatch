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
    <p class="pagetext">Log Source:</p>
    <p class="pageinput">
    {foreach from=$available_logs key=log_key item=log_info}
        <input type="radio" name="{$actionid}log_source" value="{$log_key}" {if $log_key == $selected_log_source}checked{/if} {if !$log_info.exists}disabled{/if}/> 
        {$log_info.name}
        {if !$log_info.exists}<em> (not found)</em>{/if}
        <br/><small style="margin-left: 20px; color: #666;">{$log_info.path}</small><br/><br/>
    {/foreach}
    </p>
</div>

<div class="pageoverflow">
    <p class="pagetext">Display Filters:</p>
    <p class="pageinput">
        <small>Select which error types to display in the logs view:</small><br/><br/>
    {foreach from=$exceptions key=key item=label}
        <input type="checkbox" name="{$actionid}logsettings[]" value="{$key}" {if in_array($key, $selected_logsettings) || ($key == 'E_ALL' && empty($selected_logsettings))}checked{/if}/> {$label}<br/>
    {/foreach}
    </p>
</div>

<div class="pageoverflow">
    <p class="pageinput">
        <input type="submit" name="{$actionid}submit" value="{$mod->Lang('admin_save')}"/>
    </p>
</div>
{form_end}