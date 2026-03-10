<div class="pagecontainer">
    <h3>Module Preferences</h3>
    
    <h4>LogWatch Preferences</h4>
    <table class="pagetable">
        <thead>
            <tr>
                <th style="width: 40%;">Preference Name</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            {foreach $basic_prefs as $pref}
            <tr class="{cycle values='row1,row2'}">
                <td><code>{$pref.name}</code></td>
                <td>{$pref.value|default:'<em>empty</em>'}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    
    {if $pro_installed}
    <h4 style="margin-top: 20px;">LogWatchPro Preferences</h4>
    <table class="pagetable">
        <thead>
            <tr>
                <th style="width: 40%;">Preference Name</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            {foreach $pro_prefs as $pref}
            <tr class="{cycle values='row1,row2'}">
                <td><code>{$pref.name}</code></td>
                <td>{$pref.value|default:'<em>empty</em>'}</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
    {/if}
    
    <div style="margin: 20px 0;">
        <a href="{$clear_url}" class="ui-button ui-widget ui-state-default ui-corner-all" onclick="return confirm('Are you sure you want to clear all preferences?');">
            Clear All Preferences
        </a>
    </div>
    
    <h3>Debug Information</h3>
    
    <table class="pagetable">
        <thead>
            <tr>
                <th style="width: 30%;">Setting</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr class="row1">
                <td><strong>Module Version</strong></td>
                <td>{$debug_info.module_version}</td>
            </tr>
            <tr class="row2">
                <td><strong>CMS Version</strong></td>
                <td>{$debug_info.cms_version}</td>
            </tr>
            <tr class="row1">
                <td><strong>PHP Version</strong></td>
                <td>{$debug_info.php_version}</td>
            </tr>
            <tr class="row2">
                <td><strong>Selected Log Source</strong></td>
                <td>{$debug_info.selected_log_source}</td>
            </tr>
            <tr class="row1">
                <td><strong>Manual Log Path</strong></td>
                <td>{$debug_info.manual_log_path|default:'Not set'}</td>
            </tr>
            <tr class="row2">
                <td><strong>Hidden Errors Count</strong></td>
                <td>{$debug_info.hidden_errors_count}</td>
            </tr>
            <tr class="row1">
                <td><strong>PHP error_log (ini)</strong></td>
                <td>{$debug_info.error_log_ini|default:'Not set'}</td>
            </tr>
            <tr class="row2">
                <td><strong>display_errors</strong></td>
                <td>{$debug_info.display_errors}</td>
            </tr>
            <tr class="row1">
                <td><strong>log_errors</strong></td>
                <td>{$debug_info.log_errors}</td>
            </tr>
        </tbody>
    </table>
    
    <h3 style="margin-top: 20px;">Test Error Triggers</h3>
    <p>Use these buttons to generate test errors for debugging purposes. After triggering, you'll be redirected to the Logs tab.</p>
    <p><strong>Note:</strong> The error you see in Apache logs is being logged. If it doesn't appear in LogWatch, check that your selected log source matches where PHP is writing errors.</p>
    
    <div style="margin: 15px 0;">
        {form_start}
            <input type="submit" name="{$actionid}submit_1" value="Trigger Notice" style="margin: 5px;" />
            <input type="submit" name="{$actionid}submit_2" value="Trigger Warning" style="margin: 5px;" />
            <input type="submit" name="{$actionid}submit_3" value="Trigger User Error" style="margin: 5px;" />
            <input type="submit" name="{$actionid}submit_4" value="Trigger User Warning" style="margin: 5px;" />
            <input type="submit" name="{$actionid}submit_5" value="Trigger User Notice" style="margin: 5px;" />
            <input type="submit" name="{$actionid}submit_fatal" value="Trigger Fatal Error (Test Notifications)" style="margin: 5px; background: #d9534f; color: white;" onclick="return confirm('This will trigger a fatal error and test your notification settings. Continue?');" />
        {form_end}
    </div>
    
    <h3 style="margin-top: 20px;">Available Log Files</h3>
    <table class="pagetable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Path</th>
                <th>Exists</th>
                <th>Readable</th>
            </tr>
        </thead>
        <tbody>
            {foreach $debug_info.available_logs as $key => $log}
            <tr class="{cycle values='row1,row2'}">
                <td>{$log.name}</td>
                <td style="font-family: monospace; font-size: 11px;">{$log.path}</td>
                <td>{if $log.exists}✓{else}✗{/if}</td>
                <td>{if $log.exists && is_readable($log.path)}✓{else}✗{/if}</td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="4">No log files detected</td>
            </tr>
            {/foreach}
        </tbody>
    </table>
</div>
