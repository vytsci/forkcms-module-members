{include:{$BACKEND_CORE_PATH}/Layout/Templates/Head.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureStartModule.tpl}
<div class="row fork-module-heading">
  <div class="col-md-12">
    <h2>{$msgEditMember|sprintf:{$profile.email}|ucfirst}</h2>
  </div>
</div>
{form:edit}
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
                  {option:member.avatar}
                  <div class="form-group">
                    <img src="{$FRONTEND_FILES_URL}/Members/avatars/128x128/{$member.avatar}" class="img-thumbnail" alt="{$profile.display_name}" />
                  </div>
                  <div class="form-group">
                    <ul class="list-unstyled">
                      <li class="checkbox">
                        <label for="avatarDelete">{$chkAvatarDelete} {$lblDelete}</label>
                      </li>
                    </ul>
                  </div>
                  {/option:member.avatar}
                  <div class="form-group">
                    {$fileAvatar} {$fileAvatarError}
                  </div>
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
                <div class="btn-toolbar pull-right">
                  <div class="btn-group" role="group">
                    <a href="{$var|geturl:'addAddress':'Members':'&member_id={$member.id}'}" class="btn btn-default" title="{$lblAdd|ucfirst}">
                      <span class="glyphicon glyphicon-plus"></span>&nbsp;
                      {$lblAddAddress|ucfirst}
                    </a>
                  </div>
                </div>
              </div>
            </div>
            {option:member.addresses}
            <div class="row">
              <div class="col-md-12">
                <div class="list-group jsAddresses">
                  {iteration:member.addresses}
                  <div class="panel{option:member.addresses.primary} panel-primary{/option:member.addresses.primary}{option:!member.addresses.primary} panel-default{/option:!member.addresses.primary} jsAddress">
                    <div class="panel-heading">
                      <h4>
                        {option:member.addresses.address}<!--
                        -->{$member.addresses.address}<!--
                        -->{option:member.addresses.country}, {$member.addresses.country}{/option:member.addresses.country}<!--
                        -->{option:member.addresses.city}, {$member.addresses.city}{/option:member.addresses.city}<!--
                        -->{/option:member.addresses.address}
                      </h4>
                    </div>
                    <table class="table">
                      {option:member.addresses.phone}
                      <tr>
                        <th>{$lblPhone}</th>
                        <td>{$member.addresses.phone}</td>
                      </tr>
                      {/option:member.addresses.phone}
                    </table>
                    <div class="panel-footer">
                      <div class="btn-toolbar">
                        <div class="btn-group pull-right">
                          <a href="{$var|geturl:'editAddress':'Members':'&id={$member.addresses.id}'}" class="btn btn-default">
                            <span class="glyphicon glyphicon-pencil"></span>&nbsp;{$lblEditAddress|ucfirst}
                          </a>
                          <button type="button" data-address-id="{$member.addresses.id}" data-address-primary="1" class="btn btn-primary jsSwitchAddressPrimary"{option:member.addresses.primary} style="display: none;"{/option:member.addresses.primary}>
                            {$lblSetPrimary|ucfirst}
                          </button>
                          <button type="button" data-address-id="{$member.addresses.id}" data-address-primary="0" class="btn btn-danger jsSwitchAddressPrimary"{option:!member.addresses.primary} style="display: none;"{/option:!member.addresses.primary}>
                            {$lblUnsetPrimary|ucfirst}
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  {/iteration:member.addresses}
                </div>
              </div>
            </div>
            {/option:member.addresses}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row fork-page-actions">
    <div class="col-md-12">
      <div class="btn-toolbar">
        <div class="btn-group pull-left" role="group">
          {option:showProfilesDelete}
          {option:deleted}
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmUndelete">
            <span class="glyphicon glyphicon-ok"></span>
            {$lblUndelete|ucfirst}
          </button>
          {/option:deleted}
          {option:!deleted}
          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete">
            <span class="glyphicon glyphicon-trash"></span>
            {$lblDelete|ucfirst}
          </button>
          {/option:!deleted}
          {/option:showProfilesDelete}
          {option:showProfilesBlock}
          {option:blocked}
          <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmUnblock">
            <span class="glyphicon glyphicon-ok"></span>
            {$lblUnblock|ucfirst}
          </button>
          {/option:blocked}
          {option:!blocked}
          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmBlock">
            <span class="glyphicon glyphicon-ban-circle"></span>
            {$lblBlock|ucfirst}
          </button>
          {/option:!blocked}
          {/option:showProfilesBlock}
        </div>
        <div class="btn-group pull-right" role="group">
          <button id="saveButton" type="submit" name="edit" class="btn btn-primary">
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;{$lblSave|ucfirst}
          </button>
        </div>
      </div>
      {option:showProfilesDelete}
      <div class="modal fade" id="{option:deleted}confirmUndelete{/option:deleted}{option:!deleted}confirmDelete{/option:!deleted}" tabindex="-1" role="dialog" aria-labelledby="{option:deleted}{$lblUndelete|ucfirst}{/option:deleted}{option:!deleted}{$lblDelete|ucfirst}{/option:!deleted}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              {option:deleted}
              <span class="modal-title h4">{$lblUndelete|ucfirst}</span>
              {/option:deleted}
              {option:!deleted}
              <span class="modal-title h4">{$lblDelete|ucfirst}</span>
              {/option:!deleted}
            </div>
            <div class="modal-body">
              {option:deleted}
              <p>{$msgConfirmUndelete|sprintf:{$profile.email}}</p>
              {/option:deleted}
              {option:!deleted}
              <p>{$msgConfirmDelete|sprintf:{$profile.email}}</p>
              {/option:!deleted}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{$lblCancel|ucfirst}</button>
              {option:deleted}
              <a href="{$var|geturl:'delete':'profiles'}&amp;id={$profile.id}" class="btn btn-primary">
                {$lblOK|ucfirst}
              </a>
              {/option:deleted}
              {option:!deleted}
              <a href="{$var|geturl:'delete':'profiles'}&amp;id={$profile.id}" class="btn btn-primary">
                {$lblOK|ucfirst}
              </a>
              {/option:!deleted}
            </div>
          </div>
        </div>
      </div>
      {/option:showProfilesDelete}
      {option:showProfilesBlock}
      <div class="modal fade" id="{option:blocked}confirmUnblock{/option:blocked}{option:!blocked}confirmBlock{/option:!blocked}" tabindex="-1" role="dialog" aria-labelledby="{option:blocked}{$lblUnblock|ucfirst}{/option:blocked}{option:!blocked}{$lblBlock|ucfirst}{/option:!blocked}" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              {option:blocked}
              <span class="modal-title h4">{$lblUnblock|ucfirst}</span>
              {/option:blocked}
              {option:!blocked}
              <span class="modal-title h4">{$lblBlock|ucfirst}</span>
              {/option:!blocked}
            </div>
            <div class="modal-body">
              {option:blocked}
              <p>{$msgConfirmUnblock|sprintf:{$profile.email}}</p>
              {/option:blocked}
              {option:!blocked}
              <p>{$msgConfirmBlock|sprintf:{$profile.email}}</p>
              {/option:!blocked}
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">{$lblCancel|ucfirst}</button>
              {option:blocked}
              <a href="{$var|geturl:'block':'profiles'}&amp;id={$profile.id}" class="btn btn-primary">
                {$lblOK|ucfirst}
              </a>
              {/option:blocked}
              {option:!blocked}
              <a href="{$var|geturl:'block':'profiles'}&amp;id={$profile.id}" class="btn btn-primary">
                {$lblOK|ucfirst}
              </a>
              {/option:!blocked}
            </div>
          </div>
        </div>
      </div>
      {/option:showProfilesBlock}
    </div>
  </div>
{/form:edit}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/StructureEndModule.tpl}
{include:{$BACKEND_CORE_PATH}/Layout/Templates/Footer.tpl}
