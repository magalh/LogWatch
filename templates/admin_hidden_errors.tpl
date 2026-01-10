{if !empty($error) }
	<div class="warning">{$message}</div>
{/if}

{if !empty($hidden_errors)}
  <p>These errors have been marked as fixed and are hidden from the main log view.</p>
  
  <table class="pagetable">
    <thead>
      <tr>
        <th>Hidden Date</th>
        <th>Hidden By</th>
        <th>File</th>
        <th>Line</th>
        <th>Message</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      {foreach $hidden_errors as $error}
      <tr class="{cycle values='row1,row2'}">
        <td>
          {$error.hidden_date|cms_date_format}
        </td>
        <td>
          {$error.username|default:'Unknown'}
        </td>
        <td class="word-wrap">
          {if $error.file_path}{$error.file_path|noroot}{else}<em>N/A</em>{/if}
        </td>
        <td>
          {if $error.line_number}{$error.line_number}{else}<em>N/A</em>{/if}
        </td>
        <td class="word-wrap">
          {if $error.error_message}{$error.error_message|truncate:100}{else}<em>N/A</em>{/if}
        </td>
        <td>
          <input type="submit" class="view-hidden-error" data-ui-icon="ui-icon-info" value="View" data-hash="{$error.error_hash}" data-file="{$error.file_path}" data-line="{$error.line_number}" data-message="{$error.error_message|escape}" data-notes="{$error.notes|escape}"/>
          <input type="submit" class="unhide-error" data-ui-icon="ui-icon-disk" value="Unhide" data-hash="{$error.error_hash}"/>
        </td>
      </tr>
      {/foreach}
    </tbody>
  </table>

  <script>
    $(document).ready(function() {
      $('.view-hidden-error').on('click', function() {
        var file = $(this).data('file');
        var line = $(this).data('line');
        var message = $(this).data('message');
        var notes = $(this).data('notes');
        
        $('#hiddenModalFile').text(file || 'N/A');
        $('#hiddenModalLine').text(line || 'N/A');
        $('#hiddenModalMessage').text(message || 'N/A');
        $('#hiddenModalNotes').text(notes || 'No notes');
        
        $('#viewHiddenDialog').dialog({
          modal: true,
          width: 600,
          buttons: {
            "Close": function() {
              $(this).dialog("close");
            }
          }
        });
      });
      
      $('.unhide-error').on('click', function() {
        var hash = $(this).data('hash');
        
        if (confirm('Are you sure you want to unhide this error? It will appear in the main log view again.')) {
          // Create form and submit
          var form = $('<form method="post" action="{cms_action_url action=hide_error}"></form>');
          form.append('<input type="hidden" name="{$actionid}unhide_error" value="1">');
          form.append('<input type="hidden" name="{$actionid}error_hash" value="' + hash + '">');
          $('body').append(form);
          form.submit();
        }
      });
    });
  </script>

    <!-- View Hidden Error Modal -->
    <div id="viewHiddenDialog" title="Hidden Error Details" style="display:none;">
      <div id="hiddenErrorDetails" style="margin-bottom: 15px; padding: 10px; background: #f5f5f5; border: 1px solid #ddd;">
        <strong>Error Details:</strong><br>
        <strong>File:</strong> <span id="hiddenModalFile"></span><br>
        <strong>Line:</strong> <span id="hiddenModalLine"></span><br>
        <strong>Message:</strong> <span id="hiddenModalMessage"></span><br>
        <strong>Notes:</strong> <span id="hiddenModalNotes"></span>
      </div>
    </div>


{else}
  <div class="information">
    <p>No hidden errors found. Errors marked as "fixed" will appear here.</p>
  </div>
{/if}