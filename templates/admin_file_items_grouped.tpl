{if !empty($grouped_logs)}
    {if $total_items > 0 && $total_pages > 1}
    <div class="row c_full">
        <div class="pageoptions grid_6 p_top_10" style="text-align: left;">
            Showing <strong>{$total_items}</strong> unique error {if $total_items == 1}type{else}types{/if}
        </div>
        <div class="pageoptions grid_6" style="text-align: right;">
            {form_start}
            {$mod->Lang('prompt_page')}&nbsp;
            <select name="{$actionid}pagenumber">
                {cms_pageoptions numpages=$total_pages curpage=$pagenumber}
            </select>&nbsp;
            <input type="submit" name="{$actionid}paginate" value="{$mod->Lang('prompt_go')}"/>
            {form_end}
        </div>
    </div>
    {/if}

    <table class="pagetable cms_sortable tablesorter">
        <thead>
            <tr>
                <th class="pageicon">{$mod->Lang('type')}</th>
                <th>{$mod->Lang('message')}</th>
                <th>{$mod->Lang('file')}</th>
                <th>Line</th>
                <th>Count</th>
                <th>First / Last Seen</th>
                <th class="pageicon">View</th>
                <th class="pageicon">Explain</th>
                <th class="pageicon">Actions</th>
            </tr>
        </thead>
        <tbody>
            {foreach $grouped_logs as $group}
            <tr class="{cycle values='row1,row2'}">
                <td>{$group.sample_error->type|typeicon}</td>
                <td class="word-wrap">
                    <strong>{$group.sample_error->description|truncate:80|trim}</strong>
                </td>
                <td class="word-wrap">
                    {if $group.sample_error->file}{$group.sample_error->file|noroot}{/if}
                </td>
                <td>
                    {if $group.sample_error->line}{$group.sample_error->line}{/if}
                </td>
                <td>
                    <span class="error-count-badge">{$group.count}</span>
                </td>
                <td style="font-size: 11px; white-space: nowrap;">
                    <div>{$group.first_seen|cms_date_format:'%b %d, %H:%M'}</div>
                    <div>{$group.last_seen|cms_date_format:'%b %d, %H:%M'}</div>
                </td>
                <td>
                    {if $group.sample_error->stacktrace}<input type="submit" class="view-stack-trace" data-ui-icon="ui-icon-info" data-stacktrace="{$group.sample_error->stacktrace}" value="View"/>{/if}
                </td>
                <td>
                    {if $pro_enabled}
                        <input type="submit" class="no-ui-btn explain-error" value="Explain" data-type="{$group.sample_error->type}" data-message="{$group.sample_error->description|escape}" data-file="{$group.sample_error->file}" data-line="{$group.sample_error->line}" data-hash="{$group.hash}" data-stacktrace="{$group.sample_error->stacktrace|escape}" />
                    {else}
                        <span style="color: #999; font-size: 11px;">Pro Only</span>
                    {/if}
                </td>
                <td>
                    <input type="submit" class="mark-fixed" data-ui-icon="ui-icon-disk" value="Fixed" data-hash="{$group.hash}" data-file="{$group.sample_error->file}" data-line="{$group.sample_error->line}" data-message="{$group.sample_error->description|escape}" />
                </td>
            </tr>

            {/foreach}
        </tbody>
    </table>

    {* Bottom pagination and export *}
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

{else}
    <div class="warning">No errors found in the log file.</div>
{/if}

{* Stack Trace Modal *}
<div id="logStackTraceDialog" title="Stack Trace" style="display:none;">
    <pre id="logStackTraceContent"></pre>
</div>

{* AI Explanation Modal *}
<div id="explainDialog" title="AI Error Explanation" style="display:none;">
    <div id="explainContent" style="padding: 10px;">
        <div class="loading" style="text-align: center; padding: 20px;">
            <div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div>
            <p style="margin-top: 15px;">Analyzing error with AI...</p>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

{* Mark Fixed Modal *}
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
    // View stack trace
    $('.view-stack-trace').on('click', function() {
        var stackTrace = $(this).data('stacktrace');
        stackTrace = stackTrace.replace(/<br\s*\/?>/gi, '\n');
        $('#logStackTraceContent').text(stackTrace);
        $('#logStackTraceDialog').dialog({
            modal: true,
            width: 800,
            buttons: {
                "Close": function() {
                    $(this).dialog("close");
                }
            }
        });
    });
    
    // Explain error with AI
    {if $pro_enabled}
    $('.explain-error').on('click', function() {
        var errorType = $(this).data('type');
        var message = $(this).data('message');
        var file = $(this).data('file');
        var line = $(this).data('line');
        var hash = $(this).data('hash');
        var stacktrace = $(this).data('stacktrace');
        
        $('#explainContent').html('<div class="loading" style="text-align: center; padding: 20px;"><div class="spinner" style="border: 4px solid #f3f3f3; border-top: 4px solid #3498db; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 0 auto;"></div><p style="margin-top: 15px;">Analyzing error with AI...</p></div>');
        
        $('#explainDialog').dialog({
            modal: true,
            width: 800,
            buttons: {
                "Close": function() {
                    $(this).dialog("close");
                }
            }
        });
        
        var payload = {
            m1_error_type: errorType,
            m1_message: message,
            m1_file: file,
            m1_line: parseInt(line) || 0,
            m1_error_hash: hash,
            m1_stack_trace: stacktrace || ''
        };
                
        $.ajax({
            url: '{cms_action_url module=LogWatchPro action=ajax_explain forjs=1}&showtemplate=false',
            method: 'POST',
            data: payload,
            success: function(response) {
                $('#explainContent').html('<div style="line-height: 1.6;">' + response + '</div>');
            },
            error: function(xhr, status, error) {
                $('#explainContent').html('<div class="error" style="color: red; padding: 10px;">Failed to get AI explanation. Please try again.</div>');
            }
        });
    });
    {/if}
    
    // Mark as fixed
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
