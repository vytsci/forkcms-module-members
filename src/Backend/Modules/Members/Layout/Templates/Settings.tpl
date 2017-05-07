{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>{$lblSettings|ucfirst}</h2>
  </div>
</div>
{form:settings}
  <div class="row fork-module-content">
    <div class="col-md-12">
      <div class="form-group">
        <ul class="list-unstyled">
          <li class="checkbox">
            <label for="showTypeChoice">
              {$chkShowTypeChoice}
              {$lblShowTypeChoice}
            </label>
          </li>
          <li class="checkbox">
            <label for="showTypeChoiceAsPage">
              {$chkShowTypeChoiceAsPage}
              {$lblShowTypeChoiceAsPage}
            </label>
          </li>
        </ul>
      </div>
      <div class="form-group">
        <label for="defaultType">{$lblDefaultType|ucfirst}</label>
        {$ddmDefaultType} {$ddmDefaultTypeError}
      </div>
      <div class="form-group">
        <ul class="list-unstyled">
          <li class="checkbox">
            <label for="enableIndexPage">
              {$chkEnableIndexPage}
              {$lblEnableIndexPage}
            </label>
          </li>
          <li class="checkbox">
            <label for="enableEmailRegistration">
              {$chkEnableEmailRegistration}
              {$lblEnableEmailRegistration}
            </label>
          </li>
          <li class="checkbox">
            <label for="enableAutoApproveRequisites">
              {$chkEnableAutoApproveRequisites}
              {$lblEnableAutoApproveRequisites}
            </label>
          </li>
        </ul>
      </div>
      <div class="form-group">
        <label for="pendingText">{$lblPendingText|ucfirst}</label>
        {$txtPendingText} {$txtPendingTextError}
      </div>
      <div class="form-group">
        <label for="welcomeText">{$lblWelcomeText|ucfirst}</label>
        {$txtWelcomeText} {$txtWelcomeTextError}
      </div>
      <div class="form-group">
        <label for="addValue-sources">{$lblSources|ucfirst}</label>
        {$txtSources} {$txtSourcesError}
      </div>
      <div class="form-group">
        <label for="urlTerms">{$lblUrlTerms|ucfirst}</label>
        {$txtUrlTerms} {$txtUrlTermsError}
      </div>
      <div class="form-group">
        <label for="termsRequisites">{$lblTermsRequisites|ucfirst}</label>
        {$txtTermsRequisites} {$txtTermsRequisitesError}
      </div>
    </div>
  </div>
  <div class="row fork-module-content">
    <div class="col-md-12">
      <h3>{$lblTypesGroups|ucfirst}</h3>
    </div>
  </div>
  <div class="row fork-module-content">
    <div class="col-md-12">
      {iteration:typesGroupsFields}
      <div class="form-group">
        <label for="{$typesGroupsFields.name}">{$typesGroupsFields.label}</label>
        {$typesGroupsFields.field} {$typesGroupsFields.errors}
      </div>
      {/iteration:typesGroupsFields}
    </div>
  </div>
  <div class="row fork-module-actions">
    <div class="col-md-12">
      <div class="btn-toolbar">
        <div class="btn-group pull-right" role="group">
          <button id="save" type="submit" name="save" class="btn btn-primary">{$lblSave|ucfirst}</button>
        </div>
      </div>
    </div>
  </div>
{/form:settings}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
