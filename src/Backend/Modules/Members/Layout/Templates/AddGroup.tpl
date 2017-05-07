{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>{$lblAddGroup|ucfirst}</h2>
  </div>
</div>
{form:addGroup}
  <div class="row fork-module-content">
    <div class="col-md-12">
      <div class="form-group">
        <label for="identifier">{$lblIdentifier|ucfirst}</label>
        {$txtIdentifier} {$txtIdentifierError}
      </div>
    </div>
  </div>
  <div class="row fork-module-content">
    <div class="col-md-8">
      <div class="panel-group" id="languages" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
          {iteration:formLocalization}
          <div class="panel-heading" role="tab" id="heading{$formLocalization.code|ucfirst}">
            <h4 class="panel-title">
              <a data-toggle="collapse" data-parent="#languages" href="#collapse{$formLocalization.code|ucfirst}" aria-expanded="true" aria-controls="collapse{$formLocalization.code|ucfirst}">
                {$formLocalization.title|ucfirst}
              </a>
            </h4>
          </div>
          <div id="collapse{$formLocalization.code|ucfirst}" class="panel-collapse collapse{option:formLocalization.first} in{/option:formLocalization.first}" role="tabpanel" aria-labelledby="heading{$formLocalization.code|ucfirst}">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="title">
                      {$lblTitle|ucfirst}
                      <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
                    </label>
                    {$formLocalization.fields.title}
                    {$formLocalization.errors.title}
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active">
                        <a href="#tab{$formLocalization.code|ucfirst}Content" aria-controls="content" role="tab" data-toggle="tab">
                          {$lblContent|ucfirst}
                        </a>
                      </li>
                      {option:formLocalization.seo}
                      <li role="presentation">
                        <a href="#tab{$formLocalization.code|ucfirst}SEO" aria-controls="content" role="tab" data-toggle="tab">
                          {$lblSEO|ucfirst}
                        </a>
                      </li>
                      {/option:formLocalization.seo}
                    </ul>
                    <div class="tab-content">
                      <div role="tabpanel" class="tab-pane active" id="tab{$formLocalization.code|ucfirst}Content">
                        <div class="row">
                          <div class="col-md-12">
                            <h4>{$lblContent|ucfirst}</h4>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label for="introduction">
                                {$lblIntroduction|ucfirst}
                              </label>
                              {$formLocalization.fields.introduction}
                              {$formLocalization.errors.introduction}
                            </div>
                            <div class="form-group">
                              <label for="text">
                                {$lblText|ucfirst}
                                <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
                              </label>
                              {$formLocalization.fields.text}
                              {$formLocalization.errors.text}
                            </div>
                          </div>
                        </div>
                      </div>
                      {option:formLocalization.seo}
                      <div role="tabpanel" class="tab-pane" id="tab{$formLocalization.code|ucfirst}SEO">
                        {$formLocalization.seo}
                      </div>
                      {/option:formLocalization.seo}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          {/iteration:formLocalization}
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">{$lblGeneral|ucfirst}</h3>
        </div>
        <div class="panel-body">
          <div class="form-group">
            <ul class="list-unstyled">
              <li class="checkbox">
                <label for="default">{$chkDefault} {$lblGroupIsDefault}</label>
              </li>
            </ul>
          </div>
          <div class="form-group">
            <ul class="list-unstyled">
              <li class="checkbox">
                <label for="registration">{$chkRegistration} {$lblGroupIsRegistration}</label>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row fork-module-actions">
    <div class="col-md-12">
      <div class="btn-toolbar">
        <div class="btn-group pull-right" role="group">
          <button id="addButton" type="submit" name="add" class="btn btn-primary">
            <span class="glyphicon glyphicon-plus"></span>&nbsp;
            {$lblAdd|ucfirst}
          </button>
        </div>
      </div>
    </div>
  </div>
{/form:addGroup}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
