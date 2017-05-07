{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>{$lblAdd|ucfirst}</h2>
  </div>
</div>
{form:add}
  <div class="row fork-module-content">
    <div class="col-md-12">
      <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active">
            <a href="#tabProfile" aria-controls="profile" role="tab" data-toggle="tab">{$lblProfile|ucfirst}</a>
          </li>
          <li role="presentation">
            <a href="#tabMember" aria-controls="member" role="tab" data-toggle="tab">{$lblMember|ucfirst}</a>
          </li>
          <li role="presentation">
            <a href="#tabAddresses" aria-controls="addresses" role="tab" data-toggle="tab">{$lblAddresses|ucfirst}</a>
          </li>
        </ul>
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane active" id="tabProfile">
            <div class="row">
              <div class="col-md-12">
                <h3>{$lblProfile|ucfirst}</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="email">
                    {$lblEmail|ucfirst}
                    <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
                  </label>
                  {$txtEmail} {$txtEmailError}
                </div>
                <div class="form-group">
                  <label for="displayName">
                    {$lblDisplayName|ucfirst}
                    <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
                  </label>
                  {$txtDisplayName} {$txtDisplayNameError}
                </div>
                <div class="form-group">
                  <label for="password">
                    {$lblPassword|ucfirst}
                    <abbr class="glyphicon glyphicon-asterisk" title="{$lblRequiredField|ucfirst}"></abbr>
                  </label>
                  {$txtPassword} {$txtPasswordError}
                </div>
              </div>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="tabMember">
            <div class="row">
              <div class="col-md-12">
                <h3>{$lblMember|ucfirst}</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="type">{$lblType|ucfirst}</label>
                  {$ddmType} {$ddmTypeError}
                </div>
                <div class="form-group">
                  <label for="firstName">{$lblFirstName|ucfirst}</label>
                  {$txtFirstName} {$txtFirstNameError}
                </div>
                <div class="form-group">
                  <label for="lastName">{$lblLastName|ucfirst}</label>
                  {$txtLastName} {$txtLastNameError}
                </div>
                <div class="form-group">
                  <label for="phone">{$lblPhone|ucfirst}</label>
                  {$txtPhone} {$txtPhoneError}
                </div>
                <div class="form-group">
                  {$fileAvatar} {$fileAvatarError}
                </div>
                <div class="form-group">
                  <label for="gender">{$lblGender|ucfirst}</label>
                  {$ddmGender} {$ddmGenderError}
                </div>
                <div class="form-group">
                  <label>{$lblDateBirth|ucfirst}</label>
                  <div class="row">
                    <div class="col-xs-4">
                      {$ddmDateBirthYear} {$ddmDateBirthYearError}
                    </div>
                    <div class="col-xs-4">
                      {$ddmDateBirthMonth} {$ddmDateBirthMonthError}
                    </div>
                    <div class="col-xs-4">
                      {$ddmDateBirthDay} {$ddmDateBirthDayError}
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="source">{$lblSource|ucfirst}</label>
                  {$txtSource} {$txtSourceError}
                </div>
              </div>
            </div>
          </div>
          <div role="tabpanel" class="tab-pane" id="tabAddresses">
            <div class="row">
              <div class="col-md-12">
                <h3>{$lblAddresses|ucfirst}</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <ul class="list-unstyled">
                    <li class="checkbox">
                      <label for="addAddress" data-toggle-on-check="#addAddressFields">
                        {$chkAddAddress} {$lblAddAddress|ucfirst}
                      </label>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div id="addAddressFields" class="row" style="display: none;">
              <div class="col-md-12">
                {include:{$BACKEND_MODULES_PATH}/Members/Layout/Templates/Forms/FieldsAddress.tpl}
              </div>
            </div>
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
{/form:add}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
