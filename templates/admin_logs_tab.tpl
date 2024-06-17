{if !empty($error) }
	<div class="warning">{$message}</div>
{else}
  {if !empty($logs)}

<div class="row c_full">
  <div class="pageoptions grid_6" style="margin-top: 8px;">
    <a id="toggle_filter" {if $curcategory != ''} style="font-weight: bold; color: green;"{/if}>{admin_icon icon='view.gif' alt=$mod->Lang('viewfilter')} {if $curcategory != ''}*{/if}
    {$mod->Lang('viewfilter')}</a>
  </div>
  {if $total_items > 0 && $total_pages > 1}
    <div class="pageoptions grid_6" style="text-align: right;">
      {form_start}
      {$mod->Lang('prompt_page')}&nbsp;
      <select name="{$actionid}pagenumber">
        {cms_pageoptions numpages=$total_pages curpage=$pagenumber}
      </select>&nbsp;
      <input type="submit" name="{$actionid}paginate" value="{$mod->Lang('prompt_go')}"/>
      {form_end}
    </div>
  {/if}
</div>

    <table class="pagetable cms_sortable tablesorter">
    <thead>
    <tr>
    <th></th>
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
    <td>{$log->row}</td>
    <td>{$log->created}</td>
    <td>{$log->icon}</td>
    <td class="word-wrap"><strong>{$log->description}</strong><br><br>{$log->file}<br>line# {$log->line}</td>
    <td>
    <pre>{if $log->stack_trace !== ''}<small>{$log->stack_trace}</small>{/if}</pre>
    </td>
    <td><a class="del_log" href="{cms_action_url action=log_delete hid=$log->row}" title="{$mod->Lang('delete')}">{admin_icon icon='delete.gif'}</a></td>
    </tr>
    {/foreach}
    </tbody>
    </table>
  {/if}

  <div class="row c_full">
  {if $total_items > 0 && $total_pages > 1}
    <div class="pageoptions grid_12" style="text-align: right;">
      {form_start}
      {$mod->Lang('prompt_page')}&nbsp;
      <select name="{$actionid}pagenumber">
        {cms_pageoptions numpages=$total_pages curpage=$pagenumber}
      </select>&nbsp;
      <input type="submit" name="{$actionid}paginate" value="{$mod->Lang('prompt_go')}"/>
      <input type="hidden" name="{$actionid}prefix" value="{$path}"/>
      {form_end}
    </div>
  {/if}
</div>
{/if}
{*get_template_vars*}

<style>
    .word-wrap {
        word-wrap: break-word;
        word-break: break-all;
        white-space: normal;
    }
</style>