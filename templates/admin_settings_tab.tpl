<h3>Get started with LogWatch:</h3>
{form_start}
<div class="pageoverflow">
    <p class="pagetext">{$mod->Lang('logsettings')}:
        <br><small>Your Root Path is: {$root_path}</small>
    </p>
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