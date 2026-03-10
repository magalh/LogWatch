<style>
.help-tabs { margin: 20px 0 0; border-bottom: 2px solid #ddd; }
.help-tabs a { display: inline-block; padding: 10px 20px; margin-right: 5px; background: #f5f5f5; border: 1px solid #ddd; border-bottom: none; text-decoration: none; color: #333; }
.help-tabs a.active { background: #fff; border-bottom: 2px solid #fff; margin-bottom: -2px; font-weight: bold; }
.help-tabs a:hover { background: #e9e9e9; }
.help-content { padding: 20px; border: 1px solid #ddd; border-top: none; background: #fff; }
.help-section { display: none; }
.help-section.active { display: block; }
</style>

<div class="help-tabs">
  <a href="#general" class="help-tab active" data-tab="general">{$logwatch->Lang('help_general')}</a>
  <a href="#features" class="help-tab" data-tab="features">{$logwatch->Lang('help_features')}</a>
  <a href="#configuration" class="help-tab" data-tab="configuration">{$logwatch->Lang('help_configuration')}</a>
  {if $have_pro}
    <a href="#pro" class="help-tab" data-tab="pro">{$logwatch->Lang('help_pro_features')}</a>
  {else}
    <a href="#upgrade" class="help-tab" data-tab="upgrade">{$logwatch->Lang('help_upgrade')}</a>
  {/if}
  <a href="#troubleshooting" class="help-tab" data-tab="troubleshooting">{$logwatch->Lang('help_troubleshooting')}</a>
</div>

<div class="help-content">
  <div id="general" class="help-section active">
    {include file='module_file_tpl:LogWatch;help_general_tab.tpl'}
  </div>
  
  <div id="features" class="help-section">
    {include file='module_file_tpl:LogWatch;help_features_tab.tpl'}
  </div>
  
  <div id="configuration" class="help-section">
    {include file='module_file_tpl:LogWatch;help_configuration_tab.tpl'}
  </div>
  
  {if $have_pro}
    <div id="pro" class="help-section">
      {include file='module_file_tpl:LogWatch;help_pro_features_tab.tpl'}
    </div>
  {else}
    <div id="upgrade" class="help-section">
      {include file='module_file_tpl:LogWatch;help_upgrade_tab.tpl'}
    </div>
  {/if}
  
  <div id="troubleshooting" class="help-section">
    {include file='module_file_tpl:LogWatch;help_troubleshooting_tab.tpl'}
  </div>
</div>

<script>
(function() {
  var tabs = document.querySelectorAll('.help-tab');
  var sections = document.querySelectorAll('.help-section');
  
  tabs.forEach(function(tab) {
    tab.addEventListener('click', function(e) {
      e.preventDefault();
      var target = this.getAttribute('data-tab');
      
      tabs.forEach(function(t) { t.classList.remove('active'); });
      sections.forEach(function(s) { s.classList.remove('active'); });
      
      this.classList.add('active');
      document.getElementById(target).classList.add('active');
    });
  });
})();
</script>
