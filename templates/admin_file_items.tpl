<style>
    .word-wrap {
        word-wrap: break-word;
        word-break: break-all;
        white-space: normal;
    }
</style>
  
{if !empty($error) }
	<div class="warning">{$message}</div>
{/if}

{if !empty($logs)}
  <div class="row c_full">
    <div class="pageoptions grid_6" style="margin-top: 8px;">
      <a href="{cms_action_url action=defaultadmin}">{admin_icon icon='newobject.gif'} {$mod->Lang('refresh')}</a>
      {if $clear_logs}
        <a href="{cms_action_url action=clear_all_logs}" class="clear-all-logs" onclick="return confirm('{$mod->Lang('confirm_clear_all')}')">{admin_icon icon='delete.gif'} {$mod->Lang('clear_all_logs')}</a>
      {/if}
      {if $export_logs}
        <a href="{cms_action_url action=export}">{admin_icon icon='export.gif'} {$mod->Lang('export_csv')}</a>
      {/if}
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
        <th>{$mod->Lang('file')}</th>
        <th>Line</th>
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
        </td>
        <td class="word-wrap">
          {if $log->file}{$log->file|noroot}{/if}
        </td>
        <td>
          {if $log->line}{$log->line}{/if}
        </td>
        <td>
        {if $log->stacktrace}<button type="button" class="view-stack-trace" data-stacktrace="{$log->stacktrace}">View</button>{/if}
        </td>
        <td><a class="del_log" href="{cms_action_url action=delete_line hid=$log->row}" title="{$mod->Lang('delete')}">{admin_icon icon='delete.gif'}</a></td>
        </tr>
        {/foreach}
      </tbody>
    </table>

    <div class="row c_full">
      {if $total_items > 0 && $total_pages > 1}
        <div class="pageoptions grid_12" style="text-align: right;">
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

    <!-- Modal -->
    <div id="logStackTraceDialog" title="Stack Trace" style="display:none;">
      <pre id="logStackTraceContent"></pre>
    </div>

    <script>
      $(document).ready(function() {
        $('.view-stack-trace').on('click', function() {
          var stackTrace = $(this).data('stacktrace');
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
{else}
    <div class="warning">Nothing to display yet</div>
{/if}
{*get_template_vars*}