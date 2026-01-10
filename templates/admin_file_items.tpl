<style>
    .word-wrap {
        word-wrap: break-word;
        word-break: break-all;
        white-space: normal;
    }
    .pagetable th:first-child,
    .pagetable td:first-child {
        width: 150px;
        white-space: nowrap;
    }
</style>
  
{if !empty($error) }
	<div class="warning">{$message}</div>
{/if}

{if !empty($logs)}
  <div class="row c_full">
    <div class="pageoptions grid_6" style="margin-top: 8px;">
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
        <th>View</th>
        <th>Actions</th>
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
          {if $log->stacktrace}<input type="submit" class="view-stack-trace" data-ui-icon="ui-icon-info" data-stacktrace="{$log->stacktrace}" value="View"/>{/if}
        </td>
        <td>
          <input type="submit" class="mark-fixed" data-ui-icon="ui-icon-disk" value="Fixed" data-hash="{$mod->getErrorHash($log)}" data-file="{$log->file}" data-line="{$log->line}" data-message="{$log->description|escape}" />
        </td>
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

    <!-- Mark Fixed Modal -->
    <div id="markFixedDialog" title="Mark Error as Fixed" style="display:none;">
      <div id="errorDetails" style="margin-bottom: 15px; padding: 10px; background: #f5f5f5; border: 1px solid #ddd;">
        <strong>Error Details:</strong><br>
        <strong>File:</strong> <span id="modalFile"></span><br>
        <strong>Line:</strong> <span id="modalLine"></span><br>
        <strong>Message:</strong> <span id="modalMessage"></span>
      </div>
      
      {form_start id="markFixedForm" action="hide_error" hide_error=1}
        <input type="hidden" name="{$actionid}error_hash" id="fixedHash" />
        <input type="hidden" name="{$actionid}file" id="fixedFile" />
        <input type="hidden" name="{$actionid}line" id="fixedLine" />
        <input type="hidden" name="{$actionid}message" id="fixedMessage" />
        
        <div style="margin-bottom: 10px;">
          <label for="fixedNotes">Notes (optional):</label><br>
          <textarea name="{$actionid}notes" id="fixedNotes" rows="3" style="width: 100%; box-sizing: border-box;" placeholder="Why was this error marked as fixed?"></textarea>
        </div>
      {form_end}
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
        
        $('.mark-fixed').on('click', function() {
          var hash = $(this).data('hash');
          var file = $(this).data('file');
          var line = $(this).data('line');
          var message = $(this).data('message');
          
          $('#fixedHash').val(hash);
          $('#fixedFile').val(file);
          $('#fixedLine').val(line);
          $('#fixedMessage').val(message);
          $('#fixedNotes').val('');
          
          // Display error details in modal
          $('#modalFile').text(file || 'N/A');
          $('#modalLine').text(line || 'N/A');
          $('#modalMessage').text(message || 'N/A');
          
          $('#markFixedDialog').dialog({
            modal: true,
            width: 600,
            buttons: {
              "Mark as Fixed": function() {
                $('#markFixedForm').submit();
              },
              "Cancel": function() {
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