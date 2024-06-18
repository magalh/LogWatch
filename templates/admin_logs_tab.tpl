{if !empty($error) }
	<div class="warning">{$message}</div>
{else}
  {if !empty($logs)}

<div class="row c_full">
  <div class="pageoptions grid_6" style="margin-top: 8px;">
    {*<a id="toggle_filter" {if $curcategory != ''} style="font-weight: bold; color: green;"{/if}>{admin_icon icon='view.gif' alt="Coming Soon"} {if $curcategory != ''}*{/if}
    {$mod->Lang('viewfilter')}</a>*}

    <a href="{cms_action_url action=defaultadmin}">{admin_icon icon='newobject.gif'} {$mod->Lang('refresh')}</a>
    
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
    <th>{$mod->Lang('date')}</th>
    <th>{$mod->Lang('type')}</th>
    <th>{$mod->Lang('message')}</th>
    <th></th>
    <th></th>
    </tr>
    </thead>
    <tbody>
    {foreach $logs as $log}
    <tr class="{cycle values='row1,row2'}">
    <td>{$log->created|cms_date_format}</td>
    <td>{$log->type|typeicon}</td>
    <td class="word-wrap">
      <strong>{$log->description}</strong>
      {if $log->file}<br>{$log->file|noroot}{/if}
      {if $log->line}<br>line# {$log->line}{/if}
    </td>
    <td>
    {if $log->stack_trace}<button type="button" class="view-stack-trace" data-stack-trace="{$log->stack_trace}">View</button>{/if}
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


<!-- Modal -->
<div id="logStackTraceDialog" title="Stack Trace" style="display:none;">
  <pre id="logStackTraceContent"></pre>
</div>



<script>
  $(document).ready(function() {
    $('.view-stack-trace').on('click', function() {
      var stackTrace = $(this).data('stack-trace');
      stackTrace = stackTrace.replace(/<br\s*\/?>/gi, '\n');
      $('#logStackTraceContent').text(stackTrace);
      $('#logStackTraceDialog').dialog({
        modal: true,
        width: 600,
        buttons: {
          "Close": function() {
            $(this).dialog("close");
          }
        }
      });
    });
  });
</script>

{get_template_vars}