<h3>Get started with CMSMS Logs:</h3>
{form_start}
<div class="pageoverflow">
 <p class="pagetext">{$mod->Lang('logfilepath')}:
 <br><small>Your Root Path is: {$root_path}</small></p>
 <p class="pageinput">
<input type="text" name="{$actionid}logfilepath" value="{$logfilepath}" size="100" maxlength="255"/>
 </p>
</div>
<div class="pageoverflow">
 <p class="pageinput">
 <input type="submit" name="{$actionid}submit" value="{$mod->Lang('admin_save')}"/>
 </p>
</div>
{form_end}