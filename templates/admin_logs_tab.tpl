{if !empty($error) }
	<div class="warning">{$message}</div>
{else}
  {if !empty($logs)}
    <table class="pagetable cms_sortable tablesorter">
    <thead>
    <tr>
    <th>{$mod->Lang('date')}</th>
    <th>{$mod->Lang('type')}</th>
    <th>{$mod->Lang('message')}</th>
    <th>{$mod->Lang('type_Detail')}</th>
    <th></th>
    </tr>
    </thead>
    <tbody>
    {foreach $logs as $log}
    <tr class="{cycle values='row1,row2'}">
    <td>{$log->created|cms_date_format}</td>
    <td>{$log->icon}</td>
    <td><strong>{$log->description}</strong><br>{$log->file}<br>line# {$log->line}</td>
    <td>
    <pre>{if $log->stack_trace !== ''}<small>{$log->stack_trace}</small>{/if}</pre>
    </td>
    <td><a class="del_log" href="{cms_action_url action=log_delete hid=$log->id}" title="{$mod->Lang('delete')}">{admin_icon icon='delete.gif'}</a></td>
    </tr>
    {/foreach}
    </tbody>
    </table>
  {/if}
{/if}