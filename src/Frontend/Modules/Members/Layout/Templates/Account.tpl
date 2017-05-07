<section id="members-account" class="module module-members">
  {form:account}
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
          </label>
          {$txtDisplayName} {$txtDisplayNameError}
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <h3>{$lblMember|ucfirst}</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <label for="firstName">{$lblFirstName|ucfirst}</label>
          {$txtFirstName} {$txtFirstNameError}
        </div>
        <div class="form-group">
          <label for="lastName">{$lblLastName|ucfirst}</label>
          {$txtLastName} {$txtLastNameError}
        </div>
        <div class="form-group">
          <label for="introduction">{$lblIntroduction|ucfirst}</label>
          {$txtIntroduction} {$txtIntroductionError}
        </div>
        <div class="form-group">
          <label for="phone">{$lblPhone|ucfirst}</label>
          {$txtPhone} {$txtPhoneError}
        </div>
        <div class="form-group">
          {option:item.avatar}
          <div class="form-group">
            <img src="{$FRONTEND_FILES_URL}/Members/avatars/128x128/{$item.avatar}" class="img-thumbnail" alt="#" />
          </div>
          <div class="form-group">
            <ul class="list-unstyled">
              <li class="checkbox">
                <label for="avatarDelete">{$chkAvatarDelete} {$lblDelete}</label>
              </li>
            </ul>
          </div>
          {/option:item.avatar}
          <div class="form-group">
            {$fileAvatar} {$fileAvatarError}
          </div>
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
          {$ddmSource} {$ddmSourceError}
        </div>
      </div>
    </div>
    {option:member.addresses}
    <div class="row">
      <div class="col-md-12">
        <h3>{$lblAddresses|ucfirst}</h3>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="list-group jsAddresses">
          {iteration:member.addresses}
          <div class="panel{option:member.addresses.primary} panel-primary{/option:member.addresses.primary}{option:!member.addresses.primary} panel-default{/option:!member.addresses.primary} jsAddress">
            <div class="panel-heading">
              <h4>
                {option:member.addresses.address}<!--
                -->{$member.addresses.address}<!--
                -->{option:member.addresses.country}, {$member.addresses.country.locale.name}{/option:member.addresses.country}<!--
                -->{option:member.addresses.state}, {$member.addresses.state.locale.name}{/option:member.addresses.state}<!--
                -->{option:member.addresses.city}, {$member.addresses.city.locale.name}{/option:member.addresses.city}<!--
                -->{/option:member.addresses.address}
              </h4>
            </div>
            <table class="table">
              {option:member.addresses.company}
              <tr>
                <th>{$lblCompany}</th>
                <td>{$member.addresses.company}</td>
              </tr>
              {option:member.addresses.company_code}
              <tr>
                <th>{$lblCompanyCode}</th>
                <td>{$member.addresses.company_code}</td>
              </tr>
              {/option:member.addresses.company_code}
              {/option:member.addresses.company}
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
                  <a href="{$var|geturlforblock:'Members':'Address'}/{$member.addresses.id}" class="btn btn-default">
                    <span class="glyphicon glyphicon-pencil"></span>&nbsp;{$lblEditAddress|ucfirst}
                  </a>
                </div>
              </div>
            </div>
          </div>
          {/iteration:member.addresses}
        </div>
      </div>
    </div>
    {/option:member.addresses}
    <div class="row">
      <div class="col-md-12">
        <div class="btn-toolbar">
          <div class="btn-group pull-right" role="group">
            <a href="{$var|geturlforblock:'Members':'Address'}" class="btn btn-default">
              <span class="glyphicon glyphicon-plus"></span>&nbsp;
              {$lblAddAddress|ucfirst}
            </a>
            <button id="addButton" type="submit" name="add" class="btn btn-default">
              <span class="glyphicon glyphicon-plus"></span>&nbsp;
              {$lblMembersAccountSave|ucfirst}
            </button>
          </div>
        </div>
      </div>
    </div>
  {/form:account}
</section>
