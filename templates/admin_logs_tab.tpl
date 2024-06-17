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
    <th>{$mod->Lang('file')}</th>
    <th>{$mod->Lang('line')}</th>
    <th></th>
    </tr>
    </thead>
    <tbody>
    {foreach $logs as $log}
    <tr class="{cycle values='row1,row2'}">
    <td>{$log->created|date_format:'%x'}</td>
    <td>{$mod->getLineIcon($log->type)}</td>
    <td>{$log->description}</td>
    <td>{$log->file}</td>
    <td>{$log->line}</td>
    <td><a class="del_log" href="{cms_action_url action=log_delete hid=$log->id}" title="{$mod->Lang('delete')}">{admin_icon icon='delete.gif'}</a></td>
    </tr>
    {/foreach}
    </tbody>
    </table>
  {/if}
{/if}