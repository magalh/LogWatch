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
    
    function toggleManualPath() {
        var manualRadio = document.querySelector('input[name="{$actionid}log_source"][value="manual"]');
        var manualPathDiv = document.getElementById('manual_log_path');
        
        if (manualRadio && manualRadio.checked) {
            manualPathDiv.style.display = 'block';
        } else {
            manualPathDiv.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var allCheckbox = document.querySelector('input[name="{$actionid}logsettings[]"][value="E_ALL"]');
        allCheckbox.addEventListener('change', toggleCheckboxes);
        
        var logSourceRadios = document.querySelectorAll('input[name="{$actionid}log_source"]');
        logSourceRadios.forEach(function(radio) {
            radio.addEventListener('change', toggleManualPath);
        });

        toggleCheckboxes();
        toggleManualPath();
    });
</script>
    
<h3>{$mod->Lang('get_started')} LogWatch:</h3>

{if ($selected_log_source && isset($selected_log_info) && !$selected_log_info.exists) || ($selected_log_source == 'manual' && !empty($manual_log_path) && $manual_log_error)}
<div class="red">
    <p><strong>{$mod->Lang('error_log_file_not_found')}</strong></p>
    {if $selected_log_source == 'manual'}
        <p>{$mod->Lang('error_log_file_manual', $manual_log_path)}</p>
    {else}
        <p>{$mod->Lang('error_log_file_selected', $selected_log_info.path)}</p>
    {/if}
    <p>{$mod->Lang('error_log_file_reasons')}</p>
</div>
{elseif empty($available_logs) && empty($manual_log_path)}
<div class="red">
    <p>{$mod->Lang('error_no_log_sources')}</p>
</div>
{/if}

{form_start}

<div class="pageoverflow">
    <p class="pagetext">{$mod->Lang('log_source')}:</p>
    <p class="pageinput">
    {if !empty($available_logs)}
        {foreach from=$available_logs key=log_key item=log_info}
            {if $log_key != 'manual'}
                <input type="radio" name="{$actionid}log_source" value="{$log_key}" {if $log_key == $selected_log_source}checked{/if} {if !$log_info.exists}disabled{/if}/> 
                {$log_info.name}
                {if !$log_info.exists}<em> (not found)</em>{/if}
                <br/><small style="margin-left: 20px; color: #666;">{$log_info.path}</small><br/><br/>
            {/if}
        {/foreach}
    {/if}
    
        <input type="radio" name="{$actionid}log_source" value="manual" {if $selected_log_source == 'manual'}checked{/if}/> 
        {$mod->Lang('manual_log_path')}<br/>
        <div id="manual_log_path" style="margin-left: 20px; margin-top: 5px;">
            <input type="text" name="{$actionid}manual_log_path" value="{$manual_log_path}" 
                   placeholder="/full/path/to/error.log" style="width: 400px;"/><br/>
            <small style="color: #666;">{$mod->Lang('manual_log_path_desc')}</small>
        </div>
    </p>
</div>

<div class="pageoverflow">
<br>
    <p class="pageinput">
        <input type="submit" name="{$actionid}submit" value="{$mod->Lang('admin_save')}"/>
    </p>
</div>
{form_end}