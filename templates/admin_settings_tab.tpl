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

{if !$pro_enabled}
<div style="background: #e3f2fd; border-left: 4px solid #2196f3; padding: 15px; margin: 15px 0;">
    <h4 style="margin-top: 0;">💡 Upgrade to LogWatch Pro!</h4>
    <p>LogWatchPro provides advanced error monitoring capabilities:</p>
    <ul>
        <li>Real-time notifications via Slack, Discord, or Email</li>
        <li>Error grouping and analytics dashboard</li>
        <li>Notification history tracking</li>
        <li>Works even without server log file access</li>
    </ul>
    <p style="margin-bottom: 0;"><a href="https://pixelsolutions.biz/plugins/logwatch-pro" target="_blank" style="font-weight: bold;">Learn More →</a></p>
</div>
{/if}

{form_start}

{if $pro_available}
<div class="pageoverflow" style="background: #f9f9f9; padding: 15px; margin-bottom: 20px; border-left: 4px solid {if $pro_active}#4caf50{else}#ff9800{/if};">
    <p class="pagetext" style="font-weight: bold;">{$mod->Lang('logwatch_pro_status')}:</p>
    <p class="pageinput">
        <label style="display: inline-flex; align-items: center; gap: 10px;">
            <input type="checkbox" name="{$actionid}pro_active" value="1" {if $pro_active}checked{/if} />
            <span style="font-size: 14px;">
                {if $pro_active}
                    <strong style="color: #4caf50;">✓ {$mod->Lang('pro_enabled')}</strong> - {$mod->Lang('pro_enabled_desc')}
                {else}
                    <strong style="color: #ff9800;">✗ {$mod->Lang('pro_disabled')}</strong> - {$mod->Lang('pro_disabled_desc')}
                {/if}
            </span>
        </label>
    </p>
</div>
{/if}

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